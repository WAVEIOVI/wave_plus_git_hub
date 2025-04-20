<script setup>
import { useClients } from '@/composables/business-partner/useClients'
import { useSnackbarStore } from '@/stores/useSnackbarStore'
import ClientslistView from '@/views/business-partner/clients/list/ClientslistView.vue'

// ℹ️ - Declarations
const {
  searchQuery,
  selectedTier,
  selectedIndustry,
  selectedStatus,
  clients,
  totalPage,
  totalClients,
  loading,
  error,
  options,
  statisticsData,
  addClient,
  fetchClient,
  deleteClient,
  printClients,
  printClientData,
  fetchedClient,
} = useClients()

const snackbarStore = useSnackbarStore()
</script>

<template>
  <div>
    <!-- 👉 Clients list View -->
    <ClientslistView
      :clients="clients"
      :total-page="totalPage"
      :total-clients="totalClients"
      :options="options"
      :search-query="searchQuery"
      :selected-tier="selectedTier"
      :selected-industry="selectedIndustry"
      :selected-status="selectedStatus"
      :loading="loading"
      :error="error"
      :statistics-data="statisticsData"
      :add-client="addClient"
      :fetch-client="fetchClient"
      :delete-client="deleteClient"
      :print-clients="printClients"
      :print-client-data="printClientData"
      :fetched-client="fetchedClient"
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
  subject: BusinessPartner
</route>

