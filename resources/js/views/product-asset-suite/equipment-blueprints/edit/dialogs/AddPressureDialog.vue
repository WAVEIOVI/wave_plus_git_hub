<script setup>
import { useSettingStore } from "@/stores/product-asset-suite/settings/useSettingStore"
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
const settingStore = useSettingStore()
const pressures = ref([])

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

// Fetch pressures when the dialog is opened
watch(
  () => props.isDialogVisible,
  async newVal => {
    if (newVal) {
      try {
        const response = await settingStore.fetchPressures({ options: {} })

        const pressuresArray = extractArray(response)
        if (Array.isArray(pressuresArray)) {
          pressures.value = pressuresArray.map(pressure => ({
            title: `${pressure.pressure_id} - ${pressure.name[locale.value] || ''}`,
            value: pressure.id,
          }))
        } else {
          console.error('Unexpected response format:', response)
          pressures.value = []
        }
      } catch (error) {
        console.error('Error fetching pressures:', error)
        pressures.value = []
      }
    }
  },
)

// we only need to track the raw input here:
const formData = ref({
  attached_pressures: '',
})

const resetForm = () => {
  emit('update:isDialogVisible', false)
  formData.value.attached_pressures = '' // Reset attached_pressures to empty
}

const onFormSubmit = () => {
  // Ensure the payload is flattened when multiple records are selected:
  const payload = {
    resources: Array.isArray(formData.value.attached_pressures)
      ? formData.value.attached_pressures
      : [formData.value.attached_pressures],
  }

  emit('submit', payload)
  emit('update:isDialogVisible', false)
  formData.value.attached_pressures = '' // Reset attached_pressures to empty after submit
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
          {{ $t('Attach Pressures With Eq Blueprint') }}
        </VCardSubtitle>

        <VForm
          class="mt-4"
          @submit.prevent="onFormSubmit"
        >
          <VRow>
            <VCol cols="12">
              <AppSelect
                v-model="formData.attached_pressures"
                :label="$t('Pressures')"
                density="compact"
                :items="pressures"
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
