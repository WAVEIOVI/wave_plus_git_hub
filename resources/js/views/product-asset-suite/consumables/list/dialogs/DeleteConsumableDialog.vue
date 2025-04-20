<script setup>
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'deleteConsumable',
])

const onConfirm = () => {
  emit('deleteConsumable')
  emit('update:isDialogVisible', false)
}

const onCancel = () => {
  emit('update:isDialogVisible', false)
}
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 610"
    :model-value="props.isDialogVisible"
    class="v-dialog-sm"
    @update:model-value="val => emit('update:isDialogVisible', val)"
  >
    <!-- Dialog close button -->
    <DialogCloseBtn @click="onCancel" />

    <VCard :title="$t('Confirm Consumable Deletion')">
      <VForm
        class="mt-4"
        @submit.prevent="onConfirm"
      >
        <VCardText>
          <VRow>
            <VCardText>
              {{ $t('Are you sure you want to delete this consumable? This action cannot be undone.') }}
            </VCardText>
            <VCardText class="d-flex justify-end gap-3 flex-wrap">
              <VBtn
                color="secondary"
                variant="tonal"
                @click="onCancel"
              >
                {{ $t('Discard') }}
              </VBtn>
              <VBtn
                color="error"
                @click="onConfirm"
              >
                {{ $t('Confirm') }}
                <VIcon
                  end
                  icon="tabler-trash-x"
                />
              </VBtn>
            </VCardText>
          </VRow>
        </VCardText>
      </VForm>
    </VCard>
  </VDialog>
</template>
