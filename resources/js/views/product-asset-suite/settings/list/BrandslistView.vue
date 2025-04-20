<script setup>
import { usePagination } from '@/composables/usePagination'
import AddEditBrandDialog from './dialogs/AddEditBrandDialog.vue'
import DeleteBrandDialog from './dialogs/DeleteBrandDialog.vue'

const props = defineProps({
  brands: { type: Array, default: () => [] },
  loading: { type: Boolean, default: false },
  error: { type: String, default: null },
  addBrand: { type: Function, required: true },
  updateBrand: { type: Function, required: true },
  fetchBrand: { type: Function, required: true },
  deleteBrand: { type: Function, required: true },
  fetchedBrand: { type: Object, default: () => null },
})

const emits = defineEmits(['editBrand'])

// Pagination setup
const itemsPerPage = ref(5)
const brandsRef = computed(() => props.brands)
const { page: brandPage, totalPages: totalBrandPages, currentItems: currentBrands } = usePagination(brandsRef, itemsPerPage)

watch(() => props.brands.length, newLength => {
  if (brandPage.value > Math.ceil(newLength / itemsPerPage.value)) {
    brandPage.value = 1
  }
})

// Dialog state
const dialogState = reactive({
  brand: {
    isVisible: false,
    isDeleteVisible: false,
    current: null,
    toDelete: null,
  },
  isEditMode: false,
})

const EMPTY_BRAND = {
  brand_id: '',
  name: '',
  description: '',
  website: '',
}

// Computed properties for dialog visibility
const isBrandDialogVisible = computed({
  get: () => dialogState.brand.isVisible,
  set: value => dialogState.brand.isVisible = value,
})

const isDeleteBrandDialogVisible = computed({
  get: () => dialogState.brand.isDeleteVisible,
  set: value => dialogState.brand.isDeleteVisible = value,
})

// Brand methods
const handleAddBrand = () => {
  dialogState.brand.current = { ...EMPTY_BRAND }
  dialogState.isEditMode = false
  dialogState.brand.isVisible = true
}

const handleEditBrand = brand => {
  dialogState.brand.current = { ...brand }
  dialogState.isEditMode = true
  dialogState.brand.isVisible = true
}

const handleSubmitBrand = async brandDataInput => {
  try {
    if (dialogState.isEditMode && brandDataInput.id) {
      await props.updateBrand(brandDataInput.id, brandDataInput)
    } else {
      await props.addBrand(brandDataInput)
    }
    emits('editBrand', brandDataInput.id)
  } catch (error) {
    console.error('Error saving brand:', error)
  }
}

const handleDeleteBrand = async brandId => {
  try {
    await props.deleteBrand(brandId)
    emits('editBrand', brandId)
    dialogState.brand.isDeleteVisible = false
  } catch (error) {
    console.error('Error deleting brand:', error)
  }
}

const openBrandDeleteDialog = brand => {
  dialogState.brand.toDelete = brand.id
  dialogState.brand.isDeleteVisible = true
}
</script>

<template>
  <div>
    <VCard
      class="optimized-vcard"
      :loading="loading"
      border
      elevation="2"
      rounded="lg"
    >
      <!-- Optimized Card Header -->
      <VCardTitle class="d-flex align-center optimized-header">
        <h5 class="text-h6">
          {{ $t('Brand List') }}
        </h5>
        <VSpacer />
        <VBtn
          variant="tonal"
          size="small"
          prepend-icon="tabler-plus"
          @click="handleAddBrand"
        >
          {{ $t('Add New Brand') }}
        </VBtn>
      </VCardTitle>
      <VDivider />
      <VCardText>
        <!-- Brand List -->
        <VList class="card-list">
          <template v-if="currentBrands.length === 0">
            <VListItem>
              <VListItemTitle class="text-center text-disabled py-6">
                {{ $t('No brands found') }}
              </VListItemTitle>
            </VListItem>
          </template>

          <template v-else>
            <VListItem
              v-for="(brand, index) in currentBrands"
              :key="brand.id || index"
              class="list-item-hover"
              :class="{'bg-grey-lighten-4': index % 2 === 0}"
            >
              <template #prepend>
                <VIcon
                  icon="tabler-droplet"
                  color="primary"
                  size="24"
                  class="me-3"
                />
              </template>

              <div class="flex-grow-1">
                <VListItemTitle class="font-weight-medium text-primary">
                  {{ brand.name }}
                </VListItemTitle>
                <!-- Tooltip wrapping description with reduced width -->
                <VTooltip location="top">
                  <template #activator="{ props: tooltipProps }">
                    <VListItemSubtitle
                      class="description text-medium-emphasis"
                      v-bind="tooltipProps"
                    >
                      {{ brand.description[$i18n.locale] }} 
                    </VListItemSubtitle>
                  </template>
                  <span class="tooltip-content">{{ brand.description[$i18n.locale] }}</span>
                  <span class="tooltip-content">{{ brand.website }}</span>
                </VTooltip>
              </div>

              <template #append>
                <div class="d-flex align-center gap-2">
                  <VTooltip location="top">
                    <template #activator="{ props: tooltipProps }">
                      <IconBtn
                        v-bind="tooltipProps"
                        color="primary"
                        size="small"
                        @click.stop="handleEditBrand(brand)"
                      >
                        <VIcon icon="tabler-edit" />
                      </IconBtn>
                    </template>
                    <span>{{ $t('Edit brand') }}</span>
                  </VTooltip>

                  <VTooltip location="top">
                    <template #activator="{ props: tooltipProps }">
                      <IconBtn
                        v-bind="tooltipProps"
                        color="error"
                        size="small"
                        @click.stop="openBrandDeleteDialog(brand)"
                      >
                        <VIcon icon="tabler-trash" />
                      </IconBtn>
                    </template>
                    <span>{{ $t('Delete brand') }}</span>
                  </VTooltip>
                </div>
              </template>

              <VDivider v-if="index < currentBrands.length - 1" />
            </VListItem>
          </template>
        </VList>
      </VCardText>


      <!-- Pagination -->
      <VCardActions v-if="totalBrandPages > 1">
        <VPagination
          v-model="brandPage"
          size="small"
          :length="totalBrandPages"
          :total-visible="7"
          color="primary"
          density="comfortable"
          class="mt-3"
        />
      </VCardActions>
    </VCard>

    <!-- Dialog Components -->
    <AddEditBrandDialog
      v-model:is-dialog-visible="isBrandDialogVisible"
      :brand-data="dialogState.brand.current"
      :is-edit-mode="dialogState.isEditMode"
      @submit="handleSubmitBrand"
    />
    <DeleteBrandDialog
      v-model:is-dialog-visible="isDeleteBrandDialogVisible"
      :brand-id="dialogState.brand.toDelete"
      @delete-brand="handleDeleteBrand"
    />
  </div>
</template>

<style lang="scss" scoped>
.optimized-vcard {
  padding: 8px;
  margin-block: 16px;
  margin-inline: auto;
}

.optimized-header {
  padding-block: 8px;
  padding-inline: 12px;
}

.card-list {
  .list-item-hover {
    display: flex;
    align-items: center;
    transition: all 0.2s ease-in-out;

    &:hover {
      background-color: rgba(var(--v-theme-primary), 0.05);
      transform: translateX(4px);
    }
  }
}

/* Multi-line ellipsis for description */
.description {
  display: -webkit-box;
  overflow: hidden;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  text-overflow: ellipsis;
  white-space: normal;
}

/* Tooltip content with reduced width */
.tooltip-content {
  display: block;
  max-inline-size: 500px; /* adjust as needed */
  word-wrap: break-word;
}

.v-card-title {
  border-radius: 8px 8px 0 0;
}
</style>

<route lang="yaml">
meta:
  layout: default
  requiresAuth: true
  action: manage
  subject: ProductAssetSuite
</route>
