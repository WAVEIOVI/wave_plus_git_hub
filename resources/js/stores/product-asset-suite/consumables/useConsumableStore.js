import axios from "@/plugins/axios"
import { defineStore } from "pinia"

export const useConsumableStore = defineStore("ConsumableStore", {
  actions: {
    // SECTION Consumable API CALL
    // 👉 Fetch meta consumables
    fetchMetaConsumables() {
      return new Promise((resolve, reject) => {
        axios
          .post("/api/meta-consumables/search")
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Fetch consumables data
    fetchConsumables(params) {
      const filters = []
      if (params.type) {
        filters.push({
          field: "consumable_type",
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
            `/api/consumables/search?limit=${params.options.itemsPerPage}&page=${params.options.page}`,
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

    // 👉 fetch single consumable
    fetchConsumable(id) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/consumables/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Add Consumable
    addConsumable(consumableData) {
      return new Promise((resolve, reject) => {
        axios
          .post("/api/consumables", consumableData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 update consumable
    updateConsumable(id, consumableData) {
      return new Promise((resolve, reject) => {
        axios
          .patch(`/api/consumables/${id}`, consumableData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Delete Consumable
    deleteConsumable(id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/consumables/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Print Consumables
    printConsumables(params) {
      return new Promise((resolve, reject) => {
        // Get current language from localStorage or i18n
        const currentLang = localStorage.getItem('i18n_lang') || 'en'
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl'

        const filters = []
        
        if (params.type) {
          filters.push({
            field: "consumable_type",
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
    
        axios.post('/api/generate-consumables-list-pdf', {
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

    // 👉 Print Consumable Data
    printConsumableData(params) {
      return new Promise((resolve, reject) => {
        const currentLang = localStorage.getItem('i18n_lang') || 'en'
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl'
    
        axios.post('/api/generate-consumables-list-pdf', {
          orientation: params.orientation,
          id: params.id, // single consumable ID
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

    //!SECTION Consumable Contact API CALL
  },
})
