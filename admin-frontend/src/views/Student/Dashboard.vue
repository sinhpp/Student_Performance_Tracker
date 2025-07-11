<template>
  <StudentLayout>
    <div class="space-y-6">
      <!-- Welcome Section -->
      <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-sm p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">Welcome back, {{ authStore.user?.name }}!</h1>
        <p class="text-blue-100">Here's your academic overview</p>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Overall Grade -->
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg">
              <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Overall Grade</p>
              <p class="text-2xl font-semibold text-gray-900">A-</p>
              <p class="text-sm text-green-600">85.2%</p>
            </div>
          </div>
        </div>

        <!-- Attendance Rate -->
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
              <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Attendance Rate</p>
              <p class="text-2xl font-semibold text-gray-900">94%</p>
              <p class="text-sm text-blue-600">38/40 days</p>
            </div>
          </div>
        </div>

        <!-- Total Subjects -->
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-lg">
              <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Subjects</p>
              <p class="text-2xl font-semibold text-gray-900">6</p>
              <p class="text-sm text-purple-600">Active courses</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activities -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Grades -->
        <div class="bg-white rounded-lg shadow-sm p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Grades</h2>
          <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
              <div>
                <p class="font-medium text-gray-900">Mathematics</p>
                <p class="text-sm text-gray-600">Algebra Test</p>
              </div>
              <span class="px-2 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full">A</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
              <div>
                <p class="font-medium text-gray-900">Science</p>
                <p class="text-sm text-gray-600">Physics Quiz</p>
              </div>
              <span class="px-2 py-1 text-sm font-semibold text-blue-800 bg-blue-100 rounded-full">B+</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
              <div>
                <p class="font-medium text-gray-900">English</p>
                <p class="text-sm text-gray-600">Essay Assignment</p>
              </div>
              <span class="px-2 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full">A-</span>
            </div>
          </div>
          <div class="mt-4">
            <router-link 
              to="/student/grades" 
              class="text-blue-600 hover:text-blue-800 text-sm font-medium"
            >
              View all grades →
            </router-link>
          </div>
        </div>

        <!-- Attendance Summary -->
        <div class="bg-white rounded-lg shadow-sm p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">This Week's Attendance</h2>
          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium text-gray-600">Monday</span>
              <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Present</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium text-gray-600">Tuesday</span>
              <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Present</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium text-gray-600">Wednesday</span>
              <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Late</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium text-gray-600">Thursday</span>
              <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Present</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium text-gray-600">Friday</span>
              <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Present</span>
            </div>
          </div>
          <div class="mt-4">
            <router-link 
              to="/student/attendance" 
              class="text-blue-600 hover:text-blue-800 text-sm font-medium"
            >
              View attendance history →
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </StudentLayout>
</template>

<script setup>
import { storeToRefs } from 'pinia'
import { useUnifiedAuthStore } from '@/stores/unifiedAuth'
import StudentLayout from '@/components/StudentLayout.vue'

const authStore = useUnifiedAuthStore()
</script>
