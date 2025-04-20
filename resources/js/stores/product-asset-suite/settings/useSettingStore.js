import axios from "@/plugins/axios"
import { defineStore } from "pinia"

export const useSettingStore = defineStore("SettingStore", {
  actions: {
    // SECTION Setting API CALL

    // 👉 Fetch pressures data
    fetchPressures() {
      
      return new Promise((resolve, reject) => {
        axios
          .post(
            `/api/pressures/search`,
          )
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 fetch single pressure
    fetchPressure(id) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/pressures/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Add Pressure
    addPressure(pressureData) {
      return new Promise((resolve, reject) => {
        axios
          .post("/api/pressures", pressureData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 update pressure
    updatePressure(id, pressureData) {
      return new Promise((resolve, reject) => {
        axios
          .patch(`/api/pressures/${id}`, pressureData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Delete Pressure
    deletePressure(id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/pressures/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Fetch weights data
    fetchWeights() {
      
      return new Promise((resolve, reject) => {
        axios
          .post(
            `/api/weights/search`,
          )
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 fetch single weight
    fetchWeight(id) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/weights/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 Add Weight
    addWeight(weightData) {
      return new Promise((resolve, reject) => {
        axios
          .post("/api/weights", weightData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 update weight
    updateWeight(id, weightData) {
      return new Promise((resolve, reject) => {
        axios
          .patch(`/api/weights/${id}`, weightData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 Delete Weight
    deleteWeight(id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/weights/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Fetch brands data
    fetchBrands() {
      
      return new Promise((resolve, reject) => {
        axios
          .post(
            `/api/brands/search`,
          )
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
        
    // 👉 fetch single brand
    fetchBrand(id) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/brands/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
        
    // 👉 Add Brand
    addBrand(brandData) {
      return new Promise((resolve, reject) => {
        axios
          .post("/api/brands", brandData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
        
    // 👉 update brand
    updateBrand(id, brandData) {
      return new Promise((resolve, reject) => {
        axios
          .patch(`/api/brands/${id}`, brandData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
        
    // 👉 Delete Brand
    deleteBrand(id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/brands/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    //!SECTION Setting Settings API CALL
  },
})
