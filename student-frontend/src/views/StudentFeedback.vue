<template>
  <StudentLayout>
    <div class="space-y-6">
      <!-- Feedback Overview -->
      <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl p-8 text-white">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-2xl font-bold mb-2">Teacher Feedback 📝</h2>
            <p class="text-purple-100 text-lg">Messages and guidance from your teachers</p>
            <p class="text-purple-200 text-sm mt-2">{{ feedback.length }} total messages</p>
          </div>
          <div class="hidden md:block">
            <svg class="h-20 w-20 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
          </div>
        </div>
      </div>

      <!-- Filter and Search -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
          <div class="flex items-center space-x-4">
            <div class="relative">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search feedback..."
                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
              />
              <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </div>
            
            <select
              v-model="filterType"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
            >
              <option value="">All Types</option>
              <option value="academic">Academic</option>
              <option value="behavior">Behavior</option>
              <option value="general">General</option>
              <option value="encouragement">Encouragement</option>
            </select>

            <select
              v-model="filterSubject"
              class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
            >
              <option value="">All Subjects</option>
              <option v-for="subject in subjects" :key="subject" :value="subject">
                {{ subject }}
              </option>
            </select>
          </div>

          <div class="flex items-center space-x-2 text-sm text-gray-600">
            <span>Showing {{ filteredFeedback.length }} of {{ feedback.length }} messages</span>
          </div>
        </div>
      </div>

      <!-- Feedback List -->
      <div class="space-y-4">
        <div v-if="loading" class="text-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600 mx-auto"></div>
          <p class="mt-4 text-gray-600">Loading feedback...</p>
        </div>

        <div v-else-if="filteredFeedback.length === 0" class="text-center py-12">
          <svg class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
          <p class="text-gray-500 text-lg">No feedback messages found</p>
          <p class="text-gray-400 text-sm mt-1">Your teachers haven't left any feedback yet</p>
        </div>

        <div v-else>
          <div
            v-for="message in filteredFeedback"
            :key="message.id"
            class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200"
          >
            <!-- Message Header -->
            <div class="px-6 py-4 border-b border-gray-100" :class="getTypeHeaderColor(message.type)">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="h-10 w-10 rounded-full flex items-center justify-center" :class="getTypeIconBg(message.type)">
                    <svg class="h-5 w-5" :class="getTypeIconColor(message.type)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getTypeIcon(message.type)"/>
                    </svg>
                  </div>
                  <div>
                    <h4 class="font-semibold text-gray-900">{{ message.teacher_name || 'Teacher' }}</h4>
                    <p class="text-sm text-gray-600">{{ message.subject || 'General Feedback' }}</p>
                  </div>
                </div>
                <div class="text-right">
                  <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full" :class="getTypeBadgeColor(message.type)">
                    {{ formatType(message.type) }}
                  </span>
                  <p class="text-sm text-gray-500 mt-1">{{ formatDate(message.created_at) }}</p>
                </div>
              </div>
            </div>

            <!-- Message Content -->
            <div class="px-6 py-6">
              <div v-if="message.title" class="mb-3">
                <h5 class="text-lg font-medium text-gray-900">{{ message.title }}</h5>
              </div>
              
              <div class="prose prose-sm max-w-none">
                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ message.content || message.message }}</p>
              </div>

              <!-- Attachments or Additional Info -->
              <div v-if="message.grade_related" class="mt-4 p-3 bg-blue-50 rounded-lg">
                <div class="flex items-center text-sm text-blue-800">
                  <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                  </svg>
                  Related to: {{ message.assignment_name || 'Recent assignment' }}
                  <span v-if="message.grade" class="ml-2 font-medium">
                    (Grade: {{ message.grade }}%)
                  </span>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="mt-4 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                  <button
                    @click="markAsRead(message.id)"
                    v-if="!message.read_at"
                    class="text-sm text-blue-600 hover:text-blue-800 font-medium"
                  >
                    Mark as read
                  </button>
                  <span v-else class="text-sm text-green-600 flex items-center">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Read
                  </span>
                </div>
                
                <div class="flex items-center space-x-2">
                  <button
                    @click="toggleFavorite(message.id)"
                    class="p-2 rounded-lg transition-colors"
                    :class="message.favorited ? 'text-yellow-600 hover:text-yellow-700' : 'text-gray-400 hover:text-gray-600'"
                  >
                    <svg class="h-4 w-4" :fill="message.favorited ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
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

const searchQuery = ref('')
const filterType = ref('')
const filterSubject = ref('')

const { loading, feedback } = performanceStore

const subjects = computed(() => {
  const subjectSet = new Set()
  feedback.forEach(message => {
    if (message.subject) {
      subjectSet.add(message.subject)
    }
  })
  return Array.from(subjectSet).sort()
})

const filteredFeedback = computed(() => {
  return feedback.filter(message => {
    const matchesSearch = !searchQuery.value || 
      message.content?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      message.title?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      message.teacher_name?.toLowerCase().includes(searchQuery.value.toLowerCase())
    
    const matchesType = !filterType.value || message.type === filterType.value
    const matchesSubject = !filterSubject.value || message.subject === filterSubject.value
    
    return matchesSearch && matchesType && matchesSubject
  }).sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
})

const formatType = (type) => {
  return type?.charAt(0).toUpperCase() + type?.slice(1) || 'General'
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getTypeHeaderColor = (type) => {
  const colors = {
    academic: 'bg-blue-50',
    behavior: 'bg-yellow-50',
    general: 'bg-gray-50',
    encouragement: 'bg-green-50'
  }
  return colors[type] || 'bg-gray-50'
}

const getTypeIconBg = (type) => {
  const colors = {
    academic: 'bg-blue-100',
    behavior: 'bg-yellow-100',
    general: 'bg-gray-100',
    encouragement: 'bg-green-100'
  }
  return colors[type] || 'bg-gray-100'
}

const getTypeIconColor = (type) => {
  const colors = {
    academic: 'text-blue-600',
    behavior: 'text-yellow-600',
    general: 'text-gray-600',
    encouragement: 'text-green-600'
  }
  return colors[type] || 'text-gray-600'
}

const getTypeBadgeColor = (type) => {
  const colors = {
    academic: 'bg-blue-100 text-blue-800',
    behavior: 'bg-yellow-100 text-yellow-800',
    general: 'bg-gray-100 text-gray-800',
    encouragement: 'bg-green-100 text-green-800'
  }
  return colors[type] || 'bg-gray-100 text-gray-800'
}

const getTypeIcon = (type) => {
  const icons = {
    academic: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
    behavior: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
    general: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
    encouragement: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'
  }
  return icons[type] || icons.general
}

const markAsRead = async (messageId) => {
  try {
    // API call to mark as read
    // await api.post(`/student/feedback/${messageId}/mark-read`)
    
    // Update local state
    const message = feedback.find(m => m.id === messageId)
    if (message) {
      message.read_at = new Date().toISOString()
    }
  } catch (error) {
    console.error('Error marking message as read:', error)
  }
}

const toggleFavorite = async (messageId) => {
  try {
    // API call to toggle favorite
    // await api.post(`/student/feedback/${messageId}/toggle-favorite`)
    
    // Update local state
    const message = feedback.find(m => m.id === messageId)
    if (message) {
      message.favorited = !message.favorited
    }
  } catch (error) {
    console.error('Error toggling favorite:', error)
  }
}

onMounted(async () => {
  await performanceStore.fetchFeedback()
})
</script>
