<script setup>
const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: 'Unsaved Changes',
  },
  message: {
    type: String,
    default: 'You have unsaved changes. Are you sure you want to leave without saving?',
  },
  confirmText: {
    type: String,
    default: 'Leave',
  },
  cancelText: {
    type: String,
    default: 'Cancel',
  },
})

const emits = defineEmits(['update:modelValue', 'confirm', 'cancel'])

// Create an internal ref to track the dialog state.
const internalValue = ref(props.modelValue)

watch(() => props.modelValue, newVal => {
  internalValue.value = newVal
})

watch(internalValue, val => {
  emits('update:modelValue', val)
})

const confirm = () => {
  emits('confirm')
}

const cancel = () => {
  emits('cancel')
}
</script>

<template>
  <!-- 👉 Confirm Dialog -->
  <VDialog
    max-width="500"
    :model-value="modelValue"
    @update:model-value="emits('update:modelValue', $event)"
  >
    <VCard>
      <VCardTitle class="headline">
        {{ title }}
      </VCardTitle>
      <VCardText>
        {{ message }}
      </VCardText>
      <VCardActions>
        <VSpacer />
        <VBtn
          text
          @click="cancel"
        >
          {{ cancelText }}
        </VBtn>
        <VBtn
          color="primary"
          text
          @click="confirm"
        >
          {{ confirmText }}
        </VBtn>
      </VCardActions>
    </VCard>
  </VDialog>
</template>
