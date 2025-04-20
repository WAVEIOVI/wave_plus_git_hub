<script setup>
import { usePagination } from '@/composables/usePagination'
import AddEditPressureDialog from './dialogs/AddEditPressureDialog.vue'
import DeletePressureDialog from './dialogs/DeletePressureDialog.vue'

const props = defineProps({
  pressures: { type: Array, default: () => [] },
  loading: { type: Boolean, default: false },
  error: { type: String, default: null },
  addPressure: { type: Function, required: true },
  updatePressure: { type: Function, required: true },
  fetchPressure: { type: Function, required: true },
  deletePressure: { type: Function, required: true },
  fetchedPressure: { type: Object, default: () => null },
})

const emits = defineEmits(['editPressure'])

// Pagination setup
const itemsPerPage = ref(5)
const pressuresRef = computed(() => props.pressures)
const { page: pressurePage, totalPages: totalPressurePages, currentItems: currentPressures } = usePagination(pressuresRef, itemsPerPage)

watch(() => props.pressures.length, newLength => {
  if (pressurePage.value > Math.ceil(newLength / itemsPerPage.value)) {
    pressurePage.value = 1
  }
})

// Dialog state
const dialogState = reactive({
  pressure: {
    isVisible: false,
    isDeleteVisible: false,
    current: null,
    toDelete: null,
  },
  isEditMode: false,
})

const EMPTY_PRESSION = {
  pressure_id: '',
  name: '',
  description: '',
}

// Computed properties for dialog visibility
const isPressureDialogVisible = computed({
  get: () => dialogState.pressure.isVisible,
  set: value => dialogState.pressure.isVisible = value,
})

const isDeletePressureDialogVisible = computed({
  get: () => dialogState.pressure.isDeleteVisible,
  set: value => dialogState.pressure.isDeleteVisible = value,
})

// Pressure methods
const handleAddPressure = () => {
  dialogState.pressure.current = { ...EMPTY_PRESSION }
  dialogState.isEditMode = false
  dialogState.pressure.isVisible = true
}

const handleEditPressure = pressure => {
  dialogState.pressure.current = { ...pressure }
  dialogState.isEditMode = true
  dialogState.pressure.isVisible = true
}

const handleSubmitPressure = async pressureDataInput => {
  try {
    if (dialogState.isEditMode && pressureDataInput.id) {
      await props.updatePressure(pressureDataInput.id, pressureDataInput)
    } else {
      await props.addPressure(pressureDataInput)
    }
    emits('editPressure', pressureDataInput.id)
  } catch (error) {
    console.error('Error saving pressure:', error)
  }
}

const handleDeletePressure = async pressureId => {
  try {
    await props.deletePressure(pressureId)
    emits('editPressure', pressureId)
    dialogState.pressure.isDeleteVisible = false
  } catch (error) {
    console.error('Error deleting pressure:', error)
  }
}

const openPressureDeleteDialog = pressure => {
  dialogState.pressure.toDelete = pressure.id
  dialogState.pressure.isDeleteVisible = true
}
</script>

<template>
  <div>
    <VCard
      class="optimized-vcard"
      :loading="loading"
      border
      elevation="2"
      rounded="lg"
    >
      <!-- Optimized Card Header -->
      <VCardTitle class="d-flex align-center optimized-header">
        <h5 class="text-h6">
          {{ $t('Pressure List') }}
        </h5>
        <VSpacer />
        <VBtn
          variant="tonal"
          size="small"
          prepend-icon="tabler-plus"
          @click="handleAddPressure"
        >
          {{ $t('Add New Pressure') }}
        </VBtn>
      </VCardTitle>
      <VDivider />
      <VCardText>
        <!-- Pressure List -->
        <VList class="card-list">
          <template v-if="currentPressures.length === 0">
            <VListItem>
              <VListItemTitle class="text-center text-disabled py-6">
                {{ $t('No pressures found') }}
              </VListItemTitle>
            </VListItem>
          </template>

          <template v-else>
            <VListItem
              v-for="(pressure, index) in currentPressures"
              :key="pressure.id || index"
              class="list-item-hover"
              :class="{'bg-grey-lighten-4': index % 2 === 0}"
            >
              <template #prepend>
                <VIcon
                  icon="tabler-droplet"
                  color="primary"
                  size="24"
                  class="me-3"
                />
              </template>

              <div class="flex-grow-1">
                <VListItemTitle class="font-weight-medium text-primary">
                  {{ pressure.name[$i18n.locale] }}
                </VListItemTitle>
                <!-- Tooltip wrapping description with reduced width -->
                <VTooltip location="top">
                  <template #activator="{ props: tooltipProps }">
                    <VListItemSubtitle
                      class="description text-medium-emphasis"
                      v-bind="tooltipProps"
                    >
                      {{ pressure.description[$i18n.locale] }}
                    </VListItemSubtitle>
                  </template>
                  <span class="tooltip-content">{{ pressure.description[$i18n.locale] }}</span>
                </VTooltip>
              </div>

              <template #append>
                <div class="d-flex align-center gap-2">
                  <VTooltip location="top">
                    <template #activator="{ props: tooltipProps }">
                      <IconBtn
                        v-bind="tooltipProps"
                        color="primary"
                        size="small"
                        @click.stop="handleEditPressure(pressure)"
                      >
                        <VIcon icon="tabler-edit" />
                      </IconBtn>
                    </template>
                    <span>{{ $t('Edit pressure') }}</span>
                  </VTooltip>

                  <VTooltip location="top">
                    <template #activator="{ props: tooltipProps }">
                      <IconBtn
                        v-bind="tooltipProps"
                        color="error"
                        size="small"
                        @click.stop="openPressureDeleteDialog(pressure)"
                      >
                        <VIcon icon="tabler-trash" />
                      </IconBtn>
                    </template>
                    <span>{{ $t('Delete pressure') }}</span>
                  </VTooltip>
                </div>
              </template>

              <VDivider v-if="index < currentPressures.length - 1" />
            </VListItem>
          </template>
        </VList>
      </VCardText>


      <!-- Pagination -->
      <VCardActions v-if="totalPressurePages > 1">
        <VPagination
          v-model="pressurePage"
          size="small"
          :length="totalPressurePages"
          :total-visible="7"
          color="primary"
          density="comfortable"
          class="mt-3"
        />
      </VCardActions>
    </VCard>

    <!-- Dialog Components -->
    <AddEditPressureDialog
      v-model:is-dialog-visible="isPressureDialogVisible"
      :pressure-data="dialogState.pressure.current"
      :is-edit-mode="dialogState.isEditMode"
      @submit="handleSubmitPressure"
    />
    <DeletePressureDialog
      v-model:is-dialog-visible="isDeletePressureDialogVisible"
      :pressure-id="dialogState.pressure.toDelete"
      @delete-pressure="handleDeletePressure"
    />
  </div>
</template>

<style lang="scss" scoped>
.optimized-vcard {
  padding: 8px;
  margin-block: 16px;
  margin-inline: auto;
}

.optimized-header {
  padding-block: 8px;
  padding-inline: 12px;
}

.card-list {
  .list-item-hover {
    display: flex;
    align-items: center;
    transition: all 0.2s ease-in-out;

    &:hover {
      background-color: rgba(var(--v-theme-primary), 0.05);
      transform: translateX(4px);
    }
  }
}

/* Multi-line ellipsis for description */
.description {
  display: -webkit-box;
  overflow: hidden;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  text-overflow: ellipsis;
  white-space: normal;
}

/* Tooltip content with reduced width */
.tooltip-content {
  display: block;
  max-inline-size: 500px; /* adjust as needed */
  word-wrap: break-word;
}

.v-card-title {
  border-radius: 8px 8px 0 0;
}
</style>

<route lang="yaml">
meta:
  layout: default
  requiresAuth: true
  action: manage
  subject: ProductAssetSuite
</route>
