<script setup>
import { useTranslation } from '@/composables/useTranslation'
import { getCategoryStatus } from '@/constants/filterOptions'
import { avatarText } from '@core/utils/formatters'

const props = defineProps({
  fetchedCategory: {
    type: null,
    required: true,
  },
})

//👉 - Translations and computed properties

// Factory function for localized computed lists
const createLocalizedComputed = getter => computed(() => getter())

const categoryStatusList = createLocalizedComputed(getCategoryStatus)

// Use translation composable
const { translateValue } = useTranslation()

// Translated values
const translatedCategoryStatus = computed(() => translateValue(categoryStatusList, props.fetchedCategory.category_status))

// Status variant resolver
const resolveCategoryStatusVariant = stat => ({
  pending: 'warning',
  active: 'success',
  inactive: 'error',
  blocked: 'error',
}[stat?.toLowerCase()] || 'secondary')
</script>

<template>
  <VRow>
    <!-- SECTION Category Details -->
    <VCol cols="12">
      <VCard v-if="fetchedCategory">
        <VCardText class="text-center pt-12">
          <VAvatar
            rounded
            :size="120"
            :color="!fetchedCategory.avatar ? 'primary' : undefined"
            :variant="!fetchedCategory.avatar ? 'tonal' : undefined"
          >
            <VImg
              v-if="fetchedCategory.avatar"
              :src="fetchedCategory.avatar"
            />
            <span
              v-else
              class="text-5xl font-weight-medium"
            >
              {{ avatarText(fetchedCategory.category_name[$i18n.locale]) }}
            </span>
          </VAvatar>

          <h5 class="text-h5 mt-4">
            {{ fetchedCategory.category_name[$i18n.locale] }}
          </h5>
          <div class="text-body-1">
            {{ $t('Category ID') }} #{{ fetchedCategory.category_id }}
          </div>
        </VCardText>

        <VCardText>
          <h5 class="text-h5">
            {{ $t('Details') }}
          </h5>
          <VDivider class="my-4" />

          <VList class="card-list mt-2">
            <VListItem>
              <h6 class="text-h6">
                {{ $t('Category ID') }}:
                <span class="text-body-1 d-inline-block">{{ fetchedCategory.category_id }}</span>
              </h6>
            </VListItem>

            <VListItem>
              <h6 class="text-h6">
                {{ $t('Category Name') }}:
                <span class="text-body-1 d-inline-block">{{ fetchedCategory.category_name[$i18n.locale] }}</span>
              </h6>
            </VListItem>
            <VListItem>
              <h6 class="text-h6">
                {{ $t('Description') }}:
                <span class="text-body-1 d-inline-block">{{ fetchedCategory.category_description[$i18n.locale] }}</span>
              </h6>
            </VListItem>

            <VListItem>
              <div class="d-flex gap-x-2 align-center">
                <h6 class="text-h6">
                  {{ $t('Status') }}:
                </h6>
                <VChip
                  :color="resolveCategoryStatusVariant(fetchedCategory.category_status)"
                  size="small"
                  label
                  class="text-capitalize"
                >
                  {{ translatedCategoryStatus }}
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

.current-plan {
  background: linear-gradient(45deg, rgb(var(--v-theme-primary)) 0%, #9e95f5 100%);
  color: #fff;

  &.expired-certificate {
    background: linear-gradient(45deg, #f00 0%, #ff7f7f 100%);
  }
}
</style>
