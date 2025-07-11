<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Sign in to Student Performance Tracker
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Enter your credentials to access {{ userTypeText }}
        </p>
      </div>
      
      <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
        <div class="rounded-md shadow-sm space-y-4">
          <div>
            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Login as</label>
            <select
              id="role"
              v-model="form.role"
              name="role"
              required
              class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
            >
              <option value="">Select Role</option>
              <option value="admin">Administrator</option>
              <option value="teacher">Teacher</option>
              <option value="student">Student</option>
            </select>
          </div>
          <div>
            <label for="email" class="sr-only">Email address</label>
            <input
              id="email"
              v-model="form.email"
              name="email"
              type="email"
              autocomplete="email"
              required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Email address"
            />
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input
              id="password"
              v-model="form.password"
              name="password"
              type="password"
              autocomplete="current-password"
              required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Password"
            />
          </div>
        </div>

        <div v-if="authStore.errors.general" class="text-red-600 text-sm">
          {{ authStore.errors.general[0] }}
        </div>

        <div>
          <button
            type="submit"
            :disabled="authStore.loading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
          >
            <span v-if="authStore.loading">Signing in...</span>
            <span v-else>Sign in</span>
          </button>
        </div>

        <!-- Demo Credentials -->
        <div class="mt-6 border-t pt-6">
          <p class="text-center text-sm text-gray-600 mb-4">Demo Credentials:</p>
          <div class="grid grid-cols-1 gap-2 text-sm">
            <button
              type="button"
              @click="fillDemo('admin')"
              class="text-left p-2 border rounded hover:bg-gray-50"
            >
              <strong>Admin:</strong> Select "Administrator" → admin@example.com / admin123
            </button>
            <button
              type="button"
              @click="fillDemo('teacher')"
              class="text-left p-2 border rounded hover:bg-gray-50"
            >
              <strong>Teacher:</strong> Select "Teacher" → teacher@example.com / teacher123
            </button>
            <button
              type="button"
              @click="fillDemo('student')"
              class="text-left p-2 border rounded hover:bg-gray-50"
            >
              <strong>Student:</strong> Select "Student" → student@example.com / student123
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useUnifiedAuthStore } from '@/stores/unifiedAuth'

const router = useRouter()
const authStore = useUnifiedAuthStore()

const form = ref({
  role: '',
  email: '',
  password: ''
})

const userTypeText = computed(() => {
  return 'the system'
})

const handleLogin = async () => {
  authStore.clearErrors()
  
  try {
    const response = await authStore.login(form.value)
    console.log('Login successful, user role:', form.value.role)
    
    // Redirect based on selected role (guaranteed to match user's actual role)
    if (form.value.role === 'admin') {
      console.log('Redirecting to admin frontend')
      window.location.href = 'http://localhost:5173/dashboard'
    } else if (form.value.role === 'teacher') {
      console.log('Redirecting teacher to dashboard')
      router.push('/dashboard')
    } else if (form.value.role === 'student') {
      console.log('Redirecting student to dashboard')
      router.push('/dashboard')
    }
  } catch (error) {
    console.error('Login failed:', error)
  }
}

const fillDemo = (type) => {
  if (type === 'admin') {
    form.value.role = 'admin'
    form.value.email = 'admin@example.com'
    form.value.password = 'admin123'
  } else if (type === 'teacher') {
    form.value.role = 'teacher'
    form.value.email = 'teacher@example.com'
    form.value.password = 'teacher123'
  } else if (type === 'student') {
    form.value.role = 'student'
    form.value.email = 'student@example.com'
    form.value.password = 'student123'
  }
}
</script>
