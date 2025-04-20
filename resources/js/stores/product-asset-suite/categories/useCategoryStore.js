import axios from "@/plugins/axios"
import { defineStore } from "pinia"

export const useCategoryStore = defineStore("CategoryStore", {
  actions: {
    // SECTION Category API CALL
    // 👉 Fetch categories data
    fetchCategories(params) {
      const filters = []
      if (params.status) {
        filters.push({
          field: "category_status",
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
            `/api/categories/search?limit=${params.options.itemsPerPage}&page=${params.options.page}`,
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

    // 👉 fetch single category
    fetchCategory(id) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/categories/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Add Category
    addCategory(categoryData) {
      return new Promise((resolve, reject) => {
        axios
          .post("/api/categories", categoryData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 update category
    updateCategory(id, categoryData) {
      return new Promise((resolve, reject) => {
        axios
          .patch(`/api/categories/${id}`, categoryData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Delete Category
    deleteCategory(id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/categories/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },


    // 👉 Fetch Category Subcategories
    fetchCategorySubcategories(params) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/categories/${params.id}/subcategories/search`, {
            search: { value: params.search },
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 fetch single Category Subcategory
    fetchCategorySubcategory(id, subcategoryId) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/categories/${id}/subcategories/${subcategoryId}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Add New Category Subcategory
    addCategorySubcategory(id, subcategoryData) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/api/categories/${id}/subcategories`, subcategoryData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 Update Category Subcategory
    updateCategorySubcategory(id, subcategoryId, subcategoryData) {
      return new Promise((resolve, reject) => {
        axios
          .patch(`/api/categories/${id}/subcategories/${subcategoryId}`, subcategoryData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    
    // 👉 Delete Category Subcategory
    deleteCategorySubcategory(id, subcategoryId) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/categories/${id}/subcategories/${subcategoryId}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    //!SECTION Category Subcategory API CALL
  },
})
