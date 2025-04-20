<script setup>
import { getBpTiers, getTeam } from '@/constants/filterOptions'

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
const supplier = computed({
  get: () => props.modelValue,
  set: value => emits('update:modelValue', value),
})

const supplierTiers = computed(() => getBpTiers())
const team = computed(() => getTeam())
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardText>
          <h5 class="text-h5">
            {{ $t('Supplier Status & Relationship') }}
          </h5>
          <VDivider class="my-4" />
          <VRow>
            <!-- 👉 Account Manager -->
            <VCol cols="12">
              <VAutocomplete
                v-model="supplier.account_manager"
                :label="$t('Account Manager')"
                prepend-icon="tabler-user"
                :items="team"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
                :clearable="!readOnly"
                :clear-icon="!readOnly ? 'tabler-x' : ''"
                :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
              />
            </VCol>

            <!-- 👉 Supplier Tier -->
            <VCol
              md="4"
              cols="12"
            >
              <VAutocomplete
                v-model="supplier.supplier_tier"
                :label="$t('Supplier Tier')"
                prepend-icon="tabler-star"
                :items="supplierTiers"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
                :clearable="!readOnly"
                :clear-icon="!readOnly ? 'tabler-x' : ''"
                :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
              />
            </VCol>

            <!-- 👉 Satisfaction Score -->
            <VCol
              md="4"
              cols="12"
            >
              <VTextField
                v-model="supplier.satisfaction_score"
                :label="$t('Satisfaction Score')"
                prepend-icon="tabler-mood-happy"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
              />
            </VCol>

            <!-- 👉 Churn Risk -->
            <VCol
              md="4"
              cols="12"
            >
              <VTextField
                v-model="supplier.churn_risk"
                :label="$t('Churn Risk')"
                prepend-icon="tabler-alert-triangle"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
              />
            </VCol>
            <!-- 👉 Supplier Since -->
            <VCol
              md="4"
              cols="12"
            >
              <VTextField
                v-model="supplier.supplier_since"
                :label="$t('Supplier Since')"
                prepend-icon="tabler-calendar"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
              />
            </VCol>

            <!-- 👉 Last Mission Date -->
            <VCol
              md="4"
              cols="12"
            >
              <VTextField
                v-model="supplier.last_mission_date"
                :label="$t('Last Mission Date')"
                prepend-icon="tabler-clock"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
              />
            </VCol>

            <!-- 👉 Notes -->
            <VCol cols="12">
              <VTextarea
                v-model="supplier.notes"
                :label="$t('Notes')"
                rows="3"
                prepend-icon="tabler-note"
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
