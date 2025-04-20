<script setup>
import { useSuppliers } from '@/composables/business-partner/useSuppliers'
import SupplierBioPanel from '@/views/business-partner/suppliers/edit/SupplierBioPanel.vue'
import SupplierTabAddressAndContacts from '@/views/business-partner/suppliers/edit/SupplierTabAddressAndContacts.vue'
import SupplierTabFinancial from '@/views/business-partner/suppliers/edit/SupplierTabFinancial.vue'
import SupplierTabOverview from '@/views/business-partner/suppliers/edit/SupplierTabOverview.vue'
import SupplierTabStatus from '@/views/business-partner/suppliers/edit/SupplierTabStatus.vue'
import { useI18n } from 'vue-i18n'


const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  fetchedSupplier: {
    type: Object,
    default: () => ({}),
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'print-supplier-data',
])

const { t } = useI18n()

const supplierTab = ref(0)
const contactData = ref([])
const addressData = ref([])

// Tabs configuration
const tabs = computed(() => [
  { title: t('Overview'), icon: 'tabler-layout-dashboard' },
  { title: t('Address & Contacts'), icon: 'tabler-book-2' },
  { title: t('Financial Data'), icon: 'tabler-coin' },
  { title: t('Supplier Status'), icon: 'tabler-user-bolt' },
])

// Fetch contacts and addresses
const { fetchSupplierContacts, fetchSupplierAddresses } = useSuppliers(false)

const loadAdditionalData = async () => {
  try {
    if (props.fetchedSupplier?.id) {
      contactData.value = await fetchSupplierContacts({ id: props.fetchedSupplier.id })
      addressData.value = await fetchSupplierAddresses({ id: props.fetchedSupplier.id })
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
  () => props.fetchedSupplier,
  newSupplier => {
    if (newSupplier && newSupplier.supplier_id) {
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
            {{ $t('Supplier View') }} #{{ fetchedSupplier?.supplier_id }}
          </h4>
          <div>
            <div class="d-flex gap-4">
              <VBtn
                variant="tonal"
                color="secondary"
                prepend-icon="tabler-printer"
                @click="emit('print-supplier-data', props.fetchedSupplier.id)"
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
            <SupplierBioPanel :fetched-supplier="fetchedSupplier" />
          </VCol>
          
          <VCol
            cols="12"
            md="7"
            lg="8"
          >
            <VTabs
              v-model="supplierTab"
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
              v-model="supplierTab"
              class="mt-4"
            >
              <VWindowItem>
                <SupplierTabOverview 
                  :model-value="fetchedSupplier" 
                  read-only
                />
              </VWindowItem>
              
              <VWindowItem>
                <SupplierTabAddressAndContacts
                  :model-value="fetchedSupplier"
                  :contact-data="contactData"
                  :address-data="addressData"
                  read-only
                />
              </VWindowItem>
              
              <VWindowItem>
                <SupplierTabFinancial 
                  :model-value="fetchedSupplier" 
                  read-only
                />
              </VWindowItem>
              
              <VWindowItem>
                <SupplierTabStatus 
                  :model-value="fetchedSupplier" 
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
