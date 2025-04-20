<script setup>
import { useTranslation } from '@/composables/useTranslation'
import { getEqBlueprintHeaders } from '@/constants/filterOptions'
import i18n from "@/plugins/i18n"
import { useRouter } from 'vue-router'
import { VDataTableServer } from 'vuetify/labs/VDataTable'
import AddNewEqBlueprintDialog from './dialogs/AddNewEqBlueprintDialog.vue'
import DeleteEqBlueprintDialog from './dialogs/DeleteEqBlueprintDialog.vue'
import ViewEqBlueprintDialog from './dialogs/ViewEqBlueprintDialog.vue'

const props = defineProps({
  eqBlueprints: { type: Array, default: () => [] },
  totalPage: { type: Number, default: 1 },
  totalEqBlueprints: { type: Number, default: 0 },
  options: {
    type: Object,
    required: true,
    validator: value => 'page' in value && 'itemsPerPage' in value,
  },
  searchQuery: { type: String, default: '' },
  selectedSubcategory: { type: [String, Number], default: null },
  selectedCategory: { type: [String, Number], default: null },
  loading: { type: Boolean, default: false },
  error: { type: String, default: null },
  statisticsData: {
    type: Object,
    default: () => ({
      total_eq_blueprints: 0,
      total_active: 0,
      total_inactive: 0,
      total_draft: 0,
    }),
  },
  addEqBlueprint: { type: Function, required: true },
  fetchEqBlueprint: { type: Function, required: true },
  deleteEqBlueprint: { type: Function, required: true },
  printEqBlueprints: { type: Function, required: true },
  printEqBlueprintData: { type: Function, required: true },
  fetchedEqBlueprint: { type: Object, default: () => null },
})

// Emits
const emit = defineEmits([
  'update:searchQuery',
  'update:selectedSubcategory',
  'update:selectedCategory',
  'update:options',
])

const handleEqBlueprintDataPrint = () => {

}

// 👉 - Translations and computed lists
// Factory function for localized computed lists
const createLocalizedComputed = getter => computed(() => getter())

// Use translation composable
const { translateValue } = useTranslation()

// Helper functions to translate per value

// Dialog visibility state
const isAddNewEqBlueprintDialogVisible = ref(false)
const isDeleteEqBlueprintDialogVisible = ref(false)
const isViewEqBlueprintDialogVisible = ref(false)

// Data table options binding
const dataTableOptions = computed({
  get: () => props.options,
  set: value => emit('update:options', value),
})

// Pagination metadata for table footer
const paginationMeta = computed(() => {
  const { page, itemsPerPage } = props.options
  const start = (page - 1) * itemsPerPage + 1
  const end = Math.min(page * itemsPerPage, props.totalEqBlueprints)
  
  return `${i18n.global.t("Showing")} ${start} ${i18n.global.t("To")} ${end} ${i18n.global.t("Of")} ${props.totalEqBlueprints} ${i18n.global.t("Entries")}`
})

// Router
const router = useRouter()

// Define stats cards data for a simpler loop
const statsCards = computed(() => ([
  {
    label: 'Total Equipment Blueprints',
    value: props.statisticsData.total_eq_blueprints,
    color: 'primary',
    icon: 'tabler-tools', // General tools icon
  },
  {
    label: 'Active Equipment Blueprints',
    value: props.statisticsData.total_active,
    color: 'success',
    icon: 'tabler-checklist', // Inspection tasks
  },
  {
    label: 'Inactive Equipment Blueprints',
    value: props.statisticsData.total_inactive,
    color: 'info',
    icon: 'tabler-hammer', // Repair work
  },
  {
    label: 'Draft Equipment Blueprints',
    value: props.statisticsData.total_draft,
    color: 'warning',
    icon: 'tabler-flame', // Fire-related refills
  },
]))

const selectedEqBlueprintId = ref(null)

// Handler for adding a new eqBlueprint via the dialog
const handleAddEqBlueprint = async fetchedEqBlueprint => {
  try {
    const newEqBlueprintId = await props.addEqBlueprint(fetchedEqBlueprint)

    router.push({ name: 'product-asset-suite-equipment-blueprint-catalog-edit-id', params: { id: newEqBlueprintId } })
  } catch (error) {
    console.error("Error adding equipment blueprint:", error.message)
  }
}

// Handle delete confirmation
const handleDeleteEqBlueprint = async () => {
  if (selectedEqBlueprintId.value) {
    try {
      await props.deleteEqBlueprint(selectedEqBlueprintId.value)
      selectedEqBlueprintId.value = null
    } catch (error) {
      console.error("Error deleting equipment blueprint:", error.message)
    }
  }
}

const handleViewEqBlueprint = async eqBlueprintId => {
  await props.fetchEqBlueprint(eqBlueprintId)
  isViewEqBlueprintDialogVisible.value = true
}

const eqBlueprintHeaders = computed(() => getEqBlueprintHeaders())
</script>

<template>
  <div>
    <!-- Dialogs -->
    <ViewEqBlueprintDialog
      v-model:isDialogVisible="isViewEqBlueprintDialogVisible"
      :fetched-eq-blueprint="props.fetchedEqBlueprint"
      @print-eq-blueprint-data="props.printEqBlueprintData"
    />
    <AddNewEqBlueprintDialog
      v-model:isDialogVisible="isAddNewEqBlueprintDialogVisible"
      @add-eq-blueprint="handleAddEqBlueprint"
    />
    <DeleteEqBlueprintDialog
      v-model:isDialogVisible="isDeleteEqBlueprintDialogVisible"
      @delete-eq-blueprint="handleDeleteEqBlueprint"
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
                md="3"
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
                sm="6"
              >
                <AppSelect
                  :model-value="selectedCategory"
                  :label="$t('Select Category')"
                  :items="props.eqBlueprints
                    .filter(eqBlueprint => eqBlueprint && eqBlueprint.category_name && eqBlueprint.category_id)
                    .map(eqBlueprint => ({
                      title: eqBlueprint.category_name[$i18n.locale] || eqBlueprint.category_name || 'N/A',
                      value: eqBlueprint.category_id,
                      text: `${eqBlueprint.category_id} - ${eqBlueprint.category_name[$i18n.locale] || eqBlueprint.category_name || 'N/A'}`
                    }))"
                  clearable
                  clear-icon="tabler-x"
                  @update:model-value="emit('update:selectedCategory', $event)"
                />
              </VCol>
              <VCol
                cols="12"
                sm="6"
              >
                <AppSelect
                  :model-value="selectedSubcategory"
                  :label="$t('Select Subcategory')"
                  :items="props.eqBlueprints
                    .filter(eqBlueprint => eqBlueprint && eqBlueprint.subcategory_name && eqBlueprint.subcategory_id)
                    .map(eqBlueprint => ({
                      title: eqBlueprint.subcategory_name[$i18n.locale] || eqBlueprint.subcategory_name || 'N/A',
                      value: eqBlueprint.subcategory_id,
                      text: `${eqBlueprint.subcategory_id} - ${eqBlueprint.subcategory_name[$i18n.locale] || eqBlueprint.subcategory_name || 'N/A'}`
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
                @click="printEqBlueprints"
              >
                {{ $t('Print') }}
              </VBtn>
              <VBtn
                prepend-icon="tabler-plus"
                @click="isAddNewEqBlueprintDialogVisible = !isAddNewEqBlueprintDialogVisible"
              >
                {{ $t('Add New Equipment Blueprint') }}
              </VBtn>
            </div>
          </VCardText>

          <VDivider />

          <!-- Data Table -->
          <VDataTableServer
            v-model:items-per-page="dataTableOptions.itemsPerPage"
            v-model:page="dataTableOptions.page"
            :items="props.eqBlueprints"
            :items-length="props.totalEqBlueprints"
            :headers="eqBlueprintHeaders"
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
                <span class="text-sm text-medium-emphasis">#{{ item.raw.eq_blueprint_id || 'N/A' }}</span>
              </div>
            </template>
            <template #[`item.description`]="{ item }">
              <div class="d-flex flex-column">
                {{ item.raw.description?.[$i18n.locale] || item.raw.description || 'N/A' }}
              </div>
            </template>
            <template #[`item.actions`]="{ item }">
              <IconBtn @click="() => handleViewEqBlueprint(item.raw.id)">
                <VIcon icon="tabler-eye" />
              </IconBtn>
              <IconBtn :to="{ name: 'product-asset-suite-equipment-blueprint-catalog-edit-id', params: { id: item.raw.id } }">
                <VIcon icon="tabler-edit" />
              </IconBtn>
              
              <IconBtn
                @click="() => {
                  selectedEqBlueprintId = item.raw.id;
                  isDeleteEqBlueprintDialogVisible = true;
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
