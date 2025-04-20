import axios from "@/plugins/axios"
import { defineStore } from "pinia"

export const useProductStore = defineStore("ProductStore", {
  actions: {
    // SECTION Product API CALL
    // 👉 Fetch meta products
    fetchMetaProducts() {
      return new Promise((resolve, reject) => {
        axios
          .post("/api/meta-products/search")
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Fetch products data
    fetchProducts(params) {
      const filters = []
      if (params.type) {
        filters.push({
          field: "product_type",
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
            `/api/products/search?limit=${params.options.itemsPerPage}&page=${params.options.page}`,
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

    // 👉 fetch single product
    fetchProduct(id) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/products/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Add Product
    addProduct(productData) {
      return new Promise((resolve, reject) => {
        axios
          .post("/api/products", productData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 update product
    updateProduct(id, productData) {
      return new Promise((resolve, reject) => {
        axios
          .patch(`/api/products/${id}`, productData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Delete Product
    deleteProduct(id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/api/products/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 Print Products
    printProducts(params) {
      return new Promise((resolve, reject) => {
        // Get current language from localStorage or i18n
        const currentLang = localStorage.getItem('i18n_lang') || 'en'
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl'

        const filters = []
        
        if (params.type) {
          filters.push({
            field: "product_type",
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
    
        axios.post('/api/generate-products-list-pdf', {
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

    // 👉 Print Product Data
    printProductData(params) {
      return new Promise((resolve, reject) => {
        const currentLang = localStorage.getItem('i18n_lang') || 'en'
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl'
    
        axios.post('/api/generate-products-list-pdf', {
          orientation: params.orientation,
          id: params.id, // single product ID
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

    //!SECTION Product Contact API CALL
  },
})
