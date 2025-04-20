<script setup>
import { useTranslation } from '@/composables/useTranslation'
import { getProductHeaders, getProductTypes } from '@/constants/filterOptions'
import i18n from "@/plugins/i18n"
import { useRouter } from 'vue-router'
import { VDataTableServer } from 'vuetify/labs/VDataTable'
import AddNewProductDialog from './dialogs/AddNewProductDialog.vue'
import DeleteProductDialog from './dialogs/DeleteProductDialog.vue'
import ViewProductDialog from './dialogs/ViewProductDialog.vue'

const props = defineProps({
  products: { type: Array, default: () => [] },
  totalPage: { type: Number, default: 1 },
  totalProducts: { type: Number, default: 0 },
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
      total_products: 0,
      total_physical_products: 0,
      total_service_products: 0,
      total_digital_products: 0,
    }),
  },
  addProduct: { type: Function, required: true },
  fetchProduct: { type: Function, required: true },
  deleteProduct: { type: Function, required: true },
  printProducts: { type: Function, required: true },
  printProductData: { type: Function, required: true },
  fetchedProduct: { type: Object, default: () => null },
})

// Emits
const emit = defineEmits([
  'update:searchQuery',
  'update:selectedType',
  'update:selectedSubcategory',
  'update:selectedCategory',
  'update:options',
])

const handleProductDataPrint = () => {

}

// 👉 - Translations and computed lists
// Factory function for localized computed lists
const createLocalizedComputed = getter => computed(() => getter())

const productTypesList = createLocalizedComputed(getProductTypes)

// Use translation composable
const { translateValue } = useTranslation()

// Helper functions to translate per value
const translateProductTypes = type => translateValue(productTypesList, type)

// Dialog visibility state
const isAddNewProductDialogVisible = ref(false)
const isDeleteProductDialogVisible = ref(false)
const isViewProductDialogVisible = ref(false)

// Data table options binding
const dataTableOptions = computed({
  get: () => props.options,
  set: value => emit('update:options', value),
})

// Pagination metadata for table footer
const paginationMeta = computed(() => {
  const { page, itemsPerPage } = props.options
  const start = (page - 1) * itemsPerPage + 1
  const end = Math.min(page * itemsPerPage, props.totalProducts)
  
  return `${i18n.global.t("Showing")} ${start} ${i18n.global.t("To")} ${end} ${i18n.global.t("Of")} ${props.totalProducts} ${i18n.global.t("Entries")}`
})

// Router
const router = useRouter()

// UI Variant Resolvers
const resolveProductTypeVariant = type => {
  const variants = {
    'physical': { 
      color: 'success', 
      icon: 'tabler-tool', // Chemical agents
    },
    'service': { 
      color: 'info', 
      icon: 'tabler-settings', // Mechanical parts
    },
    'digital': { 
      color: 'warning', 
      icon: 'tabler-binary', // Warning symbols
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
    label: 'Total Products',
    value: props.statisticsData.total_products,
    color: 'primary',
    icon: 'tabler-package', // Represents general products
  },
  {
    label: 'Physical Products',
    value: props.statisticsData.total_physical_products,
    color: 'success',
    icon: 'tabler-tool', // Represents tangible/physical items
  },
  {
    label: 'Service Products',
    value: props.statisticsData.total_service_products,
    color: 'info',
    icon: 'tabler-settings', // Represents services/settings
  },
  {
    label: 'Digital Products',
    value: props.statisticsData.total_digital_products,
    color: 'error',
    icon: 'tabler-binary', // Represents digital/data
  },
]))

const selectedProductId = ref(null)

// Handler for adding a new product via the dialog
const handleAddProduct = async fetchedProduct => {
  try {
    const newProductId = await props.addProduct(fetchedProduct)

    router.push({ name: 'product-asset-suite-product-catalog-edit-id', params: { id: newProductId } })
  } catch (error) {
    console.error("Error adding product:", error.message)
  }
}

// Handle delete confirmation
const handleDeleteProduct = async () => {
  if (selectedProductId.value) {
    try {
      await props.deleteProduct(selectedProductId.value)
      selectedProductId.value = null
    } catch (error) {
      console.error("Error deleting product:", error.message)
    }
  }
}

const handleViewProduct = async productId => {
  await props.fetchProduct(productId)
  isViewProductDialogVisible.value = true
}

const productHeaders = computed(() => getProductHeaders())
</script>

<template>
  <div>
    <!-- Dialogs -->
    <ViewProductDialog
      v-model:isDialogVisible="isViewProductDialogVisible"
      :fetched-product="props.fetchedProduct"
      @print-product-data="props.printProductData"
    />
    <AddNewProductDialog
      v-model:isDialogVisible="isAddNewProductDialogVisible"
      @add-product="handleAddProduct"
    />
    <DeleteProductDialog
      v-model:isDialogVisible="isDeleteProductDialogVisible"
      @delete-product="handleDeleteProduct"
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
                sm="4"
              >
                <AppSelect
                  :model-value="selectedType"
                  :label="$t('Select Type')"
                  :items="productTypesList"
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
                  :items="props.products
                    .filter(product => product && product.category_name && product.category_id)
                    .map(product => ({
                      title: product.category_name[$i18n.locale] || product.category_name || 'N/A',
                      value: product.category_id,
                      text: `${product.category_id} - ${product.category_name[$i18n.locale] || product.category_name || 'N/A'}`
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
                  :items="props.products
                    .filter(product => product && product.subcategory_name && product.subcategory_id)
                    .map(product => ({
                      title: product.subcategory_name[$i18n.locale] || product.subcategory_name || 'N/A',
                      value: product.subcategory_id,
                      text: `${product.subcategory_id} - ${product.subcategory_name[$i18n.locale] || product.subcategory_name || 'N/A'}`
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
                @click="printProducts"
              >
                {{ $t('Print') }}
              </VBtn>
              <VBtn
                prepend-icon="tabler-plus"
                @click="isAddNewProductDialogVisible = !isAddNewProductDialogVisible"
              >
                {{ $t('Add New Product') }}
              </VBtn>
            </div>
          </VCardText>

          <VDivider />

          <!-- Data Table -->
          <VDataTableServer
            v-model:items-per-page="dataTableOptions.itemsPerPage"
            v-model:page="dataTableOptions.page"
            :items="props.products"
            :items-length="props.totalProducts"
            :headers="productHeaders"
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
                <span class="text-sm text-medium-emphasis">#{{ item.raw.product_id || 'N/A' }}</span>
              </div>
            </template>
            <template #[`item.product_type`]="{ item }">
              <div class="d-flex align-center gap-4">
                <VAvatar
                  rounded
                  :size="30"
                  :color="resolveProductTypeVariant(item.raw.product_type)?.color"
                >
                  <VIcon
                    :size="20"
                    :icon="resolveProductTypeVariant(item.raw.product_type)?.icon"
                  />
                </VAvatar>
                <span class="text-capitalize">{{ translateProductTypes(item.raw.product_type) || 'N/A' }}</span>
              </div>
            </template>
            <template #[`item.actions`]="{ item }">
              <IconBtn @click="() => handleViewProduct(item.raw.id)">
                <VIcon icon="tabler-eye" />
              </IconBtn>
              <IconBtn :to="{ name: 'product-asset-suite-product-catalog-edit-id', params: { id: item.raw.id } }">
                <VIcon icon="tabler-edit" />
              </IconBtn>
              
              <IconBtn
                @click="() => {
                  selectedProductId = item.raw.id;
                  isDeleteProductDialogVisible = true;
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
