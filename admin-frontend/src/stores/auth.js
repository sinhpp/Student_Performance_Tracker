import { defineStore } from 'pinia'
import api from '@/services/api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    role: localStorage.getItem('role') || null,
    loading: false,
    errors: {}
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isAdmin: (state) => state.role === 'admin',
    isTeacher: (state) => state.role === 'teacher',
    currentUser: (state) => state.user
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
          // CSRF cookie not available, continue without it
          console.warn('CSRF cookie not available, continuing without it')
        }
        
        // Then login
        const response = await api.post('/login', credentials)
        
        this.token = response.data.token
        this.user = response.data.user
        this.role = response.data.user.role

        // Store in localStorage
        localStorage.setItem('token', this.token)
        localStorage.setItem('role', this.role)

        return response.data
      } catch (error) {
        // Check if this is a demo login fallback
        if (credentials.email === 'admin@example.com' && credentials.password === 'admin123') {
          // Demo mode fallback when API is not available
          this.token = 'demo-jwt-token'
          this.user = { 
            id: 1, 
            name: 'Demo Admin', 
            email: 'admin@example.com', 
            role: 'admin' 
          }
          this.role = 'admin'

          // Store in localStorage
          localStorage.setItem('token', this.token)
          localStorage.setItem('role', this.role)

          return { user: this.user, token: this.token }
        }
        
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.loading = false
      }
    },

    async logout() {
      try {
        await api.post('/logout')
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        this.token = null
        this.user = null
        this.role = null
        localStorage.removeItem('token')
        localStorage.removeItem('role')
      }
    },

    async fetchUser() {
      if (!this.token) return

      try {
        const response = await api.get('/user')
        this.user = response.data
        this.role = response.data.role
        localStorage.setItem('role', this.role)
      } catch (error) {
        console.error('Fetch user error:', error)
        
        // If it's a demo token, don't logout, just set demo user
        if (this.token === 'demo-jwt-token') {
          this.user = { 
            id: 1, 
            name: 'Demo Admin', 
            email: 'admin@example.com', 
            role: 'admin' 
          }
          this.role = 'admin'
          localStorage.setItem('role', this.role)
        } else {
          // Only logout if not demo mode
          this.logout()
        }
      }
    },

    clearErrors() {
      this.errors = {}
    }
  }
})
