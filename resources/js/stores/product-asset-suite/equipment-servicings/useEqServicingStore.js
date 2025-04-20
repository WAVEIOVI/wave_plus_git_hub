import axios from "@/plugins/axios"
import { defineStore } from "pinia"

export const useEqServicingStore = defineStore("EqServicingStore", {
  actions: {
    // SECTION EqServicing API CALL
    // 👉 Fetch meta eqServicings
    fetchMetaEqServicings() {
      return new Promise((resolve, reject) => {
        axios
          .post("/api/meta-equipment-servicings/search")
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Fetch eqServicings data
    fetchEqServicings(params) {
      const filters = []
      if (params.type) {
        filters.push({
          field: "eqServicing_type",
          operator: "=",
          value: params.type,
        })
      }
      if (params.category) {
        filters.push({
          field: "category_id",
          operator: "=",
          value: params.category,
        })
      }
      if (params.subcategory) {
        filters.push({
          field: "subcategory_id",
          operator: "=",
          value: params.subcategory,
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
            `/api/equipment-servicings/search?limit=${params.options.itemsPerPage}&page=${params.options.page}`,
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

    // 👉 fetch single eqServicing
    fetchEqServicing(id) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/equipment-servicings/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Add EqServicing
    addEqServicing(eqServicingData) {
      return new Promise((resolve, reject) => {
        axios
          .post("/api/equipment-servicings", eqServicingData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 update eqServicing
    updateEqServicing(id, eqServicingData) {
      return new Promise((resolve, reject) => {
        axios
          .patch(`/api/equipment-servicings/${id}`, eqServicingData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Delete EqServicing
    deleteEqServicing(id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/equipment-servicings/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Print EqServicings
    printEqServicings(params) {
      return new Promise((resolve, reject) => {
        // Get current language from localStorage or i18n
        const currentLang = localStorage.getItem('i18n_lang') || 'en'
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl'

        const filters = []
        
        if (params.type) {
          filters.push({
            field: "eqServicing_type",
            operator: "=",
            value: params.type,
          })
        }
        
        if (params.category) {
          filters.push({
            field: "category_id",
            operator: "=",
            value: params.category,
          })
        }
        
        if (params.subcategory) {
          filters.push({
            field: "subcategory_id",
            operator: "=",
            value: params.subcategory,
          })
        }
    
        const sort = params.sort?.map(sortItem => ({
          field: sortItem.key,
          direction: sortItem.order === "asc" ? "asc" : "desc",
        })) || []
    
        axios.post('/api/generate-equipment-servicing-list-pdf', {
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

    // 👉 Print EqServicing Data
    printEqServicingData(params) {
      return new Promise((resolve, reject) => {
        const currentLang = localStorage.getItem('i18n_lang') || 'en'
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl'
    
        axios.post('/api/generate-equipment-servicing-pdf', {
          orientation: params.orientation,
          id: params.id, // single eqServicing ID
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

    //!SECTION EqServicing Contact API CALL
  },
})
