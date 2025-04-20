import axios from "@/plugins/axios"
import { defineStore } from "pinia"

export const useEqBlueprintStore = defineStore("EqBlueprintStore", {
  actions: {
    // SECTION EqBlueprint API CALL
    // 👉 Fetch meta eqBlueprints
    fetchMetaEqBlueprints() {
      return new Promise((resolve, reject) => {
        axios
          .post("/api/meta-equipment-blueprints/search")
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Fetch eqBlueprints data
    fetchEqBlueprints(params) {
      const filters = []
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
            `/api/equipment-blueprints/search?limit=${params.options.itemsPerPage}&page=${params.options.page}`,
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

    // 👉 fetch single eqBlueprint
    fetchEqBlueprint(id) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/equipment-blueprints/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Add EqBlueprint
    addEqBlueprint(eqBlueprintData) {
      return new Promise((resolve, reject) => {
        axios
          .post("/api/equipment-blueprints", eqBlueprintData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 update eqBlueprint
    updateEqBlueprint(id, eqBlueprintData) {
      return new Promise((resolve, reject) => {
        axios
          .patch(`/api/equipment-blueprints/${id}`, eqBlueprintData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Delete EqBlueprint
    deleteEqBlueprint(id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/equipment-blueprints/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Fetch EqBlueprint Capacities
    fetchEqBlueprintCapacities(params) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/equipment-blueprints/${params.id}/capacities/search`, {
            search: { value: params.search },
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 fetch single EqBlueprint Capacity
    fetchEqBlueprintCapacity(id, capacityId) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/equipment-blueprints/${id}/capacities/${capacityId}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Attach New EqBlueprint Capacity
    attachEqBlueprintCapacity(id, capacityData) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/equipment-blueprints/${id}/capacities/attach`, capacityData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 Detach EqBlueprint Capacity
    detachEqBlueprintCapacity(id, capacityId) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/equipment-blueprints/${id}/capacities/detach`, {
            data: {
              resources: [capacityId],
            },
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },


    // 👉 Fetch EqBlueprint Pressures
    fetchEqBlueprintPressures(params) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/equipment-blueprints/${params.id}/pressures/search`, {
            search: { value: params.search },
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 fetch single EqBlueprint Pressure
    fetchEqBlueprintPressure(id, pressureId) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/equipment-blueprints/${id}/pressures/${pressureId}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Attach New EqBlueprint Pressure
    attachEqBlueprintPressure(id, pressureData) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/equipment-blueprints/${id}/pressures/attach`, pressureData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 Detach EqBlueprint Pressure
    detachEqBlueprintPressure(id, pressureId) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/equipment-blueprints/${id}/pressures/detach`, {
            data: {
              resources: [pressureId],
            },
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Fetch EqBlueprint Consumables
    fetchEqBlueprintConsumables(params) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/equipment-blueprints/${params.id}/consumables/search`, {
            search: { value: params.search },
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 fetch single EqBlueprint Consumable
    fetchEqBlueprintConsumable(id, consumableId) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/equipment-blueprints/${id}/consumables/${consumableId}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 Attach New EqBlueprint Consumable
    attachEqBlueprintConsumable(id, consumableData) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/equipment-blueprints/${id}/consumables/attach`, consumableData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
        
    // 👉 Detach EqBlueprint Consumable
    detachEqBlueprintConsumable(id, consumableId) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/equipment-blueprints/${id}/consumables/detach`, {
            data: {
              resources: [consumableId],
            },
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Fetch EqBlueprint EqServicings
    fetchEqBlueprintEqServicings(params) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/equipment-blueprints/${params.id}/equipment-Servicings/search`, {
            search: { value: params.search },
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
        
    // 👉 fetch single EqBlueprint EqServicing
    fetchEqBlueprintEqServicing(id, eqServicingId) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/equipment-blueprints/${id}/equipment-Servicings/${eqServicingId}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
        
    // 👉 Attach New EqBlueprint eqServicing
    attachEqBlueprintEqServicing(id, eqServicingData) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/equipment-blueprints/${id}/equipment-Servicings/attach`, eqServicingData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
            
    // 👉 Detach EqBlueprint EqServicing
    detachEqBlueprintEqServicing(id, eqServicingId) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/equipment-blueprints/${id}/equipment-Servicings/detach`, {
            data: {
              resources: [eqServicingId],
            },
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Print EqBlueprints
    printEqBlueprints(params) {
      return new Promise((resolve, reject) => {
        // Get current language from localStorage or i18n
        const currentLang = localStorage.getItem('i18n_lang') || 'en'
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl'

        const filters = []
        
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
    
        axios.post('/api/generate-equipment-blueprint-list-pdf', {
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

    // 👉 Print EqBlueprint Data
    printEqBlueprintData(params) {
      return new Promise((resolve, reject) => {
        const currentLang = localStorage.getItem('i18n_lang') || 'en'
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl'
    
        axios.post('/api/generate-equipment-blueprint-pdf', {
          orientation: params.orientation,
          id: params.id, // single eqBlueprint ID
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

    //!SECTION EqBlueprint Capacity API CALL
  },
})
