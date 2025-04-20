import { defineStore } from 'pinia'

export const useSnackbarStore = defineStore('snackbar', {
  state: () => ({
    show: false,
    text: '',
    color: 'success',
    timeout: 3000,
  }),
  actions: {
    showMessage(text, color = 'success') {
      this.text = text
      this.color = color
      this.show = true
    },
    hideMessage() {
      this.show = false
    },
  },
})
