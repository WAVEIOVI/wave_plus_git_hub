<script setup>
import { requiredValidator } from '@/@core/utils/validators'
import { getEqServicingTypes, getProductCertifications, getProductStatus } from '@/constants/filterOptions'

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

const eqBlueprintCertifications = computed(() => getProductCertifications())
const eqBlueprintStatus = computed(() => getProductStatus())
const eqBlueprintTypes = computed(() => getEqServicingTypes())

const eqBlueprint = computed({
  get: () => props.modelValue,
  set: value => emit('update:modelValue', value),
})

// Ensure localized fields exist with safe access
const ensureLocalizedField = field => {
  if (!eqBlueprint.value[field]) {
    eqBlueprint.value[field] = {}
  }
  
  if (!eqBlueprint.value[field][locale.value]) {
    eqBlueprint.value[field][locale.value] = ''
  }
  
  return eqBlueprint.value[field][locale.value]
}

// Computed properties for localized fields with proper initialization
const eqBlueprintName = computed({
  get: () => ensureLocalizedField('name'),
  set: value => {
    if (!eqBlueprint.value.name) eqBlueprint.value.name = {}
    eqBlueprint.value.name[locale.value] = value
  },
})

const eqBlueprintDescription = computed({
  get: () => ensureLocalizedField('description'),
  set: value => {
    if (!eqBlueprint.value.description) eqBlueprint.value.description = {}
    eqBlueprint.value.description[locale.value] = value
  },
})

const categoryName = computed({
  get: () => ensureLocalizedField('category_name'),
  set: value => {
    if (!eqBlueprint.value.category_name) eqBlueprint.value.category_name = {}
    eqBlueprint.value.category_name[locale.value] = value
  },
})

const subcategoryName = computed({
  get: () => ensureLocalizedField('subcategory_name'),
  set: value => {
    if (!eqBlueprint.value.subcategory_name) eqBlueprint.value.subcategory_name = {}
    eqBlueprint.value.subcategory_name[locale.value] = value
  },
})

const formattedCertifications = computed({
  get: () => {
    try {
      return JSON.parse(eqBlueprint.value.certifications) || []
    } catch (e) {
      return []
    }
  },
  set: value => {
    eqBlueprint.value.certifications = JSON.stringify(value)
  },
})
</script>

<template>
  <VCol
    md="8"
    cols="12"
  >
    <!-- 👉 EqBlueprint Information -->
    <VCard
      class="mb-6"
      :title="$t('Equipment Blueprint Information')"
    >
      <VCardText>
        <VRow>
          <VCol cols="12">
            <VTextField
              v-model="eqBlueprintName"
              :label="$t('Equipment Blueprint Name')"
              :variant="readOnly ? 'plain' : 'outlined'"
              :readonly="readOnly"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12">
            <VTextarea
              v-model="eqBlueprintDescription"
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
              v-model="eqBlueprint.eq_blueprint_type"
              :label="$t('Type')"
              :items="eqBlueprintTypes"
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
              v-model="eqBlueprint.brand_name"
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
          <VCol cols="12">
            <VTextField
              v-model="eqBlueprint.warranty_info"
              :label="$t('Warranty Information')"
              :variant="readOnly ? 'plain' : 'outlined'"
              :readonly="readOnly"
            />
          </VCol>
          <VCol cols="12">
            <VAutocomplete
              v-model="formattedCertifications"
              :label="$t('Certifications')"
              :items="eqBlueprintCertifications"
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
              v-model="eqBlueprint.eq_blueprint_status"
              :label="$t('Status')"
              :items="eqBlueprintStatus"
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
