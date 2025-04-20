<script setup>
import { useTranslation } from '@/composables/useTranslation'
import { getConsumableHeaders, getConsumableTypes } from '@/constants/filterOptions'
import i18n from "@/plugins/i18n"
import { useRouter } from 'vue-router'
import { VDataTableServer } from 'vuetify/labs/VDataTable'
import AddNewConsumableDialog from './dialogs/AddNewConsumableDialog.vue'
import DeleteConsumableDialog from './dialogs/DeleteConsumableDialog.vue'
import ViewConsumableDialog from './dialogs/ViewConsumableDialog.vue'

const props = defineProps({
  consumables: { type: Array, default: () => [] },
  totalPage: { type: Number, default: 1 },
  totalConsumables: { type: Number, default: 0 },
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
      total_consumables: 0,
      total_agents_and_disposables: 0,
      total_replacement_parts: 0,
      total_safety_signage: 0,
      total_accessories: 0,
    }),
  },
  addConsumable: { type: Function, required: true },
  fetchConsumable: { type: Function, required: true },
  deleteConsumable: { type: Function, required: true },
  printConsumables: { type: Function, required: true },
  printConsumableData: { type: Function, required: true },
  fetchedConsumable: { type: Object, default: () => null },
})

// Emits
const emit = defineEmits([
  'update:searchQuery',
  'update:selectedType',
  'update:selectedSubcategory',
  'update:selectedCategory',
  'update:options',
])

const handleConsumableDataPrint = () => {

}

// 👉 - Translations and computed lists
// Factory function for localized computed lists
const createLocalizedComputed = getter => computed(() => getter())

const consumableTypesList = createLocalizedComputed(getConsumableTypes)

// Use translation composable
const { translateValue } = useTranslation()

// Helper functions to translate per value
const translateConsumableTypes = type => translateValue(consumableTypesList, type)

// Dialog visibility state
const isAddNewConsumableDialogVisible = ref(false)
const isDeleteConsumableDialogVisible = ref(false)
const isViewConsumableDialogVisible = ref(false)

// Data table options binding
const dataTableOptions = computed({
  get: () => props.options,
  set: value => emit('update:options', value),
})

// Pagination metadata for table footer
const paginationMeta = computed(() => {
  const { page, itemsPerPage } = props.options
  const start = (page - 1) * itemsPerPage + 1
  const end = Math.min(page * itemsPerPage, props.totalConsumables)
  
  return `${i18n.global.t("Showing")} ${start} ${i18n.global.t("To")} ${end} ${i18n.global.t("Of")} ${props.totalConsumables} ${i18n.global.t("Entries")}`
})

// Router
const router = useRouter()

// UI Variant Resolvers
const resolveConsumableTypeVariant = type => {
  const variants = {
    'agents and disposables': { 
      color: 'success', 
      icon: 'tabler-flask', // Chemical agents
    },
    'replacement parts': { 
      color: 'info', 
      icon: 'tabler-engine', // Mechanical parts
    },
    'safety signage': { 
      color: 'warning', 
      icon: 'tabler-alert-triangle', // Warning symbols
    },
    'accessories': { 
      color: 'error', 
      icon: 'tabler-puzzle', // Supplementary items
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
    label: 'Total Consumables',
    value: props.statisticsData.total_consumables,
    color: 'primary',
    icon: 'tabler-packages', // Multiple consumables
  },
  {
    label: 'Agents & Disposables',
    value: props.statisticsData.total_agents_and_disposables,
    color: 'success',
    icon: 'tabler-flask', // Chemical agents
  },
  {
    label: 'Replacement Parts',
    value: props.statisticsData.total_replacement_parts,
    color: 'info',
    icon: 'tabler-engine', // Mechanical parts
  },
  {
    label: 'Safety Signage',
    value: props.statisticsData.total_safety_signage,
    color: 'warning',
    icon: 'tabler-alert-triangle', // Warning signs
  },
  {
    label: 'Accessories',
    value: props.statisticsData.total_accessories,
    color: 'error',
    icon: 'tabler-puzzle', // Supplementary parts
  },
]))

const selectedConsumableId = ref(null)

// Handler for adding a new consumable via the dialog
const handleAddConsumable = async fetchedConsumable => {
  try {
    const newConsumableId = await props.addConsumable(fetchedConsumable)

    router.push({ name: 'product-asset-suite-consumable-catalog-edit-id', params: { id: newConsumableId } })
  } catch (error) {
    console.error("Error adding consumable:", error.message)
  }
}

// Handle delete confirmation
const handleDeleteConsumable = async () => {
  if (selectedConsumableId.value) {
    try {
      await props.deleteConsumable(selectedConsumableId.value)
      selectedConsumableId.value = null
    } catch (error) {
      console.error("Error deleting consumable:", error.message)
    }
  }
}

const handleViewConsumable = async consumableId => {
  await props.fetchConsumable(consumableId)
  isViewConsumableDialogVisible.value = true
}

const consumableHeaders = computed(() => getConsumableHeaders())
</script>

<template>
  <div>
    <!-- Dialogs -->
    <ViewConsumableDialog
      v-model:isDialogVisible="isViewConsumableDialogVisible"
      :fetched-consumable="props.fetchedConsumable"
      @print-consumable-data="props.printConsumableData"
    />
    <AddNewConsumableDialog
      v-model:isDialogVisible="isAddNewConsumableDialogVisible"
      @add-consumable="handleAddConsumable"
    />
    <DeleteConsumableDialog
      v-model:isDialogVisible="isDeleteConsumableDialogVisible"
      @delete-consumable="handleDeleteConsumable"
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
                  :items="consumableTypesList"
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
                  :items="props.consumables
                    .filter(consumable => consumable && consumable.category_name && consumable.category_id)
                    .map(consumable => ({
                      title: consumable.category_name[$i18n.locale] || consumable.category_name || 'N/A',
                      value: consumable.category_id,
                      text: `${consumable.category_id} - ${consumable.category_name[$i18n.locale] || consumable.category_name || 'N/A'}`
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
                  :items="props.consumables
                    .filter(consumable => consumable && consumable.subcategory_name && consumable.subcategory_id)
                    .map(consumable => ({
                      title: consumable.subcategory_name[$i18n.locale] || consumable.subcategory_name || 'N/A',
                      value: consumable.subcategory_id,
                      text: `${consumable.subcategory_id} - ${consumable.subcategory_name[$i18n.locale] || consumable.subcategory_name || 'N/A'}`
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
                @click="printConsumables"
              >
                {{ $t('Print') }}
              </VBtn>
              <VBtn
                prepend-icon="tabler-plus"
                @click="isAddNewConsumableDialogVisible = !isAddNewConsumableDialogVisible"
              >
                {{ $t('Add New Consumable') }}
              </VBtn>
            </div>
          </VCardText>

          <VDivider />

          <!-- Data Table -->
          <VDataTableServer
            v-model:items-per-page="dataTableOptions.itemsPerPage"
            v-model:page="dataTableOptions.page"
            :items="props.consumables"
            :items-length="props.totalConsumables"
            :headers="consumableHeaders"
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
                <span class="text-sm text-medium-emphasis">#{{ item.raw.consumable_id || 'N/A' }}</span>
              </div>
            </template>
            <template #[`item.consumable_type`]="{ item }">
              <div class="d-flex align-center gap-4">
                <VAvatar
                  rounded
                  :size="30"
                  :color="resolveConsumableTypeVariant(item.raw.consumable_type)?.color"
                >
                  <VIcon
                    :size="20"
                    :icon="resolveConsumableTypeVariant(item.raw.consumable_type)?.icon"
                  />
                </VAvatar>
                <span class="text-capitalize">{{ translateConsumableTypes(item.raw.consumable_type) || 'N/A' }}</span>
              </div>
            </template>
            <template #[`item.actions`]="{ item }">
              <IconBtn @click="() => handleViewConsumable(item.raw.id)">
                <VIcon icon="tabler-eye" />
              </IconBtn>
              <IconBtn :to="{ name: 'product-asset-suite-consumable-catalog-edit-id', params: { id: item.raw.id } }">
                <VIcon icon="tabler-edit" />
              </IconBtn>
              
              <IconBtn
                @click="() => {
                  selectedConsumableId = item.raw.id;
                  isDeleteConsumableDialogVisible = true;
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
