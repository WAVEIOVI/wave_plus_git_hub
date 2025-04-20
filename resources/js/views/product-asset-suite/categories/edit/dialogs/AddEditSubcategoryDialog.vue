<script setup>
import { getCategoryStatus } from '@/constants/filterOptions'
import i18n from "@/plugins/i18n"
import {
  requiredValidator,
} from '@validators'


const props = defineProps({
  subcategoryData: {
    type: Object,
    required: false,
    default: () => ({
      subcategory_id: '',
      subcategory_name: '',
      subcategory_description: '',
      subcategory_status: '',
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

const formData = ref({ ...props.subcategoryData })

// Watch for changes in subcategoryData prop to update the local form data
watch(() => props.subcategoryData, newData => {
  if (newData) {
    formData.value = { ...newData }
  }
}, { deep: true, immediate: true })

// Watch for dialog visibility changes to reset form when closed
watch(() => props.isDialogVisible, isVisible => {
  if (isVisible && props.subcategoryData) {
    formData.value = { ...props.subcategoryData }
  }
})

const subcategoryStatus = computed(() => getCategoryStatus())

const resetForm = () => {
  emit('update:isDialogVisible', false)
  formData.value = { ...props.subcategoryData }
}

const onFormSubmit = () => {
  emit('submit', formData.value)
  emit('update:isDialogVisible', false)
}

const dialogTitle = computed(() => {
  return props.isEditMode ? i18n.global.t('Edit Subcategory') : i18n.global.t('Add New Subcategory')
})

const dialogSubtitle = computed(() => {
  return props.isEditMode 
    ? `${i18n.global.t('Editing')} ${formData.value.first_name} ${formData.value.last_name}`
    : `${i18n.global.t('Add Subcategory Information For This Category')}`
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
            <VCol cols="12">
              <AppTextField
                v-model="formData.subcategory_id"
                :label="$t('Subcategory ID')"
                :rules="[requiredValidator]"
              />
            </VCol>
            <VCol cols="12">
              <AppTextField
                v-model="formData.subcategory_name[$i18n.locale]"
                :label="$t('Subcategory Name')"
                :rules="[requiredValidator]"
              />
            </VCol>
            <VCol cols="12">
              <AppTextarea
                v-model="formData.subcategory_description[$i18n.locale]"
                :label="$t('Subcategory Description')"
                :rules="[requiredValidator]"
                auto-grow
              />
            </VCol>
            <VCol cols="12">
              <AppSelect
                v-model="formData.subcategory_status"
                :label="$t('Status')"
                density="compact"
                :items="subcategoryStatus"
                :rules="[requiredValidator]"
                auto-select-first
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
