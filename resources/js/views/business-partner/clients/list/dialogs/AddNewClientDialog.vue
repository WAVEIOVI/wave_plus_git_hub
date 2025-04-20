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
  'addClient',
])

const fetchedClient = ref({
  legal_name: '',
  client_tier: '',
  industry: '',
  client_status: '',
})

const resetForm = () => {
  emit('update:isDialogVisible', false)
  fetchedClient.value = {
    legal_name: '',
    client_tier: '',
    industry: '',
    client_status: '',
  }
}

const onFormSubmit = () => {
  emit('addClient', fetchedClient.value)
  emit('update:isDialogVisible', false)
  resetForm()
}

const clientTiers = computed(() => getBpTiers())
const clientStatus = computed(() => getBpStatus())
const clientIndustries = computed(() => getBpIndustries())
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
    <VCard :title="$t('Add New Client')">
      <!-- 👉 Form -->
      <VForm
        class="mt-4"
        @submit.prevent="onFormSubmit"
      >
        <VCardText>
          <VRow>
            <VCol cols="12">
              <AppTextField
                v-model="fetchedClient.legal_name"
                :label="$t('Client Name')"
                :rules="[requiredValidator]"
              />
            </VCol>
            <VCol cols="12">
              <AppSelect
                v-model="fetchedClient.client_tier"
                :label="$t('Client Tier')"
                density="compact"
                :items="clientTiers"
                :rules="[requiredValidator]"
                auto-select-first
              />
            </VCol>
            <VCol cols="12">
              <AppSelect
                v-model="fetchedClient.industry"
                :label="$t('Industry')"
                density="compact"
                :items="clientIndustries"
                :rules="[requiredValidator]"
                auto-select-first
              />
            </VCol>
            <VCol cols="12">
              <AppSelect
                v-model="fetchedClient.client_status"
                :label="$t('Status')"
                density="compact"
                :items="clientStatus"
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
