<script setup>
import { useSuppliers } from '@/composables/business-partner/useSuppliers'
import { useSnackbarStore } from '@/stores/useSnackbarStore'
import SupplierBioPanel from '@/views/business-partner/suppliers/edit/SupplierBioPanel.vue'
import SupplierTabAddressAndContacts from '@/views/business-partner/suppliers/edit/SupplierTabAddressAndContacts.vue'
import SupplierTabFinancial from '@/views/business-partner/suppliers/edit/SupplierTabFinancial.vue'
import SupplierTabOverview from '@/views/business-partner/suppliers/edit/SupplierTabOverview.vue'
import SupplierTabStatus from '@/views/business-partner/suppliers/edit/SupplierTabStatus.vue'
import DeleteSupplierDialog from '@/views/business-partner/suppliers/list/dialogs/DeleteSupplierDialog.vue'
import { useI18n } from 'vue-i18n'
import { onBeforeRouteLeave, useRoute, useRouter } from 'vue-router'

const snackbarStore = useSnackbarStore()
const route = useRoute()
const router = useRouter()
const { t } = useI18n()

// Use a dedicated ref for supplier ID and active tab index
const selectedSupplierId = ref(route.params.id)
const supplierTab = ref(0)
const isDeleteSupplierDialogVisible = ref(false)
const contactData = ref([])
const addressData = ref([])


// Helper to show snackbar messages
const showSnackbar = (text, color) => {
  snackbarStore.show = true
  snackbarStore.text = text
  snackbarStore.color = color
}

// Tabs setup
const tabs = computed(() => [
  { title: t('Overview'), icon: 'tabler-layout-dashboard' },
  { title: t('Address & Contacts'), icon: 'tabler-book-2' },
  { title: t('Financial Data'), icon: 'tabler-coin' },
  { title: t('Supplier Status'), icon: 'tabler-user-bolt' },
])

// Destructure methods from the useSuppliers composable
const {
  fetchedSupplier,
  fetchSupplier,
  updateSupplier,
  deleteSupplier,
  fetchSupplierContacts,
  fetchSupplierContact,
  addSupplierContact,
  updateSupplierContact,
  deleteSupplierContact,
  fetchSupplierAddresses,
  fetchSupplierAddress,
  addSupplierAddress,
  updateSupplierAddress,
  deleteSupplierAddress,
  loading,
  error,
} = useSuppliers(false)

// Methods for supplier update and delete actions
const handleUpdate = async () => {
  try {
    await updateSupplier(selectedSupplierId.value, fetchedSupplier.value)
    showSnackbar("Supplier updated successfully", "success")

    // Update the original copy so the dirty flag becomes false.
    originalSupplier.value = JSON.parse(JSON.stringify(fetchedSupplier.value))
  } catch (err) {
    console.error("Update error:", err)
    showSnackbar("Error updating supplier", "error")
  }
}

const handleDelete = async () => {
  try {
    await deleteSupplier(selectedSupplierId.value)
    showSnackbar("Supplier deleted successfully", "success")
    router.push({ name: 'business-partner-suppliers-list' })
  } catch (err) {
    console.error("Delete error:", err)
    showSnackbar("Error deleting supplier", "error")
  }
}

// Refresh the contact data list
const refreshSupplierContacts = async () => {
  try {
    contactData.value = await fetchSupplierContacts({ id: selectedSupplierId.value })
  } catch (err) {
    console.error("Error fetching contacts:", err)
  }
}

// Handler for contact edits
const handleContactEdit = async contactId => {
  await refreshSupplierContacts()
  showSnackbar("Contact saved successfully", "success")
}


// Refresh the address data list
const refreshSupplierAddresses = async () => {
  try {
    addressData.value = await fetchSupplierAddresses({ id: selectedSupplierId.value })
  } catch (err) {
    console.error("Error fetching addresses:", err)
  }
}

// Handler for address edits
const handleAddressEdit = async addressId => {
  await refreshSupplierAddresses()
  showSnackbar("address saved successfully", "success")
}

// Lifecycle hook
onMounted(async () => {
  await fetchSupplier(selectedSupplierId.value)
  await refreshSupplierContacts()
  await refreshSupplierAddresses()
})

// on Before Route Leave 
const originalSupplier = ref(null)
const unsavedDialogVisible = ref(false)
const nextRoute = ref(null)

// After the supplier is fetched, store a deep copy of the original data.
watch(fetchedSupplier, newVal => {
  if (newVal && !originalSupplier.value) {
    originalSupplier.value = JSON.parse(JSON.stringify(newVal))
  }
})

// Computed dirty flag: compare current supplier with the original state.
const isDirty = computed(() => {
  // If no original data is stored, assume no changes.
  if (!originalSupplier.value) return false
  
  return JSON.stringify(fetchedSupplier.value) !== JSON.stringify(originalSupplier.value)
})

// Use Vue Router's navigation guard.
onBeforeRouteLeave((to, from, next) => {
  if (isDirty.value) {
    unsavedDialogVisible.value = true
    next(false) // Prevent immediate navigation.
    nextRoute.value = to
  } else {
    next()
  }
})

// Handlers for the unsaved changes dialog.
const confirmLeave = () => {
  // Reset dirty flag by updating originalSupplier to the current value
  originalSupplier.value = JSON.parse(JSON.stringify(fetchedSupplier.value))
  unsavedDialogVisible.value = false

  // Continue navigation manually.
  router.push(nextRoute.value.fullPath)
}

const cancelLeave = () => {
  unsavedDialogVisible.value = false
  nextRoute.value = null
}
</script>

<template>
  <div>
    <!-- Header -->
    <div class="d-flex justify-space-between align-center flex-wrap gap-y-4 mb-6">
      <div>
        <h4 class="text-h4 mb-1">
          {{ $t('Supplier ID') }} #{{ fetchedSupplier?.supplier_id || 'Loading...' }}
        </h4>
        <div class="text-body-1">
          {{ fetchedSupplier?.supplier_since }}
        </div>
      </div>
      <div class="d-flex gap-4">
        <VBtn
          variant="tonal"
          color="primary"
          @click="handleUpdate"
        >
          {{ $t('Update Supplier') }}
        </VBtn>
        <VBtn
          variant="tonal"
          color="error"
          @click="isDeleteSupplierDialogVisible = true"
        >
          {{ $t('Delete Supplier') }}
        </VBtn>
      </div>
    </div>

    <!-- Supplier Profile and Tabs -->
    <VRow v-if="fetchedSupplier">
      <VCol
        cols="12"
        md="5"
        lg="4"
      >
        <SupplierBioPanel :fetched-supplier="fetchedSupplier" />
      </VCol>
      <VCol
        cols="12"
        md="7"
        lg="8"
      >
        <VTabs
          v-model="supplierTab"
          class="v-tabs-pill mb-3 disable-tab-transition"
        >
          <VTab
            v-for="tab in tabs"
            :key="tab.title"
          >
            <VIcon
              size="20"
              start
              :icon="tab.icon"
            />
            {{ tab.title }}
          </VTab>
        </VTabs>
        <VWindow
          v-model="supplierTab"
          class="disable-tab-transition"
          :touch="false"
        >
          <VWindowItem>
            <SupplierTabOverview v-model="fetchedSupplier" />
          </VWindowItem>
          <VWindowItem>
            <SupplierTabAddressAndContacts
              v-model="fetchedSupplier"
              :contact-data="contactData"
              :add-supplier-contact="addSupplierContact"
              :update-supplier-contact="updateSupplierContact"
              :delete-supplier-contact="deleteSupplierContact"
              :fetch-supplier-contact="fetchSupplierContact"
              :address-data="addressData"
              :add-supplier-address="addSupplierAddress"
              :update-supplier-address="updateSupplierAddress"
              :delete-supplier-address="deleteSupplierAddress"
              :fetch-supplier-address="fetchSupplierAddress"
              @edit-contact="handleContactEdit"
              @edit-address="handleAddressEdit"
            />
          </VWindowItem>
          <VWindowItem>
            <SupplierTabFinancial v-model="fetchedSupplier" />
          </VWindowItem>
          <VWindowItem>
            <SupplierTabStatus v-model="fetchedSupplier" />
          </VWindowItem>
        </VWindow>
      </VCol>
    </VRow>
    <div v-else>
      <VAlert
        type="error"
        variant="tonal"
      >
        {{ error || 'Supplier not found!' }}
      </VAlert>
    </div>

    <!-- Delete Dialog and Snackbar -->
    <DeleteSupplierDialog
      v-model:isDialogVisible="isDeleteSupplierDialogVisible"
      @delete-supplier="handleDelete"
    />
    <UnsavedChangesDialog
      v-model="unsavedDialogVisible"
      title="Unsaved Changes"
      message="You have unsaved changes. Do you really want to leave without saving?"
      confirm-text="Leave"
      cancel-text="Cancel"
      @confirm="confirmLeave"
      @cancel="cancelLeave"
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

<route lang="yaml">
meta:
  layout: default
  requiresAuth: true
  action: manage
  subject: BusinessPartner
</route>
