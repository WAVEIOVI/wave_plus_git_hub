<template>
  <div>
    <VCard class="mb-6">
      <VCardText>ADMIN DASHBOARD</VCardText>
      <VCardText>ADMIN DASHBOARD TEST</VCardText>
    </VCard>
  </div>
</template>

<route lang="yaml">
meta:
  layout: default
  requiresAuth: true
  action: manage
  subject: AdminDashboard
</route>
