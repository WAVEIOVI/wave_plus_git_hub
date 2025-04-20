<script setup>
import { useClients } from '@/composables/business-partner/useClients'
import { useSuppliers } from '@/composables/business-partner/useSuppliers'
import router from '@/router'

const { statisticsData: clientStats } = useClients()
const { statisticsData: supplierStats } = useSuppliers()

// Computed property for statistics
const statisticsData = computed(() => [
  {
    title: 'Total Clients',
    value: clientStats.value.total_clients || 0,
    icon: 'tabler-users',
    color: 'primary',
  },
  {
    title: 'Active Clients',
    value: clientStats.value.total_active_clients || 0,
    icon: 'tabler-user-check',
    color: 'success',
  },
  {
    title: 'Total Suppliers',
    value: supplierStats.value.total_suppliers || 0,
    icon: 'tabler-users',
    color: 'primary',
  },
  {
    title: 'Active Suppliers',
    value: supplierStats.value.total_active_suppliers || 0,
    icon: 'tabler-user-check',
    color: 'success',
  },
])

// 👉 Card's Content

const clientsCard = [
  {
    icon: "tabler-user-search",
    desc: 'Browse all client profiles quickly.',
    titre: 'View Client List',
  },
  {
    icon: "tabler-users-plus",
    desc: 'Streamline the process of adding new clients.',
    titre: 'Add New Client',
  },
  {
    icon: "tabler-user-square",
    desc: 'Analyze client activity and performance.',
    titre: 'Client Insights',
  },
]

const suppliersCard = [
  {
    icon: "tabler-user-search",
    desc: 'Access detailed supplier information.',
    titre: 'View Supplier List',
  },
  {
    icon: "tabler-users-plus",
    desc: 'Seamlessly integrate new suppliers.',
    titre: 'Add New Supplier',
  },
  {
    icon: "tabler-user-square",
    desc: 'Monitor and evaluate supplier metrics.',
    titre: 'Supplier Performance',
  },
]
</script>

<template>
  <div>
    <!-- 👉 Header -->
    <VRow class="match-height">
      <!-- 👉 Statistics -->
      <VCol
        v-for="(data, index) in statisticsData"
        :key="index"
        cols="12"
        md="3"
        sm="6"
      >
        <VCard>
          <VCardText>
            <div class="d-flex justify-space-between align-center">
              <div class="d-flex flex-column">
                <h5 class="text-h5 mb-1">
                  {{ data.value }}
                </h5>
                <div class="text-body-2">
                  {{ $t(data.title) }}
                </div>
              </div>
              <VAvatar
                size="40"
                variant="tonal"
                :color="data.color"
              >
                <VIcon :icon="data.icon" />
              </VAvatar>
            </div>
          </VCardText>
        </VCard>
      </VCol>

      <!-- 👉 Client Card -->
      <VCol
        cols="12"
        md="6"
      >
        <VCard @click="router.push({ name: 'business-partner-clients-list' })">
          <VCardItem>
            <VCardTitle class="mb-1">
              {{ $t('Clients Management') }}
            </VCardTitle>
            <VCardSubtitle> 
              {{ $t('Easily Access And Manage Your Client Database.') }}
            </VCardSubtitle>
          </VCardItem>
          <VCardText>
            <div class="d-flex flex-column flex-sm-row gap-6 justify-sm-space-between align-center">
              <div
                v-for="(step, index) in clientsCard"
                :key="index"
                class="d-flex flex-column align-center gap-y-2"
                style="max-inline-size: 185px;"
              >
                <div class="icon-container">
                  <VIcon
                    :icon="step.icon"
                    color="primary"
                    size="36"
                  />
                </div>
                <h6 class="text-primary text-h6">
                  {{ $t(step.titre) }}
                </h6>
                <div class="text-body-1 text-wrap text-center">
                  {{ $t(step.desc) }}
                </div>
              </div>
            </div>
          </VCardText>
        </VCard>
      </VCol>
      <!-- 👉 Suppliers Card -->
      <VCol
        cols="12"
        md="6"
      >
        <VCard @click="router.push({ name: 'business-partner-suppliers-list' })">
          <VCardItem>
            <VCardTitle class="mb-1">
              {{ $t('Suppliers Management') }}
            </VCardTitle>
            <VCardSubtitle>
              {{ $t('Efficiently Handle Your Supplier Interactions.') }}
            </VCardSubtitle>
          </VCardItem>
          <VCardText>
            <div class="d-flex flex-column flex-sm-row gap-6 justify-sm-space-between align-center">
              <div
                v-for="(step, index) in suppliersCard"
                :key="index"
                class="d-flex flex-column align-center gap-y-2"
                style="max-inline-size: 185px;"
              >
                <div class="icon-container">
                  <VIcon
                    :icon="step.icon"
                    color="primary"
                    size="36"
                  />
                </div>
                <h6 class="text-primary text-h6">
                  {{ $t(step.titre) }}
                </h6>
                <div class="text-body-1 text-wrap text-center">
                  {{ $t(step.desc) }}
                </div>
              </div>
            </div>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>

<style lang="scss" scoped>
.icon-container {
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px dashed rgb(var(--v-theme-primary));
  border-radius: 50%;
  block-size: 70px;
  inline-size: 70px;
}

.icon {
  color: #000;
  font-size: 42px;
}
</style>

<route lang="yaml">
meta:
  layout: default
  requiresAuth: true
  action: manage
  subject: BusinessPartner
</route>
