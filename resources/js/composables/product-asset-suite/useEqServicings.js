import i18n from "@/plugins/i18n"
import { useEqServicingStore } from "@/stores/product-asset-suite/equipment-servicings/useEqServicingStore"
import { useSnackbarStore } from "@/stores/useSnackbarStore"

// Helper function for handling API errors
const handleApiError = (err, defaultMessage) => {
  console.error(err)
  
  return err.response?.data?.message || i18n.global.t(defaultMessage)
}

export function useEqServicings(autoFetch = true) {
  const eqServicingStore = useEqServicingStore()
  const snackbarStore = useSnackbarStore()

  // State Variables
  const fetchedEqServicing = ref(null)
  const searchQuery = ref("")
  const selectedType = ref(null)
  const selectedSubcategory = ref(null)
  const selectedCategory = ref(null)

  const eqServicings = ref([])
  const totalPage = ref(1)
  const totalEqServicings = ref(0)
  const loading = ref(false)
  const error = ref(null)

  const options = ref({
    page: 1,
    itemsPerPage: 10,
    sortBy: [],
    groupBy: [],
  })

  const statisticsData = ref({
    total_eq_servicings: 0,
    total_inspection_and_testing: 0,
    total_maintenance_and_repair: 0,
    total_refill_recharge: 0,
    total_calibration_and_certification: 0,
    total_installation_decommissioning: 0,
  })

  // --- New helper to abstract common try-catch-finally logic ---
  const executeWithLoading = async (
    asyncCallback,
    successMessage = null,
    errorMessage = "Operation failed",
    processResult = null,
  ) => {
    loading.value = true
    try {
      const response = await asyncCallback()
      if (processResult) processResult(response)
      if (successMessage) snackbarStore.showMessage(successMessage)
      
      return response
    } catch (err) {
      error.value = handleApiError(err, errorMessage)
      snackbarStore.showMessage(error.value, "error")
      throw err
    } finally {
      loading.value = false
    }
  }

  // --------------------------------------------------------------

  const fetchEqServicingMetadata = async () => {
    try {
      const metaResponse = await eqServicingStore.fetchMetaEqServicings()

      statisticsData.value = metaResponse.data.data.count
    } catch (err) {
      error.value = handleApiError(err, "Failed to load metadata")
      snackbarStore.showMessage(error.value, "error")
    }
  }

  const fetchEqServicingsList = async () => {
    try {
      const eqServicingsResponse = await eqServicingStore.fetchEqServicings({
        search: searchQuery.value,
        category: selectedCategory.value,
        subcategory: selectedSubcategory.value,
        type: selectedType.value,
        options: options.value,
      })

      eqServicings.value = eqServicingsResponse.data.data
      totalPage.value = eqServicingsResponse.data.meta.last_page
      totalEqServicings.value = eqServicingsResponse.data.meta.total
      options.value.page = eqServicingsResponse.data.meta.current_page
    } catch (err) {
      error.value = handleApiError(err, "Failed to load eqServicings")
      snackbarStore.showMessage(error.value, "error")
    }
  }

  const fetchAllData = async () => {
    loading.value = true
    error.value = null
    try {
      await Promise.all([fetchEqServicingsList(), fetchEqServicingMetadata()])
      snackbarStore.showMessage(i18n.global.t("EqServicings Loaded Successfully"))
    } catch (err) {
      // Individual functions handle errors
    } finally {
      loading.value = false
    }
  }

  // Debounce to avoid rapid API calls
  const debouncedFetchAllData = useDebounceFn(fetchAllData, 600)

  if (autoFetch) {
    onMounted(debouncedFetchAllData)
    watch(
      [searchQuery, selectedType, selectedSubcategory, selectedCategory, options],
      debouncedFetchAllData,
      { deep: true },
    )
  }

  const fetchEqServicing = async id =>
    executeWithLoading(
      () => eqServicingStore.fetchEqServicing(id),
      "EqServicing loaded successfully",
      "Failed to load eqServicing data",
      response => (fetchedEqServicing.value = response.data.data),
    )

  const addEqServicing = async eqServicingData =>
    executeWithLoading(
      () => eqServicingStore.addEqServicing(eqServicingData),
      "EqServicing created successfully!",
      "Failed to create eqServicing",
    ).then(response => response.data.data.id)

  const updateEqServicing = async (id, eqServicingData) =>
    executeWithLoading(
      () => eqServicingStore.updateEqServicing(id, eqServicingData),
      "EqServicing updated successfully!",
      "Failed to update eqServicing",
      response => (fetchedEqServicing.value = response.data.data),
    )

  const deleteEqServicing = async eqServicingId =>
    executeWithLoading(
      () => eqServicingStore.deleteEqServicing(eqServicingId),
      "EqServicing deleted successfully!",
      "Failed to delete eqServicing",
    ).then(() => fetchAllData())

  const printEqServicings = async () =>
    executeWithLoading(
      () =>
        eqServicingStore.printEqServicings({
          search: searchQuery.value,
          category: selectedCategory.value,
          subcategory: selectedSubcategory.value,
          type: selectedType.value,
          sort: options.value.sortBy,
          orientation: "landscape",
        }),
      "PDF generation successful!",
      "Failed to generate PDF",
      response => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement("a")
  
        link.href = url
        link.setAttribute("download", `eqServicings-${new Date().toISOString()}.pdf`)
        document.body.appendChild(link)
        link.click()
        link.remove()
      },
    )

  const printEqServicingData = async id =>
    executeWithLoading(
      () => eqServicingStore.printEqServicingData({
        id,
        orientation: "portrait",
      }),
      "PDF generation successful!",
      "Failed to generate PDF",
      response => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement("a")
    
        link.href = url
        link.setAttribute("download", `eqServicing-data-${id}-${new Date().toISOString()}.pdf`)
        document.body.appendChild(link)
        link.click()
        link.remove()
      },
    )
  
  return {
    fetchedEqServicing,
    searchQuery,
    selectedType,
    selectedSubcategory,
    selectedCategory,
    eqServicings,
    totalPage,
    totalEqServicings,
    loading,
    error,
    options,
    statisticsData,
    debouncedFetchAllData,
    fetchEqServicing,
    addEqServicing,
    updateEqServicing,
    deleteEqServicing,
    printEqServicings,
    printEqServicingData,
  }
}
