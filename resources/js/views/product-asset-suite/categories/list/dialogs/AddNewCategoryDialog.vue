<script setup>
import { requiredValidator } from '@/@core/utils/validators'
import { getCategoryStatus } from '@/constants/filterOptions'

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'addCategory',
])

const fetchedCategory = ref({
  category_id: '',
  category_name: {},
  category_description: {},
  category_status: '',
})

const resetForm = () => {
  emit('update:isDialogVisible', false)
  fetchedCategory.value = {
    category_id: '',
    category_name: {},
    category_description: {},
    category_status: '',
  }
}

const onFormSubmit = () => {
  emit('addCategory', fetchedCategory.value)
  emit('update:isDialogVisible', false)
  resetForm()
}

const categoryStatus = computed(() => getCategoryStatus())
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 610"
    :model-value="props.isDialogVisible"
    @update:model-value="val => $emit('update:isDialogVisible', val)"
  >
    <!-- 👉 Dialog close btn -->
    <DialogCloseBtn @click="$emit('update:isDialogVisible', false)" />

    <!-- Dialog Content -->
    <VCard :title="$t('Add New Category')">
      <!-- 👉 Form -->
      <VForm
        class="mt-4"
        @submit.prevent="onFormSubmit"
      >
        <VCardText>
          <VRow>
            <VCol cols="12">
              <AppTextField
                v-model="fetchedCategory.category_id"
                :label="$t('Category ID')"
                :rules="[requiredValidator]"
              />
            </VCol>
            <VCol cols="12">
              <AppTextField
                v-model="fetchedCategory.category_name[$i18n.locale]"
                :label="$t('Category Name')"
                :rules="[requiredValidator]"
              />
            </VCol>
            <VCol cols="12">
              <AppTextarea
                v-model="fetchedCategory.category_description[$i18n.locale]"
                :label="$t('Category Description')"
                :rules="[requiredValidator]"
                auto-grow
              />
            </VCol>
            <VCol cols="12">
              <AppSelect
                v-model="fetchedCategory.category_status"
                :label="$t('Status')"
                density="compact"
                :items="categoryStatus"
                :rules="[requiredValidator]"
                auto-select-first
              />
            </VCol>
          </VRow>
        </VCardText>
        <VCardText class="d-flex justify-end flex-wrap gap-3">
          <VBtn
            type="submit"
            color="primary"
          >
            {{ $t('Add') }}
          </VBtn>
          <VBtn
            color="error"
            variant="tonal"
            @click="resetForm"
          >
            {{ $t('Discard') }}
          </VBtn>
        </VCardText>
      </VForm>
    </VCard>
  </VDialog>
</template>
