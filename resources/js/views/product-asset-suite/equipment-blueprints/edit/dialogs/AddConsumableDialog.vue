<script setup>
import { useConsumableStore } from "@/stores/product-asset-suite/consumables/useConsumableStore"
import { requiredValidator } from '@validators'
import { useI18n } from 'vue-i18n'

const props = defineProps({
  isDialogVisible: { type: Boolean, required: true },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'submit',
])

const { locale, t } = useI18n()
const ConsumableStore = useConsumableStore()
const consumables = ref([])

// Helper to extract array from the response in case of nested data
const extractArray = response => {
  if (Array.isArray(response.data)) {
    return response.data
  }
  if (response.data && Array.isArray(response.data.data)) {
    return response.data.data
  }
  
  return []
}

// Fetch consumables when the dialog is opened
watch(
  () => props.isDialogVisible,
  async newVal => {
    if (newVal) {
      try {
        const response = await ConsumableStore.fetchConsumables({ options: {} })

        const consumablesArray = extractArray(response)
        if (Array.isArray(consumablesArray)) {
          consumables.value = consumablesArray.map(consumable => ({
            title: `${consumable.consumable_id} - ${consumable.name[locale.value] || ''}`,
            value: consumable.id,
          }))
        } else {
          console.error('Unexpected response format:', response)
          consumables.value = []
        }
      } catch (error) {
        console.error('Error fetching consumables:', error)
        consumables.value = []
      }
    }
  },
)

// we only need to track the raw input here:
const formData = ref({
  attached_consumables: '',
})

const resetForm = () => {
  emit('update:isDialogVisible', false)
  formData.value.attached_consumables = '' // Reset attached_consumables to empty
}

const onFormSubmit = () => {
  // Ensure the payload is flattened when multiple records are selected:
  const payload = {
    resources: Array.isArray(formData.value.attached_consumables)
      ? formData.value.attached_consumables
      : [formData.value.attached_consumables],
  }

  emit('submit', payload)
  emit('update:isDialogVisible', false)
  formData.value.attached_consumables = '' // Reset attached_consumables to empty after submit
}
</script>

<template>
  <VDialog
    :model-value="props.isDialogVisible"
    :width="$vuetify.display.smAndDown ? 'auto' : 610"
    @update:model-value="val => emit('update:isDialogVisible', val)"
  >
    <DialogCloseBtn @click="resetForm" />

    <VCard class="pa-sm-8 pa-5">
      <VCardText>
        <VCardSubtitle class="text-center mb-6">
          {{ $t('Attach Consumables With Eq Blueprint') }}
        </VCardSubtitle>

        <VForm
          class="mt-4"
          @submit.prevent="onFormSubmit"
        >
          <VRow>
            <VCol cols="12">
              <AppSelect
                v-model="formData.attached_consumables"
                :label="$t('Consumables')"
                density="compact"
                :items="consumables"
                item-title="title"
                item-value="value"
                :rules="[requiredValidator]"
                auto-select-first
                chips
                multiple
                closable-chips
              />
            </VCol>

            <VCol
              cols="12"
              class="text-center"
            >
              <VBtn
                type="submit"
                class="me-3"
              >
                {{ $t('Submit') }}
              </VBtn>
              <VBtn
                variant="tonal"
                color="secondary"
                @click="resetForm"
              >
                {{ $t('Discard') }}
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
