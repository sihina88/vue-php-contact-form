<template>
  <!-- Main App Container / メインアプリコンテナ -->
  <div class="app">
    <ContactForm />
  </div>
</template>

<script setup>
/**
 * Main App Component
 * メインアプリコンポーネント
 * 
 * Root component that wraps the ContactForm component
 * ContactFormコンポーネントをラップするルートコンポーネント
 */

import ContactForm from './components/ContactForm.vue'
</script>

<style>
/* App Container Styles / アプリコンテナスタイル */
.app {
  width: 100%;
  max-width: var(--container-max-width);
}
</style>

