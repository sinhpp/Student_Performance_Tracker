import { defineStore } from 'pinia'
import api from '@/services/api'

export const useStudentAuthStore = defineStore('studentAuth', {
  state: () => ({
    student: null,
    token: localStorage.getItem('student_token') || null,
    loading: false,
    errors: {}
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    currentStudent: (state) => state.student
  },

  actions: {
    async login(credentials) {
      this.loading = true
      this.errors = {}

      try {
        // First, get CSRF token
        await api.get('/sanctum/csrf-cookie')
        
        // Then login as student
        const response = await api.post('/student/login', credentials)
        
        this.token = response.data.token
        this.student = response.data.student

        // Store in localStorage with different key for student
        localStorage.setItem('student_token', this.token)

        return response.data
      } catch (error) {
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.loading = false
      }
    },

    async logout() {
      try {
        await api.post('/student/logout')
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        this.token = null
        this.student = null
        localStorage.removeItem('student_token')
      }
    },

    async fetchProfile() {
      if (!this.token) return

      try {
        const response = await api.get('/student/profile')
        this.student = response.data
      } catch (error) {
        console.error('Fetch profile error:', error)
        this.logout()
      }
    },

    async updateProfile(profileData) {
      this.loading = true
      this.errors = {}
      
      try {
        const response = await api.put('/student/profile', profileData)
        this.student = response.data
        return response.data
      } catch (error) {
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.loading = false
      }
    },

    clearErrors() {
      this.errors = {}
    }
  }
})
