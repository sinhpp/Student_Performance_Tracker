import { defineStore } from 'pinia'
import api from '@/services/api'

export const useGradeStore = defineStore('grade', {
  state: () => ({
    grades: [],
    grade: null,
    loading: false,
    errors: {},
    subjects: [],
    terms: [],
    statistics: {
      averageBySubject: {},
      gradeDistribution: {},
      studentPerformance: {}
    }
  }),

  getters: {
    gradesByStudent: (state) => (studentId) => {
      return state.grades.filter(grade => grade.student_id === studentId)
    },
    gradesBySubject: (state) => (subjectId) => {
      return state.grades.filter(grade => grade.subject_id === subjectId)
    }
  },

  actions: {
    async fetchGrades(filters = {}) {
      this.loading = true
      try {
        const response = await api.get('/admin/grades', { params: filters })
        this.grades = response.data.data || response.data
      } catch (error) {
        console.error('Fetch grades error:', error)
        // Fallback to demo data when API is not available
        this.grades = [
          {
            id: 1,
            student: { id: 1, name: 'John Doe', student_id: 'STU001' },
            subject: { id: 1, name: 'Mathematics' },
            term: { id: 1, name: 'Term 1' },
            score: 85,
            letter_grade: 'A',
            created_at: '2024-01-15'
          },
          {
            id: 2,
            student: { id: 2, name: 'Jane Smith', student_id: 'STU002' },
            subject: { id: 1, name: 'Mathematics' },
            term: { id: 1, name: 'Term 1' },
            score: 92,
            letter_grade: 'A+',
            created_at: '2024-01-15'
          },
          {
            id: 3,
            student: { id: 1, name: 'John Doe', student_id: 'STU001' },
            subject: { id: 2, name: 'Science' },
            term: { id: 1, name: 'Term 1' },
            score: 78,
            letter_grade: 'B',
            created_at: '2024-01-16'
          }
        ]
        this.errors = error.response?.data?.errors || {}
      } finally {
        this.loading = false
      }
    },

    async fetchGrade(id) {
      this.loading = true
      try {
        const response = await api.get(`/admin/grades/${id}`)
        this.grade = response.data
      } catch (error) {
        console.error('Fetch grade error:', error)
        this.errors = error.response?.data?.errors || {}
      } finally {
        this.loading = false
      }
    },

    async createGrade(gradeData) {
      this.loading = true
      this.errors = {}
      try {
        const response = await api.post('/admin/grades', gradeData)
        this.grades.unshift(response.data)
        return response.data
      } catch (error) {
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateGrade(id, gradeData) {
      this.loading = true
      this.errors = {}
      try {
        const response = await api.put(`/admin/grades/${id}`, gradeData)
        const index = this.grades.findIndex(g => g.id === id)
        if (index !== -1) {
          this.grades[index] = response.data
        }
        this.grade = response.data
        return response.data
      } catch (error) {
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.loading = false
      }
    },

    async deleteGrade(id) {
      this.loading = true
      try {
        await api.delete(`/admin/grades/${id}`)
        this.grades = this.grades.filter(g => g.id !== id)
      } catch (error) {
        console.error('Delete grade error:', error)
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchSubjects() {
      try {
        const response = await api.get('/subjects')
        this.subjects = response.data.data || response.data
      } catch (error) {
        console.error('Fetch subjects error:', error)
        // Fallback demo data
        this.subjects = [
          { id: 1, name: 'Mathematics' },
          { id: 2, name: 'Science' },
          { id: 3, name: 'English' },
          { id: 4, name: 'History' },
          { id: 5, name: 'Geography' }
        ]
      }
    },

    async fetchTerms() {
      try {
        const response = await api.get('/terms')
        this.terms = response.data.data || response.data
      } catch (error) {
        console.error('Fetch terms error:', error)
        // Fallback demo data
        this.terms = [
          { id: 1, name: 'Term 1' },
          { id: 2, name: 'Term 2' },
          { id: 3, name: 'Term 3' }
        ]
      }
    },

    async fetchStatistics() {
      try {
        const response = await api.get('/admin/grades/statistics')
        this.statistics = response.data
      } catch (error) {
        console.error('Fetch statistics error:', error)
      }
    },

    clearErrors() {
      this.errors = {}
    }
  }
})
