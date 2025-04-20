<script setup>
import { useCategories } from '@/composables/product-asset-suite/useCategories'
import { useSnackbarStore } from '@/stores/useSnackbarStore'
import CategoryBioPanel from '@/views/product-asset-suite/categories/edit/CategoryBioPanel.vue'
import CategoryTabOverview from '@/views/product-asset-suite/categories/edit/CategoryTabOverview.vue'
import CategoryTabSubcategories from '@/views/product-asset-suite/categories/edit/CategoryTabSubcategories.vue'
import DeleteCategoryDialog from '@/views/product-asset-suite/categories/list/dialogs/DeleteCategoryDialog.vue'
import { useI18n } from 'vue-i18n'
import { onBeforeRouteLeave, useRoute, useRouter } from 'vue-router'


const snackbarStore = useSnackbarStore()
const route = useRoute()
const router = useRouter()
const { t } = useI18n()

// Use a dedicated ref for category ID and active tab index
const selectedCategoryId = ref(route.params.id)
const categoryTab = ref(0)
const isDeleteCategoryDialogVisible = ref(false)
const subcategoryData = ref([])


// Helper to show snackbar messages
const showSnackbar = (text, color) => {
  snackbarStore.show = true
  snackbarStore.text = text
  snackbarStore.color = color
}

// Tabs setup
const tabs = computed(() => [
  { title: t('Overview'), icon: 'tabler-layout-dashboard' },
  { title: t('Subcategories'), icon: 'tabler-book-2' },
])

// Destructure methods from the useCategories composable
const {
  fetchedCategory,
  fetchCategory,
  updateCategory,
  deleteCategory,
  fetchCategorySubcategories,
  fetchCategorySubcategory,
  addCategorySubcategory,
  updateCategorySubcategory,
  deleteCategorySubcategory,
  loading,
  error,
} = useCategories(false)

// Methods for category update and delete actions
const handleUpdate = async () => {
  try {
    await updateCategory(selectedCategoryId.value, fetchedCategory.value)
    showSnackbar("Category updated successfully", "success")

    // Update the original copy so the dirty flag becomes false.
    originalCategory.value = JSON.parse(JSON.stringify(fetchedCategory.value))
  } catch (err) {
    console.error("Update error:", err)
    showSnackbar("Error updating category", "error")
  }
}

const handleDelete = async () => {
  try {
    await deleteCategory(selectedCategoryId.value)
    showSnackbar("Category deleted successfully", "success")
    router.push({ name: 'product-asset-suite-category-framework-list' })
  } catch (err) {
    console.error("Delete error:", err)
    showSnackbar("Error deleting category", "error")
  }
}

// Refresh the subcategory data list
const refreshCategorySubcategories = async () => {
  try {
    subcategoryData.value = await fetchCategorySubcategories({ id: selectedCategoryId.value })
  } catch (err) {
    console.error("Error fetching subcategories:", err)
  }
}

// Handler for subcategory edits
const handleSubcategoryEdit = async subcategoryId => {
  await refreshCategorySubcategories()
  showSnackbar("Subcategory saved successfully", "success")
}

// Lifecycle hook
onMounted(async () => {
  await fetchCategory(selectedCategoryId.value)
  await refreshCategorySubcategories()
})

// on Before Route Leave 
const originalCategory = ref(null)
const unsavedDialogVisible = ref(false)
const nextRoute = ref(null)

// After the category is fetched, store a deep copy of the original data.
watch(fetchedCategory, newVal => {
  if (newVal && !originalCategory.value) {
    originalCategory.value = JSON.parse(JSON.stringify(newVal))
  }
})

// Computed dirty flag: compare current category with the original state.
const isDirty = computed(() => {
  // If no original data is stored, assume no changes.
  if (!originalCategory.value) return false
  
  return JSON.stringify(fetchedCategory.value) !== JSON.stringify(originalCategory.value)
})

// Use Vue Router's navigation guard.
onBeforeRouteLeave((to, from, next) => {
  if (isDirty.value) {
    unsavedDialogVisible.value = true
    next(false) // Prevent immediate navigation.
    nextRoute.value = to
  } else {
    next()
  }
})

// Handlers for the unsaved changes dialog.
const confirmLeave = () => {
  // Reset dirty flag by updating originalCategory to the current value
  originalCategory.value = JSON.parse(JSON.stringify(fetchedCategory.value))
  unsavedDialogVisible.value = false

  // Continue navigation manually.
  router.push(nextRoute.value.fullPath)
}

const cancelLeave = () => {
  unsavedDialogVisible.value = false
  nextRoute.value = null
}
</script>

<template>
  <div>
    <!-- Header -->
    <div class="d-flex justify-space-between align-center flex-wrap gap-y-4 mb-6">
      <div>
        <h4 class="text-h4 mb-1">
          {{ $t('Category ID') }} #{{ fetchedCategory?.category_id || 'Loading...' }}
        </h4>
        <div class="text-body-1">
          {{ fetchedCategory?.category_since }}
        </div>
      </div>
      <div class="d-flex gap-4">
        <VBtn
          variant="tonal"
          color="primary"
          @click="handleUpdate"
        >
          {{ $t('Update Category') }}
        </VBtn>
        <VBtn
          variant="tonal"
          color="error"
          @click="isDeleteCategoryDialogVisible = true"
        >
          {{ $t('Delete Category') }}
        </VBtn>
      </div>
    </div>

    <!-- Category Profile and Tabs -->
    <VRow v-if="fetchedCategory">
      <VCol
        cols="12"
        md="5"
        lg="4"
      >
        <CategoryBioPanel :fetched-category="fetchedCategory" />
      </VCol>
      <VCol
        cols="12"
        md="7"
        lg="8"
      >
        <VTabs
          v-model="categoryTab"
          class="v-tabs-pill mb-3 disable-tab-transition"
        >
          <VTab
            v-for="tab in tabs"
            :key="tab.title"
          >
            <VIcon
              size="20"
              start
              :icon="tab.icon"
            />
            {{ tab.title }}
          </VTab>
        </VTabs>
        <VWindow
          v-model="categoryTab"
          class="disable-tab-transition"
          :touch="false"
        >
          <VWindowItem>
            <CategoryTabOverview v-model="fetchedCategory" />
          </VWindowItem>
          <VWindowItem>
            <CategoryTabSubcategories
              v-model="fetchedCategory"
              :subcategory-data="subcategoryData"
              :add-category-subcategory="addCategorySubcategory"
              :update-category-subcategory="updateCategorySubcategory"
              :delete-category-subcategory="deleteCategorySubcategory"
              :fetch-category-subcategory="fetchCategorySubcategory"
              @edit-subcategory="handleSubcategoryEdit"
            />
          </VWindowItem>
        </VWindow>
      </VCol>
    </VRow>
    <div v-else>
      <VAlert
        type="error"
        variant="tonal"
      >
        {{ error || 'Category not found!' }}
      </VAlert>
    </div>

    <!-- Delete Dialog and Snackbar -->
    <DeleteCategoryDialog
      v-model:isDialogVisible="isDeleteCategoryDialogVisible"
      @delete-category="handleDelete"
    />
    <UnsavedChangesDialog
      v-model="unsavedDialogVisible"
      title="Unsaved Changes"
      message="You have unsaved changes. Do you really want to leave without saving?"
      confirm-text="Leave"
      cancel-text="Cancel"
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

<route lang="yaml">
meta:
  layout: default
  requiresAuth: true
  action: manage
  subject: ProductAssetSuite
</route>
