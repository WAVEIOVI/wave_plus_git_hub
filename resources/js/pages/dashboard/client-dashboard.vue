<template>
  <div>
    <VCard class="mb-6">
      <VCardText>Client DASHBOARD</VCardText>
      <VCardText>Client DASHBOARD TEST</VCardText>
    </VCard>
  </div>
</template>

<route lang="yaml">
meta:
  layout: default
  requiresAuth: true
  action: manage
  subject: ClientDashboard
</route>
