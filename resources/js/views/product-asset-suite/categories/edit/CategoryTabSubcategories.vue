<script setup>
import { usePagination } from '@/composables/usePagination'
import { useTranslation } from '@/composables/useTranslation'
import { getCategoryStatus } from '@/constants/filterOptions'

// Dialog components for subcategories
import AddEditSubcategoryDialog from './dialogs/AddEditSubcategoryDialog.vue'
import DeleteSubcategoryDialog from './dialogs/DeleteSubcategoryDialog.vue'

const props = defineProps({
  modelValue: { type: Object, required: true },
  subcategoryData: { type: Array, required: true },
  addCategorySubcategory: { type: Function, default: () => {} },
  updateCategorySubcategory: { type: Function, default: () => {} },
  fetchCategorySubcategory: { type: Function, default: () => {} },
  deleteCategorySubcategory: { type: Function, default: () => {} },
  readOnly: { type: Boolean, default: false },
})

const emits = defineEmits([
  'update:modelValue',
  'editSubcategory',
])

// Two-way binding for category data
const category = computed({
  get: () => props.modelValue,
  set: value => emits('update:modelValue', value),
})

// Pagination state for subcategories
const itemsPerPage = ref(3)

// Subcategory pagination setup with reactive ref wrapping subcategoryData
const subcategoriesRef = computed(() => props.subcategoryData)
const { page: subcategoryPage, totalPages: totalSubcategoryPages, currentItems: currentSubcategories } = usePagination(subcategoriesRef, itemsPerPage)


// Dialog state
const dialogState = reactive({
  subcategory: {
    isVisible: false,
    isDeleteVisible: false,
    current: null,
    toDelete: null,
  },
  isEditMode: false,
})

// Translation helper
const { translateValue } = useTranslation()

const CONTACT_STATUS_VARIANTS = {
  active: 'success',
  inactive: 'error',
  draft: 'warning',
}

// Empty objects for initialization
const EMPTY_CONTACT = {
  subcategory_id: '',
  subcategory_name: '',
  subcategory_description: '',
  subcategory_status: '',
}

// Filter Options
const subcategoryStatusList = computed(() => getCategoryStatus())

// Dialog shorthands - making component template cleaner
const isSubcategoryDialogVisible = computed({
  get: () => dialogState.subcategory.isVisible,
  set: value => dialogState.subcategory.isVisible = value,
})

const isDeleteSubcategoryDialogVisible = computed({
  get: () => dialogState.subcategory.isDeleteVisible,
  set: value => dialogState.subcategory.isDeleteVisible = value,
})

const resolveSubcategoryStatusVariant = status => {
  if (!status) return CONTACT_STATUS_VARIANTS.default
  
  return CONTACT_STATUS_VARIANTS[status.toLowerCase()] || CONTACT_STATUS_VARIANTS.default
}

// Translation helpers
const translateSubcategoryStatus = status => translateValue(subcategoryStatusList, status)

const handleAddSubcategory = () => {
  if (props.readOnly) return
  dialogState.subcategory.current = { ...EMPTY_CONTACT }
  dialogState.isEditMode = false
  dialogState.subcategory.isVisible = true
}

const handleEditSubcategory = subcategory => {
  if (props.readOnly) return
  dialogState.subcategory.current = { ...subcategory }
  dialogState.isEditMode = true
  dialogState.subcategory.isVisible = true
}

const handleSubmitSubcategory = async subcategoryDataInput => {
  try {
    if (dialogState.isEditMode && subcategoryDataInput.id) {
      await props.updateCategorySubcategory(category.value.id, subcategoryDataInput.id, subcategoryDataInput)
    } else {
      await props.addCategorySubcategory(category.value.id, subcategoryDataInput)
    }
    emits('editSubcategory', subcategoryDataInput.id)
  } catch (error) {
    console.error('Error saving subcategory:', error)
  }
}

const handleDeleteSubcategory = async subcategoryId => {
  try {
    await props.deleteCategorySubcategory(category.value.id, subcategoryId)
    emits('editSubcategory', subcategoryId)
    dialogState.subcategory.isDeleteVisible = false
  } catch (error) {
    console.error('Error deleting subcategory:', error)
  }
}

const openSubcategoryDeleteDialog = subcategory => {
  dialogState.subcategory.toDelete = subcategory.id
  dialogState.subcategory.isDeleteVisible = true
}
</script>

<template>
  <VCard class="mb-6">
    <VCardText>
      <div class="d-flex justify-space-between mb-6 flex-wrap align-center gap-y-4 gap-x-6">
        <h5 class="text-h5">
          {{ $t('Subcategory List') }}
        </h5>
        <VBtn
          v-if="!readOnly"
          variant="tonal"
          size="small"
          @click="handleAddSubcategory"
        >
          {{ $t('Add New Subcategory') }}
        </VBtn>
      </div>
      <VExpansionPanels variant="inset">
        <VExpansionPanel
          v-for="(subcategory, index) in currentSubcategories"
          :key="subcategory.id || index"
        >
          <!-- CONTACT HEADER -->
          <VExpansionPanelTitle>
            <div class="d-flex align-center gap-x-4 w-100">
              <div class="d-flex flex-grow-1">
                <div class="d-flex align-center gap-x-2 mb-1">
                  <h6 class="text-h6">
                    {{ subcategory.subcategory_id }} | {{ subcategory.subcategory_name[$i18n.locale] }}
                  </h6>
                </div>
              </div>
              <!-- Action Buttons -->
              <div
                v-if="!readOnly"
                class="d-flex align-center"
              >
                <IconBtn @click.stop="handleEditSubcategory(subcategory)">
                  <VIcon
                    icon="tabler-edit"
                    class="flip-in-rtl"
                  />
                </IconBtn>
                <IconBtn @click.stop="openSubcategoryDeleteDialog(subcategory)">
                  <VIcon
                    icon="tabler-trash"
                    class="flip-in-rtl"
                  />
                </IconBtn>
              </div>
            </div>
          </VExpansionPanelTitle>
          <!-- CONTACT DETAILS -->
          <VExpansionPanelText>
            <VRow>
              <VCol cols="6">
                <h6 class="mb-2">
                  {{ $t('Subcategory Details') }}
                </h6>
                <div class="text-body-1 whitespace-pre-line">
                  <div v-if="subcategory.subcategory_description">
                    <strong>{{ $t('Description') }}:</strong>{{ subcategory.subcategory_description[$i18n.locale] }}
                  </div>
                </div>
              </VCol>
            </VRow>
          </VExpansionPanelText>
        </VExpansionPanel>
      </VExpansionPanels>
      <div class="d-flex justify-center mt-6">
        <VPagination
          v-if="totalSubcategoryPages > 0"
          v-model="subcategoryPage"
          size="small"
          :length="totalSubcategoryPages"
          :total-visible="5"
        />
      </div>
    </VCardText>
  </VCard>
  <AddEditSubcategoryDialog
    v-model:is-dialog-visible="isSubcategoryDialogVisible"
    :subcategory-data="dialogState.subcategory.current"
    :is-edit-mode="dialogState.isEditMode"
    @submit="handleSubmitSubcategory"
  />
  <DeleteSubcategoryDialog
    v-model:is-dialog-visible="isDeleteSubcategoryDialogVisible"
    :subcategory-id="dialogState.subcategory.toDelete"
    @delete-subcategory="handleDeleteSubcategory"
  />
</template>
