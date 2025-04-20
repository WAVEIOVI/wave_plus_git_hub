<script setup>
import { useClients } from '@/composables/business-partner/useClients'
import ClientBioPanel from '@/views/business-partner/clients/edit/ClientBioPanel.vue'
import ClientTabAddressAndContacts from '@/views/business-partner/clients/edit/ClientTabAddressAndContacts.vue'
import ClientTabFinancial from '@/views/business-partner/clients/edit/ClientTabFinancial.vue'
import ClientTabOverview from '@/views/business-partner/clients/edit/ClientTabOverview.vue'
import ClientTabStatus from '@/views/business-partner/clients/edit/ClientTabStatus.vue'
import { useI18n } from 'vue-i18n'


const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  fetchedClient: {
    type: Object,
    default: () => ({}),
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'print-client-data',
])

const { t } = useI18n()

const clientTab = ref(0)
const contactData = ref([])
const addressData = ref([])

// Tabs configuration
const tabs = computed(() => [
  { title: t('Overview'), icon: 'tabler-layout-dashboard' },
  { title: t('Address & Contacts'), icon: 'tabler-book-2' },
  { title: t('Financial Data'), icon: 'tabler-coin' },
  { title: t('Client Status'), icon: 'tabler-user-bolt' },
])

// Fetch contacts and addresses
const { fetchClientContacts, fetchClientAddresses } = useClients(false)

const loadAdditionalData = async () => {
  try {
    if (props.fetchedClient?.id) {
      contactData.value = await fetchClientContacts({ id: props.fetchedClient.id })
      addressData.value = await fetchClientAddresses({ id: props.fetchedClient.id })
    }

  } catch (error) {
    console.error('Error loading contacts/addresses:', error)
  }
}

onMounted(async () => {
  await loadAdditionalData()
})

const closeDialog = () => {
  emit('update:isDialogVisible', false)
}

watch(
  () => props.fetchedClient,
  newClient => {
    if (newClient && newClient.client_id) {
      loadAdditionalData()
    }
  },
  { immediate: true },
)
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 1400"
    transition="dialog-top-transition"
    :model-value="props.isDialogVisible"
    @update:model-value="val => $emit('update:isDialogVisible', val)"
  >
    <!-- 👉 Dialog close btn -->
    <DialogCloseBtn @click="$emit('update:isDialogVisible', false)" />
    <VCard>
      <VCardText>
        <!-- Header -->
        <div class="d-flex justify-space-between align-center mb-6">
          <h4 class="text-h4">
            {{ $t('Client View') }} #{{ fetchedClient?.client_id }}
          </h4>
          <div>
            <div class="d-flex gap-4">
              <VBtn
                variant="tonal"
                color="secondary"
                prepend-icon="tabler-printer"
                @click="emit('print-client-data', props.fetchedClient.id)"
              >
                {{ $t('Print') }}
              </VBtn>
              <VBtn
                color="error"
                variant="tonal"
                @click="closeDialog"
              >
                {{ $t('Discard') }}
              </VBtn>
            </div>
          </div>
          <DialogCloseBtn @click="emit('update:isDialogVisible', false)" />
        </div>

        <!-- Content -->
        <VRow>
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
              class="v-tabs-pill"
            >
              <VTab
                v-for="tab in tabs"
                :key="tab.title"
              >
                <VIcon
                  :icon="tab.icon"
                  size="20"
                  class="me-2"
                />
                {{ tab.title }}
              </VTab>
            </VTabs>

            <VWindow
              v-model="clientTab"
              class="mt-4"
            >
              <VWindowItem>
                <ClientTabOverview 
                  :model-value="fetchedClient" 
                  read-only
                />
              </VWindowItem>
              
              <VWindowItem>
                <ClientTabAddressAndContacts
                  :model-value="fetchedClient"
                  :contact-data="contactData"
                  :address-data="addressData"
                  read-only
                />
              </VWindowItem>
              
              <VWindowItem>
                <ClientTabFinancial 
                  :model-value="fetchedClient" 
                  read-only
                />
              </VWindowItem>
              
              <VWindowItem>
                <ClientTabStatus 
                  :model-value="fetchedClient" 
                  read-only
                />
              </VWindowItem>
            </VWindow>
          </VCol>
        </VRow>
      </VCardText>
    </VCard>
  </VDialog>
</template>
