<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full space-y-8">
      <!-- Header Section -->
      <div class="text-center">
        <div class="mx-auto h-20 w-20 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mb-6 shadow-lg">
          <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
          Student Performance Tracker
        </h1>
        <p class="text-xl text-gray-600 mb-2">
          Select your role to continue
        </p>
        <p class="text-gray-500">
          Choose the appropriate option below to access your dashboard
        </p>
      </div>
      
      <!-- Role Selection Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
        <!-- Admin Card -->
        <div 
          @click="selectRole('admin')"
          class="group bg-white rounded-2xl shadow-xl border border-gray-100 p-8 text-center cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:border-indigo-200"
        >
          <div class="mx-auto h-16 w-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mb-6 group-hover:shadow-lg transition-shadow">
            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-indigo-700 transition-colors">
            Administrator
          </h3>
          <p class="text-gray-600 mb-4">
            Manage the entire system, users, and configurations
          </p>
          <ul class="text-sm text-gray-500 space-y-1">
            <li>• Manage students and teachers</li>
            <li>• System administration</li>
            <li>• Analytics and reports</li>
            <li>• Full system access</li>
          </ul>
          <div class="mt-6">
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 group-hover:bg-indigo-200 transition-colors">
              Login Required
            </span>
          </div>
        </div>

        <!-- Teacher Card -->
        <div 
          @click="selectRole('teacher')"
          class="group bg-white rounded-2xl shadow-xl border border-gray-100 p-8 text-center cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:border-blue-200"
        >
          <div class="mx-auto h-16 w-16 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-full flex items-center justify-center mb-6 group-hover:shadow-lg transition-shadow">
            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-blue-700 transition-colors">
            Teacher
          </h3>
          <p class="text-gray-600 mb-4">
            Manage classes, grades, and student performance
          </p>
          <ul class="text-sm text-gray-500 space-y-1">
            <li>• Manage student grades</li>
            <li>• Track attendance</li>
            <li>• Class management</li>
            <li>• Student progress reports</li>
          </ul>
          <div class="mt-6 space-y-2">
            <button 
              @click.stop="showRegisterForm('teacher')"
              class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"
            >
              Register
            </button>
            <button 
              @click.stop="showLoginForm('teacher')"
              class="w-full px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors text-sm font-medium"
            >
              Login
            </button>
          </div>
        </div>

        <!-- Student Card -->
        <div 
          @click="selectRole('student')"
          class="group bg-white rounded-2xl shadow-xl border border-gray-100 p-8 text-center cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:border-green-200"
        >
          <div class="mx-auto h-16 w-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center mb-6 group-hover:shadow-lg transition-shadow">
            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-green-700 transition-colors">
            Student
          </h3>
          <p class="text-gray-600 mb-4">
            View your grades, attendance, and academic progress
          </p>
          <ul class="text-sm text-gray-500 space-y-1">
            <li>• View your grades</li>
            <li>• Check attendance</li>
            <li>• Academic progress</li>
            <li>• Profile management</li>
          </ul>
          <div class="mt-6 space-y-2">
            <button 
              @click.stop="showRegisterForm('student')"
              class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium"
            >
              Register
            </button>
            <button 
              @click.stop="showLoginForm('student')"
              class="w-full px-4 py-2 border border-green-600 text-green-600 rounded-lg hover:bg-green-50 transition-colors text-sm font-medium"
            >
              Login
            </button>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="text-center pt-8">
        <p class="text-sm text-gray-500">
          © 2025 Student Performance Tracker. All rights reserved.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const selectRole = (role) => {
  if (role === 'admin') {
    // For admin, go directly to login (no register option)
    showLoginForm('admin')
  }
  // For teacher and student, they can choose register or login via buttons
}

const showLoginForm = (role) => {
  router.push({
    name: 'Login',
    query: { role }
  })
}

const showRegisterForm = (role) => {
  router.push({
    name: 'Register',
    query: { role }
  })
}
</script>
