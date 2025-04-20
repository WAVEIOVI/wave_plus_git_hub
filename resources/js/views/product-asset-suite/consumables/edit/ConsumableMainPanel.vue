<script setup>
import { requiredValidator } from '@/@core/utils/validators'
import { getConsumableTypes, getProductCertifications, getProductStatus } from '@/constants/filterOptions'

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

const consumableCertifications = computed(() => getProductCertifications())
const consumableStatus = computed(() => getProductStatus())
const consumableTypes = computed(() => getConsumableTypes())

const consumable = computed({
  get: () => props.modelValue,
  set: value => emit('update:modelValue', value),
})

// Ensure localized fields exist with safe access
const ensureLocalizedField = field => {
  if (!consumable.value[field]) {
    consumable.value[field] = {}
  }
  
  if (!consumable.value[field][locale.value]) {
    consumable.value[field][locale.value] = ''
  }
  
  return consumable.value[field][locale.value]
}

// Computed properties for localized fields with proper initialization
const consumableName = computed({
  get: () => ensureLocalizedField('name'),
  set: value => {
    if (!consumable.value.name) consumable.value.name = {}
    consumable.value.name[locale.value] = value
  },
})

const consumableDescription = computed({
  get: () => ensureLocalizedField('description'),
  set: value => {
    if (!consumable.value.description) consumable.value.description = {}
    consumable.value.description[locale.value] = value
  },
})

const categoryName = computed({
  get: () => ensureLocalizedField('category_name'),
  set: value => {
    if (!consumable.value.category_name) consumable.value.category_name = {}
    consumable.value.category_name[locale.value] = value
  },
})

const subcategoryName = computed({
  get: () => ensureLocalizedField('subcategory_name'),
  set: value => {
    if (!consumable.value.subcategory_name) consumable.value.subcategory_name = {}
    consumable.value.subcategory_name[locale.value] = value
  },
})

const formattedCertifications = computed({
  get: () => {
    try {
      return JSON.parse(consumable.value.certifications) || []
    } catch (e) {
      return []
    }
  },
  set: value => {
    consumable.value.certifications = JSON.stringify(value)
  },
})
</script>

<template>
  <VCol
    md="8"
    cols="12"
  >
    <!-- 👉 Consumable Information -->
    <VCard
      class="mb-6"
      :title="$t('Consumable Information')"
    >
      <VCardText>
        <VRow>
          <VCol cols="12">
            <VTextField
              v-model="consumableName"
              :label="$t('Consumable Name')"
              :variant="readOnly ? 'plain' : 'outlined'"
              :readonly="readOnly"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12">
            <VTextarea
              v-model="consumableDescription"
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
              v-model="consumable.consumable_type"
              :label="$t('Type')"
              :items="consumableTypes"
              :readonly="readOnly"
              :variant="readOnly ? 'plain' : 'outlined'"
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
              v-model="consumable.brand_name"
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
              v-model="consumable.dimensions"
              :label="$t('Dimensions (L×W×H)')"
              :variant="readOnly ? 'plain' : 'outlined'"
              :readonly="readOnly"
            />
          </VCol>
          <VCol cols="12">
            <VTextField
              v-model="consumable.warranty_info"
              :label="$t('Warranty Information')"
              :variant="readOnly ? 'plain' : 'outlined'"
              :readonly="readOnly"
            />
          </VCol>
          <VCol cols="12">
            <VAutocomplete
              v-model="formattedCertifications"
              :label="$t('Certifications')"
              :items="consumableCertifications"
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
              v-model="consumable.consumable_status"
              :label="$t('Status')"
              :items="consumableStatus"
              :variant="readOnly ? 'plain' : 'outlined'"
              :readonly="readOnly"
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
