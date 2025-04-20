<script setup>
import { useProducts } from '@/composables/product-asset-suite/useProducts'
import { useSettingStore } from "@/stores/product-asset-suite/settings/useSettingStore"
import { useSnackbarStore } from '@/stores/useSnackbarStore'
import ProductMainPanel from '@/views/product-asset-suite/products/edit/ProductMainPanel.vue'
import ProductSidePanel from '@/views/product-asset-suite/products/edit/ProductSidePanel.vue'
import DeleteProductDialog from '@/views/product-asset-suite/products/list/dialogs/DeleteProductDialog.vue'
import { useI18n } from 'vue-i18n'
import { onBeforeRouteLeave, useRoute, useRouter } from 'vue-router'

const snackbarStore = useSnackbarStore()
const settingStore = useSettingStore()
const brands = ref([])
const route = useRoute()
const router = useRouter()
const { t, locale } = useI18n()

// Use a dedicated ref for product ID
const selectedProductId = ref(route.params.id)
const isDeleteProductDialogVisible = ref(false)

// Helper to show snackbar messages
const showSnackbar = (text, color) => {
  snackbarStore.show = true
  snackbarStore.text = text
  snackbarStore.color = color
}

// Destructure methods from the useProducts composable
const {
  fetchedProduct,
  fetchProduct,
  updateProduct,
  deleteProduct,
  loading,
  error,
} = useProducts(false)

// Initialize localized fields if they don't exist
const ensureLocalizedFields = product => {
  if (!product) return

  // Define fields that should have localization
  const localizedFields = ['name', 'description', 'category_name', 'subcategory_name']
  
  localizedFields.forEach(field => {
    if (!product[field]) {
      product[field] = {}
    }
    
    if (!product[field][locale.value]) {
      product[field][locale.value] = ''
    }
  })
  
  return product
}

// Watch for product data and ensure it has the correct structure
watch(fetchedProduct, newVal => {
  if (newVal) {
    ensureLocalizedFields(newVal)
  }
}, { immediate: true })

// Methods for product update and delete actions
const handleUpdate = async () => {
  try {
    // Ensure all localized fields exist before updating
    ensureLocalizedFields(fetchedProduct.value)
    
    await updateProduct(selectedProductId.value, fetchedProduct.value)
    showSnackbar("Product updated successfully", "success")
    originalProduct.value = JSON.parse(JSON.stringify(fetchedProduct.value))
  } catch (err) {
    console.error("Update error:", err)
    showSnackbar("Error updating product", "error")
  }
}

const handleDelete = async () => {
  try {
    await deleteProduct(selectedProductId.value)
    showSnackbar("Product deleted successfully", "success")
    router.push({ name: 'product-asset-suite-product-catalog-list' })
  } catch (err) {
    console.error("Delete error:", err)
    showSnackbar("Error deleting product", "error")
  }
}

// Lifecycle hook
onMounted(async () => {
  await fetchProduct(selectedProductId.value)
})

// Route leave handling
const originalProduct = ref(null)
const unsavedDialogVisible = ref(false)
const nextRoute = ref(null)

// Store original product data
watch(fetchedProduct, newVal => {
  if (newVal && !originalProduct.value) {
    // Ensure the product has all required fields before storing
    ensureLocalizedFields(newVal)
    originalProduct.value = JSON.parse(JSON.stringify(newVal))
  }
})

// Computed dirty flag
const isDirty = computed(() => {
  if (!originalProduct.value) return false
  
  return JSON.stringify(fetchedProduct.value) !== JSON.stringify(originalProduct.value)
})

// Navigation guard
onBeforeRouteLeave((to, from, next) => {
  if (isDirty.value) {
    unsavedDialogVisible.value = true
    next(false)
    nextRoute.value = to
  } else {
    next()
  }
})

// Dialog handlers
const confirmLeave = () => {
  originalProduct.value = JSON.parse(JSON.stringify(fetchedProduct.value))
  unsavedDialogVisible.value = false
  router.push(nextRoute.value.fullPath)
}

const cancelLeave = () => {
  unsavedDialogVisible.value = false
  nextRoute.value = null
}

// Helper to extract array from the response in case of nested data
const extractArray = response => {
  if (Array.isArray(response.data)) {
    return response.data
  }
  if (response.data && Array.isArray(response.data.data)) {
    return response.data.data
  }
  
  return []
}

// Fetch brands when the component is mounted
onMounted(async () => {
  try {
    const response = await settingStore.fetchBrands({ options: {} })
    const brandsArray = extractArray(response)
    if (Array.isArray(brandsArray)) {
      brands.value = brandsArray.map(brand => ({
        title: `${brand.name || ''}`,
        value: brand.name,
      }))
    } else {
      console.error('Unexpected response format:', response)
      brands.value = []
    }
  } catch (error) {
    console.error('Error fetching brands:', error)
    brands.value = []
  }
})
</script>

<template>
  <div>
    <!-- Header -->
    <div class="d-flex justify-space-between align-center flex-wrap gap-y-4 mb-6">
      <div>
        <h4 class="text-h4 mb-1">
          {{ $t('Product ID') }} #{{ fetchedProduct?.product_id || 'Loading...' }}
        </h4>
        <div class="text-body-1">
          {{ fetchedProduct?.created_at ? new Date(fetchedProduct.created_at).toLocaleDateString() : '' }}
        </div>
      </div>
      <div class="d-flex gap-4">
        <VBtn
          variant="tonal"
          color="primary"
          @click="handleUpdate"
        >
          {{ $t('Update Product') }}
        </VBtn>
        <VBtn
          variant="tonal"
          color="error"
          @click="isDeleteProductDialogVisible = true"
        >
          {{ $t('Delete Product') }}
        </VBtn>
      </div>
    </div>

    <VRow v-if="fetchedProduct">
      <ProductMainPanel
        v-model="fetchedProduct"
        :brands="brands"
      />
      <ProductSidePanel v-model="fetchedProduct" />
    </VRow>

    <div v-else>
      <VAlert
        type="error"
        variant="tonal"
      >
        {{ error || $t('Product not found!') }}
      </VAlert>
    </div>

    <!-- Dialogs -->
    <DeleteProductDialog
      v-model:isDialogVisible="isDeleteProductDialogVisible"
      @delete-product="handleDelete"
    />
    <UnsavedChangesDialog
      v-model="unsavedDialogVisible"
      :title="$t('Unsaved Changes')"
      :message="$t('You have unsaved changes. Do you really want to leave without saving?')"
      :confirm-text="$t('Leave')"
      :cancel-text="$t('Cancel')"
      @confirm="confirmLeave"
      @cancel="cancelLeave"
    />
    <LoadingDialog
      v-model="loading"
      :auto-close="false"
    />
    <VSnackbar
      v-model="snackbarStore.show"
      :color="snackbarStore.color"
      :timeout="snackbarStore.timeout"
      transition="scale-transition"
      variant="tonal"
    >
      <div style="text-align: center;">
        {{ snackbarStore.text }}
      </div>
    </VSnackbar>
  </div>
</template>
