import i18n from "@/plugins/i18n"
import { useCategoryStore } from "@/stores/product-asset-suite/categories/useCategoryStore"
import { useSnackbarStore } from "@/stores/useSnackbarStore"

// Helper function for handling API errors
const handleApiError = (err, defaultMessage) => {
  console.error(err)
  
  return err.response?.data?.message || i18n.global.t(defaultMessage)
}

export function useCategories(autoFetch = true) {
  const categoryStore = useCategoryStore()
  const snackbarStore = useSnackbarStore()

  // State Variables
  const fetchedCategory = ref(null)
  const searchQuery = ref("")
  const selectedStatus = ref(null)

  const categories = ref([])
  const totalPage = ref(1)
  const totalCategories = ref(0)
  const loading = ref(false)
  const error = ref(null)

  const options = ref({
    page: 1,
    itemsPerPage: 10,
    sortBy: [],
    groupBy: [],
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

  const fetchCategoriesList = async () => {
    try {
      const categoriesResponse = await categoryStore.fetchCategories({
        search: searchQuery.value,
        status: selectedStatus.value,
        options: options.value,
      })

      categories.value = categoriesResponse.data.data
      totalPage.value = categoriesResponse.data.meta.last_page
      totalCategories.value = categoriesResponse.data.meta.total
      options.value.page = categoriesResponse.data.meta.current_page
    } catch (err) {
      error.value = handleApiError(err, "Failed to load categories")
      snackbarStore.showMessage(error.value, "error")
    }
  }

  const fetchAllData = async () => {
    loading.value = true
    error.value = null
    try {
      await Promise.all([fetchCategoriesList()])
      snackbarStore.showMessage(i18n.global.t("Categories Loaded Successfully"))
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
      [searchQuery, selectedStatus, options],
      debouncedFetchAllData,
      { deep: true },
    )
  }

  const fetchCategory = async id =>
    executeWithLoading(
      () => categoryStore.fetchCategory(id),
      "Category loaded successfully",
      "Failed to load category data",
      response => (fetchedCategory.value = response.data.data),
    )

  const addCategory = async categoryData =>
    executeWithLoading(
      () => categoryStore.addCategory(categoryData),
      "Category created successfully!",
      "Failed to create category",
    ).then(response => response.data.data.id)

  const updateCategory = async (id, categoryData) =>
    executeWithLoading(
      () => categoryStore.updateCategory(id, categoryData),
      "Category updated successfully!",
      "Failed to update category",
      response => (fetchedCategory.value = response.data.data),
    )

  const deleteCategory = async categoryId =>
    executeWithLoading(
      () => categoryStore.deleteCategory(categoryId),
      "Category deleted successfully!",
      "Failed to delete category",
    ).then(() => fetchAllData())

  const fetchCategorySubcategories = async ({ id }) =>
    executeWithLoading(
      () => categoryStore.fetchCategorySubcategories({ id, options: options.value }),
      "Category subcategories loaded successfully",
      "Failed to load category subcategories",
    ).then(response => response.data.data)

  const fetchCategorySubcategory = async (id, subcategoryId) =>
    executeWithLoading(
      () => categoryStore.fetchCategorySubcategory(id, subcategoryId),
      "Subcategory loaded successfully",
      "Failed to load Subcategory data",
    )
    
  const addCategorySubcategory = async (id, subcategoryData) =>
    executeWithLoading(
      () => categoryStore.addCategorySubcategory(id, subcategoryData),
      "Subcategory added successfully!",
      "Failed to add subcategory",
    )
    
  const updateCategorySubcategory = async (id, subcategoryId, subcategoryData) =>
    executeWithLoading(
      () => categoryStore.updateCategorySubcategory(id, subcategoryId, subcategoryData),
      "Subcategory updated successfully!",
      "Failed to update subcategory",
    )

  const deleteCategorySubcategory = async (id, subcategoryId) =>
    executeWithLoading(
      () => categoryStore.deleteCategorySubcategory(id, subcategoryId),
      "Subcategory deleted successfully!",
      "Failed to delete Subcategory",
    )
  
  return {
    fetchedCategory,
    searchQuery,
    selectedStatus,
    categories,
    totalPage,
    totalCategories,
    loading,
    error,
    options,
    debouncedFetchAllData,
    fetchCategory,
    addCategory,
    updateCategory,
    deleteCategory,
    fetchCategorySubcategories,
    fetchCategorySubcategory,
    addCategorySubcategory,
    updateCategorySubcategory,
    deleteCategorySubcategory,
  }
}
