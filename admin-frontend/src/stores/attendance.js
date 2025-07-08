import { defineStore } from 'pinia'
import api from '@/services/api'

export const useAttendanceStore = defineStore('attendance', {
  state: () => ({
    attendances: [],
    attendance: null,
    loading: false,
    errors: {},
    attendanceStats: {
      totalPresent: 0,
      totalAbsent: 0,
      attendanceRate: 0
    },
    classes: []
  }),

  getters: {
    attendanceByDate: (state) => (date) => {
      return state.attendances.filter(attendance => 
        attendance.date === date
      )
    },
    attendanceByStudent: (state) => (studentId) => {
      return state.attendances.filter(attendance => 
        attendance.student_id === studentId
      )
    }
  },

  actions: {
    async fetchAttendances(filters = {}) {
      this.loading = true
      try {
        const response = await api.get('/admin/attendance', { params: filters })
        this.attendances = response.data.data || response.data
      } catch (error) {
        console.error('Fetch attendances error:', error)
        this.errors = error.response?.data?.errors || {}
      } finally {
        this.loading = false
      }
    },

    async markAttendance(attendanceData) {
      this.loading = true
      this.errors = {}
      try {
        const response = await api.post('/admin/attendance', attendanceData)
        
        // Update existing attendance or add new one
        const existingIndex = this.attendances.findIndex(a => 
          a.student_id === attendanceData.student_id && 
          a.date === attendanceData.date
        )
        
        if (existingIndex !== -1) {
          this.attendances[existingIndex] = response.data
        } else {
          this.attendances.unshift(response.data)
        }
        
        return response.data
      } catch (error) {
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.loading = false
      }
    },

    async bulkMarkAttendance(attendanceList) {
      this.loading = true
      this.errors = {}
      try {
        const response = await api.post('/admin/attendance/bulk', {
          attendances: attendanceList
        })
        
        // Update attendances array
        response.data.forEach(newAttendance => {
          const existingIndex = this.attendances.findIndex(a => 
            a.student_id === newAttendance.student_id && 
            a.date === newAttendance.date
          )
          
          if (existingIndex !== -1) {
            this.attendances[existingIndex] = newAttendance
          } else {
            this.attendances.unshift(newAttendance)
          }
        })
        
        return response.data
      } catch (error) {
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchAttendanceStats(filters = {}) {
      try {
        const response = await api.get('/admin/attendance/stats', { params: filters })
        this.attendanceStats = response.data
      } catch (error) {
        console.error('Fetch attendance stats error:', error)
        // Fallback demo data
        this.attendanceStats = {
          totalPresent: 85,
          totalAbsent: 15,
          attendanceRate: 85.0
        }
      }
    },

    async fetchClasses() {
      try {
        const response = await api.get('/classes')
        this.classes = response.data.data || response.data
      } catch (error) {
        console.error('Fetch classes error:', error)
      }
    },

    clearErrors() {
      this.errors = {}
    }
  }
})
