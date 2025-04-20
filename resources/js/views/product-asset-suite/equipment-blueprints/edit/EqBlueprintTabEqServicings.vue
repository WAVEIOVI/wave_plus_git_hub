<script setup>
import { usePagination } from '@/composables/usePagination'
import { useTranslation } from '@/composables/useTranslation'
import { getEquipmentFireClasses, getEquipmentFrequencies } from '@/constants/filterOptions'
import AddCapacityDialog from './dialogs/AddCapacityDialog.vue'
import AddPressureDialog from './dialogs/AddPressureDialog.vue'
import DeleteCapacityDialog from './dialogs/DeleteCapacityDialog.vue'
import DeletePressureDialog from './dialogs/DeletePressureDialog.vue'

const props = defineProps({
  modelValue: { type: Object, required: true },
  capacityData: { type: Array, required: true },
  attachEqBlueprintCapacity: { type: Function, default: () => {} },
  detachEqBlueprintCapacity: { type: Function, default: () => {} },
  fetchEqBlueprintCapacity: { type: Function, default: () => {} },
  pressureData: { type: Array, required: true },
  attachEqBlueprintPressure: { type: Function, default: () => {} },
  detachEqBlueprintPressure: { type: Function, default: () => {} },
  fetchEqBlueprintPressure: { type: Function, default: () => {} },
  readOnly: { type: Boolean, default: false },
})

const emits = defineEmits([
  'update:modelValue',
  'editCapacity',
  'editPressure',
])

const eqBlueprint = computed({
  get: () => props.modelValue,
  set: value => emits('update:modelValue', value),
})

const itemsPerPage = ref(5)
const capacitiesRef = computed(() => props.capacityData)
const { page: capacityPage, totalPages: totalCapacityPages, currentItems: currentCapacities } = usePagination(capacitiesRef, itemsPerPage)

const pressuresRef = computed(() => props.pressureData)
const { page: pressurePage, totalPages: totalPressurePages, currentItems: currentPressures } = usePagination(pressuresRef, itemsPerPage)

const dialogState = reactive({
  capacity: {
    isVisible: false,
    isDeleteVisible: false,
    current: null,
    toDelete: null,
  },
  pressure: {
    isVisible: false,
    isDeleteVisible: false,
    current: null,
    toDelete: null,
  },
})

const { translateValue } = useTranslation()

const EMPTY_CAPACITY = {
  weight_id: '',
  unit: '',
}

const EMPTY_PRESSURE = {
  pressure_id: '',
  name: '',
}

const isCapacityDialogVisible = computed({
  get: () => dialogState.capacity.isVisible,
  set: value => dialogState.capacity.isVisible = value,
})

const isDeleteCapacityDialogVisible = computed({
  get: () => dialogState.capacity.isDeleteVisible,
  set: value => dialogState.capacity.isDeleteVisible = value,
})

const isPressureDialogVisible = computed({
  get: () => dialogState.pressure.isVisible,
  set: value => dialogState.pressure.isVisible = value,
})

const isDeletePressureDialogVisible = computed({
  get: () => dialogState.pressure.isDeleteVisible,
  set: value => dialogState.pressure.isDeleteVisible = value,
})


const openCapacityDeleteDialog = capacity => {
  dialogState.capacity.toDelete = capacity.id
  dialogState.capacity.isDeleteVisible = true
}

const openPressureDeleteDialog = pressure => {
  dialogState.pressure.toDelete = pressure.id
  dialogState.pressure.isDeleteVisible = true
}

const handleAddCapacity = () => {
  if (props.readOnly) return
  dialogState.capacity.current = { ...EMPTY_CAPACITY }
  dialogState.capacity.isVisible = true
}

const handleSubmitCapacity = async capacityDataInput => {
  try {
    await props.attachEqBlueprintCapacity(eqBlueprint.value.id, capacityDataInput)
    emits('editCapacity', capacityDataInput.id)
  } catch (error) {
    console.error('Error saving capacity:', error)
  }
}

const handleDeleteCapacity = async capacityId => {
  try {
    await props.detachEqBlueprintCapacity(eqBlueprint.value.id, capacityId)
    emits('editCapacity', capacityId)
    dialogState.capacity.isDeleteVisible = false
  } catch (error) {
    console.error('Error deleting capacity:', error)
  }
}

const handleAddPressure = () => {
  if (props.readOnly) return
  dialogState.pressure.current = { ...EMPTY_PRESSURE }
  dialogState.pressure.isVisible = true
}

const handleSubmitPressure = async pressureDataInput => {
  try {
    await props.attachEqBlueprintPressure(eqBlueprint.value.id, pressureDataInput)
    emits('editPressure', pressureDataInput.id)
  } catch (error) {
    console.error('Error saving pressure:', error)
  }
}

const handleDeletePressure = async pressureId => {
  try {
    await props.detachEqBlueprintPressure(eqBlueprint.value.id, pressureId)
    emits('editPressure', pressureId)
    dialogState.pressure.isDeleteVisible = false
  } catch (error) {
    console.error('Error deleting pressure:', error)
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
          <h5 class="text-h5">
            {{ $t('Blueprint Spefications') }}
          </h5>
          <VDivider class="my-4" />
          <VRow>
            <VCol
              cols="12"
              md="6"
            >
              <VAutocomplete
                v-model="eqBlueprint.inspection_frequency"
                :label="$t('Inspection Frequency')"
                prepend-icon="tabler-user-check"
                :items="equipmentFrequencies"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
                :clearable="!readOnly"
                :clear-icon="!readOnly ? 'tabler-x' : ''"
                :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
              />
            </VCol>
            <VCol
              cols="12"
              md="6"
            >
              <VAutocomplete
                v-model="eqBlueprint.hydro_test_frequency"
                :label="$t('Hydro Test Frequency')"
                prepend-icon="tabler-user-check"
                :items="equipmentFrequencies"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
                :clearable="!readOnly"
                :clear-icon="!readOnly ? 'tabler-x' : ''"
                :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
              />
            </VCol>
            <VCol cols="12">
              <VAutocomplete
                v-model="eqBlueprint.fire_class_rating"
                :label="$t('Fire Class Rating')"
                prepend-icon="tabler-user-check"
                :items="equipmentFireClasses"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
                :clearable="!readOnly"
                :clear-icon="!readOnly ? 'tabler-x' : ''"
                :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
              />
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
      <!--
        =====================================================
        CAPACITY BOOK SECTION
        ====================================================== 
      -->
      <VCard class="mb-6">
        <VCardText>
          <div class="d-flex justify-space-between mb-6 flex-wrap align-center gap-y-4 gap-x-6">
            <h5 class="text-h5">
              {{ $t('Capacity List') }}
            </h5>
            <VBtn
              v-if="!readOnly"
              variant="tonal"
              size="small"
              @click="handleAddCapacity"
            >
              {{ $t('Add New Capacity') }}
            </VBtn>
          </div>
          <VList
            lines="two"
            border
          >
            <template
              v-for="(capacity, index) in currentCapacities"
              :key="capacity.id || index"
            >
              <VListItem>
                <VListItemTitle>
                  {{ capacity.weight_id }} 
                  <span class="ms-4">{{ capacity.value }} {{ capacity.abbreviation[$i18n.locale] }}</span>
                </VListItemTitle>
                <template
                  v-if="!readOnly"
                  #append
                >
                  <IconBtn @click.stop="openCapacityDeleteDialog(capacity)">
                    <VIcon
                      icon="tabler-trash"
                      class="flip-in-rtl"
                    />
                  </IconBtn>
                </template>
              </VListItem>
              <VDivider v-if="index !== currentCapacities.length - 1" />
            </template>
          </VList>
          <div class="d-flex justify-center mt-6">
            <VPagination
              v-if="totalCapacityPages > 0"
              v-model="capacityPage"
              size="small"
              :length="totalCapacityPages"
              :total-visible="5"
            />
          </div>
        </VCardText>
      </VCard>
      <!--
        =====================================================
        PRESSURE BOOK SECTION
        ====================================================== 
      -->
      <VCard class="mb-6">
        <VCardText>
          <div class="d-flex justify-space-between mb-6 flex-wrap align-center gap-y-4 gap-x-6">
            <h5 class="text-h5">
              {{ $t('Pressure List') }}
            </h5>
            <VBtn
              v-if="!readOnly"
              variant="tonal"
              size="small"
              @click="handleAddPressure"
            >
              {{ $t('Add New Pressure') }}
            </VBtn>
          </div>
          <VList
            lines="two"
            border
          >
            <template
              v-for="(pressure, index) in currentPressures"
              :key="pressure.id || index"
            >
              <VListItem>
                <VListItemTitle>
                  {{ pressure.pressure_id }} 
                  <span class="ms-4">{{ pressure.name[$i18n.locale] }}</span>
                </VListItemTitle>
                <template
                  v-if="!readOnly"
                  #append
                >
                  <IconBtn @click.stop="openPressureDeleteDialog(pressure)">
                    <VIcon
                      icon="tabler-trash"
                      class="flip-in-rtl"
                    />
                  </IconBtn>
                </template>
              </VListItem>
              <VDivider v-if="index !== currentPressures.length - 1" />
            </template>
          </VList>
          <div class="d-flex justify-center mt-6">
            <VPagination
              v-if="totalPressurePages > 0"
              v-model="pressurePage"
              size="small"
              :length="totalPressurePages"
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
    <AddCapacityDialog
      v-model:is-dialog-visible="isCapacityDialogVisible"
      :capacity-data="dialogState.capacity.current"
      @submit="handleSubmitCapacity"
    />
    <DeleteCapacityDialog
      v-model:is-dialog-visible="isDeleteCapacityDialogVisible"
      :capacity-id="dialogState.capacity.toDelete"
      @delete-capacity="handleDeleteCapacity"
    />
    <AddPressureDialog
      v-model:is-dialog-visible="isPressureDialogVisible"
      :pressure-data="dialogState.pressure.current"
      @submit="handleSubmitPressure"
    />
    <DeletePressureDialog
      v-model:is-dialog-visible="isDeletePressureDialogVisible"
      :pressure-id="dialogState.pressure.toDelete"
      @delete-pressure="handleDeletePressure"
    />
  </VRow>
</template>
