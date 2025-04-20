<script setup>
import { useTranslation } from '@/composables/useTranslation'
import { getBpHeaders, getBpIndustries, getBpStatus, getBpTiers } from '@/constants/filterOptions'
import i18n from "@/plugins/i18n"
import { avatarText } from '@core/utils/formatters'
import { useRouter } from 'vue-router'
import { VDataTableServer } from 'vuetify/labs/VDataTable'
import AddNewSupplierDialog from './dialogs/AddNewSupplierDialog.vue'
import DeleteSupplierDialog from './dialogs/DeleteSupplierDialog.vue'
import ViewSupplierDialog from './dialogs/ViewSupplierDialog.vue'

const props = defineProps({
  suppliers: { type: Array, default: () => [] },
  totalPage: { type: Number, default: 1 },
  totalSuppliers: { type: Number, default: 0 },
  options: {
    type: Object,
    required: true,
    validator: value => 'page' in value && 'itemsPerPage' in value,
  },
  searchQuery: { type: String, default: '' },
  selectedTier: { type: [String, Number], default: null },
  selectedIndustry: { type: [String, Number], default: null },
  selectedStatus: { type: [String, Number], default: null },
  loading: { type: Boolean, default: false },
  error: { type: String, default: null },
  statisticsData: {
    type: Object,
    default: () => ({
      total_suppliers: 0,
      total_active_suppliers: 0,
      total_pending_suppliers: 0,
      total_inactive_suppliers: 0,
      total_blocked_suppliers: 0,
    }),
  },
  addSupplier: { type: Function, required: true },
  fetchSupplier: { type: Function, required: true },
  deleteSupplier: { type: Function, required: true },
  printSuppliers: { type: Function, required: true },
  printSupplierData: { type: Function, required: true },
  fetchedSupplier: { type: Object, default: () => null },
})

// Emits
const emit = defineEmits([
  'update:searchQuery',
  'update:selectedTier',
  'update:selectedIndustry',
  'update:selectedStatus',
  'update:options',
])

const handleSupplierDataPrint = () => {

}

// 👉 - Translations and computed lists
// Factory function for localized computed lists
const createLocalizedComputed = getter => computed(() => getter())

const supplierStatusList = createLocalizedComputed(getBpStatus)
const supplierIndustriesList = createLocalizedComputed(getBpIndustries)
const supplierTiersList = createLocalizedComputed(getBpTiers)

// Use translation composable
const { translateValue } = useTranslation()

// Helper functions to translate per value
const translateSupplierStatus = status => translateValue(supplierStatusList, status)
const translateSupplierIndustries = industry => translateValue(supplierIndustriesList, industry)
const translateSupplierTiers = tier => translateValue(supplierTiersList, tier)

// Dialog visibility state
const isAddNewSupplierDialogVisible = ref(false)
const isDeleteSupplierDialogVisible = ref(false)
const isViewSupplierDialogVisible = ref(false)

// Data table options binding
const dataTableOptions = computed({
  get: () => props.options,
  set: value => emit('update:options', value),
})

// Pagination metadata for table footer
const paginationMeta = computed(() => {
  const { page, itemsPerPage } = props.options
  const start = (page - 1) * itemsPerPage + 1
  const end = Math.min(page * itemsPerPage, props.totalSuppliers)
  
  return `${i18n.global.t("Showing")} ${start} ${i18n.global.t("To")} ${end} ${i18n.global.t("Of")} ${props.totalSuppliers} ${i18n.global.t("Entries")}`
})

// Router
const router = useRouter()

// UI Variant Resolvers
const resolveSupplierTierVariant = tier => {
  const variants = {
    'unclassified': { color: 'danger', icon: 'tabler-help' },
    'individual': { color: 'warning', icon: 'tabler-user' },
    'basic': { color: 'info', icon: 'tabler-file-text' },
    'enterprise': { color: 'success', icon: 'tabler-building' },
    'premium': { color: 'primary', icon: 'tabler-crown' },
    'group': { color: 'success', icon: 'tabler-users-group' },
  }

  
  return variants[tier?.toLowerCase()] || variants['unclassified']
}

const resolveSupplierStatusVariant = stat => {
  const variants = {
    'pending': 'warning',
    'active': 'success',
    'inactive': 'error',
    'blocked': 'error',
  }

  
  return variants[stat.toLowerCase()] || 'secondary'
}

// Define stats cards data for a simpler loop
const statsCards = computed(() => ([
  {
    label: 'Total Suppliers',
    value: props.statisticsData.total_suppliers,
    color: 'primary',
    icon: 'tabler-users-group',
  },
  {
    label: 'Active Suppliers',
    value: props.statisticsData.total_active_suppliers,
    color: 'success',
    icon: 'tabler-user-check',
  },
  {
    label: 'Pending Suppliers',
    value: props.statisticsData.total_pending_suppliers,
    color: 'warning',
    icon: 'tabler-user-pause',
  },
  {
    label: 'Inactive Suppliers',
    value: props.statisticsData.total_inactive_suppliers,
    color: 'error',
    icon: 'tabler-user-exclamation',
  },
  {
    label: 'Blocked Suppliers',
    value: props.statisticsData.total_blocked_suppliers,
    color: 'error',
    icon: 'tabler-user-x',
  },
]))

const selectedSupplierId = ref(null)

// Handler for adding a new supplier via the dialog
const handleAddSupplier = async fetchedSupplier => {
  try {
    const newSupplierId = await props.addSupplier(fetchedSupplier)

    router.push({ name: 'business-partner-suppliers-edit-id', params: { id: newSupplierId } })
  } catch (error) {
    console.error("Error adding supplier:", error.message)
  }
}

// Handle delete confirmation
const handleDeleteSupplier = async () => {
  if (selectedSupplierId.value) {
    try {
      await props.deleteSupplier(selectedSupplierId.value)
      selectedSupplierId.value = null
    } catch (error) {
      console.error("Error deleting supplier:", error.message)
    }
  }
}

const handleViewSupplier = async supplierId => {
  await props.fetchSupplier(supplierId)
  isViewSupplierDialogVisible.value = true
}

const supplierHeaders = computed(() => getBpHeaders())
const supplierTiers = computed(() => getBpTiers())
const supplierStatus = computed(() => getBpStatus())
const supplierIndustries = computed(() => getBpIndustries())
</script>

<template>
  <div>
    <!-- Dialogs -->
    <AddNewSupplierDialog
      v-model:isDialogVisible="isAddNewSupplierDialogVisible"
      @add-supplier="handleAddSupplier"
    />
    <ViewSupplierDialog
      v-model:isDialogVisible="isViewSupplierDialogVisible"
      :fetched-supplier="props.fetchedSupplier"
      @print-supplier-data="props.printSupplierData"
    />
    <DeleteSupplierDialog
      v-model:isDialogVisible="isDeleteSupplierDialogVisible"
      @delete-supplier="handleDeleteSupplier"
    />
    <!-- Error Alert -->
    <VAlert
      v-if="props.error"
      color="error"
      variant="tonal"
      class="mb-6"
    >
      {{ props.error }}
    </VAlert>
    <!-- Statistics Cards -->
    <div class="stats-cards">
      <VCard
        v-for="(card, index) in statsCards"
        :key="index"
        :loading="loading"
        class="stat-card"
      >
        <VCardText>
          <div class="d-flex justify-space-between align-center">
            <div class="d-flex flex-column">
              <h5 class="text-h5 mb-1">
                {{ card.value }}
              </h5>
              <div class="text-body-2">
                {{ $t(card.label) }}
              </div>
            </div>
            <VAvatar
              rounded
              size="40"
              variant="tonal"
              :color="card.color"
            >
              <VIcon :icon="card.icon" />
            </VAvatar>
          </div>
        </VCardText>
      </VCard>
    </div>

    <VRow>
      <VCol cols="12">
        <VCard :loading="loading">
          <!-- Filters -->
          <VCardText>
            <VRow>
              <VCol
                cols="12"
                sm="4"
              >
                <AppSelect
                  :model-value="selectedTier"
                  :label="$t('Select Tier')"
                  :items="supplierTiers"
                  clearable
                  clear-icon="tabler-x"
                  @update:model-value="emit('update:selectedTier', $event)"
                />
              </VCol>
              <VCol
                cols="12"
                sm="4"
              >
                <AppSelect
                  :model-value="selectedIndustry"
                  :label="$t('Select Industry')"
                  :items="supplierIndustries"
                  clearable
                  clear-icon="tabler-x"
                  @update:model-value="emit('update:selectedIndustry', $event)"
                />
              </VCol>
              <VCol
                cols="12"
                sm="4"
              >
                <AppSelect
                  :model-value="selectedStatus"
                  :label="$t('Select Status')"
                  :items="supplierStatus"
                  clearable
                  clear-icon="tabler-x"
                  @update:model-value="emit('update:selectedStatus', $event)"
                />
              </VCol>
            </VRow>
          </VCardText>

          <VDivider />

          <!-- Table Tools -->
          <VCardText class="d-flex flex-wrap py-4 gap-4">
            <div class="me-3 d-flex gap-3">
              <AppSelect
                :model-value="options.itemsPerPage"
                :items="[
                  { value: 10, title: '10' },
                  { value: 25, title: '25' },
                  { value: 50, title: '50' },
                  { value: 100, title: '100' }
                ]"
                style="width: 6.25rem;"
                @update:model-value="emit('update:options', { ...options, itemsPerPage: parseInt($event, 10) })"
              />
            </div>
            <VSpacer />
            <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
              <div style="inline-size: 10rem;">
                <AppTextField
                  :model-value="searchQuery"
                  :placeholder="$t('Search')"
                  density="compact"
                  :disabled="loading"
                  @update:model-value="emit('update:searchQuery', $event)"
                >
                  <template #append>
                    <VProgressCircular
                      v-if="loading"
                      indeterminate
                      color="primary"
                      size="24"
                      width="2"
                    />
                  </template>
                </AppTextField>
              </div>
              <VBtn
                variant="tonal"
                color="secondary"
                prepend-icon="tabler-printer"
                @click="printSuppliers"
              >
                {{ $t('Print') }}
              </VBtn>
              <VBtn
                prepend-icon="tabler-plus"
                @click="isAddNewSupplierDialogVisible = !isAddNewSupplierDialogVisible"
              >
                {{ $t('Add New Supplier') }}
              </VBtn>
            </div>
          </VCardText>

          <VDivider />

          <!-- Data Table -->
          <VDataTableServer
            v-model:items-per-page="dataTableOptions.itemsPerPage"
            v-model:page="dataTableOptions.page"
            :items="props.suppliers"
            :items-length="props.totalSuppliers"
            :headers="supplierHeaders"
            :loading="props.loading"
            class="text-no-wrap"
            @update:options="dataTableOptions = $event"
          >
            <template #[`item.legal_name`]="{ item }">
              <div class="d-flex align-center">
                <VAvatar
                  rounded
                  size="34"
                  :variant="!item.raw.avatar ? 'tonal' : undefined"
                  :color="!item.raw.avatar ? resolveSupplierTierVariant(item.raw.supplier_tier).color : undefined"
                  class="me-3"
                >
                  <span>{{ avatarText(item.raw.legal_name) }}</span>
                </VAvatar>
                <div class="d-flex flex-column">
                  <h6 class="text-base">
                    {{ item.raw.legal_name }}
                  </h6>
                  <span class="text-sm text-medium-emphasis">#{{ item.raw.supplier_id }}</span>
                </div>
              </div>
            </template>
            <template #[`item.supplier_tier`]="{ item }">
              <div class="d-flex align-center gap-4">
                <VAvatar
                  rounded
                  :size="30"
                  :color="resolveSupplierTierVariant(item.raw.supplier_tier).color"
                >
                  <VIcon
                    :size="20"
                    :icon="resolveSupplierTierVariant(item.raw.supplier_tier).icon"
                  />
                </VAvatar>
                <span class="text-capitalize">{{ translateSupplierTiers(item.raw.supplier_tier) }}</span>
              </div>
            </template>
            <template #[`item.industry`]="{ item }">
              <span class="text-capitalize font-weight-medium">{{ translateSupplierIndustries(item.raw.industry) }}</span>
            </template>
            <template #[`item.supplier_status`]="{ item }">
              <VChip
                :color="resolveSupplierStatusVariant(item.raw.supplier_status)"
                size="small"
                label
                class="text-capitalize"
              >
                {{ translateSupplierStatus(item.raw.supplier_status) }}
              </VChip>
            </template>
            <template #[`item.actions`]="{ item }">
              <IconBtn @click="() => handleViewSupplier(item.raw.id)">
                <VIcon icon="tabler-eye" />
              </IconBtn>
              <IconBtn :to="{ name: 'business-partner-suppliers-edit-id', params: { id: item.raw.id } }">
                <VIcon icon="tabler-edit" />
              </IconBtn>
              <IconBtn
                @click="() => {
                  selectedSupplierId = item.raw.id;
                  isDeleteSupplierDialogVisible = true;
                }"
              >
                <VIcon icon="tabler-trash" />
              </IconBtn>
            </template>
            <template #bottom>
              <VDivider />
              <div class="d-flex align-center justify-sm-space-between justify-center flex-wrap gap-3 pa-5 pt-3">
                <VProgressCircular
                  v-if="props.loading"
                  indeterminate
                  color="primary"
                  size="24"
                  width="2"
                />
                <p
                  v-else
                  class="text-sm text-disabled mb-0"
                >
                  {{ paginationMeta }}
                </p>
                <VPagination
                  v-model="dataTableOptions.page"
                  :length="props.totalPage"
                  :total-visible="$vuetify.display.xs ? 1 : props.totalPage"
                >
                  <template #prev="slotProps">
                    <VBtn
                      variant="tonal"
                      color="default"
                      v-bind="slotProps"
                      :icon="false"
                    >
                      {{ $t('Previous') }}
                    </VBtn>
                  </template>
                  <template #next="slotProps">
                    <VBtn
                      variant="tonal"
                      color="default"
                      v-bind="slotProps"
                      :icon="false"
                    >
                      {{ $t('Next') }}
                    </VBtn>
                  </template>
                </VPagination>
              </div>
            </template>
          </VDataTableServer>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>

<style lang="scss" scoped>
.stats-cards {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  gap: 1rem;
  margin-block-end: 1rem;
}

.stat-card {
  flex: 1 1 calc(20% - 1rem);
  min-inline-size: 150px;
}
</style>

<route lang="yaml">
meta:
  layout: default
  requiresAuth: true
  action: manage
  subject: AdminDashboard
</route>
