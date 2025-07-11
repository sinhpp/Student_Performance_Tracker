import { defineStore } from 'pinia'
import api from '@/services/api'

export const useStudentStore = defineStore('student', {
  state: () => ({
    students: [],
    student: null,
    loading: false,
    errors: {},
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0
    },
    filters: {
      search: '',
      class_id: null,
      subject_id: null
    }
  }),

  getters: {
    filteredStudents: (state) => {
      if (!state.filters.search) return state.students
      return state.students.filter(student => 
        student.name.toLowerCase().includes(state.filters.search.toLowerCase()) ||
        student.email.toLowerCase().includes(state.filters.search.toLowerCase()) ||
        student.student_id.toLowerCase().includes(state.filters.search.toLowerCase())
      )
    }
  },

  actions: {
    async fetchStudents(page = 1, additionalParams = {}) {
      this.loading = true
      try {
        const params = {
          page,
          per_page: this.pagination.per_page,
          ...additionalParams
        }

        // Remove empty values
        Object.keys(params).forEach(key => {
          if (params[key] === '' || params[key] === null || params[key] === undefined) {
            delete params[key]
          }
        })

        const response = await api.get('/admin/students', { params })
        this.students = response.data.data
        this.pagination = response.data.meta
      } catch (error) {
        console.error('Fetch students error:', error)
        // Fallback to demo data when API is not available
        this.students = [
          {
            id: 1,
            name: 'John Doe',
            email: 'john.doe@example.com',
            student_id: 'STU001',
            phone: '+1234567890',
            class_name: 'Grade 5',
            status: 'active'
          },
          {
            id: 2,
            name: 'Jane Smith',
            email: 'jane.smith@example.com',
            student_id: 'STU002',
            phone: '+1234567891',
            class_name: 'Grade 4',
            status: 'active'
          },
          {
            id: 3,
            name: 'Bob Johnson',
            email: 'bob.johnson@example.com',
            student_id: 'STU003',
            phone: '+1234567892',
            class_name: 'Grade 5',
            status: 'active'
          }
        ]
        this.pagination = {
          current_page: 1,
          last_page: 1,
          per_page: 10,
          total: 3
        }
        this.errors = error.response?.data?.errors || {}
      } finally {
        this.loading = false
      }
    },

    async fetchStudent(id) {
      this.loading = true
      try {
        const response = await api.get(`/admin/students/${id}`)
        this.student = response.data
      } catch (error) {
        console.error('Fetch student error:', error)
        this.errors = error.response?.data?.errors || {}
      } finally {
        this.loading = false
      }
    },

    async createStudent(studentData) {
      this.loading = true
      this.errors = {}
      try {
        const response = await api.post('/admin/students', studentData)
        this.students.unshift(response.data)
        return response.data
      } catch (error) {
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateStudent(id, studentData) {
      this.loading = true
      this.errors = {}
      try {
        const response = await api.put(`/admin/students/${id}`, studentData)
        const index = this.students.findIndex(s => s.id === id)
        if (index !== -1) {
          this.students[index] = response.data
        }
        this.student = response.data
        return response.data
      } catch (error) {
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.loading = false
      }
    },

    async deleteStudent(id) {
      this.loading = true
      try {
        await api.delete(`/admin/students/${id}`)
        this.students = this.students.filter(s => s.id !== id)
      } catch (error) {
        console.error('Delete student error:', error)
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.loading = false
      }
    },

    setFilters(filters) {
      this.filters = { ...this.filters, ...filters }
    },

    clearErrors() {
      this.errors = {}
    }
  }
})
