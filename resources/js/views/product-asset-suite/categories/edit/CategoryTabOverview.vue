<script setup>
import { requiredValidator } from '@/@core/utils/validators'
import { getCategoryStatus } from '@/constants/filterOptions'

const props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  readOnly: {
    type: Boolean,
    default: false,
  },
})

const emits = defineEmits(['update:modelValue'])

// Create a computed property that acts as a two-way binding proxy
const category = computed({
  get: () => props.modelValue,
  set: value => emits('update:modelValue', value),
})

const categoryStatus = computed(() => getCategoryStatus())
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardText>
          <h5 class="text-h5">
            {{ $t('Core Category Information') }}
          </h5>
          <VDivider class="my-4" />
          <VRow>
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="category.category_id"
                :label="$t('Category ID')"
                prepend-icon="tabler-user"
                :variant="readOnly ? 'plain' : 'outlined'"
                :readonly="readOnly"
                :rules="[requiredValidator]"
              />
            </VCol>
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="category.category_name[$i18n.locale]"
                :label="$t('Category Name')"
                prepend-icon="tabler-building-store"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
              />
            </VCol>
            <VCol
              cols="12"
              md="6"
            >
              <VAutocomplete
                v-model="category.category_status"
                :label="$t('Category Status')"
                prepend-icon="tabler-user-check"
                :items="categoryStatus"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
                :clearable="!readOnly"
                :clear-icon="!readOnly ? 'tabler-x' : ''"
                :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
              />
            </VCol>
          </VRow>
          <VRow>
            <VCol cols="12">
              <VTextarea
                v-model="category.category_description[$i18n.locale]"
                :label="$t('Category Description')"
                prepend-icon="tabler-id"
                :readonly="readOnly"
                :variant="readOnly ? 'plain' : 'outlined'"
              />
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
