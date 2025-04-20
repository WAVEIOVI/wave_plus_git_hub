<script setup>
import { useCategories } from '@/composables/product-asset-suite/useCategories'
import { useSnackbarStore } from '@/stores/useSnackbarStore'
import CategorieslistView from '@/views/product-asset-suite/categories/list/CategorieslistView.vue'

// ℹ️ - Declarations
const {
  searchQuery,
  selectedType,
  selectedStatus,
  categories,
  totalPage,
  totalCategories,
  loading,
  error,
  options,
  addCategory,
  fetchCategory,
  deleteCategory,
  fetchedCategory,
} = useCategories()

const snackbarStore = useSnackbarStore()
</script>

<template>
  <div>
    <!-- 👉 Categories list View -->
    <CategorieslistView
      :categories="categories"
      :total-page="totalPage"
      :total-categories="totalCategories"
      :options="options"
      :search-query="searchQuery"
      :selected-type="selectedType"
      :selected-status="selectedStatus"
      :loading="loading"
      :error="error"
      :add-category="addCategory"
      :fetch-category="fetchCategory"
      :delete-category="deleteCategory"
      :fetched-category="fetchedCategory"
      @update:search-query="val => searchQuery = val"
      @update:selected-type="val => selectedType = val"
      @update:selected-status="val => selectedStatus = val"
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
