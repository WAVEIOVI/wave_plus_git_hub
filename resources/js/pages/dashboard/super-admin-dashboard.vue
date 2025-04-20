<template>
  <div>
    <VCard class="mb-6">
      <VCardText>Super ADMIN DASHBOARD</VCardText>
      <VCardText>
        Super ADMIN DASHBOARD TEST
      </VCardText>
    </VCard>
  </div>
</template>

<route lang="yaml">
  meta:
  layout: default
  requiresAuth: true
  action: manage
  subject: all
  </route>
