<script setup>
import { useTranslation } from '@/composables/useTranslation'
import { getBpStatus } from '@/constants/filterOptions'
import { avatarText } from '@core/utils/formatters'

const props = defineProps({
  fetchedEqBlueprint: {
    type: null,
    required: true,
  },
})

//👉 - Translations and computed properties

// Factory function for localized computed lists
const createLocalizedComputed = getter => computed(() => getter())

const clientStatusList = createLocalizedComputed(getBpStatus)

// Use translation composable
const { translateValue } = useTranslation()

// Translated values
const translatedEqBlueprintStatus = computed(() => translateValue(clientStatusList, props.fetchedEqBlueprint.eq_blueprint_status))

// Status variant resolver
const resolveEqBlueprintStatusVariant = stat => ({
  pending: 'warning',
  active: 'success',
  inactive: 'error',
  blocked: 'error',
}[stat?.toLowerCase()] || 'secondary')


// Template data structures
const eqBlueprintDetailFields = [
  { label: 'Name', key: 'name' },
  { label: 'Category', key: 'category_name' },
  { label: 'Subcategory', key: 'subcategory_name' },
]
</script>

<template>
  <VRow>
    <!-- SECTION EqBlueprint Details -->
    <VCol cols="12">
      <VCard v-if="fetchedEqBlueprint">
        <VCardText class="text-center pt-12">
          <VAvatar
            rounded
            :size="120"
            :color="!fetchedEqBlueprint.avatar ? 'primary' : undefined"
            :variant="!fetchedEqBlueprint.avatar ? 'tonal' : undefined"
          >
            <VImg
              v-if="fetchedEqBlueprint.avatar"
              :src="fetchedEqBlueprint.avatar"
            />
            <span
              v-else
              class="text-5xl font-weight-medium"
            >
              {{ avatarText(fetchedEqBlueprint.name[$i18n.locale]) }}
            </span>
          </VAvatar>

          <h5 class="text-h5 mt-4">
            {{ fetchedEqBlueprint.name[$i18n.locale] }}
          </h5>
          <div class="text-body-1">
            {{ $t('Equipment Blueprint ID') }} #{{ fetchedEqBlueprint.eq_blueprint_id }}
          </div>
        </VCardText>

        <VCardText>
          <h5 class="text-h5">
            {{ $t('Details') }}
          </h5>
          <VDivider class="my-4" />

          <VList class="card-list mt-2">
            <VListItem
              v-for="field in eqBlueprintDetailFields"
              :key="field.key"
            >
              <h6 class="text-h6">
                {{ $t(field.label) }}:
                <span class="text-body-1 d-inline-block">{{ fetchedEqBlueprint[field.key][$i18n.locale] }}</span>
              </h6>
            </VListItem>

            <VListItem>
              <div class="d-flex gap-x-2 align-center">
                <h6 class="text-h6">
                  {{ $t('Status') }}:
                </h6>
                <VChip
                  :color="resolveEqBlueprintStatusVariant(fetchedEqBlueprint.eq_blueprint_status)"
                  size="small"
                  label
                  class="text-capitalize"
                >
                  {{ translatedEqBlueprintStatus }}
                </VChip>
              </div>
            </VListItem>
          </VList>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 0.5rem;
}
</style>
