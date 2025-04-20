<script setup>
import { requiredValidator } from '@/@core/utils/validators'
import { getBpIndustries, getBpStatus, getBpTiers } from '@/constants/filterOptions'

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'addSupplier',
])

const fetchedSupplier = ref({
  legal_name: '',
  supplier_tier: '',
  industry: '',
  supplier_status: '',
})

const resetForm = () => {
  emit('update:isDialogVisible', false)
  fetchedSupplier.value = {
    legal_name: '',
    supplier_tier: '',
    industry: '',
    supplier_status: '',
  }
}

const onFormSubmit = () => {
  emit('addSupplier', fetchedSupplier.value)
  emit('update:isDialogVisible', false)
  resetForm()
}

const supplierTiers = computed(() => getBpTiers())
const supplierStatus = computed(() => getBpStatus())
const supplierIndustries = computed(() => getBpIndustries())
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
    <VCard :title="$t('Add New Supplier')">
      <!-- 👉 Form -->
      <VForm
        class="mt-4"
        @submit.prevent="onFormSubmit"
      >
        <VCardText>
          <VRow>
            <VCol cols="12">
              <AppTextField
                v-model="fetchedSupplier.legal_name"
                :label="$t('Supplier Name')"
                :rules="[requiredValidator]"
              />
            </VCol>
            <VCol cols="12">
              <AppSelect
                v-model="fetchedSupplier.supplier_tier"
                :label="$t('Supplier Tier')"
                density="compact"
                :items="supplierTiers"
                :rules="[requiredValidator]"
                auto-select-first
              />
            </VCol>
            <VCol cols="12">
              <AppSelect
                v-model="fetchedSupplier.industry"
                :label="$t('Industry')"
                density="compact"
                :items="supplierIndustries"
                :rules="[requiredValidator]"
                auto-select-first
              />
            </VCol>
            <VCol cols="12">
              <AppSelect
                v-model="fetchedSupplier.supplier_status"
                :label="$t('Status')"
                density="compact"
                :items="supplierStatus"
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
