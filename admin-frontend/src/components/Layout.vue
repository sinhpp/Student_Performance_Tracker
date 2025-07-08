<template>
  <div class="min-h-screen bg-gray-50 flex">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-lg">
      <Navigation />
    </div>
    
    <!-- Main content -->
    <div class="flex-1">
      <!-- Header -->
      <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="px-6 py-4">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-2xl font-semibold text-gray-900">{{ pageTitle }}</h1>
              <p class="text-gray-600">{{ pageDescription }}</p>
            </div>
            <div class="flex items-center space-x-4">
              <div class="flex items-center space-x-2 text-sm text-gray-500">
                <span>{{ formatDate(new Date()) }}</span>
              </div>
            </div>
          </div>
        </div>
      </header>
      
      <!-- Page content -->
      <main class="p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Navigation from '@/components/Navigation.vue'

const route = useRoute()

const pageTitle = computed(() => {
  const titles = {
    '/dashboard': 'Dashboard',
    '/students': 'Students',
    '/students/create': 'Add Student',
    '/grades': 'Grades',
    '/attendance': 'Attendance'
  }
  return titles[route.path] || 'Admin Panel'
})

const pageDescription = computed(() => {
  const descriptions = {
    '/dashboard': 'Overview of your school management system',
    '/students': 'Manage student records and information',
    '/students/create': 'Add a new student to the system',
    '/grades': 'View and manage student grades',
    '/attendance': 'Track student attendance records'
  }
  return descriptions[route.path] || 'School Management System'
})

const formatDate = (date) => {
  return new Intl.DateTimeFormat('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(date)
}
</script>
