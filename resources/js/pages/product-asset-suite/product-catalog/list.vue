<script setup>
import { useProducts } from '@/composables/product-asset-suite/useProducts'
import { useSnackbarStore } from '@/stores/useSnackbarStore'
import ProductslistView from '@/views/product-asset-suite/products/list/ProductslistView.vue'

// ℹ️ - Declarations
const {
  searchQuery,
  selectedType,
  selectedSubcategory,
  selectedCategory,
  products,
  totalPage,
  totalProducts,
  loading,
  error,
  options,
  statisticsData,
  addProduct,
  fetchProduct,
  deleteProduct,
  printProducts,
  printProductData,
  fetchedProduct,
} = useProducts()

const snackbarStore = useSnackbarStore()
</script>

<template>
  <div>
    <!-- 👉 Products list View -->
    <ProductslistView
      :products="products"
      :total-page="totalPage"
      :total-products="totalProducts"
      :options="options"
      :search-query="searchQuery"
      :selected-type="selectedType"
      :selected-subcategory="selectedSubcategory"
      :selected-category="selectedCategory"
      :loading="loading"
      :error="error"
      :statistics-data="statisticsData"
      :add-product="addProduct"
      :fetch-product="fetchProduct"
      :delete-product="deleteProduct"
      :print-products="printProducts"
      :print-product-data="printProductData"
      :fetched-product="fetchedProduct"
      @update:search-query="val => searchQuery = val"
      @update:selected-type="val => selectedType = val"
      @update:selected-subcategory="val => selectedSubcategory = val"
      @update:selected-category="val => selectedCategory = val"
      @update:options="val => options = val"
    />
    <!-- Snackbar -->
    <VSnackbar
      v-model="snackbarStore.show"
      :color="snackbarStore.color"
      :timeout="snackbarStore.timeout"
      transition="scale-transition"
      variant="tonal"
    >
      <div style="text-align: center;">
        {{ snackbarStore.text }}
      </div>
    </VSnackbar>
    <LoadingDialog
      v-model="loading"
      :auto-close="false"
    />
  </div>
</template>

<route lang="yaml">
meta:
  layout: default
  requiresAuth: true
  action: manage
  subject: ProductAssetSuite
</route>

