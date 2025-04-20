<script setup>
import { getAddressTypes, getCountries, getFireStations } from '@/constants/filterOptions'
import i18n from "@/plugins/i18n"
import {
  emailValidator,
  requiredValidator,
} from '@validators'


const props = defineProps({
  addressData: {
    type: Object,
    required: false,
    default: () => ({
      type: '',
      address_street: '',
      city: '',
      postcode: '',
      country: '',
      phone: '',
      email: '',
      notes: '',
      nearest_fire_station: '',
      responsible: '',
      work_team: '',
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
  contactData: {
    type: Array,
    required: true,
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'submit',
])

const formData = ref({ ...props.addressData })

// Watch for changes in addressData prop to update the local form data
watch(() => props.addressData, newData => {
  if (newData) {
    formData.value = { ...newData }
  }
}, { deep: true, immediate: true })

// Watch for dialog visibility changes to reset form when closed
watch(() => props.isDialogVisible, isVisible => {
  if (isVisible && props.addressData) {
    formData.value = { ...props.addressData }
  }
})

const countries = computed(() => getCountries())
const addressTypes = computed(() => getAddressTypes())
const fireStations = computed(() => getFireStations())

const resetForm = () => {
  emit('update:isDialogVisible', false)
  formData.value = { ...props.addressData }
}

const onFormSubmit = () => {
  emit('submit', formData.value)
  emit('update:isDialogVisible', false)
}

const dialogTitle = computed(() => {
  return props.isEditMode ? i18n.global.t('Edit Address') : i18n.global.t('Add New Address')
})

const dialogSubtitle = computed(() => {
  return props.isEditMode 
    ? `${i18n.global.t('Editing')} ${formData.value.type}`
    : `${i18n.global.t('Add Address Information For This Client')}`
})

const formattedWorkTeam = computed({
  get: () => {
    try {
      return JSON.parse(formData.value.work_team) || []
    } catch (e) {
      return []
    }
  },
  set: value => {
    formData.value.work_team = JSON.stringify(value)
  },
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
            <!-- 👉 Address Type -->
            <VCol
              cols="12"
              md="6"
            >
              <AppSelect
                v-model="formData.type"
                :label="$t('Address Type')"
                :rules="[requiredValidator]"
                :items="addressTypes"
              />
            </VCol>

            <!-- 👉 Responsible -->
            <VCol
              cols="12"
              md="6"
            >
              <AppSelect
                v-model="formData.responsible"
                :label="$t('Responsible')"
                density="compact"
                :items="props.contactData.map(contact => ({
                  text: `${contact.first_name} ${contact.last_name}`,
                  value: `${contact.first_name} ${contact.last_name}`,
                })).map(item => item.text)"
              />
            </VCol>

            <!-- 👉 Address Street -->
            <VCol cols="12">
              <AppTextField
                v-model="formData.address_street"
                :label="$t('Address Street')"
                :rules="[requiredValidator]"
              />
            </VCol>
            <!-- 👉 Postcode -->
            <VCol 
              cols="12"
              md="4"
            >
              <AppTextField
                v-model="formData.postcode"
                :label="$t('Postcode')"
              />
            </VCol>

            <!-- 👉 City -->
            <VCol 
              cols="12"
              md="4"
            >
              <AppTextField
                v-model="formData.city"
                :label="$t('City')"
              />
            </VCol>

            <!-- 👉 Country -->
            <VCol
              cols="12"
              md="4"
            >
              <AppSelect
                v-model="formData.country"
                :label="$t('Country')"
                density="compact"
                :items="countries"
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

            <!-- 👉 Work Team -->
            <VCol cols="12">
              <AppSelect
                v-model="formattedWorkTeam"
                multiple
                :label="$t('Work Team')"
                density="compact"
                closable-chips
                chips
                :items="props.contactData.map(contact => ({
                  text: `${contact.first_name} ${contact.last_name}`,
                  value: `${contact.first_name} ${contact.last_name}`,
                })).map(item => item.text)"
              />
            </VCol>
            <!-- 👉 nearest fire station -->
            <VCol cols="12">
              <AppSelect
                v-model="formData.nearest_fire_station"
                :label="$t('Nearest Fire Station')"
                :rules="[requiredValidator]"
                :items="fireStations"
              />
            </VCol>

            <!-- 👉 Notes -->
            <VCol cols="12">
              <AppTextarea
                v-model="formData.notes"
                :label="$t('Notes')"
                auto-grow
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
