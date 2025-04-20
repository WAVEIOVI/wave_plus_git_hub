<script setup>
import { useEqBlueprints } from '@/composables/product-asset-suite/useEqBlueprints'
import { useSnackbarStore } from '@/stores/useSnackbarStore'
import EquipmentBlueprintBioPanel from '@/views/product-asset-suite/equipment-blueprints/edit/EqBlueprintBioPanel.vue'
import EqBlueprintTabConsumables from '@/views/product-asset-suite/equipment-blueprints/edit/EqBlueprintTabConsumables.vue'
import EqBlueprintTabEqServicings from '@/views/product-asset-suite/equipment-blueprints/edit/EqBlueprintTabEqServicings.vue'
import EqBlueprintTabExtinguishingAgent from '@/views/product-asset-suite/equipment-blueprints/edit/EqBlueprintTabExtinguishingAgent.vue'
import EqBlueprintTabOverview from '@/views/product-asset-suite/equipment-blueprints/edit/EqBlueprintTabOverview.vue'
import EqBlueprintTabPressureRefillServices from '@/views/product-asset-suite/equipment-blueprints/edit/EqBlueprintTabPressureRefillServices.vue'
import EqBlueprintTabSpecifications from '@/views/product-asset-suite/equipment-blueprints/edit/EqBlueprintTabSpecifications.vue'
import DeleteEqBlueprintDialog from '@/views/product-asset-suite/equipment-blueprints/list/dialogs/DeleteEqBlueprintDialog.vue'
import { useI18n } from 'vue-i18n'
import { onBeforeRouteLeave, useRoute, useRouter } from 'vue-router'

const snackbarStore = useSnackbarStore()
const route = useRoute()
const router = useRouter()
const { t } = useI18n()

const selectedEqBlueprintId = ref(route.params.id)
const eqBlueprintTab = ref(0)
const isDeleteEqBlueprintDialogVisible = ref(false)
const capacityData = ref([])
const pressureData = ref([])
const consumableData = ref([])
const eqServicingData = ref([])

const showSnackbar = (text, color) => {
  snackbarStore.show = true
  snackbarStore.text = text
  snackbarStore.color = color
}

const tabs = computed(() => [
  { title: t('Overview'), icon: 'tabler-layout-dashboard' },
  { title: t('Specifications'), icon: 'tabler-info-circle' },
  { title: t('Extinguishing Agent'), icon: 'tabler-info-circle' },
  { title: t('Pressure Refill Services'), icon: 'tabler-info-circle' },
  { title: t('Consumables'), icon: 'tabler-info-circle' },
  { title: t('Equipment Servicings'), icon: 'tabler-info-circle' },
])

const {
  fetchedEqBlueprint,
  fetchEqBlueprint,
  updateEqBlueprint,
  deleteEqBlueprint,
  fetchEqBlueprintCapacities,
  attachEqBlueprintCapacity,
  detachEqBlueprintCapacity,
  fetchEqBlueprintPressures,
  attachEqBlueprintPressure,
  detachEqBlueprintPressure,
  fetchEqBlueprintConsumables,
  attachEqBlueprintConsumable,
  detachEqBlueprintConsumable,
  fetchEqBlueprintEqServicings,
  attachEqBlueprintEqServicing,
  detachEqBlueprintEqServicing,
  loading,
  error,
} = useEqBlueprints(false)

const handleUpdate = async () => {
  try {
    await updateEqBlueprint(selectedEqBlueprintId.value, fetchedEqBlueprint.value)
    showSnackbar("Equipment Blueprint updated successfully", "success")
    originalEqBlueprint.value = JSON.parse(JSON.stringify(fetchedEqBlueprint.value))
  } catch (err) {
    console.error("Update error:", err)
    showSnackbar("Error updating equipment blueprint", "error")
  }
}

const handleDelete = async () => {
  try {
    await deleteEqBlueprint(selectedEqBlueprintId.value)
    showSnackbar("Equipment Blueprint deleted successfully", "success")
    router.push({ name: 'product-asset-suite-equipment-blueprint-catalog-list' })
  } catch (err) {
    console.error("Delete error:", err)
    showSnackbar("Error deleting equipment blueprint", "error")
  }
}

// Handler for subcategory edits
const handleCapacityEdit = async capacityId => {
  await refreshEqBlueprintCapacities()
  showSnackbar("Capacity saved successfully", "success")
}

const refreshEqBlueprintCapacities = async () => {
  try {
    capacityData.value = await fetchEqBlueprintCapacities({ id: selectedEqBlueprintId.value })
  } catch (err) {
    console.error("Error fetching capacities:", err)
  }
}

// Handler for subcategory edits
const handlePressureEdit = async pressureId => {
  await refreshEqBlueprintPressures()
  showSnackbar("Pressure saved successfully", "success")
}

const refreshEqBlueprintPressures = async () => {
  try {
    pressureData.value = await fetchEqBlueprintPressures({ id: selectedEqBlueprintId.value })
  } catch (err) {
    console.error("Error fetching pressures:", err)
  }
}

// Handler for subcategory edits
const handleConsumableEdit = async consumableId => {
  await refreshEqBlueprintConsumables()
  showSnackbar("Consumable saved successfully", "success")
}

const refreshEqBlueprintConsumables = async () => {
  try {
    consumableData.value = await fetchEqBlueprintConsumables({ id: selectedEqBlueprintId.value })
  } catch (err) {
    console.error("Error fetching consumables:", err)
  }
}

// Handler for subcategory edits
const handleEqServicingEdit = async eqServicingId => {
  await refreshEqBlueprintEqServicings()
  showSnackbar("Eq Servicing saved successfully", "success")
}

const refreshEqBlueprintEqServicings = async () => {
  try {
    eqServicingData.value = await fetchEqBlueprintEqServicings({ id: selectedEqBlueprintId.value })
  } catch (err) {
    console.error("Error fetching equipment servicings:", err)
  }
}

onMounted(async () => {
  await fetchEqBlueprint(selectedEqBlueprintId.value)
  await refreshEqBlueprintCapacities()
  await refreshEqBlueprintPressures()
  await fetchEqBlueprintConsumables()
  await fetchEqBlueprintEqServicings()
})

const originalEqBlueprint = ref(null)
const unsavedDialogVisible = ref(false)
const nextRoute = ref(null)

watch(fetchedEqBlueprint, newVal => {
  if (newVal && !originalEqBlueprint.value) {
    originalEqBlueprint.value = JSON.parse(JSON.stringify(newVal))
  }
})

const isDirty = computed(() => {
  if (!originalEqBlueprint.value) return false
  
  return JSON.stringify(fetchedEqBlueprint.value) !== JSON.stringify(originalEqBlueprint.value)
})

onBeforeRouteLeave((to, from, next) => {
  if (isDirty.value) {
    unsavedDialogVisible.value = true
    next(false)
    nextRoute.value = to
  } else {
    next()
  }
})

const confirmLeave = () => {
  originalEqBlueprint.value = JSON.parse(JSON.stringify(fetchedEqBlueprint.value))
  unsavedDialogVisible.value = false
  router.push(nextRoute.value.fullPath)
}

const cancelLeave = () => {
  unsavedDialogVisible.value = false
  nextRoute.value = null
}
</script>

<template>
  <div>
    <div class="d-flex justify-space-between align-center flex-wrap gap-y-4 mb-6">
      <div>
        <h4 class="text-h4 mb-1">
          {{ $t('Equipment Blueprint ID') }} #{{ fetchedEqBlueprint?.eq_blueprint_id || 'Loading...' }}
        </h4>
        <div class="text-body-1">
          {{ fetchedEqBlueprint?.created_at ? new Date(fetchedEqBlueprint.created_at).toLocaleDateString() : '' }}
        </div>
      </div>
      <div class="d-flex gap-4">
        <VBtn
          variant="tonal"
          color="primary"
          @click="handleUpdate"
        >
          {{ $t('Update Eq. Blueprint') }}
        </VBtn>
        <VBtn
          variant="tonal"
          color="error"
          @click="isDeleteEqBlueprintDialogVisible = true"
        >
          {{ $t('Delete Eq. Blueprint') }}
        </VBtn>
      </div>
    </div>

    <VRow v-if="fetchedEqBlueprint">
      <VCol
        cols="12"
        md="5"
        lg="4"
      >
        <EquipmentBlueprintBioPanel :fetched-eq-blueprint="fetchedEqBlueprint" />
      </VCol>
      <VCol
        cols="12"
        md="7"
        lg="8"
      >
        <VTabs
          v-model="eqBlueprintTab"
          class="v-tabs-pill mb-3 disable-tab-transition"
        >
          <VTab
            v-for="tab in tabs"
            :key="tab.title"
          >
            <VIcon
              size="20"
              start
              :icon="tab.icon"
            />
            {{ tab.title }}
          </VTab>
        </VTabs>
        <VWindow
          v-model="eqBlueprintTab"
          class="disable-tab-transition"
          :touch="false"
        >
          <VWindowItem>
            <EqBlueprintTabOverview v-model="fetchedEqBlueprint" />
          </VWindowItem>
          <VWindowItem>
            <EqBlueprintTabSpecifications 
              v-model="fetchedEqBlueprint"
              :capacity-data="capacityData"
              :pressure-data="pressureData"
              :attach-eq-blueprint-capacity="attachEqBlueprintCapacity"
              :detach-eq-blueprint-capacity="detachEqBlueprintCapacity"
              :attach-eq-blueprint-pressure="attachEqBlueprintPressure"
              :detach-eq-blueprint-pressure="detachEqBlueprintPressure"
              @edit-capacity="handleCapacityEdit"
              @edit-pressure="handlePressureEdit"
            />
          </VWindowItem>
          <VWindowItem>
            <EqBlueprintTabExtinguishingAgent v-model="fetchedEqBlueprint" />
          </VWindowItem>
          <VWindowItem>
            <EqBlueprintTabPressureRefillServices v-model="fetchedEqBlueprint" />
          </VWindowItem>
          <VWindowItem>
            <EqBlueprintTabConsumables 
              v-model="fetchedEqBlueprint"
              :consumable-data="consumableData"
              :attach-eq-blueprint-consumable="attachEqBlueprintConsumable"
              :detach-eq-blueprint-consumable="detachEqBlueprintConsumable"
              @edit-consumable="handleConsumableEdit"
            />
          </VWindowItem>
          <VWindowItem>
            <EqBlueprintTabEqServicings 
              v-model="fetchedEqBlueprint"
              :eq-servicing-data="eqServicingData"
              :attach-eq-blueprint-eq-servicing="attachEqBlueprintEqServicing"
              :detach-eq-blueprint-eq-servicing="detachEqBlueprintEqServicing"
              @edit-eq-servicing="handleEqServicingEdit"
            />
          </VWindowItem>
        </VWindow>
      </VCol>
    </VRow>
    <div v-else>
      <VAlert
        type="error"
        variant="tonal"
      >
        {{ error || $t('EqBlueprint not found!') }}
      </VAlert>
    </div>

    <DeleteEqBlueprintDialog
      v-model:isDialogVisible="isDeleteEqBlueprintDialogVisible"
      @delete-eq-blueprint="handleDelete"
    />
    <UnsavedChangesDialog
      v-model="unsavedDialogVisible"
      :title="$t('Unsaved Changes')"
      :message="$t('You have unsaved changes. Do you really want to leave without saving?')"
      :confirm-text="$t('Leave')"
      :cancel-text="$t('Cancel')"
      @confirm="confirmLeave"
      @cancel="cancelLeave"
    />
    <LoadingDialog
      v-model="loading"
      :auto-close="false"
    />
    <VSnackbar
      v-model="snackbarStore.show"
      :color="snackbarStore.color"
      :timeout="snackbarStore.timeout"
      transition="scale-transition"
      variant="tonal"
    >
      <div style="text-align: center;">
        {{ snackbarStore.text }}
      </div>
    </VSnackbar>
  </div>
</template>
