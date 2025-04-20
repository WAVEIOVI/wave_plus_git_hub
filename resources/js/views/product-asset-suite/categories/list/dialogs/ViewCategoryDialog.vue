<script setup>
import { useCategories } from '@/composables/product-asset-suite/useCategories'
import CategoryBioPanel from '@/views/product-asset-suite/categories/edit/CategoryBioPanel.vue'
import CategoryTabOverview from '@/views/product-asset-suite/categories/edit/CategoryTabOverview.vue'
import CategoryTabSubcategories from '@/views/product-asset-suite/categories/edit/CategoryTabSubcategories.vue'
import { useI18n } from 'vue-i18n'


const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  fetchedCategory: {
    type: Object,
    default: () => ({}),
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
])

const { t } = useI18n()

const categoryTab = ref(0)
const subcategoryData = ref([])

// Tabs configuration
const tabs = computed(() => [
  { title: t('Overview'), icon: 'tabler-layout-dashboard' },
  { title: t('Subcategories'), icon: 'tabler-book-2' },
])

// Fetch subcategories
const { fetchCategorySubcategories } = useCategories(false)

const loadAdditionalData = async () => {
  try {
    if (props.fetchedCategory?.id) {
      subcategoryData.value = await fetchCategorySubcategories({ id: props.fetchedCategory.id })
    }

  } catch (error) {
    console.error('Error loading subcategories:', error)
  }
}

onMounted(async () => {
  await loadAdditionalData()
})

const closeDialog = () => {
  emit('update:isDialogVisible', false)
}

watch(
  () => props.fetchedCategory,
  newCategory => {
    if (newCategory && newCategory.category_id) {
      loadAdditionalData()
    }
  },
  { immediate: true },
)
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
            {{ $t('Category View') }} #{{ fetchedCategory?.category_id }}
          </h4>
          <div>
            <div class="d-flex gap-4">
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
              class="v-tabs-pill"
            >
              <VTab
                v-for="tab in tabs"
                :key="tab.title"
              >
                <VIcon
                  :icon="tab.icon"
                  size="20"
                  class="me-2"
                />
                {{ tab.title }}
              </VTab>
            </VTabs>

            <VWindow
              v-model="categoryTab"
              class="mt-4"
            >              
              <VWindowItem>
                <CategoryTabOverview
                  :model-value="fetchedCategory"
                  read-only
                />
              </VWindowItem>
              <VWindowItem>
                <CategoryTabSubcategories
                  :model-value="fetchedCategory"
                  :subcategory-data="subcategoryData"
                  read-only
                />
              </VWindowItem>
            </VWindow>
          </VCol>
        </VRow>
      </VCardText>
    </VCard>
  </VDialog>
</template>
