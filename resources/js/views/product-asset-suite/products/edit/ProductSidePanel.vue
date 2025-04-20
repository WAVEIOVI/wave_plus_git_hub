<script setup>
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

const emit = defineEmits(['update:modelValue'])

const product = computed({
  get: () => props.modelValue,
  set: value => emit('update:modelValue', value),
})

// Auto-calculate base price when purchase price or profit margin changes
watch(
  [() => product.value.purchase_price, () => product.value.profit_margin],
  ([purchasePrice, profitMargin]) => {
    if (purchasePrice && profitMargin !== undefined) {
      const profit = (purchasePrice * profitMargin) / 100

      product.value.base_price = purchasePrice + profit
    }
  },
)

// Auto-calculate sale price when base price or discount rate changes
watch(
  [() => product.value.base_price, () => product.value.discount_rate],
  ([basePrice, discountRate]) => {
    if (basePrice && discountRate !== undefined) {
      const discount = (basePrice * discountRate) / 100

      product.value.sale_price = basePrice - discount
    }
  },
)

// Auto-calculate tax sale price when sale price or tax rate changes
watch(
  [() => product.value.sale_price, () => product.value.tax_rate],
  ([salePrice, taxRate]) => {
    if (salePrice && taxRate !== undefined) {
      const tax = (salePrice * taxRate) / 100

      product.value.tax_sale = salePrice + tax
    }
  },
)
</script>

<template>
  <VCol
    md="4"
    cols="12"
  >
    <!-- 👉 Pricing -->
    <VCard
      :title="$t('Pricing')"
      class="mb-6"
    >
      <VCardText>
        <VTextField
          v-model.number="product.purchase_price"
          :label="$t('Purchase Price')"
          :variant="readOnly ? 'plain' : 'outlined'"
          :readonly="readOnly"
          class="mb-6"
        />
        <VTextField
          v-model.number="product.profit_margin"
          :label="$t('Profit Rate (%)')"
          :variant="readOnly ? 'plain' : 'outlined'"
          :readonly="readOnly"
          suffix="%"
          class="mb-6"
        />
        <VTextField
          v-model.number="product.base_price"
          :label="$t('Base Price')"
          :variant="readOnly ? 'plain' : 'outlined'"
          readonly
          class="mb-6"
        />
        <VTextField
          v-model.number="product.discount_rate"
          :label="$t('Discount Rate (%)')"
          :variant="readOnly ? 'plain' : 'outlined'"
          :readonly="readOnly"
          suffix="%"
          class="mb-6"
        />
        <VTextField
          v-model.number="product.sale_price"
          :label="$t('Sale Price')"
          :variant="readOnly ? 'plain' : 'outlined'"
          readonly
          class="mb-6"
        />
        <VTextField
          v-model.number="product.tax_rate"
          :label="$t('Tax Rate (%)')"
          :readonly="readOnly"
          :variant="readOnly ? 'plain' : 'outlined'"
          suffix="%"
          class="mb-6"
        />
        <VTextField
          v-model.number="product.tax_sale"
          :label="$t('Tax Sale Price')"
          readonly
          :variant="readOnly ? 'plain' : 'outlined'"
          class="mb-6"
        />
      </VCardText>
    </VCard>
    <!-- 👉 Stock -->
    <VCard
      :title="$t('Stock')"
      class="mb-6"
    >
      <VCardText>
        <VTextField
          v-model="product.stock_quantity"
          :label="$t('Stock Quantity')"
          :readonly="readOnly"
          :variant="readOnly ? 'plain' : 'outlined'"
          class="mb-6"
        />
      </VCardText>
    </VCard>
  </VCol>
</template>
