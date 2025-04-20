<script setup>
import { useEqServicings } from '@/composables/product-asset-suite/useEqServicings'
import { useSnackbarStore } from '@/stores/useSnackbarStore'
import EqServicingslistView from '@/views/product-asset-suite/equipment-servicings/list/EqServicingslistView.vue'

// ℹ️ - Declarations
const {
  searchQuery,
  selectedType,
  selectedSubcategory,
  selectedCategory,
  eqServicings,
  totalPage,
  totalEqServicings,
  loading,
  error,
  options,
  statisticsData,
  addEqServicing,
  fetchEqServicing,
  deleteEqServicing,
  printEqServicings,
  printEqServicingData,
  fetchedEqServicing,
} = useEqServicings()

const snackbarStore = useSnackbarStore()
</script>

<template>
  <div>
    <!-- 👉 EqServicings list View -->
    <EqServicingslistView
      :eq-servicings="eqServicings"
      :total-page="totalPage"
      :total-eq-servicings="totalEqServicings"
      :options="options"
      :search-query="searchQuery"
      :selected-type="selectedType"
      :selected-subcategory="selectedSubcategory"
      :selected-category="selectedCategory"
      :loading="loading"
      :error="error"
      :statistics-data="statisticsData"
      :add-eq-servicing="addEqServicing"
      :fetch-eq-servicing="fetchEqServicing"
      :delete-eq-servicing="deleteEqServicing"
      :print-eq-servicings="printEqServicings"
      :print-eq-servicing-data="printEqServicingData"
      :fetched-eq-servicing="fetchedEqServicing"
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

