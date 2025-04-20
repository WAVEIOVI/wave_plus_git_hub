import i18n from "@/plugins/i18n"
import { useSupplierStore } from "@/stores/business-partner/suppliers/useSupplierStore"
import { useSnackbarStore } from "@/stores/useSnackbarStore"

// Helper function for handling API errors
const handleApiError = (err, defaultMessage) => {
  console.error(err)
  
  return err.response?.data?.message || i18n.global.t(defaultMessage)
}

export function useSuppliers(autoFetch = true) {
  const supplierStore = useSupplierStore()
  const snackbarStore = useSnackbarStore()

  // State Variables
  const fetchedSupplier = ref(null)
  const searchQuery = ref("")
  const selectedTier = ref(null)
  const selectedIndustry = ref(null)
  const selectedStatus = ref(null)

  const suppliers = ref([])
  const totalPage = ref(1)
  const totalSuppliers = ref(0)
  const loading = ref(false)
  const error = ref(null)

  const options = ref({
    page: 1,
    itemsPerPage: 10,
    sortBy: [],
    groupBy: [],
  })

  const statisticsData = ref({
    total_suppliers: 0,
    total_active_suppliers: 0,
    total_pending_suppliers: 0,
    total_inactive_suppliers: 0,
    total_blocked_suppliers: 0,
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

  const fetchSupplierMetadata = async () => {
    try {
      const metaResponse = await supplierStore.fetchMetaSuppliers()

      statisticsData.value = metaResponse.data.data.count
    } catch (err) {
      error.value = handleApiError(err, "Failed to load metadata")
      snackbarStore.showMessage(error.value, "error")
    }
  }

  const fetchSuppliersList = async () => {
    try {
      const suppliersResponse = await supplierStore.fetchSuppliers({
        search: searchQuery.value,
        status: selectedStatus.value,
        industry: selectedIndustry.value,
        tier: selectedTier.value,
        options: options.value,
      })

      suppliers.value = suppliersResponse.data.data
      totalPage.value = suppliersResponse.data.meta.last_page
      totalSuppliers.value = suppliersResponse.data.meta.total
      options.value.page = suppliersResponse.data.meta.current_page
    } catch (err) {
      error.value = handleApiError(err, "Failed to load suppliers")
      snackbarStore.showMessage(error.value, "error")
    }
  }

  const fetchAllData = async () => {
    loading.value = true
    error.value = null
    try {
      await Promise.all([fetchSuppliersList(), fetchSupplierMetadata()])
      snackbarStore.showMessage(i18n.global.t("Suppliers Loaded Successfully"))
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
      [searchQuery, selectedTier, selectedIndustry, selectedStatus, options],
      debouncedFetchAllData,
      { deep: true },
    )
  }

  const fetchSupplier = async id =>
    executeWithLoading(
      () => supplierStore.fetchSupplier(id),
      "Supplier loaded successfully",
      "Failed to load supplier data",
      response => (fetchedSupplier.value = response.data.data),
    )

  const addSupplier = async supplierData =>
    executeWithLoading(
      () => supplierStore.addSupplier(supplierData),
      "Supplier created successfully!",
      "Failed to create supplier",
    ).then(response => response.data.data.id)

  const updateSupplier = async (id, supplierData) =>
    executeWithLoading(
      () => supplierStore.updateSupplier(id, supplierData),
      "Supplier updated successfully!",
      "Failed to update supplier",
      response => (fetchedSupplier.value = response.data.data),
    )

  const deleteSupplier = async supplierId =>
    executeWithLoading(
      () => supplierStore.deleteSupplier(supplierId),
      "Supplier deleted successfully!",
      "Failed to delete supplier",
    ).then(() => fetchAllData())

  const fetchSupplierContacts = async ({ id }) =>
    executeWithLoading(
      () => supplierStore.fetchSupplierContacts({ id, options: options.value }),
      "Supplier contacts loaded successfully",
      "Failed to load supplier contacts",
    ).then(response => response.data.data)

  const fetchSupplierContact = async (id, contactId) =>
    executeWithLoading(
      () => supplierStore.fetchSupplierContact(id, contactId),
      "Contact loaded successfully",
      "Failed to load Contact data",
    )
    
  const addSupplierContact = async (id, contactData) =>
    executeWithLoading(
      () => supplierStore.addSupplierContact(id, contactData),
      "Contact added successfully!",
      "Failed to add contact",
    )
    
  const updateSupplierContact = async (id, contactId, contactData) =>
    executeWithLoading(
      () => supplierStore.updateSupplierContact(id, contactId, contactData),
      "Contact updated successfully!",
      "Failed to update contact",
    )

  const deleteSupplierContact = async (id, contactId) =>
    executeWithLoading(
      () => supplierStore.deleteSupplierContact(id, contactId),
      "Contact deleted successfully!",
      "Failed to delete Contact",
    )

  const fetchSupplierAddresses = async ({ id }) =>
    executeWithLoading(
      () => supplierStore.fetchSupplierAddresses({ id, options: options.value }),
      "Supplier addresses loaded successfully",
      "Failed to load supplier addresses",
    ).then(response => response.data.data)
  
  const fetchSupplierAddress = async (id, addressId) =>
    executeWithLoading(
      () => supplierStore.fetchSupplierAddress(id, addressId),
      "Address loaded successfully",
      "Failed to load Address data",
    )
      
  const addSupplierAddress = async (id, addressData) =>
    executeWithLoading(
      () => supplierStore.addSupplierAddress(id, addressData),
      "Address added successfully!",
      "Failed to add address",
    )
      
  const updateSupplierAddress = async (id, addressId, addressData) =>
    executeWithLoading(
      () => supplierStore.updateSupplierAddress(id, addressId, addressData),
      "Address updated successfully!",
      "Failed to update address",
    )
  
  const deleteSupplierAddress = async (id, addressId) =>
    executeWithLoading(
      () => supplierStore.deleteSupplierAddress(id, addressId),
      "Address deleted successfully!",
      "Failed to delete Address",
    )

  const printSuppliers = async () =>
    executeWithLoading(
      () =>
        supplierStore.printSuppliers({
          search: searchQuery.value,
          status: selectedStatus.value,
          industry: selectedIndustry.value,
          tier: selectedTier.value,
          sort: options.value.sortBy,
          orientation: "landscape",
        }),
      "PDF generation successful!",
      "Failed to generate PDF",
      response => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement("a")
  
        link.href = url
        link.setAttribute("download", `suppliers-${new Date().toISOString()}.pdf`)
        document.body.appendChild(link)
        link.click()
        link.remove()
      },
    )

  const printSupplierData = async id =>
    executeWithLoading(
      () => supplierStore.printSupplierData({
        id,
        orientation: "portrait",
      }),
      "PDF generation successful!",
      "Failed to generate PDF",
      response => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement("a")
    
        link.href = url
        link.setAttribute("download", `supplier-data-${id}-${new Date().toISOString()}.pdf`)
        document.body.appendChild(link)
        link.click()
        link.remove()
      },
    )
  
  return {
    fetchedSupplier,
    searchQuery,
    selectedTier,
    selectedIndustry,
    selectedStatus,
    suppliers,
    totalPage,
    totalSuppliers,
    loading,
    error,
    options,
    statisticsData,
    debouncedFetchAllData,
    fetchSupplier,
    addSupplier,
    updateSupplier,
    deleteSupplier,
    fetchSupplierContacts,
    fetchSupplierContact,
    addSupplierContact,
    updateSupplierContact,
    deleteSupplierContact,
    fetchSupplierAddresses,
    fetchSupplierAddress,
    addSupplierAddress,
    updateSupplierAddress,
    deleteSupplierAddress,
    printSuppliers,
    printSupplierData,
  }
}
