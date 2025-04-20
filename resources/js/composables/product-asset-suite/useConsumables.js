import i18n from "@/plugins/i18n"
import { useConsumableStore } from "@/stores/product-asset-suite/consumables/useConsumableStore"
import { useSnackbarStore } from "@/stores/useSnackbarStore"

// Helper function for handling API errors
const handleApiError = (err, defaultMessage) => {
  console.error(err)
  
  return err.response?.data?.message || i18n.global.t(defaultMessage)
}

export function useConsumables(autoFetch = true) {
  const consumableStore = useConsumableStore()
  const snackbarStore = useSnackbarStore()

  // State Variables
  const fetchedConsumable = ref(null)
  const searchQuery = ref("")
  const selectedType = ref(null)
  const selectedSubcategory = ref(null)
  const selectedCategory = ref(null)

  const consumables = ref([])
  const totalPage = ref(1)
  const totalConsumables = ref(0)
  const loading = ref(false)
  const error = ref(null)

  const options = ref({
    page: 1,
    itemsPerPage: 10,
    sortBy: [],
    groupBy: [],
  })

  const statisticsData = ref({
    total_consumables: 0,
    total_agents_and_disposables: 0,
    total_replacement_parts: 0,
    total_safety_signage: 0,
    total_accessories: 0,
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

  const fetchConsumableMetadata = async () => {
    try {
      const metaResponse = await consumableStore.fetchMetaConsumables()

      statisticsData.value = metaResponse.data.data.count
    } catch (err) {
      error.value = handleApiError(err, "Failed to load metadata")
      snackbarStore.showMessage(error.value, "error")
    }
  }

  const fetchConsumablesList = async () => {
    try {
      const consumablesResponse = await consumableStore.fetchConsumables({
        search: searchQuery.value,
        category: selectedCategory.value,
        subcategory: selectedSubcategory.value,
        type: selectedType.value,
        options: options.value,
      })

      consumables.value = consumablesResponse.data.data
      totalPage.value = consumablesResponse.data.meta.last_page
      totalConsumables.value = consumablesResponse.data.meta.total
      options.value.page = consumablesResponse.data.meta.current_page
    } catch (err) {
      error.value = handleApiError(err, "Failed to load consumables")
      snackbarStore.showMessage(error.value, "error")
    }
  }

  const fetchAllData = async () => {
    loading.value = true
    error.value = null
    try {
      await Promise.all([fetchConsumablesList(), fetchConsumableMetadata()])
      snackbarStore.showMessage(i18n.global.t("Consumables Loaded Successfully"))
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

  const fetchConsumable = async id =>
    executeWithLoading(
      () => consumableStore.fetchConsumable(id),
      "Consumable loaded successfully",
      "Failed to load consumable data",
      response => (fetchedConsumable.value = response.data.data),
    )

  const addConsumable = async consumableData =>
    executeWithLoading(
      () => consumableStore.addConsumable(consumableData),
      "Consumable created successfully!",
      "Failed to create consumable",
    ).then(response => response.data.data.id)

  const updateConsumable = async (id, consumableData) =>
    executeWithLoading(
      () => consumableStore.updateConsumable(id, consumableData),
      "Consumable updated successfully!",
      "Failed to update consumable",
      response => (fetchedConsumable.value = response.data.data),
    )

  const deleteConsumable = async consumableId =>
    executeWithLoading(
      () => consumableStore.deleteConsumable(consumableId),
      "Consumable deleted successfully!",
      "Failed to delete consumable",
    ).then(() => fetchAllData())

  const printConsumables = async () =>
    executeWithLoading(
      () =>
        consumableStore.printConsumables({
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
        link.setAttribute("download", `consumables-${new Date().toISOString()}.pdf`)
        document.body.appendChild(link)
        link.click()
        link.remove()
      },
    )

  const printConsumableData = async id =>
    executeWithLoading(
      () => consumableStore.printConsumableData({
        id,
        orientation: "portrait",
      }),
      "PDF generation successful!",
      "Failed to generate PDF",
      response => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement("a")
    
        link.href = url
        link.setAttribute("download", `consumable-data-${id}-${new Date().toISOString()}.pdf`)
        document.body.appendChild(link)
        link.click()
        link.remove()
      },
    )
  
  return {
    fetchedConsumable,
    searchQuery,
    selectedType,
    selectedSubcategory,
    selectedCategory,
    consumables,
    totalPage,
    totalConsumables,
    loading,
    error,
    options,
    statisticsData,
    debouncedFetchAllData,
    fetchConsumable,
    addConsumable,
    updateConsumable,
    deleteConsumable,
    printConsumables,
    printConsumableData,
  }
}
