<script setup>
import { useTranslation } from '@/composables/useTranslation'
import { getBpStatus, getCountries } from '@/constants/filterOptions'
import { avatarText } from '@core/utils/formatters'
import safeBoxImg from '@images/misc/3d-safe-box-with-golden-dollar-coins.png'

const props = defineProps({
  fetchedClient: {
    type: null,
    required: true,
  },
})

//👉 - Translations and computed properties

// Factory function for localized computed lists
const createLocalizedComputed = getter => computed(() => getter())

const clientStatusList = createLocalizedComputed(getBpStatus)
const countriesList = createLocalizedComputed(getCountries)

// Use translation composable
const { translateValue } = useTranslation()

// Translated values
const translatedClientStatus = computed(() => translateValue(clientStatusList, props.fetchedClient.client_status))
const translatedCountry = computed(() => translateValue(countriesList, props.fetchedClient.hq_country))

// Status variant resolver
const resolveClientStatusVariant = stat => ({
  pending: 'warning',
  active: 'success',
  inactive: 'error',
  blocked: 'error',
}[stat?.toLowerCase()] || 'secondary')

// VAT certificate computations
const currentDate = computed(() => new Date())

const vatDates = computed(() => ({
  issue: new Date(props.fetchedClient.vat_exemption_certificate_issue_date),
  expiration: new Date(props.fetchedClient.vat_exemption_certificate_expiration_date),
}))

const hasValidVatCertificate = computed(() =>
  props.fetchedClient.vat_exemption_certificate_id &&
  currentDate.value >= vatDates.value.issue &&
  currentDate.value <= vatDates.value.expiration,
)

const hasExpiredVatCertificate = computed(() =>
  props.fetchedClient.vat_exemption_certificate_id &&
  currentDate.value > vatDates.value.expiration,
)

// Template data structures
const clientDetailFields = [
  { label: 'Commercial Name', key: 'commercial_name' },
  { label: 'Email', key: 'primary_email' },
  { label: 'Phone', key: 'primary_phone' },
]
</script>

<template>
  <VRow>
    <!-- SECTION Client Details -->
    <VCol cols="12">
      <VCard v-if="fetchedClient">
        <VCardText class="text-center pt-12">
          <VAvatar
            rounded
            :size="120"
            :color="!fetchedClient.avatar ? 'primary' : undefined"
            :variant="!fetchedClient.avatar ? 'tonal' : undefined"
          >
            <VImg
              v-if="fetchedClient.avatar"
              :src="fetchedClient.avatar"
            />
            <span
              v-else
              class="text-5xl font-weight-medium"
            >
              {{ avatarText(fetchedClient.legal_name) }}
            </span>
          </VAvatar>

          <h5 class="text-h5 mt-4">
            {{ fetchedClient.legal_name }}
          </h5>
          <div class="text-body-1">
            {{ $t('Client ID') }} #{{ fetchedClient.client_id }}
          </div>

          <div class="d-flex justify-space-evenly gap-x-5 mt-6">
            <div
              v-for="(metric, index) in [
                { icon: 'tabler-tools', label: 'Equipment', value: 500 },
                { icon: 'tabler-briefcase', label: 'Missions', value: 30 }
              ]"
              :key="index"
              class="d-flex align-center"
            >
              <VAvatar
                variant="tonal"
                color="primary"
                rounded
                class="me-3"
              >
                <VIcon :icon="metric.icon" />
              </VAvatar>
              <div class="d-flex flex-column align-start">
                <h5 class="text-h5">
                  {{ metric.value }}
                </h5>
                <div class="text-body-1">
                  {{ $t(metric.label) }}
                </div>
              </div>
            </div>
          </div>
        </VCardText>

        <VCardText>
          <h5 class="text-h5">
            {{ $t('Details') }}
          </h5>
          <VDivider class="my-4" />

          <VList class="card-list mt-2">
            <VListItem
              v-for="field in clientDetailFields"
              :key="field.key"
            >
              <h6 class="text-h6">
                {{ $t(field.label) }}:
                <span class="text-body-1 d-inline-block">{{ fetchedClient[field.key] }}</span>
              </h6>
            </VListItem>

            <VListItem>
              <div class="d-flex gap-x-2 align-center">
                <h6 class="text-h6">
                  {{ $t('Status') }}:
                </h6>
                <VChip
                  :color="resolveClientStatusVariant(fetchedClient.client_status)"
                  size="small"
                  label
                  class="text-capitalize"
                >
                  {{ translatedClientStatus }}
                </VChip>
              </div>
            </VListItem>

            <VListItem>
              <h6 class="text-h6">
                {{ $t('Country') }}:
                <span class="text-body-1 d-inline-block">{{ translatedCountry }}</span>
              </h6>
            </VListItem>
          </VList>
        </VCardText>
      </VCard>
    </VCol>

    <!-- SECTION VAT Exemption Certificate -->
    <VCol
      v-if="hasValidVatCertificate || hasExpiredVatCertificate"
      cols="12"
    >
      <VCard
        flat
        class="current-plan"
        :class="{ 'expired-certificate': hasExpiredVatCertificate }"
      >
        <VCardText class="d-flex align-center">
          <div class="flex-grow-1">
            <h5 class="text-h5 text-white mb-4">
              {{ $t('VAT Exemption Certificate') }}
            </h5>
            <p class="mb-6 text-wrap">
              {{ hasExpiredVatCertificate
                ? $t('This certificate has expired and can no longer be used to claim VAT exemption on your purchases.')
                : $t('This certificate can be used to claim VAT exemption on your purchases.')
              }}
            </p>
          </div>
          <VImg
            :src="safeBoxImg"
            height="108"
            width="108"
          />
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
