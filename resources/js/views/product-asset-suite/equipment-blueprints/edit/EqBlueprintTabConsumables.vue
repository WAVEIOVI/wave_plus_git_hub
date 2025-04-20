<script setup>
import { usePagination } from '@/composables/usePagination'
import { useTranslation } from '@/composables/useTranslation'
import { getEquipmentFireClasses, getEquipmentFrequencies } from '@/constants/filterOptions'
import AddConsumableDialog from './dialogs/AddConsumableDialog.vue'
import DeleteConsumableDialog from './dialogs/DeleteConsumableDialog.vue'

const props = defineProps({
  modelValue: { type: Object, required: true },
  consumableData: { type: Array, required: true },
  attachEqBlueprintConsumable: { type: Function, default: () => {} },
  detachEqBlueprintConsumable: { type: Function, default: () => {} },
  fetchEqBlueprintConsumable: { type: Function, default: () => {} },
  readOnly: { type: Boolean, default: false },
})

const emits = defineEmits([
  'update:modelValue',
  'editConsumable',
])

const eqBlueprint = computed({
  get: () => props.modelValue,
  set: value => emits('update:modelValue', value),
})

const itemsPerPage = ref(5)

const consumablesRef = computed(() => props.consumableData)
const { page: consumablePage, totalPages: totalConsumablePages, currentItems: currentConsumables } = usePagination(consumablesRef, itemsPerPage)

const dialogState = reactive({
  consumable: {
    isVisible: false,
    isDeleteVisible: false,
    current: null,
    toDelete: null,
  },
})

const { translateValue } = useTranslation()

const EMPTY_CONSUMABLE = {
  consumable_id: '',
  name: '',
}

const isConsumableDialogVisible = computed({
  get: () => dialogState.consumable.isVisible,
  set: value => dialogState.consumable.isVisible = value,
})

const isDeleteConsumableDialogVisible = computed({
  get: () => dialogState.consumable.isDeleteVisible,
  set: value => dialogState.consumable.isDeleteVisible = value,
})


const openConsumableDeleteDialog = consumable => {
  dialogState.consumable.toDelete = consumable.id
  dialogState.consumable.isDeleteVisible = true
}

const handleAddConsumable = () => {
  if (props.readOnly) return
  dialogState.consumable.current = { ...EMPTY_CONSUMABLE }
  dialogState.consumable.isVisible = true
}

const handleSubmitConsumable = async consumableDataInput => {
  try {
    await props.attachEqBlueprintConsumable(eqBlueprint.value.id, consumableDataInput)
    emits('editConsumable', consumableDataInput.id)
  } catch (error) {
    console.error('Error saving consumable:', error)
  }
}

const handleDeleteConsumable = async consumableId => {
  try {
    await props.detachEqBlueprintConsumable(eqBlueprint.value.id, consumableId)
    emits('editConsumable', consumableId)
    dialogState.consumable.isDeleteVisible = false
  } catch (error) {
    console.error('Error deleting consumable:', error)
  }
}

const equipmentFireClasses = computed(() => getEquipmentFireClasses())
const equipmentFrequencies = computed(() => getEquipmentFrequencies())
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard class="mb-6">
        <VCardText>
          <div class="d-flex justify-space-between mb-6 flex-wrap align-center gap-y-4 gap-x-6">
            <h5 class="text-h5">
              {{ $t('Blueprint Consumables') }}
            </h5>
            <VBtn
              v-if="!readOnly"
              variant="tonal"
              size="small"
              @click="handleAddConsumable"
            >
              {{ $t('Add New Consumable') }}
            </VBtn>
          </div>
          <VDivider class="my-4" />
          <VList
            lines="two"
            border
          >
            <template
              v-for="(consumable, index) in currentConsumables"
              :key="consumable.id || index"
            >
              <VListItem>
                <VListItemTitle>
                  {{ consumable.consumable_id }} 
                  <span class="ms-4">{{ consumable.name[$i18n.locale] }}</span>
                </VListItemTitle>
                <template
                  v-if="!readOnly"
                  #append
                >
                  <IconBtn @click.stop="openConsumableDeleteDialog(consumable)">
                    <VIcon
                      icon="tabler-trash"
                      class="flip-in-rtl"
                    />
                  </IconBtn>
                </template>
              </VListItem>
              <VDivider v-if="index !== currentConsumables.length - 1" />
            </template>
          </VList>
          <div class="d-flex justify-center mt-6">
            <VPagination
              v-if="totalConsumablePages > 0"
              v-model="consumablePage"
              size="small"
              :length="totalConsumablePages"
              :total-visible="5"
            />
          </div>
        </VCardText>
      </VCard>
    </VCol>
    <!--
      =====================================================
      DIALOG COMPONENTS (ADD/EDIT & DELETE)
      ====================================================== 
    -->
    <AddConsumableDialog
      v-model:is-dialog-visible="isConsumableDialogVisible"
      :consumable-data="dialogState.consumable.current"
      @submit="handleSubmitConsumable"
    />
    <DeleteConsumableDialog
      v-model:is-dialog-visible="isDeleteConsumableDialogVisible"
      :consumable-id="dialogState.consumable.toDelete"
      @delete-consumable="handleDeleteConsumable"
    />
  </VRow>
</template>
