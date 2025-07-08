<template>
  <StudentLayout>
    <div class="space-y-6">
      <!-- Filters -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
          <div class="flex items-center space-x-4">
            <select
              v-model="filters.subject"
              @change="filterGrades"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">All Subjects</option>
              <option v-for="subject in subjects" :key="subject" :value="subject">
                {{ subject }}
              </option>
            </select>
            
            <select
              v-model="filters.term"
              @change="filterGrades"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">All Terms</option>
              <option v-for="term in terms" :key="term" :value="term">
                {{ term }}
              </option>
            </select>
          </div>

          <div class="flex items-center space-x-2 text-sm text-gray-600">
            <span>Overall Average:</span>
            <span class="font-bold text-lg" :class="getGradeColor(overallAverage)">
              {{ overallAverage.toFixed(1) }}% ({{ getLetterGrade(overallAverage) }})
            </span>
          </div>
        </div>
      </div>

      <!-- Subject Performance Overview -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Subject Performance</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="subject in subjectPerformance" :key="subject.name" class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-3">
              <h4 class="font-medium text-gray-900">{{ subject.name }}</h4>
              <span class="text-sm font-bold" :class="getGradeColor(subject.average)">
                {{ subject.average.toFixed(1) }}%
              </span>
            </div>
            
            <!-- Progress Bar -->
            <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
              <div 
                class="h-2 rounded-full transition-all duration-300"
                :class="getProgressBarColor(subject.average)"
                :style="{ width: Math.min(subject.average, 100) + '%' }"
              ></div>
            </div>
            
            <div class="flex justify-between text-xs text-gray-500">
              <span>{{ subject.count }} grades</span>
              <span>{{ getLetterGrade(subject.average) }}</span>
            </div>
            
            <!-- Mini Trend -->
            <div class="mt-2 flex items-center text-xs">
              <svg v-if="subject.trend > 0" class="h-3 w-3 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
              </svg>
              <svg v-else-if="subject.trend < 0" class="h-3 w-3 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
              </svg>
              <svg v-else class="h-3 w-3 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
              </svg>
              <span :class="{
                'text-green-600': subject.trend > 0,
                'text-red-600': subject.trend < 0,
                'text-gray-500': subject.trend === 0
              }">
                {{ subject.trend > 0 ? 'Improving' : subject.trend < 0 ? 'Declining' : 'Stable' }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Grades Table -->
      <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Grade Details</h3>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Subject
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Assessment
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Term
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Score
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Grade
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Date
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading" class="animate-pulse">
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                  Loading grades...
                </td>
              </tr>
              <tr v-else-if="filteredGrades.length === 0">
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                  No grades found
                </td>
              </tr>
              <tr v-else v-for="grade in filteredGrades" :key="grade.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="h-8 w-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mr-3">
                      <span class="text-white text-xs font-medium">{{ grade.subject?.name?.charAt(0) || 'S' }}</span>
                    </div>
                    <div class="text-sm font-medium text-gray-900">{{ grade.subject?.name || 'Unknown Subject' }}</div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ grade.assessment_type || 'Assessment' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ grade.term?.name || 'Unknown Term' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <span class="text-sm font-bold" :class="getGradeColor(grade.score)">
                      {{ grade.score }}%
                    </span>
                    <div class="ml-2 w-16 bg-gray-200 rounded-full h-1.5">
                      <div 
                        class="h-1.5 rounded-full"
                        :class="getProgressBarColor(grade.score)"
                        :style="{ width: Math.min(grade.score, 100) + '%' }"
                      ></div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                        :class="getGradeBadgeColor(grade.score)">
                    {{ getLetterGrade(grade.score) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(grade.created_at) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Grade Trends Chart Placeholder -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Trends</h3>
        <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
          <div class="text-center">
            <svg class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <p class="text-gray-500">Charts will be displayed here</p>
            <p class="text-sm text-gray-400 mt-1">Integration with Chart.js coming soon</p>
          </div>
        </div>
      </div>
    </div>
  </StudentLayout>
</template>

<script setup>
import { ref, onMounted, computed, reactive } from 'vue'
import { useStudentPerformanceStore } from '@/stores/studentPerformance'
import StudentLayout from '@/components/StudentLayout.vue'

const performanceStore = useStudentPerformanceStore()

const filters = reactive({
  subject: '',
  term: ''
})

const { loading, grades } = performanceStore

const subjects = computed(() => {
  const subjectSet = new Set()
  grades.forEach(grade => {
    if (grade.subject?.name) {
      subjectSet.add(grade.subject.name)
    }
  })
  return Array.from(subjectSet).sort()
})

const terms = computed(() => {
  const termSet = new Set()
  grades.forEach(grade => {
    if (grade.term?.name) {
      termSet.add(grade.term.name)
    }
  })
  return Array.from(termSet).sort()
})

const filteredGrades = computed(() => {
  return grades.filter(grade => {
    const subjectMatch = !filters.subject || grade.subject?.name === filters.subject
    const termMatch = !filters.term || grade.term?.name === filters.term
    return subjectMatch && termMatch
  })
})

const overallAverage = computed(() => {
  if (filteredGrades.value.length === 0) return 0
  const total = filteredGrades.value.reduce((sum, grade) => sum + grade.score, 0)
  return total / filteredGrades.value.length
})

const subjectPerformance = computed(() => {
  const subjectMap = {}
  
  filteredGrades.value.forEach(grade => {
    const subjectName = grade.subject?.name || 'Unknown'
    if (!subjectMap[subjectName]) {
      subjectMap[subjectName] = {
        name: subjectName,
        scores: [],
        total: 0,
        count: 0
      }
    }
    subjectMap[subjectName].scores.push(grade.score)
    subjectMap[subjectName].total += grade.score
    subjectMap[subjectName].count += 1
  })

  return Object.values(subjectMap).map(subject => {
    const average = subject.total / subject.count
    const scores = subject.scores.sort((a, b) => a - b)
    
    // Calculate trend (simple: compare first half vs second half)
    let trend = 0
    if (scores.length >= 4) {
      const midpoint = Math.floor(scores.length / 2)
      const firstHalf = scores.slice(0, midpoint)
      const secondHalf = scores.slice(midpoint)
      const firstAvg = firstHalf.reduce((a, b) => a + b, 0) / firstHalf.length
      const secondAvg = secondHalf.reduce((a, b) => a + b, 0) / secondHalf.length
      trend = secondAvg - firstAvg
    }
    
    return {
      ...subject,
      average,
      trend
    }
  }).sort((a, b) => b.average - a.average)
})

const getLetterGrade = (score) => {
  if (score >= 90) return 'A+'
  if (score >= 80) return 'A'
  if (score >= 70) return 'B'
  if (score >= 60) return 'C'
  if (score >= 50) return 'D'
  return 'F'
}

const getGradeColor = (score) => {
  if (score >= 80) return 'text-green-600'
  if (score >= 70) return 'text-blue-600'
  if (score >= 60) return 'text-yellow-600'
  return 'text-red-600'
}

const getProgressBarColor = (score) => {
  if (score >= 80) return 'bg-green-500'
  if (score >= 70) return 'bg-blue-500'
  if (score >= 60) return 'bg-yellow-500'
  return 'bg-red-500'
}

const getGradeBadgeColor = (score) => {
  if (score >= 80) return 'bg-green-100 text-green-800'
  if (score >= 70) return 'bg-blue-100 text-blue-800'
  if (score >= 60) return 'bg-yellow-100 text-yellow-800'
  return 'bg-red-100 text-red-800'
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

const filterGrades = () => {
  // Grades are automatically filtered via computed property
}

onMounted(async () => {
  await performanceStore.fetchGrades()
})
</script>
