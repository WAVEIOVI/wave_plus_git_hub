<script setup>
import { useSuppliers } from '@/composables/business-partner/useSuppliers'
import { useSnackbarStore } from '@/stores/useSnackbarStore'
import SupplierslistView from '@/views/business-partner/suppliers/list/SupplierslistView.vue'

// ℹ️ - Declarations
const {
  searchQuery,
  selectedTier,
  selectedIndustry,
  selectedStatus,
  suppliers,
  totalPage,
  totalSuppliers,
  loading,
  error,
  options,
  statisticsData,
  addSupplier,
  fetchSupplier,
  deleteSupplier,
  printSuppliers,
  printSupplierData,
  fetchedSupplier,
} = useSuppliers()

const snackbarStore = useSnackbarStore()
</script>

<template>
  <div>
    <!-- 👉 Suppliers list View -->
    <SupplierslistView
      :suppliers="suppliers"
      :total-page="totalPage"
      :total-suppliers="totalSuppliers"
      :options="options"
      :search-query="searchQuery"
      :selected-tier="selectedTier"
      :selected-industry="selectedIndustry"
      :selected-status="selectedStatus"
      :loading="loading"
      :error="error"
      :statistics-data="statisticsData"
      :add-supplier="addSupplier"
      :fetch-supplier="fetchSupplier"
      :delete-supplier="deleteSupplier"
      :print-suppliers="printSuppliers"
      :print-supplier-data="printSupplierData"
      :fetched-supplier="fetchedSupplier"
      @update:search-query="val => searchQuery = val"
      @update:selected-tier="val => selectedTier = val"
      @update:selected-industry="val => selectedIndustry = val"
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
  </div>
</template>

<route lang="yaml">
meta:
  layout: default
  requiresAuth: true
  action: manage
  subject: BusinessPartner
</route>

