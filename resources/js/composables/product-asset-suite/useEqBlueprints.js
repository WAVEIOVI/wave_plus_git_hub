import i18n from "@/plugins/i18n"
import { useEqBlueprintStore } from "@/stores/product-asset-suite/equipment-blueprints/useEqBlueprintStore"
import { useSnackbarStore } from "@/stores/useSnackbarStore"

// Helper function for handling API errors
const handleApiError = (err, defaultMessage) => {
  console.error(err)
  
  return err.response?.data?.message || i18n.global.t(defaultMessage)
}

export function useEqBlueprints(autoFetch = true) {
  const eqBlueprintStore = useEqBlueprintStore()
  const snackbarStore = useSnackbarStore()

  // State Variables
  const fetchedEqBlueprint = ref(null)
  const searchQuery = ref("")
  const selectedSubcategory = ref(null)
  const selectedCategory = ref(null)

  const eqBlueprints = ref([])
  const totalPage = ref(1)
  const totalEqBlueprints = ref(0)
  const loading = ref(false)
  const error = ref(null)

  const options = ref({
    page: 1,
    itemsPerPage: 10,
    sortBy: [],
    groupBy: [],
  })

  const statisticsData = ref({
    total_eq_blueprints: 0,
    total_active: 0,
    total_inactive: 0,
    total_draft: 0,
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

  const fetchEqBlueprintMetadata = async () => {
    try {
      const metaResponse = await eqBlueprintStore.fetchMetaEqBlueprints()

      statisticsData.value = metaResponse.data.data.count
    } catch (err) {
      error.value = handleApiError(err, "Failed to load metadata")
      snackbarStore.showMessage(error.value, "error")
    }
  }

  const fetchEqBlueprintsList = async () => {
    try {
      const eqBlueprintsResponse = await eqBlueprintStore.fetchEqBlueprints({
        search: searchQuery.value,
        category: selectedCategory.value,
        subcategory: selectedSubcategory.value,
        options: options.value,
      })

      eqBlueprints.value = eqBlueprintsResponse.data.data
      totalPage.value = eqBlueprintsResponse.data.meta.last_page
      totalEqBlueprints.value = eqBlueprintsResponse.data.meta.total
      options.value.page = eqBlueprintsResponse.data.meta.current_page
    } catch (err) {
      error.value = handleApiError(err, "Failed to load eqBlueprints")
      snackbarStore.showMessage(error.value, "error")
    }
  }

  const fetchAllData = async () => {
    loading.value = true
    error.value = null
    try {
      await Promise.all([fetchEqBlueprintsList(), fetchEqBlueprintMetadata()])
      snackbarStore.showMessage(i18n.global.t("EqBlueprints Loaded Successfully"))
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
      [searchQuery, selectedSubcategory, selectedCategory, options],
      debouncedFetchAllData,
      { deep: true },
    )
  }

  const fetchEqBlueprint = async id =>
    executeWithLoading(
      () => eqBlueprintStore.fetchEqBlueprint(id),
      "EqBlueprint loaded successfully",
      "Failed to load eqBlueprint data",
      response => (fetchedEqBlueprint.value = response.data.data),
    )

  const addEqBlueprint = async eqBlueprintData =>
    executeWithLoading(
      () => eqBlueprintStore.addEqBlueprint(eqBlueprintData),
      "EqBlueprint created successfully!",
      "Failed to create eqBlueprint",
    ).then(response => response.data.data.id)

  const updateEqBlueprint = async (id, eqBlueprintData) =>
    executeWithLoading(
      () => eqBlueprintStore.updateEqBlueprint(id, eqBlueprintData),
      "EqBlueprint updated successfully!",
      "Failed to update eqBlueprint",
      response => (fetchedEqBlueprint.value = response.data.data),
    )

  const deleteEqBlueprint = async eqBlueprintId =>
    executeWithLoading(
      () => eqBlueprintStore.deleteEqBlueprint(eqBlueprintId),
      "EqBlueprint deleted successfully!",
      "Failed to delete eqBlueprint",
    ).then(() => fetchAllData())


  const fetchEqBlueprintCapacities = async ({ id }) =>
    executeWithLoading(
      () => eqBlueprintStore.fetchEqBlueprintCapacities({ id, options: options.value }),
      "Equipment Blueprint capacities loaded successfully",
      "Failed to load equipment blueprint capacities",
    ).then(response => response.data.data)
  
  const fetchEqBlueprintCapacity = async (id, capacityId) =>
    executeWithLoading(
      () => eqBlueprintStore.fetchEqBlueprintCapacity(id, capacityId),
      "Capacity loaded successfully",
      "Failed to load Capacity data",
    )
      
  const attachEqBlueprintCapacity = async (id, capacityData) =>
    executeWithLoading(
      () => eqBlueprintStore.attachEqBlueprintCapacity(id, capacityData),
      "Capacity added successfully!",
      "Failed to add capacity",
    )
  
  const detachEqBlueprintCapacity = async (id, capacityId) =>
    executeWithLoading(
      () => eqBlueprintStore.detachEqBlueprintCapacity(id, capacityId),
      "Capacity deleted successfully!",
      "Failed to delete Capacity",
    )

  const fetchEqBlueprintPressures = async ({ id }) =>
    executeWithLoading(
      () => eqBlueprintStore.fetchEqBlueprintPressures({ id, options: options.value }),
      "Equipment Blueprint pressures loaded successfully",
      "Failed to load equipment blueprint pressures",
    ).then(response => response.data.data)
    
  const fetchEqBlueprintPressure = async (id, pressureId) =>
    executeWithLoading(
      () => eqBlueprintStore.fetchEqBlueprintPressure(id, pressureId),
      "Pressure loaded successfully",
      "Failed to load Pressure data",
    )
        
  const attachEqBlueprintPressure = async (id, pressureData) =>
    executeWithLoading(
      () => eqBlueprintStore.attachEqBlueprintPressure(id, pressureData),
      "Pressure added successfully!",
      "Failed to add pressure",
    )
    
  const detachEqBlueprintPressure = async (id, pressureId) =>
    executeWithLoading(
      () => eqBlueprintStore.detachEqBlueprintPressure(id, pressureId),
      "Pressure deleted successfully!",
      "Failed to delete Pressure",
    )


  const fetchEqBlueprintConsumables = async ({ id }) =>
    executeWithLoading(
      () => eqBlueprintStore.fetchEqBlueprintConsumables({ id, options: options.value }),
      "Equipment Blueprint consumables loaded successfully",
      "Failed to load equipment blueprint consumables",
    ).then(response => response.data.data)
      
  const fetchEqBlueprintConsumable = async (id, consumableId) =>
    executeWithLoading(
      () => eqBlueprintStore.fetchEqBlueprintConsumable(id, consumableId),
      "Consumable loaded successfully",
      "Failed to load Consumable data",
    )
          
  const attachEqBlueprintConsumable = async (id, consumableData) =>
    executeWithLoading(
      () => eqBlueprintStore.attachEqBlueprintConsumable(id, consumableData),
      "Consumable added successfully!",
      "Failed to add consumable",
    )
      
  const detachEqBlueprintConsumable = async (id, consumableId) =>
    executeWithLoading(
      () => eqBlueprintStore.detachEqBlueprintConsumable(id, consumableId),
      "Consumable deleted successfully!",
      "Failed to delete Consumable",
    )


  const fetchEqBlueprintEqServicings = async ({ id }) =>
    executeWithLoading(
      () => eqBlueprintStore.fetchEqBlueprintEqServicings({ id, options: options.value }),
      "Equipment Blueprint Servicings loaded successfully",
      "Failed to load equipment blueprint Servicings",
    ).then(response => response.data.data)
        
  const fetchEqBlueprintEqServicing = async (id, eqServicingId) =>
    executeWithLoading(
      () => eqBlueprintStore.fetchEqBlueprintEqServicing(id, eqServicingId),
      "Eq Servicing loaded successfully",
      "Failed to load Eq Servicing data",
    )
            
  const attachEqBlueprintEqServicing = async (id, eqServicingData) =>
    executeWithLoading(
      () => eqBlueprintStore.attachEqBlueprintEqServicing(id, eqServicingData),
      "EqServicing added successfully!",
      "Failed to add Eq Servicing",
    )
        
  const detachEqBlueprintEqServicing = async (id, eqServicingId) =>
    executeWithLoading(
      () => eqBlueprintStore.detachEqBlueprintEqServicing(id, eqServicingId),
      "EqServicing deleted successfully!",
      "Failed to delete EqServicing",
    )
  
  const printEqBlueprints = async () =>
    executeWithLoading(
      () =>
        eqBlueprintStore.printEqBlueprints({
          search: searchQuery.value,
          category: selectedCategory.value,
          subcategory: selectedSubcategory.value,
          sort: options.value.sortBy,
          orientation: "landscape",
        }),
      "PDF generation successful!",
      "Failed to generate PDF",
      response => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement("a")
  
        link.href = url
        link.setAttribute("download", `eqBlueprints-${new Date().toISOString()}.pdf`)
        document.body.appendChild(link)
        link.click()
        link.remove()
      },
    )

  const printEqBlueprintData = async id =>
    executeWithLoading(
      () => eqBlueprintStore.printEqBlueprintData({
        id,
        orientation: "portrait",
      }),
      "PDF generation successful!",
      "Failed to generate PDF",
      response => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement("a")
    
        link.href = url
        link.setAttribute("download", `eqBlueprint-data-${id}-${new Date().toISOString()}.pdf`)
        document.body.appendChild(link)
        link.click()
        link.remove()
      },
    )
  
  return {
    fetchedEqBlueprint,
    searchQuery,
    selectedSubcategory,
    selectedCategory,
    eqBlueprints,
    totalPage,
    totalEqBlueprints,
    loading,
    error,
    options,
    statisticsData,
    debouncedFetchAllData,
    fetchEqBlueprint,
    addEqBlueprint,
    updateEqBlueprint,
    deleteEqBlueprint,
    fetchEqBlueprintCapacities,
    fetchEqBlueprintCapacity,
    attachEqBlueprintCapacity,
    detachEqBlueprintCapacity,
    fetchEqBlueprintPressures,
    fetchEqBlueprintPressure,
    attachEqBlueprintPressure,
    detachEqBlueprintPressure,
    fetchEqBlueprintConsumables,
    fetchEqBlueprintConsumable,
    attachEqBlueprintConsumable,
    detachEqBlueprintConsumable,
    fetchEqBlueprintEqServicings,
    fetchEqBlueprintEqServicing,
    attachEqBlueprintEqServicing,
    detachEqBlueprintEqServicing,
    printEqBlueprints,
    printEqBlueprintData,
  }
}
