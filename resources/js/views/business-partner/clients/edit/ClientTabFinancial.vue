<script setup>
import { integerValidator } from '@/@core/utils/validators'
import { getPaymentTerms, preferredCurrency } from '@/constants/filterOptions'

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

const panel = ref(1)

watch(() => client.value.vat_exemption_certificate_id, newValue => {
  if (!newValue) {
    client.value.vat_exemption_certificate_issue_date = null
    client.value.vat_exemption_certificate_expiration_date = null
    panel.value = 1 // Close the panel
  } else {
    panel.value = 0 // Open the panel
  }
})

const paymentTerms = computed(() => getPaymentTerms())
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardText>
          <h5 class="text-h5">
            {{ $t('Financial & Transactional Data') }}
          </h5>
          <VDivider class="my-4" />
          <VRow>
            <!-- 👉 Payment Terms -->
            <VCol
              md="4"
              cols="12"
            >
              <VAutocomplete
                v-model="client.payment_terms"
                :label="$t('Payment Terms')"
                prepend-icon="tabler-currency-dollar"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
                :clearable="!readOnly"
                :clear-icon="!readOnly ? 'tabler-x' : ''"
                :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
                :items="paymentTerms"
              />
            </VCol>
            <!-- 👉 Credit Limit -->
            <VCol
              md="4"
              cols="12"
            >
              <VTextField
                v-model="client.credit_limit"
                :label="$t('Credit Limit')"
                prepend-icon="tabler-credit-card"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
                :rules="[integerValidator]"
              />
            </VCol>
            <!-- 👉 Outstanding Balance -->
            <VCol
              md="4"
              cols="12"
            >
              <VTextField
                v-model="client.outstanding_balance"
                :label="$t('Outstanding Balance')"
                prepend-icon="tabler-wallet"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
                :rules="[integerValidator]"
              />
            </VCol>

            <!-- 👉 bank -->
            <VCol
              md="4"
              cols="12"
            >
              <VTextField
                v-model="client.bank"
                :label="$t('Bank')"
                prepend-icon="tabler-building-bank"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
              />
            </VCol>

            <!-- 👉 Bank Agence -->
            <VCol
              md="4"
              cols="12"
            >
              <VTextField
                v-model="client.bank_agence"
                :label="$t('Bank Agence')"
                prepend-icon="tabler-building"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
              />
            </VCol>
            <!-- 👉 Preferred Currency -->
            <VCol
              md="4"
              cols="12"
            >
              <VAutocomplete
                v-model="client.preferred_currency"
                :label="$t('Preferred Currency')"
                prepend-icon="tabler-coins"
                :items="preferredCurrency"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
                :clearable="!readOnly"
                :clear-icon="!readOnly ? 'tabler-x' : ''"
                :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
              />
            </VCol>
            <!-- 👉 Bank RIB -->
            <VCol cols="12">
              <VTextField
                v-model="client.bank_rib"
                :label="$t('Bank RIB')"
                prepend-icon="tabler-barcode"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
              />
            </VCol>
          </VRow>
          <VRow>
            <VCol cols="12">
              <VTextField
                v-model="client.vat_exemption_certificate_id"
                :label="$t('Certificate ID')"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
              />
            </VCol>
            <VCol cols="12">
              <div v-if="!readOnly">
                <VExpansionPanels
                  v-model="panel"
                  class="no-icon-rotate"
                >
                  <VExpansionPanel :disabled="!client.vat_exemption_certificate_id">
                    <VExpansionPanelTitle disable-icon-rotate>
                      {{ $t('Vat Exemption Certificate Period') }}
                    </VExpansionPanelTitle>
                    <VExpansionPanelText>
                      <VRow>
                        <VCol
                          cols="6"
                          md="6"
                        >
                          <AppDateTimePicker
                            v-model="client.vat_exemption_certificate_issue_date"
                            :label="$t('Issue Date')"
                            :config="{ inline: true }"
                          />
                        </VCol>
                        <VCol
                          cols="6"
                          md="6"
                        >
                          <AppDateTimePicker
                            v-model="client.vat_exemption_certificate_expiration_date"
                            :label="$t('Expiration Date')"
                            :config="{ inline: true }"
                          />
                        </VCol>
                      </VRow>
                    </VExpansionPanelText>
                  </VExpansionPanel>
                </VExpansionPanels>
              </div>
              <div v-else>
                <h5>{{ $t('Vat Exemption Certificate Period') }}</h5>
                <VRow>
                  <VCol
                    cols="6"
                    md="6"
                  >
                    <div>
                      <strong>{{ $t('Issue Date') }}:</strong>
                      {{ client.vat_exemption_certificate_issue_date }}
                    </div>
                  </VCol>
                  <VCol
                    cols="6"
                    md="6"
                  >
                    <div>
                      <strong>{{ $t('Expiration Date') }}:</strong>
                      {{ client.vat_exemption_certificate_expiration_date }}
                    </div>
                  </VCol>
                </VRow>
              </div>
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
