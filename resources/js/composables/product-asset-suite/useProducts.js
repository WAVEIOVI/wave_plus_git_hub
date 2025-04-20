import i18n from "@/plugins/i18n"
import { useProductStore } from "@/stores/product-asset-suite/products/useProductStore"
import { useSnackbarStore } from "@/stores/useSnackbarStore"

// Helper function for handling API errors
const handleApiError = (err, defaultMessage) => {
  console.error(err)
  
  return err.response?.data?.message || i18n.global.t(defaultMessage)
}

export function useProducts(autoFetch = true) {
  const productStore = useProductStore()
  const snackbarStore = useSnackbarStore()

  // State Variables
  const fetchedProduct = ref(null)
  const searchQuery = ref("")
  const selectedType = ref(null)
  const selectedSubcategory = ref(null)
  const selectedCategory = ref(null)

  const products = ref([])
  const totalPage = ref(1)
  const totalProducts = ref(0)
  const loading = ref(false)
  const error = ref(null)

  const options = ref({
    page: 1,
    itemsPerPage: 10,
    sortBy: [],
    groupBy: [],
  })

  const statisticsData = ref({
    total_products: 0,
    total_physical_products: 0,
    total_service_products: 0,
    total_digital_products: 0,
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

  const fetchProductMetadata = async () => {
    try {
      const metaResponse = await productStore.fetchMetaProducts()

      statisticsData.value = metaResponse.data.data.count
    } catch (err) {
      error.value = handleApiError(err, "Failed to load metadata")
      snackbarStore.showMessage(error.value, "error")
    }
  }

  const fetchProductsList = async () => {
    try {
      const productsResponse = await productStore.fetchProducts({
        search: searchQuery.value,
        category: selectedCategory.value,
        subcategory: selectedSubcategory.value,
        type: selectedType.value,
        options: options.value,
      })

      products.value = productsResponse.data.data
      totalPage.value = productsResponse.data.meta.last_page
      totalProducts.value = productsResponse.data.meta.total
      options.value.page = productsResponse.data.meta.current_page
    } catch (err) {
      error.value = handleApiError(err, "Failed to load products")
      snackbarStore.showMessage(error.value, "error")
    }
  }

  const fetchAllData = async () => {
    loading.value = true
    error.value = null
    try {
      await Promise.all([fetchProductsList(), fetchProductMetadata()])
      snackbarStore.showMessage(i18n.global.t("Products Loaded Successfully"))
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

  const fetchProduct = async id =>
    executeWithLoading(
      () => productStore.fetchProduct(id),
      "Product loaded successfully",
      "Failed to load product data",
      response => (fetchedProduct.value = response.data.data),
    )

  const addProduct = async productData =>
    executeWithLoading(
      () => productStore.addProduct(productData),
      "Product created successfully!",
      "Failed to create product",
    ).then(response => response.data.data.id)

  const updateProduct = async (id, productData) =>
    executeWithLoading(
      () => productStore.updateProduct(id, productData),
      "Product updated successfully!",
      "Failed to update product",
      response => (fetchedProduct.value = response.data.data),
    )

  const deleteProduct = async productId =>
    executeWithLoading(
      () => productStore.deleteProduct(productId),
      "Product deleted successfully!",
      "Failed to delete product",
    ).then(() => fetchAllData())

  const printProducts = async () =>
    executeWithLoading(
      () =>
        productStore.printProducts({
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
        link.setAttribute("download", `products-${new Date().toISOString()}.pdf`)
        document.body.appendChild(link)
        link.click()
        link.remove()
      },
    )

  const printProductData = async id =>
    executeWithLoading(
      () => productStore.printProductData({
        id,
        orientation: "portrait",
      }),
      "PDF generation successful!",
      "Failed to generate PDF",
      response => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement("a")
    
        link.href = url
        link.setAttribute("download", `product-data-${id}-${new Date().toISOString()}.pdf`)
        document.body.appendChild(link)
        link.click()
        link.remove()
      },
    )
  
  return {
    fetchedProduct,
    searchQuery,
    selectedType,
    selectedSubcategory,
    selectedCategory,
    products,
    totalPage,
    totalProducts,
    loading,
    error,
    options,
    statisticsData,
    debouncedFetchAllData,
    fetchProduct,
    addProduct,
    updateProduct,
    deleteProduct,
    printProducts,
    printProductData,
  }
}
