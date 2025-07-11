<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <!-- Header Section -->
      <div class="text-center">
        <div class="mx-auto h-16 w-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mb-6 shadow-lg">
          <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-900 mb-2">
          Welcome Back
        </h2>
        <p class="text-gray-600 text-lg">
          Sign in as {{ roleConfig.title }}
        </p>
        <p class="text-gray-500 text-sm mt-1">
          {{ roleConfig.description }}
        </p>
      </div>
      
      <!-- Login Form -->
      <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
        <form class="space-y-6" @submit.prevent="handleLogin">
          <div class="space-y-5">
            <!-- Success message if redirected from register -->
            <div v-if="successMessage" class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
              <div class="flex items-center">
                <svg class="h-5 w-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-green-700 text-sm font-medium">{{ successMessage }}</span>
              </div>
            </div>

            <!-- Email Input -->
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
                  class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                  placeholder="Enter your email"
                />
              </div>
            </div>

            <!-- Password Input -->
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
                  autocomplete="current-password"
                  required
                  class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                  placeholder="Enter your password"
                />
              </div>
            </div>
          </div>

          <!-- Error Messages -->
          <div class="space-y-2">
            <div v-if="authStore.errors.general" class="bg-red-50 border border-red-200 rounded-lg p-3">
              <div class="flex items-center">
                <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-red-700 text-sm font-medium">{{ authStore.errors.general[0] }}</span>
              </div>
            </div>
            <div v-if="authStore.errors.email" class="bg-red-50 border border-red-200 rounded-lg p-3">
              <div class="flex items-center">
                <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-red-700 text-sm font-medium">{{ authStore.errors.email[0] }}</span>
              </div>
            </div>
            <div v-if="authStore.errors.password" class="bg-red-50 border border-red-200 rounded-lg p-3">
              <div class="flex items-center">
                <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-red-700 text-sm font-medium">{{ authStore.errors.password[0] }}</span>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div>
            <button
              type="submit"
              :disabled="authStore.loading"
              :class="roleConfig.buttonClass"
              class="group relative w-full flex justify-center items-center py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transform transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl"
            >
              <span v-if="authStore.loading" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Signing in...
              </span>
              <span v-else class="flex items-center">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Sign in
              </span>
            </button>
          </div>
        </form>

        <!-- Registration Link (for teachers and students only) -->
        <div v-if="form.role !== 'admin'" class="text-center mt-6">
          <p class="text-sm text-gray-600">
            Don't have an account?
            <button 
              type="button"
              @click="goToRegister"
              class="font-medium text-blue-600 hover:text-blue-500 transition-colors"
            >
              Register here
            </button>
          </p>
        </div>

        <!-- Back to Role Selection -->
        <div class="text-center mt-4">
          <button 
            type="button"
            @click="goToRoleSelection"
            class="text-sm text-gray-500 hover:text-gray-700 transition-colors"
          >
            ← Back to role selection
          </button>
        </div>

        <!-- Demo Credentials -->
        <div class="mt-8 pt-6 border-t border-gray-200">
          <div class="text-center mb-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-1">Demo Credentials</h3>
            <p class="text-xs text-gray-500">Click to auto-fill login form</p>
          </div>
          <div class="grid grid-cols-1 gap-3">
            <button
              v-if="form.role === 'admin'"
              type="button"
              @click="fillDemo('admin')"
              class="group text-left p-4 border-2 border-gray-200 rounded-xl hover:border-indigo-300 hover:bg-indigo-50 transition-all duration-200 transform hover:scale-[1.02]"
            >
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                  <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-semibold text-gray-900 group-hover:text-indigo-700">Administrator</p>
                  <p class="text-xs text-gray-500">admin@example.com / admin123</p>
                </div>
              </div>
            </button>
            <button
              v-if="form.role === 'teacher'"
              type="button"
              @click="fillDemo('teacher')"
              class="group text-left p-4 border-2 border-gray-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 transform hover:scale-[1.02]"
            >
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center">
                  <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-semibold text-gray-900 group-hover:text-blue-700">Teacher</p>
                  <p class="text-xs text-gray-500">teacher@example.com / teacher123</p>
                </div>
              </div>
            </button>
            <button
              v-if="form.role === 'student'"
              type="button"
              @click="fillDemo('student')"
              class="group text-left p-4 border-2 border-gray-200 rounded-xl hover:border-green-300 hover:bg-green-50 transition-all duration-200 transform hover:scale-[1.02]"
            >
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-green-500 to-teal-600 rounded-lg flex items-center justify-center">
                  <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-semibold text-gray-900 group-hover:text-green-700">Student</p>
                  <p class="text-xs text-gray-500">student@example.com / student123</p>
                </div>
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useUnifiedAuthStore } from '@/stores/unifiedAuth'

const router = useRouter()
const route = useRoute()
const authStore = useUnifiedAuthStore()

const form = ref({
  email: '',
  password: '',
  role: ''
})

const successMessage = ref('')

const roleConfig = computed(() => {
  const role = route.query.role || 'admin'
  
  const configs = {
    admin: {
      title: 'Administrator',
      description: 'Access the admin dashboard to manage the system',
      buttonClass: 'bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700'
    },
    teacher: {
      title: 'Teacher',
      description: 'Manage your classes and student performance',
      buttonClass: 'bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700'
    },
    student: {
      title: 'Student',
      description: 'View your grades and academic progress',
      buttonClass: 'bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700'
    }
  }
  
  return configs[role] || configs.admin
})

onMounted(() => {
  form.value.role = route.query.role || 'admin'
  successMessage.value = route.query.message || ''
})

const handleLogin = async () => {
  authStore.clearErrors()
  
  try {
    const response = await authStore.login(form.value)
    console.log('Login successful, user role:', response.user.role)
    
    // Redirect based on actual user role from server
    if (response.user.role === 'admin') {
      console.log('Redirecting admin to dashboard')
      router.push('/admin/dashboard')
    } else if (response.user.role === 'teacher') {
      console.log('Redirecting teacher to dashboard')
      router.push('/teacher/dashboard')
    } else if (response.user.role === 'student') {
      console.log('Redirecting to student dashboard')
      router.push('/student/dashboard')
    }
  } catch (error) {
    console.error('Login failed:', error)
  }
}

const goToRegister = () => {
  router.push({
    name: 'Register',
    query: { role: form.value.role }
  })
}

const goToRoleSelection = () => {
  router.push({ name: 'RoleSelection' })
}

const fillDemo = (type) => {
  if (type === 'admin') {
    form.value.email = 'admin@example.com'
    form.value.password = 'admin123'
  } else if (type === 'teacher') {
    form.value.email = 'teacher@example.com'
    form.value.password = 'teacher123'
  } else if (type === 'student') {
    form.value.email = 'student@example.com'
    form.value.password = 'student123'
  }
}
</script>
