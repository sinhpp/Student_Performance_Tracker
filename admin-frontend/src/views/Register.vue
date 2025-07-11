<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <!-- Header Section -->
      <div class="text-center">
        <div 
          class="mx-auto h-16 w-16 rounded-full flex items-center justify-center mb-6 shadow-lg"
          :class="roleConfig.bgClass"
        >
          <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="roleConfig.icon"/>
          </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-900 mb-2">
          Register as {{ roleConfig.title }}
        </h2>
        <p class="text-gray-600 text-lg">
          {{ roleConfig.description }}
        </p>
      </div>
      
      <!-- Registration Form -->
      <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
        <form class="space-y-6" @submit.prevent="handleRegister">
          <div class="space-y-5">
            <!-- Full Name -->
            <div class="space-y-2">
              <label for="name" class="block text-sm font-semibold text-gray-700">Full Name</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                </div>
                <input
                  id="name"
                  v-model="form.name"
                  name="name"
                  type="text"
                  autocomplete="name"
                  required
                  class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                  placeholder="Enter your full name"
                />
              </div>
            </div>

            <!-- Email -->
            <div class="space-y-2">
              <label for="email" class="block text-sm font-semibold text-gray-700">Email address</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                  </svg>
                </div>
                <input
                  id="email"
                  v-model="form.email"
                  name="email"
                  type="email"
                  autocomplete="email"
                  required
                  class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                  placeholder="Enter your email"
                />
              </div>
            </div>

            <!-- Password -->
            <div class="space-y-2">
              <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                </div>
                <input
                  id="password"
                  v-model="form.password"
                  name="password"
                  type="password"
                  autocomplete="new-password"
                  required
                  class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                  placeholder="Create a password"
                />
              </div>
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
              <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Confirm Password</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                </div>
                <input
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  name="password_confirmation"
                  type="password"
                  autocomplete="new-password"
                  required
                  class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                  placeholder="Confirm your password"
                />
              </div>
            </div>
          </div>

          <!-- Error Messages -->
          <div class="space-y-2">
            <div v-if="errors.general" class="bg-red-50 border border-red-200 rounded-lg p-3">
              <div class="flex items-center">
                <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-red-700 text-sm font-medium">{{ errors.general[0] }}</span>
              </div>
            </div>
            <div v-for="(fieldErrors, field) in filteredErrors" :key="field" class="bg-red-50 border border-red-200 rounded-lg p-3">
              <div class="flex items-center">
                <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-red-700 text-sm font-medium">{{ fieldErrors[0] }}</span>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div>
            <button
              type="submit"
              :disabled="loading"
              :class="roleConfig.buttonClass"
              class="group relative w-full flex justify-center items-center py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transform transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl"
            >
              <span v-if="loading" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Creating Account...
              </span>
              <span v-else class="flex items-center">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Register as {{ roleConfig.title }}
              </span>
            </button>
          </div>

          <!-- Login Link -->
          <div class="text-center">
            <p class="text-sm text-gray-600">
              Already have an account?
              <button 
                type="button"
                @click="goToLogin"
                class="font-medium text-blue-600 hover:text-blue-500 transition-colors"
              >
                Sign in here
              </button>
            </p>
          </div>

          <!-- Back to Role Selection -->
          <div class="text-center">
            <button 
              type="button"
              @click="goToRoleSelection"
              class="text-sm text-gray-500 hover:text-gray-700 transition-colors"
            >
              ← Back to role selection
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import api from '@/services/api'

const router = useRouter()
const route = useRoute()

const loading = ref(false)
const errors = ref({})

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: ''
})

const roleConfig = computed(() => {
  const role = route.query.role || 'student'
  
  const configs = {
    teacher: {
      title: 'Teacher',
      description: 'Join our educational platform to manage your classes',
      bgClass: 'bg-gradient-to-r from-blue-500 to-cyan-600',
      buttonClass: 'bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700',
      icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'
    },
    student: {
      title: 'Student',
      description: 'Create your account to track your academic progress',
      bgClass: 'bg-gradient-to-r from-green-500 to-teal-600',
      buttonClass: 'bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700',
      icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
    }
  }
  
  return configs[role] || configs.student
})

const filteredErrors = computed(() => {
  const filtered = { ...errors.value }
  delete filtered.general
  return filtered
})

onMounted(() => {
  form.value.role = route.query.role || 'student'
})

const handleRegister = async () => {
  loading.value = true
  errors.value = {}
  
  try {
    const response = await api.post('/register', form.value)
    
    // Registration successful, redirect to login
    router.push({
      name: 'Login',
      query: { 
        role: form.value.role,
        message: 'Registration successful! Please login with your credentials.'
      }
    })
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
    } else {
      errors.value = { general: [error.response?.data?.message || 'Registration failed. Please try again.'] }
    }
  } finally {
    loading.value = false
  }
}

const goToLogin = () => {
  router.push({
    name: 'Login',
    query: { role: form.value.role }
  })
}

const goToRoleSelection = () => {
  router.push({ name: 'RoleSelection' })
}
</script>
