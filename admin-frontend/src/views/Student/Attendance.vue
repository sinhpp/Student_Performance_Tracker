<template>
  <StudentLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-900">My Attendance</h1>
        <p class="text-gray-600">Track your attendance record</p>
      </div>

      <!-- Attendance Summary -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Attendance Summary</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="text-center p-4 bg-green-50 rounded-lg">
            <p class="text-2xl font-bold text-green-600">94%</p>
            <p class="text-sm text-gray-600">Overall Rate</p>
          </div>
          <div class="text-center p-4 bg-blue-50 rounded-lg">
            <p class="text-2xl font-bold text-blue-600">38</p>
            <p class="text-sm text-gray-600">Present Days</p>
          </div>
          <div class="text-center p-4 bg-yellow-50 rounded-lg">
            <p class="text-2xl font-bold text-yellow-600">2</p>
            <p class="text-sm text-gray-600">Absent Days</p>
          </div>
          <div class="text-center p-4 bg-orange-50 rounded-lg">
            <p class="text-2xl font-bold text-orange-600">1</p>
            <p class="text-sm text-gray-600">Late Days</p>
          </div>
        </div>
      </div>

      <!-- Attendance Calendar -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">This Month's Attendance</h2>
        <div class="grid grid-cols-7 gap-2 mb-4">
          <div class="text-center text-sm font-medium text-gray-500 p-2">Mon</div>
          <div class="text-center text-sm font-medium text-gray-500 p-2">Tue</div>
          <div class="text-center text-sm font-medium text-gray-500 p-2">Wed</div>
          <div class="text-center text-sm font-medium text-gray-500 p-2">Thu</div>
          <div class="text-center text-sm font-medium text-gray-500 p-2">Fri</div>
          <div class="text-center text-sm font-medium text-gray-500 p-2">Sat</div>
          <div class="text-center text-sm font-medium text-gray-500 p-2">Sun</div>
        </div>
        <div class="grid grid-cols-7 gap-2">
          <!-- Calendar days with attendance status -->
          <div class="text-center p-2 rounded bg-green-100 text-green-800 text-sm">1</div>
          <div class="text-center p-2 rounded bg-green-100 text-green-800 text-sm">2</div>
          <div class="text-center p-2 rounded bg-green-100 text-green-800 text-sm">3</div>
          <div class="text-center p-2 rounded bg-green-100 text-green-800 text-sm">4</div>
          <div class="text-center p-2 rounded bg-gray-100 text-gray-400 text-sm">5</div>
          <div class="text-center p-2 rounded bg-gray-100 text-gray-400 text-sm">6</div>
          <div class="text-center p-2 rounded bg-green-100 text-green-800 text-sm">7</div>
          <div class="text-center p-2 rounded bg-yellow-100 text-yellow-800 text-sm">8</div>
          <div class="text-center p-2 rounded bg-green-100 text-green-800 text-sm">9</div>
          <div class="text-center p-2 rounded bg-red-100 text-red-800 text-sm">10</div>
        </div>
        <div class="mt-4 flex items-center space-x-4 text-sm">
          <div class="flex items-center">
            <div class="w-3 h-3 bg-green-100 rounded mr-2"></div>
            <span>Present</span>
          </div>
          <div class="flex items-center">
            <div class="w-3 h-3 bg-yellow-100 rounded mr-2"></div>
            <span>Late</span>
          </div>
          <div class="flex items-center">
            <div class="w-3 h-3 bg-red-100 rounded mr-2"></div>
            <span>Absent</span>
          </div>
          <div class="flex items-center">
            <div class="w-3 h-3 bg-gray-100 rounded mr-2"></div>
            <span>Holiday</span>
          </div>
        </div>
      </div>
    </div>
  </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/components/StudentLayout.vue'
</script>
