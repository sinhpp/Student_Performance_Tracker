<template>
  <nav class="bg-white shadow-lg border-r border-gray-200">
    <div class="h-16 flex items-center justify-between px-4 border-b border-gray-200">
      <div class="flex items-center space-x-3">
        <div class="h-8 w-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
          <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <h1 class="text-lg font-semibold text-gray-900">Student Portal</h1>
      </div>
    </div>
    
    <div class="py-4">
      <nav class="space-y-1 px-2">
        <router-link 
          to="/dashboard" 
          class="nav-link"
          :class="{ 'active': $route.path === '/dashboard' }"
        >
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 15v4m4-4v4m4-4v4"/>
          </svg>
          Dashboard
        </router-link>

        <router-link 
          to="/grades" 
          class="nav-link"
          :class="{ 'active': $route.path.startsWith('/grades') }"
        >
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
          My Grades
        </router-link>

        <router-link 
          to="/attendance" 
          class="nav-link"
          :class="{ 'active': $route.path.startsWith('/attendance') }"
        >
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
          My Attendance
        </router-link>

        <router-link 
          to="/feedback" 
          class="nav-link"
          :class="{ 'active': $route.path.startsWith('/feedback') }"
        >
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
          Feedback
        </router-link>

        <router-link 
          to="/profile" 
          class="nav-link"
          :class="{ 'active': $route.path.startsWith('/profile') }"
        >
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
          My Profile
        </router-link>

        <div class="pt-4 mt-4 border-t border-gray-200">
          <div class="flex items-center px-3 py-2 text-sm text-gray-600">
            <div class="h-6 w-6 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center mr-3">
              <span class="text-xs font-medium text-white">{{ userInitial }}</span>
            </div>
            <span>{{ authStore.student?.name || 'Student' }}</span>
          </div>
          <button 
            @click="logout"
            class="w-full flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
          >
            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Logout
          </button>
        </div>
      </nav>
    </div>
  </nav>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useStudentAuthStore } from '@/stores/studentAuth'

const router = useRouter()
const authStore = useStudentAuthStore()

const userInitial = computed(() => {
  return authStore.student?.name?.charAt(0).toUpperCase() || 'S'
})

const logout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
.nav-link {
  @apply flex items-center space-x-3 px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors;
}

.nav-link.active {
  @apply bg-blue-50 text-blue-600 hover:bg-blue-100;
}
</style>
