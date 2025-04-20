import axios from "@/plugins/axios"
import { defineStore } from "pinia"

export const useSupplierStore = defineStore("SupplierStore", {
  actions: {
    // SECTION Supplier API CALL
    // 👉 Fetch meta suppliers
    fetchMetaSuppliers() {
      return new Promise((resolve, reject) => {
        axios
          .post("/api/meta-suppliers/search")
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Fetch suppliers data
    fetchSuppliers(params) {
      const filters = []
      if (params.tier) {
        filters.push({
          field: "supplier_tier",
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
          field: "supplier_status",
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
            `/api/suppliers/search?limit=${params.options.itemsPerPage}&page=${params.options.page}`,
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

    // 👉 fetch single supplier
    fetchSupplier(id) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/suppliers/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Add Supplier
    addSupplier(supplierData) {
      return new Promise((resolve, reject) => {
        axios
          .post("/api/suppliers", supplierData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 update supplier
    updateSupplier(id, supplierData) {
      return new Promise((resolve, reject) => {
        axios
          .patch(`/api/suppliers/${id}`, supplierData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Delete Supplier
    deleteSupplier(id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/suppliers/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },


    // 👉 Fetch Supplier Contacts
    fetchSupplierContacts(params) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/suppliers/${params.id}/contacts/search`, {
            search: { value: params.search },
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 fetch single Supplier Contact
    fetchSupplierContact(id, contactId) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/suppliers/${id}/contacts/${contactId}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Add New Supplier Contact
    addSupplierContact(id, contactData) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/suppliers/${id}/contacts`, contactData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 Update Supplier Contact
    updateSupplierContact(id, contactId, contactData) {
      return new Promise((resolve, reject) => {
        axios
          .patch(`/api/suppliers/${id}/contacts/${contactId}`, contactData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 Delete Supplier Contact
    deleteSupplierContact(id, contactId) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/suppliers/${id}/contacts/${contactId}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Fetch Supplier Addresses
    fetchSupplierAddresses(params) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/suppliers/${params.id}/addresses/search`, {
            search: { value: params.search },
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Fetch single Supplier Address
    fetchSupplierAddress(id, addressId) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/suppliers/${id}/addresses/${addressId}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Add New Supplier Address
    addSupplierAddress(id, addressData) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/suppliers/${id}/addresses`, addressData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 Update Supplier Address
    updateSupplierAddress(id, addressId, addressData) {
      return new Promise((resolve, reject) => {
        axios
          .patch(`/api/suppliers/${id}/addresses/${addressId}`, addressData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 Delete Supplier Address
    deleteSupplierAddress(id, addressId) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/suppliers/${id}/addresses/${addressId}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Print Suppliers
    printSuppliers(params) {
      return new Promise((resolve, reject) => {
        // Get current language from localStorage or i18n
        const currentLang = localStorage.getItem('i18n_lang') || 'en'
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl'

        const filters = []
        
        if (params.tier) {
          filters.push({
            field: "supplier_tier",
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
            field: "supplier_status",
            operator: "=",
            value: params.status,
          })
        }
    
        const sort = params.sort?.map(sortItem => ({
          field: sortItem.key,
          direction: sortItem.order === "asc" ? "asc" : "desc",
        })) || []
    
        axios.post('/api/generate-suppliers-list-pdf', {
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

    // 👉 Print Supplier Data
    printSupplierData(params) {
      return new Promise((resolve, reject) => {
        const currentLang = localStorage.getItem('i18n_lang') || 'en'
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl'
    
        axios.post('/api/generate-suppliers-list-pdf', {
          orientation: params.orientation,
          id: params.id, // single supplier ID
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

    //!SECTION Supplier Contact API CALL
  },
})
