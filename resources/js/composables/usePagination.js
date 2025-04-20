export function usePagination(data, itemsPerPage) {
  const page = ref(1)
  const totalItems = computed(() => data.value.length)
  const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value))
    
  watch(() => data.value.length, newLength => {
    if (page.value > Math.ceil(newLength / itemsPerPage.value)) page.value = 1
  })
  
  const currentItems = computed(() => {
    const start = (page.value - 1) * itemsPerPage.value
    
    return data.value.slice(start, start + itemsPerPage.value)
  })
  
  return { page, totalPages, currentItems }
}
