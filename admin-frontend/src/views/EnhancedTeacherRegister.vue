<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full space-y-8">
      <!-- Header Section -->
      <div class="text-center">
        <div class="mx-auto h-16 w-16 rounded-full flex items-center justify-center mb-6 shadow-lg bg-gradient-to-r from-blue-500 to-cyan-600">
          <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-900 mb-2">
          Teacher Registration
        </h2>
        <p class="text-gray-600 text-lg">
          Join our educational platform to manage your classes
        </p>
      </div>

      <!-- Progress Bar -->
      <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
          <span class="text-sm font-medium text-gray-500">Progress</span>
          <span class="text-sm font-medium text-gray-500">{{ currentStep }}/{{ totalSteps }}</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
          <div 
            class="bg-gradient-to-r from-blue-500 to-cyan-600 h-2 rounded-full transition-all duration-300"
            :style="{ width: `${(currentStep / totalSteps) * 100}%` }"
          ></div>
        </div>
        <div class="flex justify-between mt-2">
          <span :class="stepIndicatorClass(1)">Personal Info</span>
          <span :class="stepIndicatorClass(2)">Professional Info</span>
          <span :class="stepIndicatorClass(3)">Classes & Subjects</span>
        </div>
      </div>
      
      <!-- Registration Form -->
      <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
        <form @submit.prevent="handleSubmit">
          <!-- Step 1: Personal Information -->
          <div v-if="currentStep === 1" class="space-y-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Personal Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Full Name</label>
                <input
                  v-model="form.name"
                  type="text"
                  required
                  class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                  placeholder="Enter your full name"
                />
              </div>
              
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Email Address</label>
                <input
                  v-model="form.email"
                  type="email"
                  required
                  class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                  placeholder="Enter your email"
                />
              </div>
              
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Phone Number</label>
                <input
                  v-model="form.phone"
                  type="tel"
                  class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                  placeholder="Enter your phone number"
                />
              </div>
              
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Employee ID (optional)</label>
                <input
                  v-model="form.employee_id"
                  type="text"
                  class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                  placeholder="TCH001"
                />
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Password</label>
                <input
                  v-model="form.password"
                  type="password"
                  required
                  class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                  placeholder="Create a password"
                />
              </div>
              
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Confirm Password</label>
                <input
                  v-model="form.password_confirmation"
                  type="password"
                  required
                  class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                  placeholder="Confirm your password"
                />
              </div>
            </div>
          </div>

          <!-- Step 2: Professional Information -->
          <div v-if="currentStep === 2" class="space-y-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Professional Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Department</label>
                <input
                  v-model="form.department"
                  type="text"
                  class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                  placeholder="e.g., Mathematics"
                />
              </div>
              
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Specialization</label>
                <input
                  v-model="form.specialization"
                  type="text"
                  class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                  placeholder="e.g., Algebra and Calculus"
                />
              </div>
              
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Hire Date</label>
                <input
                  v-model="form.hire_date"
                  type="date"
                  class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                />
              </div>
            </div>
            
            <div class="space-y-2">
              <label class="block text-sm font-semibold text-gray-700">Address</label>
              <textarea
                v-model="form.address"
                rows="3"
                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                placeholder="Enter your address"
              ></textarea>
            </div>
          </div>

          <!-- Step 3: Classes & Subjects -->
          <div v-if="currentStep === 3" class="space-y-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Classes & Subjects</h3>
            
            <!-- Subjects Selection -->
            <div class="space-y-4">
              <label class="block text-sm font-semibold text-gray-700">Select Subjects You Teach</label>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <div
                  v-for="subject in subjects"
                  :key="subject.id"
                  class="relative"
                >
                  <input
                    :id="`subject-${subject.id}`"
                    v-model="form.subject_ids"
                    :value="subject.id"
                    type="checkbox"
                    class="sr-only"
                  />
                  <label
                    :for="`subject-${subject.id}`"
                    :class="[
                      'block p-3 rounded-lg border-2 text-center cursor-pointer transition-all',
                      form.subject_ids.includes(subject.id)
                        ? 'border-blue-500 bg-blue-50 text-blue-700'
                        : 'border-gray-300 hover:border-blue-300'
                    ]"
                  >
                    {{ subject.name }}
                  </label>
                </div>
              </div>
            </div>

            <!-- Classes Selection -->
            <div class="space-y-4">
              <label class="block text-sm font-semibold text-gray-700">Select Classes You Teach</label>
              <div class="grid grid-cols-3 md:grid-cols-4 gap-3">
                <div
                  v-for="classItem in classes"
                  :key="classItem.id"
                  class="relative"
                >
                  <input
                    :id="`class-${classItem.id}`"
                    v-model="form.class_ids"
                    :value="classItem.id"
                    type="checkbox"
                    class="sr-only"
                  />
                  <label
                    :for="`class-${classItem.id}`"
                    :class="[
                      'block p-3 rounded-lg border-2 text-center cursor-pointer transition-all',
                      form.class_ids.includes(classItem.id)
                        ? 'border-green-500 bg-green-50 text-green-700'
                        : 'border-gray-300 hover:border-green-300'
                    ]"
                  >
                    {{ classItem.name }}
                  </label>
                </div>
              </div>
            </div>

            <!-- Add New Subject/Class -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 pt-6 border-t">
              <div class="space-y-3">
                <h4 class="font-semibold text-gray-700">Add New Subject</h4>
                <div class="flex gap-2">
                  <input
                    v-model="newSubject"
                    type="text"
                    placeholder="Subject name"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  />
                  <button
                    type="button"
                    @click="addSubject"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
                  >
                    Add
                  </button>
                </div>
              </div>
              
              <div class="space-y-3">
                <h4 class="font-semibold text-gray-700">Add New Class</h4>
                <div class="flex gap-2">
                  <input
                    v-model="newClass"
                    type="text"
                    placeholder="Class name (e.g., 1A, 2B)"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  />
                  <button
                    type="button"
                    @click="addClass"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors"
                  >
                    Add
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Error Messages -->
          <div v-if="Object.keys(errors).length > 0" class="mt-6">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
              <div class="flex">
                <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-red-700">
                  <div v-for="(fieldErrors, field) in errors" :key="field">
                    <div v-for="error in (Array.isArray(fieldErrors) ? fieldErrors : [fieldErrors])" :key="error">
                      {{ error }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Navigation Buttons -->
          <div class="flex justify-between mt-8">
            <button
              v-if="currentStep > 1"
              type="button"
              @click="previousStep"
              class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all"
            >
              Previous
            </button>
            <div v-else></div>
            
            <button
              v-if="currentStep < totalSteps"
              type="button"
              @click="nextStep"
              class="px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all"
            >
              Next
            </button>
            <button
              v-else
              type="submit"
              :disabled="loading"
              class="px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-xl hover:from-green-700 hover:to-teal-700 transition-all disabled:opacity-50"
            >
              <span v-if="loading">Creating Account...</span>
              <span v-else>Complete Registration</span>
            </button>
          </div>

          <!-- Login Link -->
          <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
              Already have an account?
              <button type="button" @click="goToLogin" class="font-medium text-blue-600 hover:text-blue-500">
                Sign in here
              </button>
            </p>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()

const currentStep = ref(1)
const totalSteps = 3
const loading = ref(false)
const errors = ref({})

const form = ref({
  name: '',
  email: '',
  phone: '',
  employee_id: '',
  password: '',
  password_confirmation: '',
  department: '',
  specialization: '',
  hire_date: '',
  address: '',
  role: 'teacher',
  subject_ids: [],
  class_ids: []
})

const subjects = ref([])
const classes = ref([])
const newSubject = ref('')
const newClass = ref('')

const stepIndicatorClass = (step) => {
  if (step < currentStep.value) {
    return 'text-xs font-medium text-blue-600'
  } else if (step === currentStep.value) {
    return 'text-xs font-medium text-blue-600 font-bold'
  } else {
    return 'text-xs font-medium text-gray-400'
  }
}

const nextStep = () => {
  if (validateCurrentStep()) {
    currentStep.value++
  }
}

const previousStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--
  }
}

const validateCurrentStep = () => {
  errors.value = {}
  
  if (currentStep.value === 1) {
    if (!form.value.name || !form.value.email || !form.value.password || !form.value.password_confirmation) {
      errors.value.general = ['Please fill in all required fields']
      return false
    }
    if (form.value.password !== form.value.password_confirmation) {
      errors.value.password = ['Passwords do not match']
      return false
    }
  }
  
  return true
}

const loadSubjects = async () => {
  try {
    const response = await api.get('/subjects')
    subjects.value = response.data
  } catch (error) {
    // Fallback data
    subjects.value = [
      { id: 1, name: 'Mathematics' },
      { id: 2, name: 'Science' },
      { id: 3, name: 'English' },
      { id: 4, name: 'History' },
      { id: 5, name: 'Geography' },
      { id: 6, name: 'Computer Science' },
      { id: 7, name: 'Art' },
      { id: 8, name: 'Physical Education' }
    ]
  }
}

const loadClasses = async () => {
  try {
    const response = await api.get('/classes')
    classes.value = response.data
  } catch (error) {
    // Fallback data - generate classes
    classes.value = [
      { id: 1, name: '1A' }, { id: 2, name: '1B' }, { id: 3, name: '1C' },
      { id: 4, name: '2A' }, { id: 5, name: '2B' }, { id: 6, name: '2C' },
      { id: 7, name: '3A' }, { id: 8, name: '3B' }, { id: 9, name: '3C' },
      { id: 10, name: '4A' }, { id: 11, name: '4B' }, { id: 12, name: '4C' },
      { id: 13, name: '5A' }, { id: 14, name: '5B' }, { id: 15, name: '5C' },
      { id: 16, name: '6A' }, { id: 17, name: '6B' }, { id: 18, name: '6C' }
    ]
  }
}

const addSubject = async () => {
  if (!newSubject.value.trim()) return
  
  try {
    const response = await api.post('/subjects', { name: newSubject.value })
    subjects.value.push(response.data)
    form.value.subject_ids.push(response.data.id)
    newSubject.value = ''
  } catch (error) {
    // Fallback for demo
    const newId = Math.max(...subjects.value.map(s => s.id)) + 1
    const newSubjectData = { id: newId, name: newSubject.value }
    subjects.value.push(newSubjectData)
    form.value.subject_ids.push(newId)
    newSubject.value = ''
  }
}

const addClass = async () => {
  if (!newClass.value.trim()) return
  
  try {
    const response = await api.post('/classes', { name: newClass.value })
    classes.value.push(response.data)
    form.value.class_ids.push(response.data.id)
    newClass.value = ''
  } catch (error) {
    // Fallback for demo
    const newId = Math.max(...classes.value.map(c => c.id)) + 1
    const newClassData = { id: newId, name: newClass.value }
    classes.value.push(newClassData)
    form.value.class_ids.push(newId)
    newClass.value = ''
  }
}

const handleSubmit = async () => {
  if (!validateCurrentStep()) return
  
  loading.value = true
  errors.value = {}
  
  try {
    const response = await api.post('/register', form.value)
    
    // Registration successful, redirect to login
    router.push({
      name: 'Login',
      query: { 
        role: 'teacher',
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
    query: { role: 'teacher' }
  })
}

onMounted(() => {
  loadSubjects()
  loadClasses()
})
</script>
