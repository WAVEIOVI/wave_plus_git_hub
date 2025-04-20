import i18n from "@/plugins/i18n"
import { useClientStore } from "@/stores/business-partner/clients/useClientStore"
import { useSnackbarStore } from "@/stores/useSnackbarStore"

// Helper function for handling API errors
const handleApiError = (err, defaultMessage) => {
  console.error(err)
  
  return err.response?.data?.message || i18n.global.t(defaultMessage)
}

export function useClients(autoFetch = true) {
  const clientStore = useClientStore()
  const snackbarStore = useSnackbarStore()

  // State Variables
  const fetchedClient = ref(null)
  const searchQuery = ref("")
  const selectedTier = ref(null)
  const selectedIndustry = ref(null)
  const selectedStatus = ref(null)

  const clients = ref([])
  const totalPage = ref(1)
  const totalClients = ref(0)
  const loading = ref(false)
  const error = ref(null)

  const options = ref({
    page: 1,
    itemsPerPage: 10,
    sortBy: [],
    groupBy: [],
  })

  const statisticsData = ref({
    total_clients: 0,
    total_active_clients: 0,
    total_pending_clients: 0,
    total_inactive_clients: 0,
    total_blocked_clients: 0,
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

  const fetchClientMetadata = async () => {
    try {
      const metaResponse = await clientStore.fetchMetaClients()

      statisticsData.value = metaResponse.data.data.count
    } catch (err) {
      error.value = handleApiError(err, "Failed to load metadata")
      snackbarStore.showMessage(error.value, "error")
    }
  }

  const fetchClientsList = async () => {
    try {
      const clientsResponse = await clientStore.fetchClients({
        search: searchQuery.value,
        status: selectedStatus.value,
        industry: selectedIndustry.value,
        tier: selectedTier.value,
        options: options.value,
      })

      clients.value = clientsResponse.data.data
      totalPage.value = clientsResponse.data.meta.last_page
      totalClients.value = clientsResponse.data.meta.total
      options.value.page = clientsResponse.data.meta.current_page
    } catch (err) {
      error.value = handleApiError(err, "Failed to load clients")
      snackbarStore.showMessage(error.value, "error")
    }
  }

  const fetchAllData = async () => {
    loading.value = true
    error.value = null
    try {
      await Promise.all([fetchClientsList(), fetchClientMetadata()])
      snackbarStore.showMessage(i18n.global.t("Clients Loaded Successfully"))
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

  const fetchClient = async id =>
    executeWithLoading(
      () => clientStore.fetchClient(id),
      "Client loaded successfully",
      "Failed to load client data",
      response => (fetchedClient.value = response.data.data),
    )

  const addClient = async clientData =>
    executeWithLoading(
      () => clientStore.addClient(clientData),
      "Client created successfully!",
      "Failed to create client",
    ).then(response => response.data.data.id)

  const updateClient = async (id, clientData) =>
    executeWithLoading(
      () => clientStore.updateClient(id, clientData),
      "Client updated successfully!",
      "Failed to update client",
      response => (fetchedClient.value = response.data.data),
    )

  const deleteClient = async clientId =>
    executeWithLoading(
      () => clientStore.deleteClient(clientId),
      "Client deleted successfully!",
      "Failed to delete client",
    ).then(() => fetchAllData())

  const fetchClientContacts = async ({ id }) =>
    executeWithLoading(
      () => clientStore.fetchClientContacts({ id, options: options.value }),
      "Client contacts loaded successfully",
      "Failed to load client contacts",
    ).then(response => response.data.data)

  const fetchClientContact = async (id, contactId) =>
    executeWithLoading(
      () => clientStore.fetchClientContact(id, contactId),
      "Contact loaded successfully",
      "Failed to load Contact data",
    )
    
  const addClientContact = async (id, contactData) =>
    executeWithLoading(
      () => clientStore.addClientContact(id, contactData),
      "Contact added successfully!",
      "Failed to add contact",
    )
    
  const updateClientContact = async (id, contactId, contactData) =>
    executeWithLoading(
      () => clientStore.updateClientContact(id, contactId, contactData),
      "Contact updated successfully!",
      "Failed to update contact",
    )

  const deleteClientContact = async (id, contactId) =>
    executeWithLoading(
      () => clientStore.deleteClientContact(id, contactId),
      "Contact deleted successfully!",
      "Failed to delete Contact",
    )

  const fetchClientAddresses = async ({ id }) =>
    executeWithLoading(
      () => clientStore.fetchClientAddresses({ id, options: options.value }),
      "Client addresses loaded successfully",
      "Failed to load client addresses",
    ).then(response => response.data.data)
  
  const fetchClientAddress = async (id, addressId) =>
    executeWithLoading(
      () => clientStore.fetchClientAddress(id, addressId),
      "Address loaded successfully",
      "Failed to load Address data",
    )
      
  const addClientAddress = async (id, addressData) =>
    executeWithLoading(
      () => clientStore.addClientAddress(id, addressData),
      "Address added successfully!",
      "Failed to add address",
    )
      
  const updateClientAddress = async (id, addressId, addressData) =>
    executeWithLoading(
      () => clientStore.updateClientAddress(id, addressId, addressData),
      "Address updated successfully!",
      "Failed to update address",
    )
  
  const deleteClientAddress = async (id, addressId) =>
    executeWithLoading(
      () => clientStore.deleteClientAddress(id, addressId),
      "Address deleted successfully!",
      "Failed to delete Address",
    )

  const printClients = async () =>
    executeWithLoading(
      () =>
        clientStore.printClients({
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
        link.setAttribute("download", `clients-${new Date().toISOString()}.pdf`)
        document.body.appendChild(link)
        link.click()
        link.remove()
      },
    )

  const printClientData = async id =>
    executeWithLoading(
      () => clientStore.printClientData({
        id,
        orientation: "portrait",
      }),
      "PDF generation successful!",
      "Failed to generate PDF",
      response => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement("a")
    
        link.href = url
        link.setAttribute("download", `client-data-${id}-${new Date().toISOString()}.pdf`)
        document.body.appendChild(link)
        link.click()
        link.remove()
      },
    )
  
  return {
    fetchedClient,
    searchQuery,
    selectedTier,
    selectedIndustry,
    selectedStatus,
    clients,
    totalPage,
    totalClients,
    loading,
    error,
    options,
    statisticsData,
    debouncedFetchAllData,
    fetchClient,
    addClient,
    updateClient,
    deleteClient,
    fetchClientContacts,
    fetchClientContact,
    addClientContact,
    updateClientContact,
    deleteClientContact,
    fetchClientAddresses,
    fetchClientAddress,
    addClientAddress,
    updateClientAddress,
    deleteClientAddress,
    printClients,
    printClientData,
  }
}
