import i18n from "@/plugins/i18n"
import { useSettingStore } from "@/stores/product-asset-suite/settings/useSettingStore"
import { useSnackbarStore } from "@/stores/useSnackbarStore"

// Helper function for handling API errors
const handleApiError = (err, defaultMessage) => {
  console.error(err)
  
  return err.response?.data?.message || i18n.global.t(defaultMessage)
}

export function useSettings(autoFetch = true) {
  const settingStore = useSettingStore()
  const snackbarStore = useSnackbarStore()

  // State Variables
  const fetchedPressure = ref(null)
  const fetchedWeight = ref(null)
  const fetchedBrand = ref(null)

  const pressures = ref([])
  const weights = ref([])
  const brands = ref([])
  const loading = ref(false)
  const error = ref(null)

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

  const fetchPressuresList = async () => {
    try {
      const pressuresResponse = await settingStore.fetchPressures()

      pressures.value = pressuresResponse.data.data
    } catch (err) {
      error.value = handleApiError(err, "Failed to load pressures")
      snackbarStore.showMessage(error.value, "error")
    }
  }

  const fetchWeightsList = async () => {
    try {
      const weightsResponse = await settingStore.fetchWeights()

      weights.value = weightsResponse.data.data
    } catch (err) {
      error.value = handleApiError(err, "Failed to load weights")
      snackbarStore.showMessage(error.value, "error")
    }
  }

  const fetchBrandsList = async () => {
    try {
      const brandsResponse = await settingStore.fetchBrands()

      brands.value = brandsResponse.data.data
    } catch (err) {
      error.value = handleApiError(err, "Failed to load brands")
      snackbarStore.showMessage(error.value, "error")
    }
  }

  const fetchAllData = async () => {
    loading.value = true
    error.value = null
    try {
      await Promise.all([fetchPressuresList(), fetchBrandsList(), fetchWeightsList()])
      snackbarStore.showMessage(i18n.global.t("Settings Loaded Successfully"))
    } catch (err) {
      // Individual functions handle errors
    } finally {
      loading.value = false
    }
  }

  // Debounce to avoid rapid API calls
  const debouncedFetchAllData = useDebounceFn(fetchAllData, 600)

  onMounted(debouncedFetchAllData)
  watchEffect(() => {
    debouncedFetchAllData()
  })

  const fetchPressure = async id =>
    executeWithLoading(
      () => settingStore.fetchPressure(id),
      "Pressure loaded successfully",
      "Failed to load Pressure data",
      response => (fetchedPressure.value = response.data.data),
    )

  const addPressure = async pressureData =>
    executeWithLoading(
      () => settingStore.addPressure(pressureData),
      "Pressure created successfully!",
      "Failed to create pressure",
    ).then(response => response.data.data.id)

  const updatePressure = async (id, pressureData) =>
    executeWithLoading(
      () => settingStore.updatePressure(id, pressureData),
      "Pressure updated successfully!",
      "Failed to update pressure",
      response => (fetchedPressure.value = response.data.data),
    )

  const deletePressure = async pressureId =>
    executeWithLoading(
      () => settingStore.deletePressure(pressureId),
      "Pressure deleted successfully!",
      "Failed to delete pressure",
    ).then(() => fetchAllData())
    
  const fetchWeight = async id =>
    executeWithLoading(
      () => settingStore.fetchWeight(id),
      "Weight loaded successfully",
      "Failed to load weight data",
      response => (fetchedWeight.value = response.data.data),
    )
  
  const addWeight = async weightData =>
    executeWithLoading(
      () => settingStore.addWeight(weightData),
      "Weight created successfully!",
      "Failed to create weight",
    ).then(response => response.data.data.id)
  
  const updateWeight = async (id, weightData) =>
    executeWithLoading(
      () => settingStore.updateWeight(id, weightData),
      "Weight updated successfully!",
      "Failed to update weight",
      response => (fetchedWeight.value = response.data.data),
    )
  
  const deleteWeight = async weightId =>
    executeWithLoading(
      () => settingStore.deleteWeight(weightId),
      "Weight deleted successfully!",
      "Failed to delete weight",
    ).then(() => fetchAllData())

  const fetchBrand = async id =>
    executeWithLoading(
      () => settingStore.fetchBrand(id),
      "Brand loaded successfully",
      "Failed to load Brand data",
      response => (fetchedBrand.value = response.data.data),
    )
    
  const addBrand = async brandData =>
    executeWithLoading(
      () => settingStore.addBrand(brandData),
      "Brand created successfully!",
      "Failed to create brand",
    ).then(response => response.data.data.id)
    
  const updateBrand = async (id, brandData) =>
    executeWithLoading(
      () => settingStore.updateBrand(id, brandData),
      "Brand updated successfully!",
      "Failed to update brand",
      response => (fetchedBrand.value = response.data.data),
    )
    
  const deleteBrand = async brandId =>
    executeWithLoading(
      () => settingStore.deleteBrand(brandId),
      "Brand deleted successfully!",
      "Failed to delete brand",
    ).then(() => fetchAllData())
  
  return {
    fetchedPressure,
    fetchedWeight,
    fetchedBrand,
    pressures,
    weights,
    brands,
    loading,
    error,
    debouncedFetchAllData,
    fetchPressuresList,
    fetchWeightsList,
    fetchBrandsList,
    fetchPressure,
    addPressure,
    updatePressure,
    deletePressure,
    fetchWeight,
    addWeight,
    updateWeight,
    deleteWeight,
    fetchBrand,
    addBrand,
    updateBrand,
    deleteBrand,
  }
}
