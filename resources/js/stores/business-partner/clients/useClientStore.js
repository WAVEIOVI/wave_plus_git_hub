import axios from "@/plugins/axios"
import { defineStore } from "pinia"

export const useClientStore = defineStore("ClientStore", {
  actions: {
    // SECTION Client API CALL
    // 👉 Fetch meta clients
    fetchMetaClients() {
      return new Promise((resolve, reject) => {
        axios
          .post("/api/meta-clients/search")
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Fetch clients data
    fetchClients(params) {
      const filters = []
      if (params.tier) {
        filters.push({
          field: "client_tier",
          operator: "=",
          value: params.tier,
        })
      }
      if (params.industry) {
        filters.push({
          field: "industry",
          operator: "=",
          value: params.industry,
        })
      }
      if (params.status) {
        filters.push({
          field: "client_status",
          operator: "=",
          value: params.status,
        })
      }

      // Process sorting parameters
      const sort =
        params.options.sortBy?.map(sortItem => ({
          field: sortItem.key,
          direction: sortItem.order === "asc" ? "asc" : "desc",
        })) || []

      
      return new Promise((resolve, reject) => {
        axios
          .post(
            `/api/clients/search?limit=${params.options.itemsPerPage}&page=${params.options.page}`,
            {
              search: { value: params.search },
              filters,
              sort,
            },
          )
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 fetch single client
    fetchClient(id) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/clients/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Add Client
    addClient(clientData) {
      return new Promise((resolve, reject) => {
        axios
          .post("/api/clients", clientData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 update client
    updateClient(id, clientData) {
      return new Promise((resolve, reject) => {
        axios
          .patch(`/api/clients/${id}`, clientData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Delete Client
    deleteClient(id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/clients/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },


    // 👉 Fetch Client Contacts
    fetchClientContacts(params) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/clients/${params.id}/contacts/search`, {
            search: { value: params.search },
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 fetch single Client Contact
    fetchClientContact(id, contactId) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/clients/${id}/contacts/${contactId}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Add New Client Contact
    addClientContact(id, contactData) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/clients/${id}/contacts`, contactData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 Update Client Contact
    updateClientContact(id, contactId, contactData) {
      return new Promise((resolve, reject) => {
        axios
          .patch(`/api/clients/${id}/contacts/${contactId}`, contactData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 Delete Client Contact
    deleteClientContact(id, contactId) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/clients/${id}/contacts/${contactId}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Fetch Client Addresses
    fetchClientAddresses(params) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/clients/${params.id}/addresses/search`, {
            search: { value: params.search },
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Fetch single Client Address
    fetchClientAddress(id, addressId) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/clients/${id}/addresses/${addressId}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Add New Client Address
    addClientAddress(id, addressData) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/clients/${id}/addresses`, addressData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 Update Client Address
    updateClientAddress(id, addressId, addressData) {
      return new Promise((resolve, reject) => {
        axios
          .patch(`/api/clients/${id}/addresses/${addressId}`, addressData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 Delete Client Address
    deleteClientAddress(id, addressId) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/clients/${id}/addresses/${addressId}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Print Clients
    printClients(params) {
      return new Promise((resolve, reject) => {
        // Get current language from localStorage or i18n
        const currentLang = localStorage.getItem('i18n_lang') || 'en'
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl'

        const filters = []
        
        if (params.tier) {
          filters.push({
            field: "client_tier",
            operator: "=",
            value: params.tier,
          })
        }
        
        if (params.industry) {
          filters.push({
            field: "industry",
            operator: "=",
            value: params.industry,
          })
        }
        
        if (params.status) {
          filters.push({
            field: "client_status",
            operator: "=",
            value: params.status,
          })
        }
    
        const sort = params.sort?.map(sortItem => ({
          field: sortItem.key,
          direction: sortItem.order === "asc" ? "asc" : "desc",
        })) || []
    
        axios.post('/api/generate-clients-list-pdf', {
          search: params.search,
          filters,
          sort,
          orientation: params.orientation,
          lang: currentLang,
        }, {
          responseType: 'blob',
          headers: {
            'X-Localization': currentLang,
            'X-Is-Rtl': isRtl,
          },
        })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Print Client Data
    printClientData(params) {
      return new Promise((resolve, reject) => {
        const currentLang = localStorage.getItem('i18n_lang') || 'en'
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl'
    
        axios.post('/api/generate-clients-list-pdf', {
          orientation: params.orientation,
          id: params.id, // single client ID
          lang: currentLang,
        }, {
          responseType: 'blob',
          headers: {
            'X-Localization': currentLang,
            'X-Is-Rtl': isRtl,
          },
        })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    //!SECTION Client Contact API CALL
  },
})
