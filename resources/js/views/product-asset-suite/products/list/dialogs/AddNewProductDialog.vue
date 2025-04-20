<script setup>
import { requiredValidator } from '@/@core/utils/validators'
import { getProductTypes } from '@/constants/filterOptions'
import { useCategoryStore } from "@/stores/product-asset-suite/categories/useCategoryStore"
import { useI18n } from 'vue-i18n'

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'addProduct',
])

// Access i18n using useI18n
const { locale, t } = useI18n()

const categoryStore = useCategoryStore()

const fetchedProduct = ref({
  name: {},
  product_type: '',
  category_id: '',
  subcategory_id: '',
})

const categories = ref([])
const subcategories = ref([])

// Helper to extract array from the response in case of nested data
const extractArray = response => {
  if (Array.isArray(response.data)) {
    return response.data
  }
  if (response.data && Array.isArray(response.data.data)) {
    return response.data.data
  }
  
  return []
}

// Fetch categories when the dialog is opened
watch(
  () => props.isDialogVisible,
  async newVal => {
    if (newVal) {
      try {
        const response = await categoryStore.fetchCategories({ options: {} })

        const categoriesArray = extractArray(response)
        if (Array.isArray(categoriesArray)) {
          categories.value = categoriesArray.map(category => ({
            title: `${category.category_id} - ${category.category_name[locale.value] || ''}`, // Changed from text to title
            value: category.id,
          }))
        } else {
          console.error('Unexpected response format:', response)
          categories.value = []
        }
      } catch (error) {
        console.error('Error fetching categories:', error)
        categories.value = []
      }
    }
  },
)

// Watch the correct property: category_id
watch(
  () => fetchedProduct.value.category_id,
  newCategoryId => {
    fetchSubcategories(newCategoryId)
  },
)

const fetchSubcategories = async categoryId => {
  if (!categoryId) {
    subcategories.value = []
    
    return
  }
  try {
    const response = await categoryStore.fetchCategorySubcategories({ id: categoryId, search: '' })

    console.log(response) // Inspect response structure

    const subcategoriesArray = extractArray(response)
    if (Array.isArray(subcategoriesArray)) {
      subcategories.value = subcategoriesArray.map(subcategory => ({
        title: `${subcategory.subcategory_id} - ${subcategory.subcategory_name[locale.value] || ''}`, // Changed from text to title
        value: subcategory.id,
      }))
    } else {
      console.error('Unexpected response format:', response)
      subcategories.value = []
    }
  } catch (error) {
    console.error('Error fetching subcategories:', error)
    subcategories.value = []
  }
}

const resetForm = () => {
  emit('update:isDialogVisible', false)
  fetchedProduct.value = {
    name: {},
    product_type: '',
    category_id: '',
    subcategory_id: '',
  }
  subcategories.value = []
}

const onFormSubmit = () => {
  emit('addProduct', fetchedProduct.value)
  resetForm()
}

const productTypes = computed(() => getProductTypes())
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 610"
    :model-value="props.isDialogVisible"
    @update:model-value="val => $emit('update:isDialogVisible', val)"
  >
    <!-- Dialog Close Button -->
    <DialogCloseBtn @click="$emit('update:isDialogVisible', false)" />

    <!-- Dialog Content -->
    <VCard :title="$t('Add New Product')">
      <VForm
        class="mt-4"
        @submit.prevent="onFormSubmit"
      >
        <VCardText>
          <VRow>
            <VCol cols="12">
              <AppSelect
                v-model="fetchedProduct.product_type"
                :label="$t('Product Type')"
                density="compact"
                :items="productTypes"
                :rules="[requiredValidator]"
                auto-select-first
              />
            </VCol>
            <VCol cols="12">
              <AppTextField
                v-model="fetchedProduct.name[$i18n.locale]"
                :label="$t('Product Name')"
                :rules="[requiredValidator]"
              />
            </VCol>
            <VCol cols="12">
              <AppSelect
                v-model="fetchedProduct.category_id"
                :label="$t('Category')"
                density="compact"
                :items="categories"
                item-title="title"
                item-value="value"
                :rules="[requiredValidator]"
                auto-select-first
              />
            </VCol>
            <VCol cols="12">
              <AppSelect
                v-model="fetchedProduct.subcategory_id"
                :label="$t('Subcategory')"
                density="compact"
                :items="subcategories"
                item-title="title"
                item-value="value"
                :rules="[requiredValidator]"
                auto-select-first
              />
            </VCol>
          </VRow>
        </VCardText>
        <VCardText class="d-flex justify-end flex-wrap gap-3">
          <VBtn
            type="submit"
            color="primary"
          >
            {{ $t('Add') }}
          </VBtn>
          <VBtn
            color="error"
            variant="tonal"
            @click="resetForm"
          >
            {{ $t('Discard') }}
          </VBtn>
        </VCardText>
      </VForm>
    </VCard>
  </VDialog>
</template>
