import { defineStore } from 'pinia'
import api from '@/services/api'

export const useStudentPerformanceStore = defineStore('studentPerformance', {
  state: () => ({
    grades: [],
    attendance: [],
    feedback: [],
    performance: {
      totalGrades: 0,
      averageScore: 0,
      attendanceRate: 0,
      improvementTrend: 0
    },
    loading: false,
    errors: {}
  }),

  getters: {
    gradesBySubject: (state) => {
      const grouped = {}
      state.grades.forEach(grade => {
        const subject = grade.subject?.name || 'Unknown'
        if (!grouped[subject]) {
          grouped[subject] = []
        }
        grouped[subject].push(grade)
      })
      return grouped
    },

    gradesByTerm: (state) => {
      const grouped = {}
      state.grades.forEach(grade => {
        const term = grade.term?.name || 'Unknown'
        if (!grouped[term]) {
          grouped[term] = []
        }
        grouped[term].push(grade)
      })
      return grouped
    },

    subjectAverages: (state) => {
      const grouped = state.grades.reduce((acc, grade) => {
        const subject = grade.subject?.name || 'Unknown'
        if (!acc[subject]) {
          acc[subject] = { total: 0, count: 0, scores: [] }
        }
        acc[subject].total += grade.score
        acc[subject].count += 1
        acc[subject].scores.push(grade.score)
        return acc
      }, {})

      return Object.entries(grouped).map(([subject, data]) => ({
        subject,
        average: data.total / data.count,
        count: data.count,
        highest: Math.max(...data.scores),
        lowest: Math.min(...data.scores)
      }))
    },

    recentAttendance: (state) => {
      return state.attendance
        .sort((a, b) => new Date(b.date) - new Date(a.date))
        .slice(0, 30) // Last 30 days
    },

    attendanceStats: (state) => {
      if (state.attendance.length === 0) return { present: 0, absent: 0, late: 0, rate: 0 }
      
      const stats = state.attendance.reduce((acc, record) => {
        acc[record.status] = (acc[record.status] || 0) + 1
        return acc
      }, {})

      const total = state.attendance.length
      return {
        ...stats,
        total,
        rate: ((stats.present || 0) / total * 100).toFixed(1)
      }
    }
  },

  actions: {
    async fetchGrades() {
      this.loading = true
      try {
        const response = await api.get('/student/grades')
        this.grades = response.data.data || response.data
        this.performance.totalGrades = this.grades.length
        
        if (this.grades.length > 0) {
          const totalScore = this.grades.reduce((sum, grade) => sum + grade.score, 0)
          this.performance.averageScore = totalScore / this.grades.length
        }
      } catch (error) {
        console.error('Fetch grades error:', error)
        this.errors = error.response?.data?.errors || {}
      } finally {
        this.loading = false
      }
    },

    async fetchAttendance() {
      this.loading = true
      try {
        const response = await api.get('/student/attendance')
        this.attendance = response.data.data || response.data
        
        // Calculate attendance rate
        if (this.attendance.length > 0) {
          const presentCount = this.attendance.filter(record => record.status === 'present').length
          this.performance.attendanceRate = (presentCount / this.attendance.length * 100).toFixed(1)
        }
      } catch (error) {
        console.error('Fetch attendance error:', error)
        this.errors = error.response?.data?.errors || {}
      } finally {
        this.loading = false
      }
    },

    async fetchFeedback() {
      this.loading = true
      try {
        const response = await api.get('/student/feedback')
        this.feedback = response.data.data || response.data
      } catch (error) {
        console.error('Fetch feedback error:', error)
        this.errors = error.response?.data?.errors || {}
      } finally {
        this.loading = false
      }
    },

    async fetchPerformanceOverview() {
      try {
        const response = await api.get('/student/performance-overview')
        this.performance = { ...this.performance, ...response.data }
      } catch (error) {
        console.error('Fetch performance overview error:', error)
      }
    },

    clearErrors() {
      this.errors = {}
    }
  }
})
