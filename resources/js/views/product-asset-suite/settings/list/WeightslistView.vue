<script setup>
import { usePagination } from '@/composables/usePagination'
import AddEditWeightDialog from './dialogs/AddEditWeightDialog.vue'
import DeleteWeightDialog from './dialogs/DeleteWeightDialog.vue'

const props = defineProps({
  weights: { type: Array, default: () => [] },
  loading: { type: Boolean, default: false },
  error: { type: String, default: null },
  addWeight: { type: Function, required: true },
  updateWeight: { type: Function, required: true },
  fetchWeight: { type: Function, required: true },
  deleteWeight: { type: Function, required: true },
  fetchedWeight: { type: Object, default: () => null },
})

const emits = defineEmits(['editWeight'])

// Pagination setup
const itemsPerPage = ref(5)
const weightsRef = computed(() => props.weights)
const { page: weightPage, totalPages: totalWeightPages, currentItems: currentWeights } = usePagination(weightsRef, itemsPerPage)

watch(() => props.weights.length, newLength => {
  if (weightPage.value > Math.ceil(newLength / itemsPerPage.value)) {
    weightPage.value = 1
  }
})

// Dialog state
const dialogState = reactive({
  weight: {
    isVisible: false,
    isDeleteVisible: false,
    current: null,
    toDelete: null,
  },
  isEditMode: false,
})

const EMPTY_WEIGHT = {
  weight_id: '',
  unit: '',
  abbreviation: '',
  value: '',
}

// Computed properties for dialog visibility
const isWeightDialogVisible = computed({
  get: () => dialogState.weight.isVisible,
  set: value => dialogState.weight.isVisible = value,
})

const isDeleteWeightDialogVisible = computed({
  get: () => dialogState.weight.isDeleteVisible,
  set: value => dialogState.weight.isDeleteVisible = value,
})

// Weight methods
const handleAddWeight = () => {
  dialogState.weight.current = { ...EMPTY_WEIGHT }
  dialogState.isEditMode = false
  dialogState.weight.isVisible = true
}

const handleEditWeight = weight => {
  dialogState.weight.current = { ...weight }
  dialogState.isEditMode = true
  dialogState.weight.isVisible = true
}

const handleSubmitWeight = async weightDataInput => {
  try {
    if (dialogState.isEditMode && weightDataInput.id) {
      await props.updateWeight(weightDataInput.id, weightDataInput)
    } else {
      await props.addWeight(weightDataInput)
    }
    emits('editWeight', weightDataInput.id)
  } catch (error) {
    console.error('Error saving weight:', error)
  }
}

const handleDeleteWeight = async weightId => {
  try {
    await props.deleteWeight(weightId)
    emits('editWeight', weightId)
    dialogState.weight.isDeleteVisible = false
  } catch (error) {
    console.error('Error deleting weight:', error)
  }
}

const openWeightDeleteDialog = weight => {
  dialogState.weight.toDelete = weight.id
  dialogState.weight.isDeleteVisible = true
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
          {{ $t('Weight List') }}
        </h5>
        <VSpacer />
        <VBtn
          variant="tonal"
          size="small"
          prepend-icon="tabler-plus"
          @click="handleAddWeight"
        >
          {{ $t('Add New Weight') }}
        </VBtn>
      </VCardTitle>
      <VDivider />
      <VCardText>
        <!-- Weight List -->
        <VList class="card-list">
          <template v-if="currentWeights.length === 0">
            <VListItem>
              <VListItemTitle class="text-center text-disabled py-6">
                {{ $t('No weights found') }}
              </VListItemTitle>
            </VListItem>
          </template>

          <template v-else>
            <VListItem
              v-for="(weight, index) in currentWeights"
              :key="weight.id || index"
              class="list-item-hover "
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
                  {{ weight.unit[$i18n.locale] }} | {{ weight.abbreviation[$i18n.locale] }} |  {{ weight.value }} 
                </VListItemTitle>
              </div>

              <template #append>
                <div class="d-flex align-center gap-2">
                  <VTooltip location="top">
                    <template #activator="{ props: tooltipProps }">
                      <IconBtn
                        v-bind="tooltipProps"
                        color="primary"
                        size="small"
                        @click.stop="handleEditWeight(weight)"
                      >
                        <VIcon icon="tabler-edit" />
                      </IconBtn>
                    </template>
                    <span>{{ $t('Edit weight') }}</span>
                  </VTooltip>

                  <VTooltip location="top">
                    <template #activator="{ props: tooltipProps }">
                      <IconBtn
                        v-bind="tooltipProps"
                        color="error"
                        size="small"
                        @click.stop="openWeightDeleteDialog(weight)"
                      >
                        <VIcon icon="tabler-trash" />
                      </IconBtn>
                    </template>
                    <span>{{ $t('Delete weight') }}</span>
                  </VTooltip>
                </div>
              </template>

              <VDivider v-if="index < currentWeights.length - 1" />
            </VListItem>
          </template>
        </VList>
      </VCardText>


      <!-- Pagination -->
      <VCardActions v-if="totalWeightPages > 1">
        <VPagination
          v-model="weightPage"
          size="small"
          :length="totalWeightPages"
          :total-visible="7"
          color="primary"
          density="comfortable"
          class="mt-3"
        />
      </VCardActions>
    </VCard>

    <!-- Dialog Components -->
    <AddEditWeightDialog
      v-model:is-dialog-visible="isWeightDialogVisible"
      :weight-data="dialogState.weight.current"
      :is-edit-mode="dialogState.isEditMode"
      @submit="handleSubmitWeight"
    />
    <DeleteWeightDialog
      v-model:is-dialog-visible="isDeleteWeightDialogVisible"
      :weight-id="dialogState.weight.toDelete"
      @delete-weight="handleDeleteWeight"
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
