<script setup>
import i18n from "@/plugins/i18n"
import {
  requiredValidator,
} from '@validators'


const props = defineProps({
  weightData: {
    type: Object,
    required: false,
    default: () => ({
      weight_id: '',
      unit: {},
      abbreviation: {},
      value: '',
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

const formData = ref({ ...props.weightData })

// Watch for changes in weightData prop to update the local form data
watch(() => props.weightData, newData => {
  if (newData) {
    formData.value = { ...newData }
  }
}, { deep: true, immediate: true })

// Watch for dialog visibility changes to reset form when closed
watch(() => props.isDialogVisible, isVisible => {
  if (isVisible && props.weightData) {
    formData.value = { ...props.weightData }
  }
})


const resetForm = () => {
  emit('update:isDialogVisible', false)
  formData.value = { ...props.weightData }
}

const onFormSubmit = () => {
  emit('submit', formData.value)
  emit('update:isDialogVisible', false)
}

const dialogTitle = computed(() => {
  return props.isEditMode ? i18n.global.t('Edit Weight') : i18n.global.t('Add New Weight')
})

const dialogSubtitle = computed(() => {
  return props.isEditMode 
    ? `${i18n.global.t('Editing')} ${formData.value.weight_id}`
    : `${i18n.global.t('Add Weight Information')}`
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
            <!-- 👉 weight id -->
            <VCol cols="12">
              <AppTextField
                v-model="formData.weight_id"
                :label="$t('Weight ID')"
                :rules="[requiredValidator]"
              />
            </VCol>

            <!-- 👉 unit -->
            <VCol cols="12">
              <AppTextField
                v-model="formData.unit[$i18n.locale]"
                :label="$t('Unit')"
                :rules="[requiredValidator]"
              />
            </VCol>

            <!-- 👉 unit -->
            <VCol cols="12">
              <AppTextField
                v-model="formData.abbreviation[$i18n.locale]"
                :label="$t('Unit')"
                :rules="[requiredValidator]"
              />
            </VCol>

            <!-- 👉 value -->
            <VCol cols="12">
              <AppTextField
                v-model="formData.value"
                :label="$t('Value')"
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
