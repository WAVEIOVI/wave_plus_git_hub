<script setup>
import i18n from "@/plugins/i18n"
import {
  requiredValidator,
} from '@validators'


const props = defineProps({
  pressureData: {
    type: Object,
    required: false,
    default: () => ({
      pressure_id: '',
      name: {},
      description: {},
    }),
  },
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  isEditMode: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'submit',
])

const formData = ref({ ...props.pressureData })

// Watch for changes in pressureData prop to update the local form data
watch(() => props.pressureData, newData => {
  if (newData) {
    formData.value = { ...newData }
  }
}, { deep: true, immediate: true })

// Watch for dialog visibility changes to reset form when closed
watch(() => props.isDialogVisible, isVisible => {
  if (isVisible && props.pressureData) {
    formData.value = { ...props.pressureData }
  }
})


const resetForm = () => {
  emit('update:isDialogVisible', false)
  formData.value = { ...props.pressureData }
}

const onFormSubmit = () => {
  emit('submit', formData.value)
  emit('update:isDialogVisible', false)
}

const dialogTitle = computed(() => {
  return props.isEditMode ? i18n.global.t('Edit Pressure') : i18n.global.t('Add New Pressure')
})

const dialogSubtitle = computed(() => {
  return props.isEditMode 
    ? `${i18n.global.t('Editing')} ${formData.value.pressure_id}`
    : `${i18n.global.t('Add Pressure Information')}`
})
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 610 "
    :model-value="props.isDialogVisible"
    @update:model-value="val => $emit('update:isDialogVisible', val)"
  >
    <!-- 👉 Dialog close btn -->
    <DialogCloseBtn @click="$emit('update:isDialogVisible', false)" />

    <VCard class="pa-sm-8 pa-5">
      <!-- 👉 Title -->
      <VCardItem>
        <VCardTitle class="text-h4 text-center">
          {{ dialogTitle }}
        </VCardTitle>
      </VCardItem>

      <VCardText>
        <!-- 👉 Subtitle -->
        <VCardSubtitle class="text-center mb-6">
          <span class="text-base">
            {{ dialogSubtitle }}
          </span>
        </VCardSubtitle>

        <!-- 👉 Form -->
        <VForm
          class="mt-4"
          @submit.prevent="onFormSubmit"
        >
          <VRow>
            <!-- 👉 pressure id -->
            <VCol cols="12">
              <AppTextField
                v-model="formData.pressure_id"
                :label="$t('Pressure ID')"
                :rules="[requiredValidator]"
              />
            </VCol>

            <!-- 👉 name -->
            <VCol cols="12">
              <AppTextField
                v-model="formData.name[$i18n.locale]"
                :label="$t('Name')"
                :rules="[requiredValidator]"
              />
            </VCol>

            <!-- 👉 description -->
            <VCol cols="12">
              <AppTextField
                v-model="formData.description[$i18n.locale]"
                :label="$t('Description')"
              />
            </VCol>
            
            <!-- 👉 Submit and Cancel button -->
            <VCol
              cols="12"
              class="text-center"
            >
              <VBtn
                type="submit"
                class="me-3"
              >
                {{ isEditMode ? $t('Update') : $t('Submit') }}
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
