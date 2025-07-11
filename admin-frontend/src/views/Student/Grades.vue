<template>
  <StudentLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-900">My Grades</h1>
        <p class="text-gray-600">View your academic performance across all subjects</p>
      </div>

      <!-- Grade Summary -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Overall Performance</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="text-center p-4 bg-green-50 rounded-lg">
            <p class="text-2xl font-bold text-green-600">A-</p>
            <p class="text-sm text-gray-600">Overall Grade</p>
          </div>
          <div class="text-center p-4 bg-blue-50 rounded-lg">
            <p class="text-2xl font-bold text-blue-600">85.2%</p>
            <p class="text-sm text-gray-600">Average Score</p>
          </div>
          <div class="text-center p-4 bg-purple-50 rounded-lg">
            <p class="text-2xl font-bold text-purple-600">6</p>
            <p class="text-sm text-gray-600">Subjects</p>
          </div>
        </div>
      </div>

      <!-- Grades Table -->
      <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-gray-900">Subject Grades</h2>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Mathematics</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Algebra Test</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">92/100</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">A</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2025-01-08</td>
              </tr>
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Science</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Physics Quiz</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">85/100</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">B+</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2025-01-06</td>
              </tr>
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">English</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Essay Assignment</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">88/100</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">A-</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2025-01-05</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/components/StudentLayout.vue'
</script>
