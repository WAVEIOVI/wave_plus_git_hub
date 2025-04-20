<script setup>
import { requiredValidator } from '@/@core/utils/validators'
import { getProductCertifications, getProductStatus, getProductTypes } from '@/constants/filterOptions'

const props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  readOnly: {
    type: Boolean,
    default: false,
  },
  brands: {
    type: Array,
    default: () => [],
    required: false,
  },
})

const emit = defineEmits(['update:modelValue'])
const { locale } = useI18n()  // Import locale directly from useI18n

const productCertifications = computed(() => getProductCertifications())
const productStatus = computed(() => getProductStatus())
const productTypes = computed(() => getProductTypes())

const product = computed({
  get: () => props.modelValue,
  set: value => emit('update:modelValue', value),
})

// Ensure localized fields exist with safe access
const ensureLocalizedField = field => {
  if (!product.value[field]) {
    product.value[field] = {}
  }
  
  if (!product.value[field][locale.value]) {
    product.value[field][locale.value] = ''
  }
  
  return product.value[field][locale.value]
}

// Computed properties for localized fields with proper initialization
const productName = computed({
  get: () => ensureLocalizedField('name'),
  set: value => {
    if (!product.value.name) product.value.name = {}
    product.value.name[locale.value] = value
  },
})

const productDescription = computed({
  get: () => ensureLocalizedField('description'),
  set: value => {
    if (!product.value.description) product.value.description = {}
    product.value.description[locale.value] = value
  },
})

const categoryName = computed({
  get: () => ensureLocalizedField('category_name'),
  set: value => {
    if (!product.value.category_name) product.value.category_name = {}
    product.value.category_name[locale.value] = value
  },
})

const subcategoryName = computed({
  get: () => ensureLocalizedField('subcategory_name'),
  set: value => {
    if (!product.value.subcategory_name) product.value.subcategory_name = {}
    product.value.subcategory_name[locale.value] = value
  },
})

const formattedCertifications = computed({
  get: () => {
    try {
      return JSON.parse(product.value.certifications) || []
    } catch (e) {
      return []
    }
  },
  set: value => {
    product.value.certifications = JSON.stringify(value)
  },
})
</script>

<template>
  <VCol
    md="8"
    cols="12"
  >
    <!-- 👉 Product Information -->
    <VCard
      class="mb-6"
      :title="$t('Product Information')"
    >
      <VCardText>
        <VRow>
          <VCol cols="12">
            <VTextField
              v-model="productName"
              :label="$t('Product Name')"
              :variant="readOnly ? 'plain' : 'outlined'"
              :readonly="readOnly"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12">
            <VTextarea
              v-model="productDescription"
              :label="$t('Description (optional)')"
              rows="3"
              :readonly="readOnly"
              :variant="readOnly ? 'plain' : 'outlined'"
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
            lg="4"
          >
            <VAutocomplete
              v-model="product.product_type"
              :label="$t('Type')"
              :items="productTypes"
              :variant="readOnly ? 'plain' : 'outlined'"
              :readonly="readOnly"
              :clearable="!readOnly"
              :clear-icon="!readOnly ? 'tabler-x' : ''"
              :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
              disabled
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
            lg="4"
          >
            <VAutocomplete
              v-model="product.brand_name"
              :label="$t('Brand Name')"
              density="compact"
              :readonly="readOnly"
              :variant="readOnly ? 'plain' : 'outlined'"
              :clearable="!readOnly"
              :clear-icon="!readOnly ? 'tabler-x' : ''"
              :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
              :items="brands"
              item-title="title"
              item-value="value"
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
            lg="4"
          >
            <VTextField
              v-model="product.dimensions"
              :label="$t('Dimensions (L×W×H)')"
              :variant="readOnly ? 'plain' : 'outlined'"
              :readonly="readOnly"
            />
          </VCol>
          <VCol cols="12">
            <VTextField
              v-model="product.warranty_info"
              :label="$t('Warranty Information')"
              :variant="readOnly ? 'plain' : 'outlined'"
              :readonly="readOnly"
            />
          </VCol>
          <VCol cols="12">
            <VAutocomplete
              v-model="formattedCertifications"
              :label="$t('Certifications')"
              :items="productCertifications"
              :readonly="readOnly"
              :variant="readOnly ? 'plain' : 'outlined'"
              :clearable="!readOnly"
              :clear-icon="!readOnly ? 'tabler-x' : ''"
              :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
              chips
              multiple
              :closable-chips="!readOnly"
            />
          </VCol>
        </VRow>
      </VCardText>
    </VCard>

    <VCard
      :title="$t('Organize')"
      class="mb-6"
    >
      <VCardText>
        <VRow>
          <VCol
            cols="12"
            md="6"
            lg="4"
          >
            <VTextField
              v-model="categoryName"
              :label="$t('Category')"
              :variant="readOnly ? 'plain' : 'outlined'"
              readonly
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
            lg="4"
          >
            <VTextField
              v-model="subcategoryName"
              :label="$t('Subcategory')"
              :variant="readOnly ? 'plain' : 'outlined'"
              readonly
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
            lg="4"
          >
            <VAutocomplete
              v-model="product.product_status"
              :label="$t('Status')"
              :items="productStatus"
              :variant="readOnly ? 'plain' : 'outlined'"
              :clearable="!readOnly"
              :clear-icon="!readOnly ? 'tabler-x' : ''"
              :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
            />
          </VCol>
        </VRow>
      </VCardText>
    </VCard>
  </VCol>
</template>
