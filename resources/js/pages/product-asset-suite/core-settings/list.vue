<script setup>
import { useSettings } from '@/composables/product-asset-suite/useSettings'
import { useSnackbarStore } from '@/stores/useSnackbarStore'
import BrandslistView from '@/views/product-asset-suite/settings/list/BrandslistView.vue'
import PressureslistView from '@/views/product-asset-suite/settings/list/PressureslistView.vue'
import WeightslistView from '@/views/product-asset-suite/settings/list/WeightslistView.vue'

// ℹ️ - Declarations
const {
  pressures,
  brands,
  weights,
  loading,
  error,
  addPressure,
  updatePressure,
  fetchPressure,
  deletePressure,
  fetchedPressure,
  addBrand,
  updateBrand,
  fetchBrand,
  deleteBrand,
  fetchedBrand,
  addWeight,
  updateWeight,
  fetchWeight,
  deleteWeight,
  fetchedWeight,
  fetchPressuresList,
  fetchWeightsList,
  fetchBrandsList,
} = useSettings()

// Helper to show snackbar messages
const showSnackbar = (text, color) => {
  snackbarStore.show = true
  snackbarStore.text = text
  snackbarStore.color = color
}

// Refresh the contact data list
const refreshPressures = async () => {
  try {
    fetchedPressure.value = await fetchPressuresList()
  } catch (err) {
    console.error("Error fetching pressures:", err)
  }
}

// Handler for Pressure edits
const handlePressureEdit = async pressureId => {
  await refreshPressures()
  showSnackbar("Pressure saved successfully", "success")
}

// Refresh the weight data list
const refreshWeights = async () => {
  try {
    fetchedWeight.value = await fetchWeightsList()
  } catch (err) {
    console.error("Error fetching weight:", err)
  }
}

// Handler for Weight edits
const handleWeightEdit = async weightId => {
  await refreshPressures()
  showSnackbar("Weight saved successfully", "success")
}

// Refresh the // Handler for Brand edits data list
const refreshBrands = async () => {
  try {
    fetchedBrand.value = await fetchBrandsList()
  } catch (err) {
    console.error("Error fetching brands:", err)
  }
}

// Handler for Brand edits
const handleBrandEdit = async BrandId => {
  await refreshBrands()
  showSnackbar("Brand saved successfully", "success")
}

const snackbarStore = useSnackbarStore()
</script>

<template>
  <div>
    <VAlert
      v-if="error"
      color="error"
      variant="tonal"
      class="mb-0"
      border="top"
    >
      {{ error }}
    </VAlert>
    <VRow>
      <VCol
        cols="12"
        md="6"
        lg="4"
      >
        <!-- 👉 Settings list View -->
        <PressureslistView
          :pressures="pressures"
          :loading="loading"
          :error="error"
          :add-pressure="addPressure"
          :update-pressure="updatePressure"
          :fetch-pressure="fetchPressure"
          :delete-pressure="deletePressure"
          :fetched-pressure="fetchedPressure"
          @edit-pressure="handlePressureEdit"
        />
      </VCol>
      <VCol
        cols="12"
        md="6"
        lg="4"
      >
        <BrandslistView
          :brands="brands"
          :loading="loading"
          :error="error"
          :add-brand="addBrand"
          :update-brand="updateBrand"
          :fetch-brand="fetchBrand"
          :delete-brand="deleteBrand"
          :fetched-brand="fetchedBrand"
          @edit-brand="handleBrandEdit"
        />
      </VCol>
      <VCol
        cols="12"
        md="6"
        lg="4"
      >
        <WeightslistView
          :weights="weights"
          :loading="loading"
          :error="error"
          :add-weight="addWeight"
          :update-weight="updateWeight"
          :fetch-weight="fetchWeight"
          :delete-weight="deleteWeight"
          :fetched-weight="fetchedWeight"
          @edit-weight="handleWeightEdit"
        />
      </VCol>
    </VRow>
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

