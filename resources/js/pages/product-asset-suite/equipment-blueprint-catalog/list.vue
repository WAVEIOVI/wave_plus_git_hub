<script setup>
import { useEqBlueprints } from '@/composables/product-asset-suite/useEqBlueprints'
import { useSnackbarStore } from '@/stores/useSnackbarStore'
import EqBlueprintslistView from '@/views/product-asset-suite/equipment-blueprints/list/EqBlueprintslistView.vue'

// ℹ️ - Declarations
const {
  searchQuery,
  selectedSubcategory,
  selectedCategory,
  eqBlueprints,
  totalPage,
  totalEqBlueprints,
  loading,
  error,
  options,
  statisticsData,
  addEqBlueprint,
  fetchEqBlueprint,
  deleteEqBlueprint,
  printEqBlueprints,
  printEqBlueprintData,
  fetchedEqBlueprint,
} = useEqBlueprints()

const snackbarStore = useSnackbarStore()
</script>

<template>
  <div>
    <!-- 👉 EqBlueprints list View -->
    <EqBlueprintslistView
      :eq-blueprints="eqBlueprints"
      :total-page="totalPage"
      :total-eq-blueprints="totalEqBlueprints"
      :options="options"
      :search-query="searchQuery"
      :selected-subcategory="selectedSubcategory"
      :selected-category="selectedCategory"
      :loading="loading"
      :error="error"
      :statistics-data="statisticsData"
      :add-eq-blueprint="addEqBlueprint"
      :fetch-eq-blueprint="fetchEqBlueprint"
      :delete-eq-blueprint="deleteEqBlueprint"
      :print-eq-blueprints="printEqBlueprints"
      :print-eq-blueprint-data="printEqBlueprintData"
      :fetched-eq-blueprint="fetchedEqBlueprint"
      @update:search-query="val => searchQuery = val"
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

