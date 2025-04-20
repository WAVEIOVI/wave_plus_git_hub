<script setup>
import { useConsumables } from '@/composables/product-asset-suite/useConsumables'
import { useSnackbarStore } from '@/stores/useSnackbarStore'
import ConsumableslistView from '@/views/product-asset-suite/consumables/list/ConsumableslistView.vue'

// ℹ️ - Declarations
const {
  searchQuery,
  selectedType,
  selectedSubcategory,
  selectedCategory,
  consumables,
  totalPage,
  totalConsumables,
  loading,
  error,
  options,
  statisticsData,
  addConsumable,
  fetchConsumable,
  deleteConsumable,
  printConsumables,
  printConsumableData,
  fetchedConsumable,
} = useConsumables()

const snackbarStore = useSnackbarStore()
</script>

<template>
  <div>
    <!-- 👉 Consumables list View -->
    <ConsumableslistView
      :consumables="consumables"
      :total-page="totalPage"
      :total-consumables="totalConsumables"
      :options="options"
      :search-query="searchQuery"
      :selected-type="selectedType"
      :selected-subcategory="selectedSubcategory"
      :selected-category="selectedCategory"
      :loading="loading"
      :error="error"
      :statistics-data="statisticsData"
      :add-consumable="addConsumable"
      :fetch-consumable="fetchConsumable"
      :delete-consumable="deleteConsumable"
      :print-consumables="printConsumables"
      :print-consumable-data="printConsumableData"
      :fetched-consumable="fetchedConsumable"
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

