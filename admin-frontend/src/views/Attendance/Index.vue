<template>
  <Layout>
    <div class="space-y-6">
      <!-- Header with date selector and filters -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
          <div class="flex items-center space-x-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
              <input
                v-model="selectedDate"
                type="date"
                @change="fetchAttendance"
                class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Class</label>
              <select
                v-model="selectedClass"
                @change="fetchStudentsAndAttendance"
                class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="">All Classes</option>
                <option v-for="cls in classes" :key="cls.id" :value="cls.id">
                  {{ cls.name }}
                </option>
              </select>
            </div>

            <div class="pt-6">
              <button
                @click="fetchAttendance"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
              >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
              </button>
            </div>
          </div>

          <div class="flex items-center space-x-4">
            <button
              @click="markAllPresent"
              class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
            >
              Mark All Present
            </button>
            <button
              @click="markAllAbsent"
              class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
            >
              Mark All Absent
            </button>
            <button
              @click="saveAttendance"
              :disabled="loading"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
            >
              {{ loading ? 'Saving...' : 'Save Attendance' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Attendance Statistics -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Total Students</p>
              <p class="text-2xl font-bold text-gray-900">{{ students.length }}</p>
            </div>
            <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Present</p>
              <p class="text-2xl font-bold text-green-600">{{ presentCount }}</p>
            </div>
            <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Absent</p>
              <p class="text-2xl font-bold text-red-600">{{ absentCount }}</p>
            </div>
            <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center">
              <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Attendance Rate</p>
              <p class="text-2xl font-bold text-blue-600">{{ attendanceRate }}%</p>
            </div>
            <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Attendance List -->
      <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">
            Attendance for {{ formatDate(selectedDate) }}
          </h3>
        </div>
        
        <div v-if="loading && students.length === 0" class="text-center py-8">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
          <p class="mt-4 text-gray-600">Loading students...</p>
        </div>

        <div v-else-if="students.length === 0" class="text-center py-8">
          <p class="text-gray-500">No students found for the selected criteria</p>
        </div>

        <div v-else class="divide-y divide-gray-200">
          <div v-for="student in students" :key="student.id" class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
            <div class="flex items-center">
              <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                <span class="text-white font-medium">{{ student.name.charAt(0) }}</span>
              </div>
              <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">{{ student.name }}</div>
                <div class="text-sm text-gray-500">{{ student.student_id || 'No ID' }}</div>
              </div>
            </div>

            <div class="flex items-center space-x-4">
              <div class="text-sm text-gray-500">
                {{ student.class_name || 'No Class' }}
              </div>
              
              <div class="flex items-center space-x-2">
                <button
                  @click="updateAttendanceStatus(student.id, 'present')"
                  :class="[
                    'px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                    getAttendanceStatus(student.id) === 'present'
                      ? 'bg-green-600 text-white'
                      : 'bg-gray-100 text-gray-700 hover:bg-green-100 hover:text-green-700'
                  ]"
                >
                  Present
                </button>
                
                <button
                  @click="updateAttendanceStatus(student.id, 'absent')"
                  :class="[
                    'px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                    getAttendanceStatus(student.id) === 'absent'
                      ? 'bg-red-600 text-white'
                      : 'bg-gray-100 text-gray-700 hover:bg-red-100 hover:text-red-700'
                  ]"
                >
                  Absent
                </button>
                
                <button
                  @click="updateAttendanceStatus(student.id, 'late')"
                  :class="[
                    'px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                    getAttendanceStatus(student.id) === 'late'
                      ? 'bg-yellow-600 text-white'
                      : 'bg-gray-100 text-gray-700 hover:bg-yellow-100 hover:text-yellow-700'
                  ]"
                >
                  Late
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Attendance History -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Recent Attendance</h3>
          <button
            @click="showAttendanceHistory = !showAttendanceHistory"
            class="text-blue-600 hover:text-blue-700 text-sm font-medium"
          >
            {{ showAttendanceHistory ? 'Hide' : 'Show' }} History
          </button>
        </div>

        <div v-if="showAttendanceHistory" class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Date
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Student
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Time
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="record in recentAttendance" :key="`${record.student_id}-${record.date}`" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(record.date) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="h-8 w-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                      <span class="text-white text-sm font-medium">{{ record.student?.name?.charAt(0) || 'S' }}</span>
                    </div>
                    <div class="ml-3">
                      <div class="text-sm font-medium text-gray-900">{{ record.student?.name || 'Unknown' }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                        :class="getStatusColor(record.status)">
                    {{ record.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatTime(record.created_at) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, onMounted, computed, reactive } from 'vue'
import { useAttendanceStore } from '@/stores/attendance'
import { useStudentStore } from '@/stores/student'
import Layout from '@/components/Layout.vue'

const attendanceStore = useAttendanceStore()
const studentStore = useStudentStore()

const selectedDate = ref(new Date().toISOString().split('T')[0])
const selectedClass = ref('')
const showAttendanceHistory = ref(false)

const students = ref([])
const classes = ref([])
const attendanceData = reactive({})
const recentAttendance = ref([])

const { loading, attendances, errors } = attendanceStore

const presentCount = computed(() => {
  return Object.values(attendanceData).filter(status => status === 'present').length
})

const absentCount = computed(() => {
  return Object.values(attendanceData).filter(status => status === 'absent').length
})

const attendanceRate = computed(() => {
  if (students.value.length === 0) return 0
  return Math.round((presentCount.value / students.value.length) * 100)
})

const fetchStudentsAndAttendance = async () => {
  await fetchStudents()
  await fetchAttendance()
}

const fetchStudents = async () => {
  const filters = {}
  if (selectedClass.value) {
    filters.class_id = selectedClass.value
  }
  
  await studentStore.fetchStudents(1, filters)
  students.value = studentStore.students
}

const fetchAttendance = async () => {
  const filters = {
    date: selectedDate.value
  }
  if (selectedClass.value) {
    filters.class_id = selectedClass.value
  }
  
  await attendanceStore.fetchAttendances(filters)
  
  // Initialize attendance data
  Object.keys(attendanceData).forEach(key => delete attendanceData[key])
  
  attendances.forEach(attendance => {
    attendanceData[attendance.student_id] = attendance.status
  })

  // Fetch recent attendance history
  await fetchRecentAttendance()
}

const fetchRecentAttendance = async () => {
  const filters = {
    limit: 20,
    order_by: 'created_at',
    order_direction: 'desc'
  }
  if (selectedClass.value) {
    filters.class_id = selectedClass.value
  }
  
  await attendanceStore.fetchAttendances(filters)
  recentAttendance.value = attendances
}

const fetchClasses = async () => {
  await attendanceStore.fetchClasses()
  classes.value = attendanceStore.classes
}

const getAttendanceStatus = (studentId) => {
  return attendanceData[studentId] || 'not_marked'
}

const updateAttendanceStatus = (studentId, status) => {
  attendanceData[studentId] = status
}

const markAllPresent = () => {
  students.value.forEach(student => {
    attendanceData[student.id] = 'present'
  })
}

const markAllAbsent = () => {
  students.value.forEach(student => {
    attendanceData[student.id] = 'absent'
  })
}

const saveAttendance = async () => {
  const attendanceList = students.value.map(student => ({
    student_id: student.id,
    date: selectedDate.value,
    status: attendanceData[student.id] || 'absent',
    class_id: selectedClass.value || null
  }))

  try {
    await attendanceStore.bulkMarkAttendance(attendanceList)
    await fetchAttendance() // Refresh data
  } catch (error) {
    console.error('Error saving attendance:', error)
  }
}

const getStatusColor = (status) => {
  const colors = {
    present: 'bg-green-100 text-green-800',
    absent: 'bg-red-100 text-red-800',
    late: 'bg-yellow-100 text-yellow-800'
  }
  return colors[status] || 'bg-gray-100 text-gray-800'
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

const formatTime = (datetime) => {
  return new Date(datetime).toLocaleTimeString()
}

onMounted(async () => {
  await Promise.all([
    fetchClasses(),
    fetchStudentsAndAttendance()
  ])
})
</script>
