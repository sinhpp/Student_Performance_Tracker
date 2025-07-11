import { defineStore } from 'pinia'
import api from '@/services/api'

export const useUnifiedAuthStore = defineStore('unifiedAuth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('auth_token') || null,
    loading: false,
    errors: {}
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isAdmin: (state) => state.user?.role === 'admin',
    isStudent: (state) => state.user?.role === 'student',
    userRole: (state) => state.user?.role,
    currentUser: (state) => state.user,
    
    // Get appropriate dashboard route based on role
    dashboardRoute() {
      if (this.isAdmin) return '/admin/dashboard'
      if (this.isStudent) return '/student/dashboard'
      return '/login'
    }
  },

  actions: {
    async login(credentials) {
      this.loading = true
      this.errors = {}

      try {
        // Try to get CSRF token first (optional for some setups)
        try {
          await api.get('/sanctum/csrf-cookie')
        } catch (csrfError) {
          console.warn('CSRF cookie not available, continuing without it')
        }
        
        // Role-based login endpoint
        const response = await api.post('/login', {
          email: credentials.email,
          password: credentials.password,
          role: credentials.role
        })
        
        this.token = response.data.access_token
        this.user = response.data.user

        // Store in localStorage with unified key
        localStorage.setItem('auth_token', this.token)

        return response.data
      } catch (error) {
        // Demo mode fallback for development
        if (credentials.email === 'admin@example.com' && credentials.password === 'admin123' && credentials.role === 'admin') {
          this.token = 'demo-admin-token'
          this.user = { 
            id: 1, 
            name: 'Demo Admin', 
            email: 'admin@example.com', 
            role: 'admin' 
          }
          localStorage.setItem('auth_token', this.token)
          return { user: this.user, access_token: this.token }
        }
        
        if (credentials.email === 'teacher@example.com' && credentials.password === 'teacher123' && credentials.role === 'teacher') {
          this.token = 'demo-teacher-token'
          this.user = { 
            id: 3, 
            name: 'Demo Teacher', 
            email: 'teacher@example.com', 
            role: 'teacher' 
          }
          localStorage.setItem('auth_token', this.token)
          return { user: this.user, access_token: this.token }
        }
        
        if (credentials.email === 'student@example.com' && credentials.password === 'student123' && credentials.role === 'student') {
          this.token = 'demo-student-token'
          this.user = { 
            id: 2, 
            name: 'Demo Student', 
            email: 'student@example.com', 
            role: 'student' 
          }
          localStorage.setItem('auth_token', this.token)
          return { user: this.user, access_token: this.token }
        }
        
        this.errors = error.response?.data?.errors || { general: [error.response?.data?.message || 'Login failed'] }
        throw error
      } finally {
        this.loading = false
      }
    },

    async logout() {
      try {
        if (this.token && !this.token.startsWith('demo-')) {
          await api.post('/logout')
        }
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        this.token = null
        this.user = null
        localStorage.removeItem('auth_token')
        // Also clear old storage keys
        localStorage.removeItem('token')
        localStorage.removeItem('role')
        localStorage.removeItem('student_token')
      }
    },

    async fetchUser() {
      if (!this.token) return

      try {
        const response = await api.get('/me')
        this.user = response.data
      } catch (error) {
        console.error('Fetch user error:', error)
        
        // Handle demo tokens
        if (this.token === 'demo-admin-token') {
          this.user = { 
            id: 1, 
            name: 'Demo Admin', 
            email: 'admin@example.com', 
            role: 'admin' 
          }
        } else if (this.token === 'demo-teacher-token') {
          this.user = { 
            id: 3, 
            name: 'Demo Teacher', 
            email: 'teacher@example.com', 
            role: 'teacher' 
          }
        } else if (this.token === 'demo-student-token') {
          this.user = { 
            id: 2, 
            name: 'Demo Student', 
            email: 'student@example.com', 
            role: 'student' 
          }
        } else {
          // Invalid token, logout
          this.logout()
        }
      }
    },

    clearErrors() {
      this.errors = {}
    },

    // Initialize auth state on app load
    async initializeAuth() {
      if (this.token) {
        await this.fetchUser()
      }
    }
  }
})
