<script setup>
import { useEqServicings } from '@/composables/product-asset-suite/useEqServicings'
import { useSettingStore } from "@/stores/product-asset-suite/settings/useSettingStore"
import { useSnackbarStore } from '@/stores/useSnackbarStore'
import EqServicingMainPanel from '@/views/product-asset-suite/equipment-servicings/edit/EqServicingMainPanel.vue'
import EqServicingSidePanel from '@/views/product-asset-suite/equipment-servicings/edit/EqServicingSidePanel.vue'
import DeleteEqServicingDialog from '@/views/product-asset-suite/equipment-servicings/list/dialogs/DeleteEqServicingDialog.vue'
import { useI18n } from 'vue-i18n'
import { onBeforeRouteLeave, useRoute, useRouter } from 'vue-router'

const snackbarStore = useSnackbarStore()
const settingStore = useSettingStore()
const brands = ref([])
const route = useRoute()
const router = useRouter()
const { t, locale } = useI18n()

// Use a dedicated ref for eqServicing ID
const selectedEqServicingId = ref(route.params.id)
const isDeleteEqServicingDialogVisible = ref(false)

// Helper to show snackbar messages
const showSnackbar = (text, color) => {
  snackbarStore.show = true
  snackbarStore.text = text
  snackbarStore.color = color
}

// Destructure methods from the useEqServicings composable
const {
  fetchedEqServicing,
  fetchEqServicing,
  updateEqServicing,
  deleteEqServicing,
  loading,
  error,
} = useEqServicings(false)

// Initialize localized fields if they don't exist
const ensureLocalizedFields = eqServicing => {
  if (!eqServicing) return

  // Define fields that should have localization
  const localizedFields = ['name', 'description', 'category_name', 'subcategory_name']
  
  localizedFields.forEach(field => {
    if (!eqServicing[field]) {
      eqServicing[field] = {}
    }
    
    if (!eqServicing[field][locale.value]) {
      eqServicing[field][locale.value] = ''
    }
  })
  
  return eqServicing
}

// Watch for eqServicing data and ensure it has the correct structure
watch(fetchedEqServicing, newVal => {
  if (newVal) {
    ensureLocalizedFields(newVal)
  }
}, { immediate: true })

// Methods for eqServicing update and delete actions
const handleUpdate = async () => {
  try {
    // Ensure all localized fields exist before updating
    ensureLocalizedFields(fetchedEqServicing.value)
    
    await updateEqServicing(selectedEqServicingId.value, fetchedEqServicing.value)
    showSnackbar("EqServicing updated successfully", "success")
    originalEqServicing.value = JSON.parse(JSON.stringify(fetchedEqServicing.value))
  } catch (err) {
    console.error("Update error:", err)
    showSnackbar("Error updating equipment Servicing", "error")
  }
}

const handleDelete = async () => {
  try {
    await deleteEqServicing(selectedEqServicingId.value)
    showSnackbar("Equipment Servicing deleted successfully", "success")
    router.push({ name: 'product-asset-suite-equipment-servicing-catalog-list' })
  } catch (err) {
    console.error("Delete error:", err)
    showSnackbar("Error deleting equipment servicing", "error")
  }
}

// Lifecycle hook
onMounted(async () => {
  await fetchEqServicing(selectedEqServicingId.value)
})

// Route leave handling
const originalEqServicing = ref(null)
const unsavedDialogVisible = ref(false)
const nextRoute = ref(null)

// Store original eqServicing data
watch(fetchedEqServicing, newVal => {
  if (newVal && !originalEqServicing.value) {
    // Ensure the eqServicing has all required fields before storing
    ensureLocalizedFields(newVal)
    originalEqServicing.value = JSON.parse(JSON.stringify(newVal))
  }
})

// Computed dirty flag
const isDirty = computed(() => {
  if (!originalEqServicing.value) return false
  
  return JSON.stringify(fetchedEqServicing.value) !== JSON.stringify(originalEqServicing.value)
})

// Navigation guard
onBeforeRouteLeave((to, from, next) => {
  if (isDirty.value) {
    unsavedDialogVisible.value = true
    next(false)
    nextRoute.value = to
  } else {
    next()
  }
})

// Dialog handlers
const confirmLeave = () => {
  originalEqServicing.value = JSON.parse(JSON.stringify(fetchedEqServicing.value))
  unsavedDialogVisible.value = false
  router.push(nextRoute.value.fullPath)
}

const cancelLeave = () => {
  unsavedDialogVisible.value = false
  nextRoute.value = null
}

// Helper to extract array from the response in case of nested data
const extractArray = response => {
  if (Array.isArray(response.data)) {
    return response.data
  }
  if (response.data && Array.isArray(response.data.data)) {
    return response.data.data
  }
  
  return []
}

// Fetch brands when the component is mounted
onMounted(async () => {
  try {
    const response = await settingStore.fetchBrands({ options: {} })
    const brandsArray = extractArray(response)
    if (Array.isArray(brandsArray)) {
      brands.value = brandsArray.map(brand => ({
        title: `${brand.name || ''}`,
        value: brand.name,
      }))
    } else {
      console.error('Unexpected response format:', response)
      brands.value = []
    }
  } catch (error) {
    console.error('Error fetching brands:', error)
    brands.value = []
  }
})
</script>

<template>
  <div>
    <!-- Header -->
    <div class="d-flex justify-space-between align-center flex-wrap gap-y-4 mb-6">
      <div>
        <h4 class="text-h4 mb-1">
          {{ $t('Equipment Servicing ID') }} #{{ fetchedEqServicing?.eq_servicing_id || 'Loading...' }}
        </h4>
        <div class="text-body-1">
          {{ fetchedEqServicing?.created_at ? new Date(fetchedEqServicing.created_at).toLocaleDateString() : '' }}
        </div>
      </div>
      <div class="d-flex gap-4">
        <VBtn
          variant="tonal"
          color="primary"
          @click="handleUpdate"
        >
          {{ $t('Update Equipment Servicing') }}
        </VBtn>
        <VBtn
          variant="tonal"
          color="error"
          @click="isDeleteEqServicingDialogVisible = true"
        >
          {{ $t('Delete Equipment Servicing') }}
        </VBtn>
      </div>
    </div>

    <VRow v-if="fetchedEqServicing">
      <EqServicingMainPanel
        v-model="fetchedEqServicing"
        :brands="brands"
      />
      <EqServicingSidePanel v-model="fetchedEqServicing" />
    </VRow>

    <div v-else>
      <VAlert
        type="error"
        variant="tonal"
      >
        {{ error || $t('EqServicing not found!') }}
      </VAlert>
    </div>

    <!-- Dialogs -->
    <DeleteEqServicingDialog
      v-model:isDialogVisible="isDeleteEqServicingDialogVisible"
      @delete-eq-servicing="handleDelete"
    />
    <UnsavedChangesDialog
      v-model="unsavedDialogVisible"
      :title="$t('Unsaved Changes')"
      :message="$t('You have unsaved changes. Do you really want to leave without saving?')"
      :confirm-text="$t('Leave')"
      :cancel-text="$t('Cancel')"
      @confirm="confirmLeave"
      @cancel="cancelLeave"
    />
    <LoadingDialog
      v-model="loading"
      :auto-close="false"
    />
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
