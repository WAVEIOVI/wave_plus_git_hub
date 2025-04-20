<template>
  <div class="h-100 d-flex align-center justify-space-between">
    <!-- 👉 Footer: left content -->
    <span class="d-flex align-center">
      &copy;
      {{ new Date().getFullYear() }}
      {{ $t('Made With') }}
      <VIcon
        icon="tabler-heart"
        color="error"
        size="1.25rem"
        class="mx-1"
      />
      {{ $t('By') }} <a
        href="https://waveio.tn"
        target="_blank"
        rel="noopener noreferrer"
        class="text-primary ms-1"
      >WAVE IO</a>
    </span>
    <!-- 👉 Footer: right content -->
    <span class="d-md-flex gap-x-4 text-primary d-none">
      <a
        href="https://ssplus.tn/"
        target="noopener noreferrer"
      >{{ $t('Exclusive For') }} SS PLUS</a>
    </span>
  </div>
</template>
