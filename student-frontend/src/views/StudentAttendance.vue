<template>
  <StudentLayout>
    <div class="space-y-6">
      <!-- Attendance Summary -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Attendance Rate</p>
              <p class="text-3xl font-bold text-gray-900 mt-2">{{ attendanceStats.rate }}%</p>
              <p class="text-sm mt-1" :class="attendanceStats.rate >= 80 ? 'text-green-600' : 'text-red-600'">
                {{ attendanceStats.rate >= 80 ? 'Good attendance' : 'Needs improvement' }}
              </p>
            </div>
            <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Days Present</p>
              <p class="text-3xl font-bold text-green-600 mt-2">{{ attendanceStats.present || 0 }}</p>
              <p class="text-sm text-gray-500 mt-1">out of {{ attendanceStats.total || 0 }} days</p>
            </div>
            <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Days Absent</p>
              <p class="text-3xl font-bold text-red-600 mt-2">{{ attendanceStats.absent || 0 }}</p>
              <p class="text-sm text-gray-500 mt-1">missed classes</p>
            </div>
            <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center">
              <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Days Late</p>
              <p class="text-3xl font-bold text-yellow-600 mt-2">{{ attendanceStats.late || 0 }}</p>
              <p class="text-sm text-gray-500 mt-1">tardy arrivals</p>
            </div>
            <div class="h-12 w-12 bg-yellow-100 rounded-lg flex items-center justify-center">
              <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Calendar View -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900">Attendance Calendar</h3>
          <div class="flex items-center space-x-4">
            <button
              @click="previousMonth"
              class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100"
            >
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
              </svg>
            </button>
            <h4 class="text-lg font-medium text-gray-900">
              {{ currentMonth.toLocaleDateString('en-US', { month: 'long', year: 'numeric' }) }}
            </h4>
            <button
              @click="nextMonth"
              class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100"
            >
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Calendar Grid -->
        <div class="grid grid-cols-7 gap-2 mb-4">
          <!-- Days of week header -->
          <div v-for="day in daysOfWeek" :key="day" class="text-center text-sm font-medium text-gray-500 py-2">
            {{ day }}
          </div>
          
          <!-- Calendar days -->
          <div
            v-for="day in calendarDays"
            :key="day.date"
            class="relative h-12 border border-gray-200 rounded-lg flex items-center justify-center text-sm"
            :class="{
              'bg-gray-50 text-gray-400': !day.inCurrentMonth,
              'bg-blue-50 border-blue-200': day.isToday,
              'bg-green-100 border-green-300': day.attendance?.status === 'present',
              'bg-red-100 border-red-300': day.attendance?.status === 'absent',
              'bg-yellow-100 border-yellow-300': day.attendance?.status === 'late'
            }"
          >
            <span :class="{
              'font-bold text-blue-600': day.isToday,
              'text-green-700': day.attendance?.status === 'present',
              'text-red-700': day.attendance?.status === 'absent',
              'text-yellow-700': day.attendance?.status === 'late'
            }">
              {{ day.day }}
            </span>
            
            <!-- Status indicator -->
            <div
              v-if="day.attendance"
              class="absolute bottom-1 right-1 w-2 h-2 rounded-full"
              :class="{
                'bg-green-500': day.attendance.status === 'present',
                'bg-red-500': day.attendance.status === 'absent',
                'bg-yellow-500': day.attendance.status === 'late'
              }"
            ></div>
          </div>
        </div>

        <!-- Legend -->
        <div class="flex items-center justify-center space-x-6 text-sm">
          <div class="flex items-center">
            <div class="w-4 h-4 bg-green-100 border border-green-300 rounded mr-2"></div>
            <span class="text-gray-600">Present</span>
          </div>
          <div class="flex items-center">
            <div class="w-4 h-4 bg-red-100 border border-red-300 rounded mr-2"></div>
            <span class="text-gray-600">Absent</span>
          </div>
          <div class="flex items-center">
            <div class="w-4 h-4 bg-yellow-100 border border-yellow-300 rounded mr-2"></div>
            <span class="text-gray-600">Late</span>
          </div>
          <div class="flex items-center">
            <div class="w-4 h-4 bg-gray-100 border border-gray-300 rounded mr-2"></div>
            <span class="text-gray-600">No class</span>
          </div>
        </div>
      </div>

      <!-- Recent Attendance List -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Attendance</h3>
        <div v-if="loading" class="text-center py-8">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
          <p class="mt-4 text-gray-600">Loading attendance records...</p>
        </div>
        
        <div v-else-if="recentAttendance.length === 0" class="text-center py-8 text-gray-500">
          <svg class="h-12 w-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
          <p>No attendance records found</p>
        </div>

        <div v-else class="space-y-3">
          <div
            v-for="record in recentAttendance"
            :key="`${record.date}-${record.id}`"
            class="flex items-center justify-between p-4 bg-gray-50 rounded-lg"
          >
            <div class="flex items-center space-x-3">
              <div class="h-10 w-10 rounded-full flex items-center justify-center"
                   :class="{
                     'bg-green-100': record.status === 'present',
                     'bg-red-100': record.status === 'absent',
                     'bg-yellow-100': record.status === 'late'
                   }">
                <svg class="h-5 w-5"
                     :class="{
                       'text-green-600': record.status === 'present',
                       'text-red-600': record.status === 'absent',
                       'text-yellow-600': record.status === 'late'
                     }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path v-if="record.status === 'present'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  <path v-else-if="record.status === 'absent'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div>
                <p class="font-medium text-gray-900">{{ formatDate(record.date) }}</p>
                <p class="text-sm text-gray-500">{{ record.subject || 'General Attendance' }}</p>
              </div>
            </div>
            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full"
                  :class="{
                    'bg-green-100 text-green-800': record.status === 'present',
                    'bg-red-100 text-red-800': record.status === 'absent',
                    'bg-yellow-100 text-yellow-800': record.status === 'late'
                  }">
              {{ record.status.charAt(0).toUpperCase() + record.status.slice(1) }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </StudentLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useStudentPerformanceStore } from '@/stores/studentPerformance'
import StudentLayout from '@/components/StudentLayout.vue'

const performanceStore = useStudentPerformanceStore()

const currentMonth = ref(new Date())
const { loading, attendance } = performanceStore

const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']

const attendanceStats = computed(() => {
  return performanceStore.attendanceStats
})

const recentAttendance = computed(() => {
  return performanceStore.recentAttendance
})

const calendarDays = computed(() => {
  const year = currentMonth.value.getFullYear()
  const month = currentMonth.value.getMonth()
  
  // Get first day of month and how many days
  const firstDay = new Date(year, month, 1)
  const lastDay = new Date(year, month + 1, 0)
  const daysInMonth = lastDay.getDate()
  const startingDayOfWeek = firstDay.getDay()
  
  const days = []
  
  // Add previous month's trailing days
  const prevMonth = new Date(year, month - 1, 0)
  for (let i = startingDayOfWeek - 1; i >= 0; i--) {
    const day = prevMonth.getDate() - i
    days.push({
      day,
      date: new Date(year, month - 1, day).toISOString().split('T')[0],
      inCurrentMonth: false,
      isToday: false,
      attendance: null
    })
  }
  
  // Add current month's days
  for (let day = 1; day <= daysInMonth; day++) {
    const date = new Date(year, month, day)
    const dateString = date.toISOString().split('T')[0]
    const today = new Date()
    const isToday = date.toDateString() === today.toDateString()
    
    // Find attendance record for this date
    const attendanceRecord = attendance.find(record => 
      record.date === dateString
    )
    
    days.push({
      day,
      date: dateString,
      inCurrentMonth: true,
      isToday,
      attendance: attendanceRecord
    })
  }
  
  // Add next month's leading days to fill the grid
  const remainingSlots = 42 - days.length
  for (let day = 1; day <= remainingSlots; day++) {
    days.push({
      day,
      date: new Date(year, month + 1, day).toISOString().split('T')[0],
      inCurrentMonth: false,
      isToday: false,
      attendance: null
    })
  }
  
  return days
})

const previousMonth = () => {
  currentMonth.value = new Date(currentMonth.value.getFullYear(), currentMonth.value.getMonth() - 1, 1)
}

const nextMonth = () => {
  currentMonth.value = new Date(currentMonth.value.getFullYear(), currentMonth.value.getMonth() + 1, 1)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

onMounted(async () => {
  await performanceStore.fetchAttendance()
})
</script>
