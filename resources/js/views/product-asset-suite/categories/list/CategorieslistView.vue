<script setup>
import { useTranslation } from '@/composables/useTranslation'
import { getCategoriesHeaders, getCategoryStatus } from '@/constants/filterOptions'
import i18n from "@/plugins/i18n"
import AddNewCategoryDialog from '@/views/product-asset-suite/categories/list/dialogs/AddNewCategoryDialog.vue'
import DeleteCategoryDialog from '@/views/product-asset-suite/categories/list/dialogs/DeleteCategoryDialog.vue'
import ViewCategoryDialog from '@/views/product-asset-suite/categories/list/dialogs/ViewCategoryDialog.vue'
import { useRouter } from 'vue-router'
import { VDataTableServer } from 'vuetify/labs/VDataTable'

const props = defineProps({
  categories: { type: Array, default: () => [] },
  totalPage: { type: Number, default: 1 },
  totalCategories: { type: Number, default: 0 },
  options: {
    type: Object,
    required: true,
    validator: value => 'page' in value && 'itemsPerPage' in value,
  },
  searchQuery: { type: String, default: '' },
  selectedType: { type: [String, Number], default: null },
  selectedStatus: { type: [String, Number], default: null },
  loading: { type: Boolean, default: false },
  error: { type: String, default: null },
  addCategory: { type: Function, required: true },
  fetchCategory: { type: Function, required: true },
  deleteCategory: { type: Function, required: true },
  fetchedCategory: { type: Object, default: () => null },
})

// Emits
const emit = defineEmits([
  'update:searchQuery',
  'update:selectedStatus',
  'update:options',
])

const handleCategoryDataPrint = () => {

}

// 👉 - Translations and computed lists
// Factory function for localized computed lists
const createLocalizedComputed = getter => computed(() => getter())

const categoryStatusList = createLocalizedComputed(getCategoryStatus)

// Use translation composable
const { translateValue } = useTranslation()

// Helper functions to translate per value
const translateCategoryStatus = status => translateValue(categoryStatusList, status)

// Dialog visibility state
const isAddNewCategoryDialogVisible = ref(false)
const isDeleteCategoryDialogVisible = ref(false)
const isViewCategoryDialogVisible = ref(false)

// Data table options binding
const dataTableOptions = computed({
  get: () => props.options,
  set: value => emit('update:options', value),
})

// Pagination metadata for table footer
const paginationMeta = computed(() => {
  const { page, itemsPerPage } = props.options
  const start = (page - 1) * itemsPerPage + 1
  const end = Math.min(page * itemsPerPage, props.totalCategories)
  
  return `${i18n.global.t("Showing")} ${start} ${i18n.global.t("To")} ${end} ${i18n.global.t("Of")} ${props.totalCategories} ${i18n.global.t("Entries")}`
})

// Router
const router = useRouter()

const resolveCategoryStatusVariant = stat => {
  const variants = {
    'pending': 'warning',
    'active': 'success',
    'inactive': 'error',
    'blocked': 'error',
  }

  
  return variants[stat.toLowerCase()] || 'secondary'
}

const selectedCategoryId = ref(null)

// Handler for adding a new category via the dialog
const handleAddCategory = async fetchedCategory => {
  try {
    const newCategoryId = await props.addCategory(fetchedCategory)

    router.push({ name: 'product-asset-suite-category-framework-edit-id', params: { id: newCategoryId } })
  } catch (error) {
    console.error("Error adding category:", error.message)
  }
}

// Handle delete confirmation
const handleDeleteCategory = async () => {
  if (selectedCategoryId.value) {
    try {
      await props.deleteCategory(selectedCategoryId.value)
      selectedCategoryId.value = null
    } catch (error) {
      console.error("Error deleting category:", error.message)
    }
  }
}

const handleViewCategory = async categoryId => {
  await props.fetchCategory(categoryId)
  isViewCategoryDialogVisible.value = true
}

const categoryHeaders = computed(() => getCategoriesHeaders())
const categoryStatus = computed(() => getCategoryStatus())
</script>

<template>
  <div>
    <!-- Dialogs -->
    <AddNewCategoryDialog
      v-model:isDialogVisible="isAddNewCategoryDialogVisible"
      @add-category="handleAddCategory"
    />
    <ViewCategoryDialog
      v-model:isDialogVisible="isViewCategoryDialogVisible"
      :fetched-category="props.fetchedCategory"
    />
    <DeleteCategoryDialog
      v-model:isDialogVisible="isDeleteCategoryDialogVisible"
      @delete-category="handleDeleteCategory"
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

    <VRow>
      <VCol cols="12">
        <VCard :loading="loading">
          <!-- Filters -->
          <VCardText>
            <VRow>
              <VCol cols="12">
                <AppSelect
                  :model-value="selectedStatus"
                  :label="$t('Select Status')"
                  :items="categoryStatus"
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
                />
              </div>
              <VBtn
                prepend-icon="tabler-plus"
                @click="isAddNewCategoryDialogVisible = !isAddNewCategoryDialogVisible"
              >
                {{ $t('Add New Category') }}
              </VBtn>
            </div>
          </VCardText>

          <VDivider />

          <!-- Data Table -->
          <VDataTableServer
            v-model:items-per-page="dataTableOptions.itemsPerPage"
            v-model:page="dataTableOptions.page"
            :items="props.categories"
            :items-length="props.totalCategories"
            :headers="categoryHeaders"
            :loading="props.loading"
            class="text-no-wrap"
            @update:options="dataTableOptions = $event"
          >
            <template #[`item.category_name`]="{ item }">
              <div class="d-flex align-center">
                <div class="d-flex flex-column">
                  <h6 class="text-base">
                    {{ item.raw.category_name[$i18n.locale] }}
                  </h6>
                  <span class="text-sm text-medium-emphasis">#{{ item.raw.category_id }}</span>
                </div>
              </div>
            </template>
            <template #[`item.category_description`]="{ item }">
              <span class="text-capitalize font-weight-medium">{{ item.raw.category_description[$i18n.locale] }}</span>
            </template>
            <template #[`item.category_status`]="{ item }">
              <VChip
                :color="resolveCategoryStatusVariant(item.raw.category_status)"
                size="small"
                label
                class="text-capitalize"
              >
                {{ translateCategoryStatus(item.raw.category_status) }}
              </VChip>
            </template>
            <template #[`item.actions`]="{ item }">
              <IconBtn @click="() => handleViewCategory(item.raw.id)">
                <VIcon icon="tabler-eye" />
              </IconBtn>
              <IconBtn :to="{ name: 'product-asset-suite-category-framework-edit-id', params: { id: item.raw.id } }">
                <VIcon icon="tabler-edit" />
              </IconBtn>
              <IconBtn
                @click="() => {
                  selectedCategoryId = item.raw.id;
                  isDeleteCategoryDialogVisible = true;
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
