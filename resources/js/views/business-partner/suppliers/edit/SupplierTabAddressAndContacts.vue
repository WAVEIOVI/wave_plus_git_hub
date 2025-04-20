<script setup>
import { useTranslation } from '@/composables/useTranslation'
import {
  getAddressTypes,
  getContactStatus,
  getCountries,
  getDepartmentRoles,
  getDepartments,
} from '@/constants/filterOptions'
import { emailValidator, requiredValidator, urlValidator } from '@validators'

// Dialog components for contacts and addresses
import AddEditAddressDialog from './dialogs/AddEditAddressDialog.vue'
import AddEditContactDialog from './dialogs/AddEditContactDialog.vue'
import DeleteAddressDialog from './dialogs/DeleteAddressDialog.vue'
import DeleteContactDialog from './dialogs/DeleteContactDialog.vue'

// =====================================================
// PROPS & EMITS
// =====================================================
const props = defineProps({
  modelValue: { type: Object, required: true },
  contactData: { type: Array, required: true },
  addressData: { type: Array, required: true },
  addSupplierContact: { type: Function, required: false, default: () => {} },
  updateSupplierContact: { type: Function, required: false, default: () => {} },
  fetchSupplierContact: { type: Function, required: false, default: () => {} },
  deleteSupplierContact: { type: Function, required: false, default: () => {} },
  addSupplierAddress: { type: Function, required: false, default: () => {} },
  updateSupplierAddress: { type: Function, required: false, default: () => {} },
  fetchSupplierAddress: { type: Function, required: false, default: () => {} },
  deleteSupplierAddress: { type: Function, required: false, default: () => {} },
  readOnly: {
    type: Boolean,
    default: false,
  },
})

const emits = defineEmits([
  'update:modelValue',
  'editContact',
  'editAddress',
])

// =====================================================
// REACTIVE STATE & TWO-WAY BINDING
// =====================================================
// Two-way binding for supplier data
const supplier = computed({
  get: () => props.modelValue,
  set: value => emits('update:modelValue', value),
})

// Pagination state for contacts and addresses
const contactPage = ref(1)
const addressPage = ref(1)
const itemsPerPage = ref(3)

// =====================================================
// COMPUTED PROPERTIES
// =====================================================
// Contacts pagination
const totalContactItems = computed(() => props.contactData.length)

const totalContactPages = computed(() =>
  Math.ceil(totalContactItems.value / itemsPerPage.value),
)

const currentContacts = computed(() => {
  const start = (contactPage.value - 1) * itemsPerPage.value
  
  return props.contactData.slice(start, start + itemsPerPage.value)
})

// Addresses pagination
const totalAddressItems = computed(() => props.addressData.length)

const totalAddressPages = computed(() =>
  Math.ceil(totalAddressItems.value / itemsPerPage.value),
)

const currentAddresses = computed(() => {
  const start = (addressPage.value - 1) * itemsPerPage.value
  
  return props.addressData.slice(start, start + itemsPerPage.value)
})

// Countries & Filter Options
const countries = computed(() => getCountries())
const contactStatusList = computed(() => getContactStatus())
const addressTypeList = computed(() => getAddressTypes())
const departmentsList = computed(() => getDepartments())
const departmentRolesList = computed(() => getDepartmentRoles())

// =====================================================
// WATCHERS
// =====================================================
// Reset contact pagination if needed
watch(
  () => props.contactData.length,
  newLength => {
    if (contactPage.value > Math.ceil(newLength / itemsPerPage.value)) {
      contactPage.value = 1
    }
  },
)

// Reset address pagination if needed
watch(
  () => props.addressData.length,
  newLength => {
    if (addressPage.value > Math.ceil(newLength / itemsPerPage.value)) {
      addressPage.value = 1
    }
  },
)

// =====================================================
// DIALOG & ITEM MANAGEMENT (CONTACTS & ADDRESSES)
// =====================================================
// CONTACT DIALOGS & STATE
const isContactDialogVisible = ref(false)
const isDeleteContactDialogVisible = ref(false)
const currentContact = ref(null)
const contactToDelete = ref(null)

// ADDRESS DIALOGS & STATE
const isAddressDialogVisible = ref(false)
const isDeleteAddressDialogVisible = ref(false)
const currentAddress = ref(null)
const addressToDelete = ref(null)

// Shared edit mode flag
const isEditMode = ref(false)

// =====================================================
// HELPER FUNCTIONS
// =====================================================
// Format work team information
const getFormattedWorkTeam = workTeam => {
  try {
    const teamArray = JSON.parse(workTeam)
    
    return Array.isArray(teamArray) && teamArray.length > 0
      ? teamArray.join(', ')
      : ''
  } catch (e) {
    return workTeam // Return original value if parsing fails
  }
}

// Contact status variant resolver
const resolveContactStatusVariant = status => {
  if (!status) return 'secondary'

  const variants = {
    active: 'success',
    inactive: 'warning',
    resigned: 'info',
    retired: 'info',
    suspended: 'error',
    terminated: 'error',
  }

  
  return variants[status.toLowerCase()] || 'secondary'
}

// Address type variant resolver
const resolveAddressTypeVariant = type => {
  if (!type) return 'secondary'

  const variants = {
    billing: 'primary',
    shipping: 'success',
    office: 'info',
  }

  
  return variants[type.toLowerCase()] || 'secondary'
}

// Translation helper using shared logic
const { translateValue } = useTranslation()
const translateContactStatus = status => translateValue(contactStatusList, status)
const translateDepartment = department => translateValue(departmentsList, department)
const translateAddressType = type => translateValue(addressTypeList, type)
const translateCountry = country => translateValue(countries, country)

// Translate department role using department roles list
const translateDepartmentRole = (department, role) => {
  const departmentObj = departmentRolesList.value.find(
    dept =>
      dept.department === department ||
      dept.department === translateDepartment(department),
  )

  if (!departmentObj) return role
  const roleObj = departmentObj.jobTitles.find(job => job.value === role)
  
  return roleObj ? roleObj.title : role
}

// =====================================================
// EMPTY OBJECTS FOR INITIALIZATION
// =====================================================
const emptyContact = {
  first_name: '',
  last_name: '',
  job_title: '',
  department: '',
  phone: '',
  email: '',
  linkedin_url: '',
  status: 'Active',
}

const emptyAddress = {
  type: '',
  address_street: '',
  city: '',
  postcode: '',
  country: '',
  phone: '',
  email: '',
  notes: '',
  responsible: '',
  nearest_fire_station: '',
  work_team: '',
}

// =====================================================
// CONTACT METHODS
// =====================================================
const handleAddContact = () => {
  if (props.readOnly) return
  currentContact.value = { ...emptyContact }
  isEditMode.value = false
  isContactDialogVisible.value = true
}

const handleEditContact = contact => {
  if (props.readOnly) return
  currentContact.value = { ...contact }
  isEditMode.value = true
  isContactDialogVisible.value = true
}

const handleSubmitContact = async contactDataInput => {
  try {
    if (isEditMode.value && contactDataInput.id) {
      await props.updateSupplierContact(supplier.value.id, contactDataInput.id, contactDataInput)
    } else {
      await props.addSupplierContact(supplier.value.id, contactDataInput)
    }
    emits('editContact', contactDataInput.id)
  } catch (error) {
    console.error('Error saving contact:', error)
  }
}

const handleDeleteContact = async contactId => {
  try {
    await props.deleteSupplierContact(supplier.value.id, contactId)
    emits('editContact', contactId)
    isDeleteContactDialogVisible.value = false
  } catch (error) {
    console.error('Error deleting contact:', error)
  }
}

const openContactDeleteDialog = contact => {
  contactToDelete.value = contact.id
  isDeleteContactDialogVisible.value = true
}

// =====================================================
// ADDRESS METHODS
// =====================================================
const handleAddAddress = () => {
  currentAddress.value = { ...emptyAddress }
  isEditMode.value = false
  isAddressDialogVisible.value = true
}

const handleEditAddress = address => {
  currentAddress.value = { ...address }
  isEditMode.value = true
  isAddressDialogVisible.value = true
}

const handleSubmitAddress = async addressDataInput => {
  try {
    if (isEditMode.value && addressDataInput.id) {
      await props.updateSupplierAddress(supplier.value.id, addressDataInput.id, addressDataInput)
    } else {
      await props.addSupplierAddress(supplier.value.id, addressDataInput)
    }
    emits('editAddress', addressDataInput.id)
  } catch (error) {
    console.error('Error saving address:', error)
  }
}

const handleDeleteAddress = async addressId => {
  try {
    await props.deleteSupplierAddress(supplier.value.id, addressId)
    emits('editAddress', addressId)
    isDeleteAddressDialogVisible.value = false
  } catch (error) {
    console.error('Error deleting address:', error)
  }
}

const openAddressDeleteDialog = address => {
  addressToDelete.value = address.id
  isDeleteAddressDialogVisible.value = true
}
</script>

<template>
  <!--
    =====================================================
    SUPPLIER DETAILS SECTION
    ====================================================== 
  -->
  <VCard class="mb-6">
    <VCardText>
      <h5 class="text-h5">
        {{ $t('Location & Contact Details') }}
      </h5>
      <VDivider class="my-4" />
      <VRow>
        <VCol
          md="4"
          cols="12"
        >
          <VTextField
            v-model="supplier.primary_phone"
            :label="$t('Primary Phone')"
            prepend-icon="tabler-phone"
            :readonly="readOnly"
            :variant="readOnly ? 'plain' : 'outlined'"
          />
        </VCol>
        <VCol
          md="4"
          cols="12"
        >
          <VTextField
            v-model="supplier.primary_email"
            :label="$t('Primary Email')"
            prepend-icon="tabler-mail"
            :readonly="readOnly"
            :variant="readOnly ? 'plain' : 'outlined'"
            :rules="[emailValidator]"
          />
        </VCol>
        <VCol
          md="4"
          cols="12"
        >
          <VTextField
            v-model="supplier.website"
            :label="$t('Website')"
            prepend-icon="tabler-link"
            :readonly="readOnly"
            :variant="readOnly ? 'plain' : 'outlined'"
            :rules="[urlValidator]"
          />
        </VCol>
        <VCol cols="12">
          <VTextField
            v-model="supplier.hq_address"
            :label="$t('Address')"
            prepend-icon="tabler-map-pin"
            :readonly="readOnly"
            :variant="readOnly ? 'plain' : 'outlined'"
          />
        </VCol>
        <VCol
          md="4"
          cols="12"
        >
          <VTextField
            v-model="supplier.hq_city"
            :label="$t('City')"
            prepend-icon="tabler-building-community"
            :readonly="readOnly"
            :variant="readOnly ? 'plain' : 'outlined'"
          />
        </VCol>
        <VCol
          md="4"
          cols="12"
        >
          <VTextField
            v-model="supplier.hq_postcode"
            :label="$t('Postcode')"
            prepend-icon="tabler-mailbox"
            :readonly="readOnly"
            :variant="readOnly ? 'plain' : 'outlined'"
            :rules="[requiredValidator]"
          />
        </VCol>
        <VCol
          md="4"
          cols="12"
        >
          <VAutocomplete
            v-model="supplier.hq_country"
            :label="$t('Country')"
            :items="countries"
            prepend-icon="tabler-flag"
            :readonly="readOnly"
            :variant="readOnly ? 'plain' : 'outlined'"
            :clearable="!readOnly"
            :clear-icon="!readOnly ? 'tabler-x' : ''"
            :append-inner-icon="readOnly ? '' : 'tabler-chevron-down'"
          />
        </VCol>
      </VRow>
    </VCardText>
  </VCard>

  <!--
    =====================================================
    CONTACT BOOK SECTION
    ====================================================== 
  -->
  <VCard class="mb-6">
    <VCardText>
      <div class="d-flex justify-space-between mb-6 flex-wrap align-center gap-y-4 gap-x-6">
        <h5 class="text-h5">
          {{ $t('Contact Book') }}
        </h5>
        <VBtn
          v-if="!readOnly"
          variant="tonal"
          size="small"
          @click="handleAddContact"
        >
          {{ $t('Add New Contact') }}
        </VBtn>
      </div>
      <VExpansionPanels variant="inset">
        <VExpansionPanel
          v-for="(contact, index) in currentContacts"
          :key="contact.id || index"
        >
          <!-- CONTACT HEADER -->
          <VExpansionPanelTitle>
            <div class="d-flex align-center gap-x-4 w-100">
              <div class="d-flex flex-grow-1">
                <div class="d-flex align-center gap-x-2 mb-1">
                  <h6 class="text-h6">
                    {{ contact.first_name }} {{ contact.last_name }}
                  </h6>
                  <VChip
                    :color="resolveContactStatusVariant(contact.status)"
                    size="small"
                    label
                    class="text-capitalize"
                  >
                    {{ translateContactStatus(contact.status) }}
                  </VChip>
                </div>
              </div>
              <!-- Action Buttons -->
              <div
                v-if="!readOnly"
                class="d-flex align-center"
              >
                <IconBtn @click.stop="handleEditContact(contact)">
                  <VIcon
                    icon="tabler-edit"
                    class="flip-in-rtl"
                  />
                </IconBtn>
                <IconBtn @click.stop="openContactDeleteDialog(contact)">
                  <VIcon
                    icon="tabler-trash"
                    class="flip-in-rtl"
                  />
                </IconBtn>
              </div>
            </div>
          </VExpansionPanelTitle>
          <!-- CONTACT DETAILS -->
          <VExpansionPanelText>
            <VRow>
              <VCol cols="6">
                <h6 class="mb-2">
                  {{ $t('Contact Details') }}
                </h6>
                <div class="text-body-1 whitespace-pre-line">
                  <div v-if="contact.email">
                    <strong>{{ $t('Email') }}:</strong> {{ contact.email }}
                  </div>
                  <div v-if="contact.phone">
                    <strong>{{ $t('Phone') }}:</strong> {{ contact.phone }}
                  </div>
                  <div v-if="contact.linkedin_url">
                    <strong>LinkedIn:</strong> {{ contact.linkedin_url }}
                  </div>
                </div>
              </VCol>
              <VCol cols="6">
                <h6 class="mb-2">
                  {{ $t('Professional Details') }}
                </h6>
                <div class="text-body-1 whitespace-pre-line">
                  <div v-if="contact.department">
                    <strong>{{ $t('Department') }}:</strong> {{ translateDepartment(contact.department) }}
                  </div>
                  <div v-if="contact.job_title">
                    <strong>{{ $t('Job Title') }}:</strong> {{ translateDepartmentRole(contact.department, contact.job_title) }}
                  </div>
                </div>
              </VCol>
            </VRow>
          </VExpansionPanelText>
        </VExpansionPanel>
      </VExpansionPanels>
      <div class="d-flex justify-center mt-6">
        <VPagination
          v-if="totalContactPages > 0"
          v-model="contactPage"
          size="small"
          :length="totalContactPages"
          :total-visible="5"
        />
      </div>
    </VCardText>
  </VCard>

  <!--
    =====================================================
    ADDRESS BOOK SECTION
    ====================================================== 
  -->
  <VCard class="mb-6">
    <VCardText>
      <div class="d-flex justify-space-between mb-6 flex-wrap align-center gap-y-4 gap-x-6">
        <h5 class="text-h5">
          {{ $t('Address Book') }}
        </h5>
        <VBtn
          v-if="!readOnly"
          variant="tonal"
          size="small"
          @click="handleAddAddress"
        >
          {{ $t('Add New Address') }}
        </VBtn>
      </div>
      <VExpansionPanels variant="inset">
        <VExpansionPanel
          v-for="(address, index) in currentAddresses"
          :key="address.id || index"
        >
          <!-- ADDRESS HEADER -->
          <VExpansionPanelTitle>
            <div class="d-flex align-center gap-x-4 w-100">
              <div class="d-flex flex-grow-1">
                <div class="d-flex align-center gap-x-2 mb-1">
                  <h6 class="text-h6">
                    {{ address.address_street }} {{ address.postcode }} {{ address.city }}
                  </h6>
                  <VChip
                    :color="resolveAddressTypeVariant(address.type)"
                    size="small"
                    label
                    class="text-capitalize"
                  >
                    {{ translateAddressType(address.type) }}
                  </VChip>
                </div>
              </div>
              <!-- Action Buttons -->
              <div
                v-if="!readOnly"
                class="d-flex align-center"
              >
                <IconBtn @click.stop="handleEditAddress(address)">
                  <VIcon
                    icon="tabler-edit"
                    class="flip-in-rtl"
                  />
                </IconBtn>
                <IconBtn @click.stop="openAddressDeleteDialog(address)">
                  <VIcon
                    icon="tabler-trash"
                    class="flip-in-rtl"
                  />
                </IconBtn>
              </div>
            </div>
          </VExpansionPanelTitle>
          <!-- ADDRESS DETAILS -->
          <VExpansionPanelText>
            <VRow>
              <VCol cols="6">
                <h6 class="mb-2">
                  {{ $t('Contact Details') }}
                </h6>
                <div class="text-body-1 whitespace-pre-line">
                  <div v-if="address.email">
                    <strong>{{ $t('Email') }}:</strong> {{ address.email }}
                  </div>
                  <div v-if="address.phone">
                    <strong>{{ $t('Phone') }}:</strong> {{ address.phone }}
                  </div>
                  <div v-if="address.country">
                    <strong>{{ $t('Country') }}:</strong> {{ translateCountry(address.country) }}
                  </div>
                </div>
              </VCol>
              <VCol cols="6">
                <h6 class="mb-2">
                  {{ $t('Team') }}
                </h6>
                <div v-if="address.responsible">
                  <strong>{{ $t('Responsible') }}:</strong> {{ address.responsible }}
                </div>
                <div v-if="address.work_team">
                  <strong>{{ $t('Work Team') }}:</strong> {{ getFormattedWorkTeam(address.work_team) }}
                </div>
              </VCol>
              <VCol cols="12">
                <div v-if="address.notes">
                  <strong>{{ $t('Notes') }}:</strong> {{ address.notes }}
                </div>
              </VCol>
            </VRow>
          </VExpansionPanelText>
        </VExpansionPanel>
      </VExpansionPanels>
      <div class="d-flex justify-center mt-6">
        <VPagination
          v-if="totalAddressPages > 0"
          v-model="addressPage"
          size="small"
          :length="totalAddressPages"
          :total-visible="5"
        />
      </div>
    </VCardText>
  </VCard>

  <!--
    =====================================================
    DIALOG COMPONENTS (ADD/EDIT & DELETE)
    ====================================================== 
  -->
  <AddEditContactDialog
    v-model:is-dialog-visible="isContactDialogVisible"
    :contact-data="currentContact"
    :is-edit-mode="isEditMode"
    @submit="handleSubmitContact"
  />
  <DeleteContactDialog
    v-model:is-dialog-visible="isDeleteContactDialogVisible"
    :contact-id="contactToDelete"
    @delete-contact="handleDeleteContact"
  />
  <AddEditAddressDialog
    v-model:is-dialog-visible="isAddressDialogVisible"
    :address-data="currentAddress"
    :is-edit-mode="isEditMode"
    :contact-data="props.contactData"
    @submit="handleSubmitAddress"
  />
  <DeleteAddressDialog
    v-model:is-dialog-visible="isDeleteAddressDialogVisible"
    :address-id="addressToDelete"
    @delete-address="handleDeleteAddress"
  />
</template>
