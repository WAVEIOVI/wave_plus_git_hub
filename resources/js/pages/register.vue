<script setup>
import axios from '@/plugins/axios'
import { getRoleAbilities } from '@/plugins/casl/ability'
import router from '@/router'
import { useAuthStore } from '@/stores/auth'
import AuthProvider from '@/views/authentication/AuthProvider.vue'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import { ref } from 'vue'

// Image imports (adjust paths as needed)

const authStore = useAuthStore()

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',

  // Choose a role on registration; default is Client here.
  role: 'Client',
  privacyPolicies: false,
})

const loading = ref(false)
const errors = ref('')
const isPasswordVisible = ref(false)

async function register() {
  loading.value = true
  errors.value = ''
  try {
    // (Optional) End any existing session
    await axios.post('/logout')
    await axios.get('/sanctum/csrf-cookie')

    // Post registration data (adjust payload as needed)
    const { data } = await axios.post('/register', form.value)

    // Assume the API returns data.user with role_names
    const roles = data.user.role_names || [form.value.role]
    const abilities = getRoleAbilities(roles)

    // Update CASL ability and store user data/abilities
    import('@/plugins/casl/ability').then(({ default: ability }) => {
      ability.update(abilities)
    })
    authStore.setUser(data.user)
    authStore.setAbilities(abilities)

    // Redirect to root (which will then redirect based on role)
    router.push("/")
  } catch (err) {
    errors.value = err.response?.data?.errors
      ? Object.values(err.response.data.errors).flat().join(' ')
      : 'Registration failed. Please check your inputs.'
    if (err.response?.status === 422) {
      form.value.password = ''
      form.value.password_confirmation = ''
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <VRow
    no-gutters
    class="auth-wrapper bg-surface"
  >
    <VCol
      md="8"
      class="d-none d-md-flex"
    >
      <div class="position-relative bg-background rounded-lg w-100 ma-8 me-0">
        <div class="d-flex align-center justify-center w-100 h-100">
          <VImg
            max-width="441"
            :src="imageVariant"
            class="auth-illustration mt-16 mb-2"
          />
        </div>

        <VImg
          class="auth-footer-mask"
          :src="authThemeMask"
        />
      </div>
    </VCol>

    <VCol
      cols="12"
      md="4"
      class="auth-card-v2  d-flex align-center justify-center"
    >
      <VCard
        flat
        :max-width="500"
        class="mt-12 mt-sm-0 pa-4"
      >
        <VCardText>
          <VNodeRenderer
            :nodes="themeConfig.app.logo"
            class="mb-6"
          />
          <h5 class="text-h5 mb-1">
            Adventure starts here 🚀
          </h5>
          <p class="mb-0">
            Make your app management easy and fun!
          </p>
        </VCardText>

        <VCardText>
          <VForm @submit.prevent="register">
            <VRow>
              <!-- Username -->
              <VCol cols="12">
                <AppTextField
                  v-model="form.name"
                  autofocus
                  label="Username"
                />
              </VCol>

              <!-- email -->
              <VCol cols="12">
                <AppTextField
                  v-model="form.email"
                  label="Email"
                  type="email"
                />
              </VCol>
              <VCol cols="12">
                <VSelect
                  v-model="form.role"
                  :items="['Super Admin', 'Admin', 'Operations Manager', 'Finance Manager', 'Technician', 'Client', 'Group Client']"
                  label="Account Type"
                  required
                />
              </VCol>
              <!-- password -->
              <VCol cols="12">
                <AppTextField
                  v-model="form.password"
                  label="Password"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />
              </VCol>
              <!-- password_confirmation -->
              <VCol cols="12">
                <AppTextField
                  v-model="form.password_confirmation"
                  label="Password Confirmation"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />


                <div class="d-flex align-center mt-2 mb-4">
                  <VCheckbox
                    id="privacy-policy"
                    v-model="form.privacyPolicies"
                    inline
                  />
                  <VLabel
                    for="privacy-policy"
                    style="opacity: 1;"
                  >
                    <span class="me-1">I agree to</span>
                    <a
                      href="javascript:void(0)"
                      class="text-primary"
                    >privacy policy & terms</a>
                  </VLabel>
                </div>

                <VBtn
                  block
                  type="submit"
                  :disabled="loading"
                >
                  Sign up
                </VBtn>
              </VCol>

              <!-- create account -->
              <VCol
                cols="12"
                class="text-center text-base"
              >
                <span>Already have an account?</span>
                <RouterLink
                  class="text-primary ms-2"
                  :to="{ name: 'login' }"
                >
                  Sign in instead
                </RouterLink>
              </VCol>

              <VCol
                cols="12"
                class="d-flex align-center"
              >
                <VDivider />
                <span class="mx-4">or</span>
                <VDivider />
              </VCol>

              <!-- auth providers -->
              <VCol
                cols="12"
                class="text-center"
              >
                <AuthProvider />
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>

<route lang="yaml">
meta:
  layout: blank
</route>
