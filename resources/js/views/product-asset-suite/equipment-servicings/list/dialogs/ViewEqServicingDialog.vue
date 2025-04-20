<script setup>
import EqServicingMainPanel from '@/views/product-asset-suite/equipment-servicings/edit/EqServicingMainPanel.vue'
import EqServicingSidePanel from '@/views/product-asset-suite/equipment-servicings/edit/EqServicingSidePanel.vue'
import { useI18n } from 'vue-i18n'


const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  fetchedEqServicing: {
    type: Object,
    default: () => ({}),
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'print-eq-servicing-data',
])

const { t } = useI18n()

const closeDialog = () => {
  emit('update:isDialogVisible', false)
}
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 1400"
    transition="dialog-top-transition"
    :model-value="props.isDialogVisible"
    @update:model-value="val => $emit('update:isDialogVisible', val)"
  >
    <!-- 👉 Dialog close btn -->
    <DialogCloseBtn @click="$emit('update:isDialogVisible', false)" />
    <VCard>
      <VCardText>
        <!-- Header -->
        <div class="d-flex justify-space-between align-center mb-6">
          <h4 class="text-h4">
            {{ $t('Equipment Servicing View') }} #{{ fetchedEqServicing?.eq_servicing_id }}
          </h4>
          <div>
            <div class="d-flex gap-4">
              <VBtn
                variant="tonal"
                color="secondary"
                prepend-icon="tabler-printer"
                @click="emit('print-eq-servicing-data', props.fetchedEqServicing.id)"
              >
                {{ $t('Print') }}
              </VBtn>
              <VBtn
                color="error"
                variant="tonal"
                @click="closeDialog"
              >
                {{ $t('Discard') }}
              </VBtn>
            </div>
          </div>
          <DialogCloseBtn @click="emit('update:isDialogVisible', false)" />
        </div>

        <!-- Content -->
        <VRow>
          <EqServicingMainPanel
            :model-value="fetchedEqServicing"
            read-only
          />
          <EqServicingSidePanel
            :model-value="fetchedEqServicing"
            read-only
          />
        </VRow>
      </VCardText>
    </VCard>
  </VDialog>
</template>
