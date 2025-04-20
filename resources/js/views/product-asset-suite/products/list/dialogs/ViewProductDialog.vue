<script setup>
import ProductMainPanel from '@/views/product-asset-suite/products/edit/ProductMainPanel.vue'
import ProductSidePanel from '@/views/product-asset-suite/products/edit/ProductSidePanel.vue'
import { useI18n } from 'vue-i18n'


const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  fetchedProduct: {
    type: Object,
    default: () => ({}),
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'print-product-data',
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
            {{ $t('Product View') }} #{{ fetchedProduct?.product_id }}
          </h4>
          <div>
            <div class="d-flex gap-4">
              <VBtn
                variant="tonal"
                color="secondary"
                prepend-icon="tabler-printer"
                @click="emit('print-product-data', props.fetchedProduct.id)"
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
          <ProductMainPanel
            :model-value="fetchedProduct"
            read-only
          />
          <ProductSidePanel
            :model-value="fetchedProduct"
            read-only
          />
        </VRow>
      </VCardText>
    </VCard>
  </VDialog>
</template>
