<script setup>
import { useTranslation } from '@/composables/useTranslation'
import { getEqServicingHeaders, getEqServicingTypes } from '@/constants/filterOptions'
import i18n from "@/plugins/i18n"
import { useRouter } from 'vue-router'
import { VDataTableServer } from 'vuetify/labs/VDataTable'
import AddNewEqServicingDialog from './dialogs/AddNewEqServicingDialog.vue'
import DeleteEqServicingDialog from './dialogs/DeleteEqServicingDialog.vue'
import ViewEqServicingDialog from './dialogs/ViewEqServicingDialog.vue'

const props = defineProps({
  eqServicings: { type: Array, default: () => [] },
  totalPage: { type: Number, default: 1 },
  totalEqServicings: { type: Number, default: 0 },
  options: {
    type: Object,
    required: true,
    validator: value => 'page' in value && 'itemsPerPage' in value,
  },
  searchQuery: { type: String, default: '' },
  selectedType: { type: [String, Number], default: null },
  selectedSubcategory: { type: [String, Number], default: null },
  selectedCategory: { type: [String, Number], default: null },
  loading: { type: Boolean, default: false },
  error: { type: String, default: null },
  statisticsData: {
    type: Object,
    default: () => ({
      total_eq_servicings: 0,
      total_inspection_and_testing: 0,
      total_maintenance_and_repair: 0,
      total_refill_recharge: 0,
      total_calibration_and_certification: 0,
      total_installation_decommissioning: 0,
    }),
  },
  addEqServicing: { type: Function, required: true },
  fetchEqServicing: { type: Function, required: true },
  deleteEqServicing: { type: Function, required: true },
  printEqServicings: { type: Function, required: true },
  printEqServicingData: { type: Function, required: true },
  fetchedEqServicing: { type: Object, default: () => null },
})

// Emits
const emit = defineEmits([
  'update:searchQuery',
  'update:selectedType',
  'update:selectedSubcategory',
  'update:selectedCategory',
  'update:options',
])

const handleEqServicingDataPrint = () => {

}

// 👉 - Translations and computed lists
// Factory function for localized computed lists
const createLocalizedComputed = getter => computed(() => getter())

const eqServicingTypesList = createLocalizedComputed(getEqServicingTypes)

// Use translation composable
const { translateValue } = useTranslation()

// Helper functions to translate per value
const translateEqServicingTypes = type => translateValue(eqServicingTypesList, type)

// Dialog visibility state
const isAddNewEqServicingDialogVisible = ref(false)
const isDeleteEqServicingDialogVisible = ref(false)
const isViewEqServicingDialogVisible = ref(false)

// Data table options binding
const dataTableOptions = computed({
  get: () => props.options,
  set: value => emit('update:options', value),
})

// Pagination metadata for table footer
const paginationMeta = computed(() => {
  const { page, itemsPerPage } = props.options
  const start = (page - 1) * itemsPerPage + 1
  const end = Math.min(page * itemsPerPage, props.totalEqServicings)
  
  return `${i18n.global.t("Showing")} ${start} ${i18n.global.t("To")} ${end} ${i18n.global.t("Of")} ${props.totalEqServicings} ${i18n.global.t("Entries")}`
})

// Router
const router = useRouter()

// UI Variant Resolvers
const resolveEqServicingTypeVariant = type => {
  const variants = {
    'inspection and testing': { 
      color: 'success', 
      icon: 'tabler-checklist', // Matches inspection tasks
    },
    'maintenance and repair': { 
      color: 'info', 
      icon: 'tabler-hammer', // Repairs/maintenance
    },
    'refill recharge': { 
      color: 'warning', 
      icon: 'tabler-flame', // Fire-related refills
    },
    'calibration and certification': { 
      color: 'secondary', 
      icon: 'tabler-ruler', // Measurement/calibration
    },
    'installation decommissioning': { 
      color: 'error', 
      icon: 'tabler-backhoe', // Installation work
    },
    'unclassified': { 
      color: 'secondary', 
      icon: 'tabler-question-mark', // Neutral default
    },
  }

  return variants[type?.toLowerCase()] || variants['unclassified']
}

// Define stats cards data for a simpler loop
const statsCards = computed(() => ([
  {
    label: 'Total Equipment Servicings',
    value: props.statisticsData.total_eq_servicings,
    color: 'primary',
    icon: 'tabler-tools', // General tools icon
  },
  {
    label: 'Inspection & Testing',
    value: props.statisticsData.total_inspection_and_testing,
    color: 'success',
    icon: 'tabler-checklist', // Inspection tasks
  },
  {
    label: 'Maintenance & Repair',
    value: props.statisticsData.total_maintenance_and_repair,
    color: 'info',
    icon: 'tabler-hammer', // Repair work
  },
  {
    label: 'Refill/Recharge',
    value: props.statisticsData.total_refill_recharge,
    color: 'warning',
    icon: 'tabler-flame', // Fire-related refills
  },
  {
    label: 'Calibration & Certification',
    value: props.statisticsData.total_calibration_and_certification,
    color: 'secondary',
    icon: 'tabler-ruler', // Calibration
  },
  {
    label: 'Installation/Decommissioning',
    value: props.statisticsData.total_installation_decommissioning,
    color: 'error',
    icon: 'tabler-backhoe', // Installation work
  },
]))

const selectedEqServicingId = ref(null)

// Handler for adding a new eqServicing via the dialog
const handleAddEqServicing = async fetchedEqServicing => {
  try {
    const newEqServicingId = await props.addEqServicing(fetchedEqServicing)

    router.push({ name: 'product-asset-suite-equipment-servicing-catalog-edit-id', params: { id: newEqServicingId } })
  } catch (error) {
    console.error("Error adding equipment servicing:", error.message)
  }
}

// Handle delete confirmation
const handleDeleteEqServicing = async () => {
  if (selectedEqServicingId.value) {
    try {
      await props.deleteEqServicing(selectedEqServicingId.value)
      selectedEqServicingId.value = null
    } catch (error) {
      console.error("Error deleting equipment servicing:", error.message)
    }
  }
}

const handleViewEqServicing = async eqServicingId => {
  await props.fetchEqServicing(eqServicingId)
  isViewEqServicingDialogVisible.value = true
}

const eqServicingHeaders = computed(() => getEqServicingHeaders())
</script>

<template>
  <div>
    <!-- Dialogs -->
    <ViewEqServicingDialog
      v-model:isDialogVisible="isViewEqServicingDialogVisible"
      :fetched-eq-servicing="props.fetchedEqServicing"
      @print-eq-servicing-data="props.printEqServicingData"
    />
    <AddNewEqServicingDialog
      v-model:isDialogVisible="isAddNewEqServicingDialogVisible"
      @add-eq-servicing="handleAddEqServicing"
    />
    <DeleteEqServicingDialog
      v-model:isDialogVisible="isDeleteEqServicingDialogVisible"
      @delete-eq-servicing="handleDeleteEqServicing"
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
    <div>
      <VCard class="mb-6">
        <VCardText class="px-3">
          <VRow>
            <template
              v-for="(card, index) in statsCards"
              :key="index"
            >
              <VCol
                cols="12"
                sm="6"
                md="4"
                class="px-6"
              >
                <div
                  class="d-flex justify-space-between"
                  :class="$vuetify.display.xs
                    ? index !== statsCards.length - 1 ? 'border-b pb-4' : ''
                    : $vuetify.display.sm
                      ? index < (statsCards.length / 2) ? 'border-b pb-4' : ''
                      : ''"
                >
                  <div class="d-flex flex-column gap-y-1">
                    <div class="text-body-1 text-capitalize">
                      {{ $t(card.label) }}
                    </div>

                    <h4 class="text-h4">
                      {{ card.value }}
                    </h4>
                  </div>

                  <VAvatar
                    variant="tonal"
                    rounded
                    size="44"
                    :color="card.color"
                  >
                    <VIcon
                      :icon="card.icon"
                      size="28"
                      class="text-high-emphasis"
                    />
                  </VAvatar>
                </div>
              </VCol>
              <VDivider
                v-if="$vuetify.display.mdAndUp ? index !== statsCards.length - 1
                  : $vuetify.display.smAndUp ? index % 2 === 0
                    : false"
                vertical
                inset
                length="92"
              />
            </template>
          </VRow>
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
                  :model-value="selectedType"
                  :label="$t('Select Type')"
                  :items="eqServicingTypesList"
                  clearable
                  clear-icon="tabler-x"
                  @update:model-value="emit('update:selectedType', $event)"
                />
              </VCol>
              <VCol
                cols="12"
                sm="4"
              >
                <AppSelect
                  :model-value="selectedCategory"
                  :label="$t('Select Category')"
                  :items="props.eqServicings
                    .filter(eqServicing => eqServicing && eqServicing.category_name && eqServicing.category_id)
                    .map(eqServicing => ({
                      title: eqServicing.category_name[$i18n.locale] || eqServicing.category_name || 'N/A',
                      value: eqServicing.category_id,
                      text: `${eqServicing.category_id} - ${eqServicing.category_name[$i18n.locale] || eqServicing.category_name || 'N/A'}`
                    }))"
                  clearable
                  clear-icon="tabler-x"
                  @update:model-value="emit('update:selectedCategory', $event)"
                />
              </VCol>
              <VCol
                cols="12"
                sm="4"
              >
                <AppSelect
                  :model-value="selectedSubcategory"
                  :label="$t('Select Subcategory')"
                  :items="props.eqServicings
                    .filter(eqServicing => eqServicing && eqServicing.subcategory_name && eqServicing.subcategory_id)
                    .map(eqServicing => ({
                      title: eqServicing.subcategory_name[$i18n.locale] || eqServicing.subcategory_name || 'N/A',
                      value: eqServicing.subcategory_id,
                      text: `${eqServicing.subcategory_id} - ${eqServicing.subcategory_name[$i18n.locale] || eqServicing.subcategory_name || 'N/A'}`
                    }))"
                  clearable
                  clear-icon="tabler-x"
                  @update:model-value="emit('update:selectedSubcategory', $event)"
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
                />
              </div>
              <VBtn
                variant="tonal"
                color="secondary"
                prepend-icon="tabler-printer"
                @click="printEqServicings"
              >
                {{ $t('Print') }}
              </VBtn>
              <VBtn
                prepend-icon="tabler-plus"
                @click="isAddNewEqServicingDialogVisible = !isAddNewEqServicingDialogVisible"
              >
                {{ $t('Add New Equipment Servicing') }}
              </VBtn>
            </div>
          </VCardText>

          <VDivider />

          <!-- Data Table -->
          <VDataTableServer
            v-model:items-per-page="dataTableOptions.itemsPerPage"
            v-model:page="dataTableOptions.page"
            :items="props.eqServicings"
            :items-length="props.totalEqServicings"
            :headers="eqServicingHeaders"
            :loading="props.loading"
            class="text-no-wrap"
            @update:options="dataTableOptions = $event"
          >
            <template #[`item.category`]="{ item }">
              <div class="d-flex flex-column">
                <h6 class="text-base">
                  {{ item.raw.category_name?.[$i18n.locale] || item.raw.category_name || 'N/A' }}
                </h6>
                <span class="text-sm text-medium-emphasis">{{ item.raw.subcategory_name?.[$i18n.locale] || item.raw.subcategory_name || 'N/A' }}</span>
              </div>
            </template>
            <template #[`item.name`]="{ item }">
              <div class="d-flex flex-column">
                <h6 class="text-base">
                  {{ item.raw.name?.[$i18n.locale] || item.raw.name || 'N/A' }}
                </h6>
                <span class="text-sm text-medium-emphasis">#{{ item.raw.eq_servicing_id || 'N/A' }}</span>
              </div>
            </template>
            <template #[`item.eq_servicing_type`]="{ item }">
              <div class="d-flex align-center gap-4">
                <VAvatar
                  rounded
                  :size="30"
                  :color="resolveEqServicingTypeVariant(item.raw.eq_servicing_type)?.color"
                >
                  <VIcon
                    :size="20"
                    :icon="resolveEqServicingTypeVariant(item.raw.eq_servicing_type)?.icon"
                  />
                </VAvatar>
                <span class="text-capitalize">{{ translateEqServicingTypes(item.raw.eq_servicing_type) || 'N/A' }}</span>
              </div>
            </template>
            <template #[`item.actions`]="{ item }">
              <IconBtn @click="() => handleViewEqServicing(item.raw.id)">
                <VIcon icon="tabler-eye" />
              </IconBtn>
              <IconBtn :to="{ name: 'product-asset-suite-equipment-servicing-catalog-edit-id', params: { id: item.raw.id } }">
                <VIcon icon="tabler-edit" />
              </IconBtn>
              
              <IconBtn
                @click="() => {
                  selectedEqServicingId = item.raw.id;
                  isDeleteEqServicingDialogVisible = true;
                }"
              >
                <VIcon icon="tabler-trash" />
              </IconBtn>
            </template>
            <template #bottom>
              <VDivider />
              <div class="d-flex align-center justify-sm-space-between justify-center flex-wrap gap-3 pa-5 pt-3">
                <p class="text-sm text-disabled mb-0">
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

<route lang="yaml">
meta:
  layout: default
  requiresAuth: true
  action: manage
  subject: ProductAssetSuite
</route>
