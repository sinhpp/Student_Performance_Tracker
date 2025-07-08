<template>
  <div class="min-h-screen bg-gray-50">
    <RouterView />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useStudentAuthStore } from '@/stores/studentAuth'

const authStore = useStudentAuthStore()

onMounted(() => {
  // Check if user is already logged in
  if (authStore.token) {
    authStore.fetchProfile()
  }
})
</script>
