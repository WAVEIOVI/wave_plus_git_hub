<script setup>
import { requiredValidator } from '@/@core/utils/validators'
import { getCategoryStatus } from '@/constants/filterOptions'

const props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  readOnly: {
    type: Boolean,
    default: false,
  },
})

const emits = defineEmits(['update:modelValue'])

// Create a computed property that acts as a two-way binding proxy
const eqBlueprint = computed({
  get: () => props.modelValue,
  set: value => emits('update:modelValue', value),
})

const { locale } = useI18n()  // Import locale directly from useI18n

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

const categoryStatus = computed(() => getCategoryStatus())
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardText>
          <h5 class="text-h5">
            {{ $t('Core Eq. Blueprint Information') }}
          </h5>
          <VDivider class="my-4" />
          <VRow>
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="eqBlueprint.eq_blueprint_id"
                :label="$t('Equipment Blueprint ID')"
                prepend-icon="tabler-user"
                :variant="readOnly ? 'plain' : 'outlined'"
                :readonly="readOnly"
                :rules="[requiredValidator]"
              />
            </VCol>
            <VCol
              cols="12"
              md="6"
            >
              <VAutocomplete
                v-model="eqBlueprint.eq_blueprint_status"
                :label="$t('Equipment Blueprint Status')"
                prepend-icon="tabler-user-check"
                :items="categoryStatus"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
                :clearable="!readOnly"
                :clear-icon="!readOnly ? 'tabler-x' : ''"
                :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
              />
            </VCol>
            <VCol cols="12">
              <VTextField
                v-model="eqBlueprint.name[$i18n.locale]"
                :label="$t('Equipment Blueprint Name')"
                prepend-icon="tabler-building-store"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
              />
            </VCol>
          </VRow>
          <VRow>
            <VCol cols="12">
              <VTextarea
                v-model="eqBlueprintDescription"
                :label="$t('Equipment Blueprint Description')"
                prepend-icon="tabler-id"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
              />
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
