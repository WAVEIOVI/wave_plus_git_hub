import axios from '@/plugins/axios'
import ability, { getRoleAbilities, initialAbility } from '@/plugins/casl/ability'
import router from '@/router'
import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('userData')) || null,
    abilities: JSON.parse(localStorage.getItem('userAbilities')) || initialAbility,
  }),
  actions: {
    setUser(user) {
      this.user = user
      localStorage.setItem('userData', JSON.stringify(user))
    },
    setAbilities(abilities) {
      this.abilities = abilities
      localStorage.setItem('userAbilities', JSON.stringify(abilities))
    },
    updateAbilities(roles) {
      const newAbilities = getRoleAbilities(roles)

      ability.update(newAbilities)
      this.abilities = newAbilities
      localStorage.setItem('userAbilities', JSON.stringify(newAbilities))
    },
    async logout() {
      try {
        await axios.post('/logout')
        ability.update(initialAbility) // Reset ability to default
        this.user = null
        this.abilities = initialAbility
        localStorage.removeItem('userData')
        localStorage.removeItem('userAbilities')
        router.push({ name: 'login' })
      } catch (error) {
        console.error('Logout failed:', error)
      }
    },
  },
})
