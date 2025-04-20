<script setup>
const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  width: {
    type: [String, Number],
    default: '300',
  },
  timeout: {
    type: Number,
    default: 4000,
  },
  autoClose: {
    type: Boolean,
    default: true,
  },
  message: {
    type: String,
    default: 'Please wait',
  },
  color: {
    type: String,
    default: 'primary',
  },
})

const emit = defineEmits(['update:modelValue'])

// Create local reactive ref for dialog visibility
const isDialogVisible = ref(props.modelValue)

// Watch for changes in prop and update local state
watch(() => props.modelValue, newValue => {
  isDialogVisible.value = newValue
})

// Watch for changes in local state and emit updates to parent
watch(isDialogVisible, newValue => {
  emit('update:modelValue', newValue)

  // Set up auto-close timer if dialog is visible and autoClose is enabled
  if (newValue && props.autoClose) {
    setTimeout(() => {
      isDialogVisible.value = false
      emit('update:modelValue', false)
    }, props.timeout)
  }
})
</script>

<template>
  <VDialog 
    v-model="isDialogVisible"
    :width="width"
    persistent
  >
    <VCard
      :color="color"
      :width="width"
    >
      <VCardText class="pt-3">
        {{ $t(message) }}
        <VProgressLinear
          indeterminate
          bg-color="rgba(var(--v-theme-surface), 0.1)"
          :height="8"
          class="mb-0 mt-4"
        />
      </VCardText>
    </VCard>
  </VDialog>
</template>
