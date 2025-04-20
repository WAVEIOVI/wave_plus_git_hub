<script setup>
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  client: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits(['update:isDialogVisible', 'update-client'])

const dialogModelValueUpdate = val => {
  emit('update:isDialogVisible', val)
}

const vat_exemption_certificate_id = ref(props.client.vat_exemption_certificate_id || '')
const vat_exemption_certificate_issue_date = ref(props.client.vat_exemption_certificate_issue_date || '')
const vat_exemption_certificate_expiration_date = ref(props.client.vat_exemption_certificate_expiration_date || '')

const resetForm = () => {
  emit('update:isDialogVisible', false)
  vat_exemption_certificate_id.value = ''
  vat_exemption_certificate_issue_date.value = ''
  vat_exemption_certificate_expiration_date.value = ''
}

const handleRenew = () => {
  emit('update-client', {
    ...props.client,
    vat_exemption_certificate_id: vat_exemption_certificate_id.value,
    vat_exemption_certificate_issue_date: vat_exemption_certificate_issue_date.value,
    vat_exemption_certificate_expiration_date: vat_exemption_certificate_expiration_date.value,
  })
  resetForm()
}
</script>

<template>
  <!-- 👉 upgrade plan -->
  <VDialog persistent :width="$vuetify.display.smAndDown ? 'auto' : 650" :model-value="props.isDialogVisible"
    @update:model-value="dialogModelValueUpdate">
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="dialogModelValueUpdate(false)" />

    <VCard class="py-8">
      <!-- 👉 dialog close btn -->
      <DialogCloseBtn variant="text" size="small" @click="dialogModelValueUpdate(false)" />

      <VCardItem class="text-center">
        <VCardTitle class="text-h5 mb-5">
          Renew Certificate
        </VCardTitle>
        <VCardSubtitle>
          Provide New Certificate Data.
        </VCardSubtitle>
      </VCardItem>

      <VCardText>
        <VRow>
          <VCol cols="12">
            <VTextField v-model="vat_exemption_certificate_id" label="Certificate ID" variant="outlined" />
          </VCol>
          <VCol cols="12" md="6">
            <AppDateTimePicker v-model="vat_exemption_certificate_issue_date" label="Issue Date"
              placeholder="Select date" :config="{ inline: true }" />
          </VCol>
          <VCol cols="12" md="6">
            <AppDateTimePicker v-model="vat_exemption_certificate_expiration_date" label="Expiration Date"
              placeholder="Select date" :config="{ inline: true }" />
          </VCol>
        </VRow>
      </VCardText>

      <VDivider class="my-3" />

      <VCardText class="px-15">
        <p class="font-weight-medium mb-2">
          The Current Certificate is Valid
        </p>
        <div class="d-flex justify-space-between flex-wrap">
          <VBtn class="mt-3" color="primary" @click="handleRenew">
            Renew This Certificate
          </VBtn>
          <VBtn color="error" variant="tonal" class="mt-3" @click="resetForm">
            Remove This Certificate
          </VBtn>
        </div>
      </VCardText>
    </VCard>
  </VDialog>
</template>
