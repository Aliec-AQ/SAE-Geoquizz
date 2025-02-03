<script setup>
import { RouterView } from 'vue-router'
import AppHeader from '@/components/structure/AppHeader.vue'
</script>

<template>
  <template class="app-container">
    <AppHeader />
    <RouterView />
  </template>
</template>

<style scoped>
.app-container {
  display: flex;
  flex-direction: column;
  height: 100vh;
}
</style>
