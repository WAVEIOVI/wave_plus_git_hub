<script setup>
import { useConsumables } from '@/composables/product-asset-suite/useConsumables'
import { useSettingStore } from "@/stores/product-asset-suite/settings/useSettingStore"
import { useSnackbarStore } from '@/stores/useSnackbarStore'
import ConsumableMainPanel from '@/views/product-asset-suite/consumables/edit/ConsumableMainPanel.vue'
import ConsumableSidePanel from '@/views/product-asset-suite/consumables/edit/ConsumableSidePanel.vue'
import DeleteConsumableDialog from '@/views/product-asset-suite/consumables/list/dialogs/DeleteConsumableDialog.vue'
import { useI18n } from 'vue-i18n'
import { onBeforeRouteLeave, useRoute, useRouter } from 'vue-router'

const snackbarStore = useSnackbarStore()
const settingStore = useSettingStore()
const brands = ref([])
const route = useRoute()
const router = useRouter()
const { t, locale } = useI18n()

// Use a dedicated ref for consumable ID
const selectedConsumableId = ref(route.params.id)
const isDeleteConsumableDialogVisible = ref(false)

// Helper to show snackbar messages
const showSnackbar = (text, color) => {
  snackbarStore.show = true
  snackbarStore.text = text
  snackbarStore.color = color
}

// Destructure methods from the useConsumables composable
const {
  fetchedConsumable,
  fetchConsumable,
  updateConsumable,
  deleteConsumable,
  loading,
  error,
} = useConsumables(false)

// Initialize localized fields if they don't exist
const ensureLocalizedFields = consumable => {
  if (!consumable) return

  // Define fields that should have localization
  const localizedFields = ['name', 'description', 'category_name', 'subcategory_name']
  
  localizedFields.forEach(field => {
    if (!consumable[field]) {
      consumable[field] = {}
    }
    
    if (!consumable[field][locale.value]) {
      consumable[field][locale.value] = ''
    }
  })
  
  return consumable
}

// Watch for consumable data and ensure it has the correct structure
watch(fetchedConsumable, newVal => {
  if (newVal) {
    ensureLocalizedFields(newVal)
  }
}, { immediate: true })

// Methods for consumable update and delete actions
const handleUpdate = async () => {
  try {
    // Ensure all localized fields exist before updating
    ensureLocalizedFields(fetchedConsumable.value)
    
    await updateConsumable(selectedConsumableId.value, fetchedConsumable.value)
    showSnackbar("Consumable updated successfully", "success")
    originalConsumable.value = JSON.parse(JSON.stringify(fetchedConsumable.value))
  } catch (err) {
    console.error("Update error:", err)
    showSnackbar("Error updating consumable", "error")
  }
}

const handleDelete = async () => {
  try {
    await deleteConsumable(selectedConsumableId.value)
    showSnackbar("Consumable deleted successfully", "success")
    router.push({ name: 'product-asset-suite-consumable-catalog-list' })
  } catch (err) {
    console.error("Delete error:", err)
    showSnackbar("Error deleting consumable", "error")
  }
}

// Lifecycle hook
onMounted(async () => {
  await fetchConsumable(selectedConsumableId.value)
})

// Route leave handling
const originalConsumable = ref(null)
const unsavedDialogVisible = ref(false)
const nextRoute = ref(null)

// Store original consumable data
watch(fetchedConsumable, newVal => {
  if (newVal && !originalConsumable.value) {
    // Ensure the consumable has all required fields before storing
    ensureLocalizedFields(newVal)
    originalConsumable.value = JSON.parse(JSON.stringify(newVal))
  }
})

// Computed dirty flag
const isDirty = computed(() => {
  if (!originalConsumable.value) return false
  
  return JSON.stringify(fetchedConsumable.value) !== JSON.stringify(originalConsumable.value)
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
  originalConsumable.value = JSON.parse(JSON.stringify(fetchedConsumable.value))
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
          {{ $t('Consumable ID') }} #{{ fetchedConsumable?.consumable_id || 'Loading...' }}
        </h4>
        <div class="text-body-1">
          {{ fetchedConsumable?.created_at ? new Date(fetchedConsumable.created_at).toLocaleDateString() : '' }}
        </div>
      </div>
      <div class="d-flex gap-4">
        <VBtn
          variant="tonal"
          color="primary"
          @click="handleUpdate"
        >
          {{ $t('Update Consumable') }}
        </VBtn>
        <VBtn
          variant="tonal"
          color="error"
          @click="isDeleteConsumableDialogVisible = true"
        >
          {{ $t('Delete Consumable') }}
        </VBtn>
      </div>
    </div>

    <VRow v-if="fetchedConsumable">
      <ConsumableMainPanel
        v-model="fetchedConsumable"
        :brands="brands"
      />
      <ConsumableSidePanel v-model="fetchedConsumable" />
    </VRow>

    <div v-else>
      <VAlert
        type="error"
        variant="tonal"
      >
        {{ error || $t('Consumable not found!') }}
      </VAlert>
    </div>

    <!-- Dialogs -->
    <DeleteConsumableDialog
      v-model:isDialogVisible="isDeleteConsumableDialogVisible"
      @delete-consumable="handleDelete"
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
