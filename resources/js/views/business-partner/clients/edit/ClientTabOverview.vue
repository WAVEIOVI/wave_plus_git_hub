<script setup>
import { requiredValidator } from '@/@core/utils/validators'
import { getBpCertifications, getBpIndustries, getBpLegalStatus, getBpStatus, getCompaniesSize } from '@/constants/filterOptions'

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
const client = computed({
  get: () => props.modelValue,
  set: value => emits('update:modelValue', value),
})

const formattedCertifications = computed({
  get: () => {
    try {
      return JSON.parse(client.value.certifications) || []
    } catch (e) {
      return []
    }
  },
  set: value => {
    client.value.certifications = JSON.stringify(value)
  },
})

const clientCertifications = computed(() => getBpCertifications())
const clientStatus = computed(() => getBpStatus())
const clientLegalStatus = computed(() => getBpLegalStatus())
const clientIndustries = computed(() => getBpIndustries())
const companiesSize = computed(() => getCompaniesSize())
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardText>
          <h5 class="text-h5">
            {{ $t('Core Client Information') }}
          </h5>
          <VDivider class="my-4" />
          <VRow>
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="client.legal_name"
                :label="$t('Legal Name')"
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
              <VTextField
                v-model="client.commercial_name"
                :label="$t('Commercial Name')"
                prepend-icon="tabler-building-store"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
              />
            </VCol>
          </VRow>
          <VRow>
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="client.tax_id"
                :label="$t('TAX ID')"
                prepend-icon="tabler-id"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
              />
            </VCol>
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="client.rne_id"
                :label="$t('RNE ID')"
                prepend-icon="tabler-id"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
              />
            </VCol>
          </VRow>
          <VRow>
            <VCol
              cols="12"
              md="6"
            >
              <VAutocomplete
                v-model="client.industry"
                :label="$t('Industry')"
                prepend-icon="tabler-building-factory-2"
                :items="clientIndustries"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
                :clearable="!readOnly"
                :clear-icon="!readOnly ? 'tabler-x' : ''"
                :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
              />
            </VCol>
            <VCol
              cols="12"
              md="6"
            >
              <VAutocomplete
                v-model="client.company_size"
                :label="$t('Company Size')"
                prepend-icon="tabler-users"
                :items="companiesSize"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
                :clearable="!readOnly"
                :clear-icon="!readOnly ? 'tabler-x' : ''"
                :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
              />
            </VCol>
            <VCol
              cols="12"
              md="6"
            >
              <VAutocomplete
                v-model="client.legal_status"
                :label="$t('Legal Status')"
                prepend-icon="tabler-scale"
                :items="clientLegalStatus"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
                :clearable="!readOnly"
                :clear-icon="!readOnly ? 'tabler-x' : ''"
                :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
              />
            </VCol>
            <VCol
              cols="12"
              md="6"
            >
              <VAutocomplete
                v-model="client.client_status"
                :label="$t('Client Status')"
                prepend-icon="tabler-user-check"
                :items="clientStatus"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
                :clearable="!readOnly"
                :clear-icon="!readOnly ? 'tabler-x' : ''"
                :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
              />
            </VCol>
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="client.founding_year"
                :label="$t('Founding Year')"
                prepend-icon="tabler-calendar"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
              />
            </VCol>

            <VCol
              cols="12"
              md="6"
            >
              <VAutocomplete
                v-model="formattedCertifications"
                :label="$t('Certifications')"
                prepend-icon="tabler-certificate"
                :items="clientCertifications"
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
    </VCol>
  </VRow>
</template>
