<script setup>
import { useClients } from '@/composables/business-partner/useClients'
import { useSnackbarStore } from '@/stores/useSnackbarStore'
import ClientBioPanel from '@/views/business-partner/clients/edit/ClientBioPanel.vue'
import ClientTabAddressAndContacts from '@/views/business-partner/clients/edit/ClientTabAddressAndContacts.vue'
import ClientTabFinancial from '@/views/business-partner/clients/edit/ClientTabFinancial.vue'
import ClientTabOverview from '@/views/business-partner/clients/edit/ClientTabOverview.vue'
import ClientTabStatus from '@/views/business-partner/clients/edit/ClientTabStatus.vue'
import DeleteClientDialog from '@/views/business-partner/clients/list/dialogs/DeleteClientDialog.vue'
import { useI18n } from 'vue-i18n'
import { onBeforeRouteLeave, useRoute, useRouter } from 'vue-router'


const snackbarStore = useSnackbarStore()
const route = useRoute()
const router = useRouter()
const { t } = useI18n()

// Use a dedicated ref for client ID and active tab index
const selectedClientId = ref(route.params.id)
const clientTab = ref(0)
const isDeleteClientDialogVisible = ref(false)
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
  { title: t('Client Status'), icon: 'tabler-user-bolt' },
])

// Destructure methods from the useClients composable
const {
  fetchedClient,
  fetchClient,
  updateClient,
  deleteClient,
  fetchClientContacts,
  fetchClientContact,
  addClientContact,
  updateClientContact,
  deleteClientContact,
  fetchClientAddresses,
  fetchClientAddress,
  addClientAddress,
  updateClientAddress,
  deleteClientAddress,
  loading,
  error,
} = useClients(false)

// Methods for client update and delete actions
const handleUpdate = async () => {
  try {
    await updateClient(selectedClientId.value, fetchedClient.value)
    showSnackbar("Client updated successfully", "success")

    // Update the original copy so the dirty flag becomes false.
    originalClient.value = JSON.parse(JSON.stringify(fetchedClient.value))
  } catch (err) {
    console.error("Update error:", err)
    showSnackbar("Error updating client", "error")
  }
}

const handleDelete = async () => {
  try {
    await deleteClient(selectedClientId.value)
    showSnackbar("Client deleted successfully", "success")
    router.push({ name: 'business-partner-clients-list' })
  } catch (err) {
    console.error("Delete error:", err)
    showSnackbar("Error deleting client", "error")
  }
}

// Refresh the contact data list
const refreshClientContacts = async () => {
  try {
    contactData.value = await fetchClientContacts({ id: selectedClientId.value })
  } catch (err) {
    console.error("Error fetching contacts:", err)
  }
}

// Handler for contact edits
const handleContactEdit = async contactId => {
  await refreshClientContacts()
  showSnackbar("Contact saved successfully", "success")
}


// Refresh the address data list
const refreshClientAddresses = async () => {
  try {
    addressData.value = await fetchClientAddresses({ id: selectedClientId.value })
  } catch (err) {
    console.error("Error fetching addresses:", err)
  }
}

// Handler for address edits
const handleAddressEdit = async addressId => {
  await refreshClientAddresses()
  showSnackbar("address saved successfully", "success")
}

// Lifecycle hook
onMounted(async () => {
  await fetchClient(selectedClientId.value)
  await refreshClientContacts()
  await refreshClientAddresses()
})

// on Before Route Leave 
const originalClient = ref(null)
const unsavedDialogVisible = ref(false)
const nextRoute = ref(null)

// After the client is fetched, store a deep copy of the original data.
watch(fetchedClient, newVal => {
  if (newVal && !originalClient.value) {
    originalClient.value = JSON.parse(JSON.stringify(newVal))
  }
})

// Computed dirty flag: compare current client with the original state.
const isDirty = computed(() => {
  // If no original data is stored, assume no changes.
  if (!originalClient.value) return false
  
  return JSON.stringify(fetchedClient.value) !== JSON.stringify(originalClient.value)
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
  // Reset dirty flag by updating originalClient to the current value
  originalClient.value = JSON.parse(JSON.stringify(fetchedClient.value))
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
          {{ $t('Client ID') }} #{{ fetchedClient?.client_id || 'Loading...' }}
        </h4>
        <div class="text-body-1">
          {{ fetchedClient?.client_since }}
        </div>
      </div>
      <div class="d-flex gap-4">
        <VBtn
          variant="tonal"
          color="primary"
          @click="handleUpdate"
        >
          {{ $t('Update Client') }}
        </VBtn>
        <VBtn
          variant="tonal"
          color="error"
          @click="isDeleteClientDialogVisible = true"
        >
          {{ $t('Delete Client') }}
        </VBtn>
      </div>
    </div>

    <!-- Client Profile and Tabs -->
    <VRow v-if="fetchedClient">
      <VCol
        cols="12"
        md="5"
        lg="4"
      >
        <ClientBioPanel :fetched-client="fetchedClient" />
      </VCol>
      <VCol
        cols="12"
        md="7"
        lg="8"
      >
        <VTabs
          v-model="clientTab"
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
          v-model="clientTab"
          class="disable-tab-transition"
          :touch="false"
        >
          <VWindowItem>
            <ClientTabOverview v-model="fetchedClient" />
          </VWindowItem>
          <VWindowItem>
            <ClientTabAddressAndContacts
              v-model="fetchedClient"
              :contact-data="contactData"
              :add-client-contact="addClientContact"
              :update-client-contact="updateClientContact"
              :delete-client-contact="deleteClientContact"
              :fetch-client-contact="fetchClientContact"
              :address-data="addressData"
              :add-client-address="addClientAddress"
              :update-client-address="updateClientAddress"
              :delete-client-address="deleteClientAddress"
              :fetch-client-address="fetchClientAddress"
              @edit-contact="handleContactEdit"
              @edit-address="handleAddressEdit"
            />
          </VWindowItem>
          <VWindowItem>
            <ClientTabFinancial v-model="fetchedClient" />
          </VWindowItem>
          <VWindowItem>
            <ClientTabStatus v-model="fetchedClient" />
          </VWindowItem>
        </VWindow>
      </VCol>
    </VRow>
    <div v-else>
      <VAlert
        type="error"
        variant="tonal"
      >
        {{ error || 'Client not found!' }}
      </VAlert>
    </div>

    <!-- Delete Dialog and Snackbar -->
    <DeleteClientDialog
      v-model:isDialogVisible="isDeleteClientDialogVisible"
      @delete-client="handleDelete"
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

<route lang="yaml">
meta:
  layout: default
  requiresAuth: true
  action: manage
  subject: BusinessPartner
</route>
