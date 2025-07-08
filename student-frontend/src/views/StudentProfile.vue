<template>
  <StudentLayout>
    <div class="space-y-6">
      <!-- Profile Header -->
      <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white">
        <div class="flex items-center space-x-6">
          <div class="h-24 w-24 bg-white/20 rounded-full flex items-center justify-center">
            <span class="text-4xl font-bold text-white">{{ userInitial }}</span>
          </div>
          <div>
            <h2 class="text-3xl font-bold mb-2">{{ authStore.student?.name || 'Student Name' }}</h2>
            <p class="text-indigo-100 text-lg">{{ authStore.student?.email || 'student@example.com' }}</p>
            <p class="text-indigo-200 text-sm mt-1">Student ID: {{ authStore.student?.student_id || 'STU001' }}</p>
          </div>
        </div>
      </div>

      <!-- Profile Information -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Personal Information -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
            <button
              @click="editMode.personal = !editMode.personal"
              class="text-blue-600 hover:text-blue-700 text-sm font-medium"
            >
              {{ editMode.personal ? 'Cancel' : 'Edit' }}
            </button>
          </div>

          <form @submit.prevent="updatePersonalInfo" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
              <input
                v-model="profileForm.name"
                type="text"
                :readonly="!editMode.personal"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'bg-gray-50': !editMode.personal }"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
              <input
                v-model="profileForm.email"
                type="email"
                :readonly="!editMode.personal"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'bg-gray-50': !editMode.personal }"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
              <input
                v-model="profileForm.phone"
                type="tel"
                :readonly="!editMode.personal"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'bg-gray-50': !editMode.personal }"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
              <input
                v-model="profileForm.date_of_birth"
                type="date"
                :readonly="!editMode.personal"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'bg-gray-50': !editMode.personal }"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
              <textarea
                v-model="profileForm.address"
                rows="3"
                :readonly="!editMode.personal"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'bg-gray-50': !editMode.personal }"
              ></textarea>
            </div>

            <div v-if="editMode.personal" class="flex justify-end space-x-3 pt-4">
              <button
                type="button"
                @click="editMode.personal = false"
                class="px-4 py-2 text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="loading"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
              >
                {{ loading ? 'Saving...' : 'Save Changes' }}
              </button>
            </div>
          </form>
        </div>

        <!-- Academic Information -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Academic Information</h3>
          
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Student ID</label>
              <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-700">
                {{ authStore.student?.student_id || 'STU001' }}
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Class</label>
              <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-700">
                {{ authStore.student?.class_name || 'Grade 10' }}
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
              <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-700">
                {{ academicYear }}
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Enrollment Date</label>
              <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-700">
                {{ formatDate(authStore.student?.created_at) || 'September 2023' }}
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                Active
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Guardian Information -->
      <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900">Guardian Information</h3>
          <button
            @click="editMode.guardian = !editMode.guardian"
            class="text-blue-600 hover:text-blue-700 text-sm font-medium"
          >
            {{ editMode.guardian ? 'Cancel' : 'Edit' }}
          </button>
        </div>

        <form @submit.prevent="updateGuardianInfo" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Guardian Name</label>
            <input
              v-model="profileForm.guardian_name"
              type="text"
              :readonly="!editMode.guardian"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :class="{ 'bg-gray-50': !editMode.guardian }"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Guardian Phone</label>
            <input
              v-model="profileForm.guardian_phone"
              type="tel"
              :readonly="!editMode.guardian"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :class="{ 'bg-gray-50': !editMode.guardian }"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Guardian Email</label>
            <input
              v-model="profileForm.guardian_email"
              type="email"
              :readonly="!editMode.guardian"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :class="{ 'bg-gray-50': !editMode.guardian }"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Relationship</label>
            <select
              v-model="profileForm.guardian_relation"
              :disabled="!editMode.guardian"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :class="{ 'bg-gray-50': !editMode.guardian }"
            >
              <option value="">Select Relationship</option>
              <option value="father">Father</option>
              <option value="mother">Mother</option>
              <option value="guardian">Guardian</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div v-if="editMode.guardian" class="md:col-span-2 flex justify-end space-x-3 pt-4">
            <button
              type="button"
              @click="editMode.guardian = false"
              class="px-4 py-2 text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="loading"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
            >
              {{ loading ? 'Saving...' : 'Save Changes' }}
            </button>
          </div>
        </form>
      </div>

      <!-- Quick Stats -->
      <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Stats</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div class="text-center">
            <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
              <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ performance.totalGrades }}</p>
            <p class="text-sm text-gray-600">Total Grades</p>
          </div>

          <div class="text-center">
            <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
              <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
              </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ performance.averageScore.toFixed(1) }}%</p>
            <p class="text-sm text-gray-600">Average Score</p>
          </div>

          <div class="text-center">
            <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
              <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ performance.attendanceRate }}%</p>
            <p class="text-sm text-gray-600">Attendance Rate</p>
          </div>

          <div class="text-center">
            <div class="h-12 w-12 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-3">
              <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
              </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">A-</p>
            <p class="text-sm text-gray-600">Current Grade</p>
          </div>
        </div>
      </div>
    </div>
  </StudentLayout>
</template>

<script setup>
import { ref, onMounted, computed, reactive } from 'vue'
import { useStudentAuthStore } from '@/stores/studentAuth'
import { useStudentPerformanceStore } from '@/stores/studentPerformance'
import StudentLayout from '@/components/StudentLayout.vue'

const authStore = useStudentAuthStore()
const performanceStore = useStudentPerformanceStore()

const loading = ref(false)
const editMode = reactive({
  personal: false,
  guardian: false
})

const profileForm = reactive({
  name: '',
  email: '',
  phone: '',
  date_of_birth: '',
  address: '',
  guardian_name: '',
  guardian_phone: '',
  guardian_email: '',
  guardian_relation: ''
})

const { performance } = performanceStore

const userInitial = computed(() => {
  return authStore.student?.name?.charAt(0).toUpperCase() || 'S'
})

const academicYear = computed(() => {
  const currentDate = new Date()
  const currentYear = currentDate.getFullYear()
  const currentMonth = currentDate.getMonth()
  
  // Academic year typically starts in September
  if (currentMonth >= 8) {
    return `${currentYear} - ${currentYear + 1}`
  } else {
    return `${currentYear - 1} - ${currentYear}`
  }
})

const loadProfileData = () => {
  const student = authStore.student
  if (student) {
    Object.assign(profileForm, {
      name: student.name || '',
      email: student.email || '',
      phone: student.phone || '',
      date_of_birth: student.date_of_birth || '',
      address: student.address || '',
      guardian_name: student.guardian_name || '',
      guardian_phone: student.guardian_phone || '',
      guardian_email: student.guardian_email || '',
      guardian_relation: student.guardian_relation || ''
    })
  }
}

const updatePersonalInfo = async () => {
  loading.value = true
  try {
    await authStore.updateProfile({
      name: profileForm.name,
      email: profileForm.email,
      phone: profileForm.phone,
      date_of_birth: profileForm.date_of_birth,
      address: profileForm.address
    })
    editMode.personal = false
  } catch (error) {
    console.error('Error updating personal info:', error)
  } finally {
    loading.value = false
  }
}

const updateGuardianInfo = async () => {
  loading.value = true
  try {
    await authStore.updateProfile({
      guardian_name: profileForm.guardian_name,
      guardian_phone: profileForm.guardian_phone,
      guardian_email: profileForm.guardian_email,
      guardian_relation: profileForm.guardian_relation
    })
    editMode.guardian = false
  } catch (error) {
    console.error('Error updating guardian info:', error)
  } finally {
    loading.value = false
  }
}

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

onMounted(async () => {
  await authStore.fetchProfile()
  loadProfileData()
  
  // Load performance data for quick stats
  try {
    await Promise.all([
      performanceStore.fetchGrades(),
      performanceStore.fetchAttendance(),
      performanceStore.fetchPerformanceOverview()
    ])
  } catch (error) {
    console.error('Error loading performance data:', error)
  }
})
</script>
