<script setup>
import { getContactStatus, getDepartmentRoles, getDepartments } from '@/constants/filterOptions'
import i18n from "@/plugins/i18n"
import {
  emailValidator,
  requiredValidator,
  urlValidator,
} from '@validators'


const props = defineProps({
  contactData: {
    type: Object,
    required: false,
    default: () => ({
      first_name: '',
      last_name: '',
      job_title: '',
      department: '',
      phone: '',
      email: '',
      linkedin_url: '',
      status: '',
    }),
  },
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  isEditMode: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'submit',
])

const formData = ref({ ...props.contactData })

// Watch for changes in contactData prop to update the local form data
watch(() => props.contactData, newData => {
  if (newData) {
    formData.value = { ...newData }
  }
}, { deep: true, immediate: true })

// Watch for dialog visibility changes to reset form when closed
watch(() => props.isDialogVisible, isVisible => {
  if (isVisible && props.contactData) {
    formData.value = { ...props.contactData }
  }
})

const contactStatus = computed(() => getContactStatus())

// Load the static options
const departments = getDepartments()
const departmentRoles = getDepartmentRoles()

// Computed property that filters the job titles based on the selected department
const filteredJobTitles = computed(() => {
  // If no department is selected, return an empty list (or you could return all job titles)
  if (!formData.value.department) return []

  // Find the selected department object by matching the 'value'
  const selectedDept = departments.find(dept => dept.value === formData.value.department)
  if (!selectedDept) return []

  // Find the roles group by matching the department title
  const rolesGroup = departmentRoles.find(group => group.department === selectedDept.title)
  
  return rolesGroup ? rolesGroup.jobTitles : []
})

const resetForm = () => {
  emit('update:isDialogVisible', false)
  formData.value = { ...props.contactData }
}

const onFormSubmit = () => {
  emit('submit', formData.value)
  emit('update:isDialogVisible', false)
}

const dialogTitle = computed(() => {
  return props.isEditMode ? i18n.global.t('Edit Contact') : i18n.global.t('Add New Contact')
})

const dialogSubtitle = computed(() => {
  return props.isEditMode 
    ? `${i18n.global.t('Editing')} ${formData.value.first_name} ${formData.value.last_name}`
    : `${i18n.global.t('Add Contact Information For This Supplier')}`
})
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 610 "
    :model-value="props.isDialogVisible"
    @update:model-value="val => $emit('update:isDialogVisible', val)"
  >
    <!-- 👉 Dialog close btn -->
    <DialogCloseBtn @click="$emit('update:isDialogVisible', false)" />

    <VCard class="pa-sm-8 pa-5">
      <!-- 👉 Title -->
      <VCardItem>
        <VCardTitle class="text-h4 text-center">
          {{ dialogTitle }}
        </VCardTitle>
      </VCardItem>

      <VCardText>
        <!-- 👉 Subtitle -->
        <VCardSubtitle class="text-center mb-6">
          <span class="text-base">
            {{ dialogSubtitle }}
          </span>
        </VCardSubtitle>

        <!-- 👉 Form -->
        <VForm
          class="mt-4"
          @submit.prevent="onFormSubmit"
        >
          <VRow>
            <!-- 👉 First Name -->
            <VCol
              cols="12"
              md="6"
            >
              <AppTextField
                v-model="formData.first_name"
                :label="$t('First Name')"
                :rules="[requiredValidator]"
              />
            </VCol>
            <!-- 👉 Last Name -->
            <VCol
              cols="12"
              md="6"
            >
              <AppTextField
                v-model="formData.last_name"
                :label="$t('Last Name')"
                
                :rules="[requiredValidator]"
              />
            </VCol>

            <!-- 👉 Email -->
            <VCol
              cols="12"
              md="6"
            >
              <AppTextField
                v-model="formData.email"
                :label="$t('Email')"
                :rules="[emailValidator]"
              />
            </VCol>

            <!-- 👉 phone -->
            <VCol
              cols="12"
              md="6"
            >
              <AppTextField
                v-model="formData.phone"
                :label="$t('Phone')"
              />
            </VCol>

            <!-- 👉 Department -->
            <VCol
              cols="12"
              md="6"
            >
              <AppSelect
                v-model="formData.department"
                :label="$t('Department')"
                density="compact"
                :items="departments"
              />
            </VCol>

            <!-- 👉 Job Title -->
            <VCol
              cols="12"
              md="6"
            >
              <AppSelect
                v-model="formData.job_title"
                :label="$t('Job Title')"
                density="compact"
                :items="filteredJobTitles"
              />
            </VCol>

            <!-- 👉 Linkedin -->
            <VCol
              cols="12"
              md="6"
            >
              <AppTextField
                v-model="formData.linkedin_url"
                label="Linkedin"
                :rules="[urlValidator]"
              />
            </VCol>

            <!-- 👉 Status -->
            <VCol
              cols="12"
              md="6"
            >
              <AppSelect
                v-model="formData.status"
                :label="$t('Status')"
                density="compact"
                :items="contactStatus"
                :rules="[requiredValidator]"
              />
            </VCol>
            
            <!-- 👉 Submit and Cancel button -->
            <VCol
              cols="12"
              class="text-center"
            >
              <VBtn
                type="submit"
                class="me-3"
              >
                {{ isEditMode ? $t('Update') : $t('Submit') }}
              </VBtn>
               
              <VBtn
                variant="tonal"
                color="secondary"
                @click="resetForm"
              >
                {{ $t('Discard') }}
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
