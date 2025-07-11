import { defineStore } from 'pinia'
import api from '@/services/api'

export const useTeacherProfileStore = defineStore('teacherProfile', {
  state: () => ({
    profile: null,
    loading: false,
    errors: {},
    uploading: false
  }),

  getters: {
    hasProfileImage: (state) => !!state.profile?.profile_image,
    profileImageUrl: (state) => state.profile?.profile_image || null,
    fullName: (state) => state.profile?.name || '',
    employeeId: (state) => state.profile?.employee_id || '',
    isLoading: (state) => state.loading || state.uploading
  },

  actions: {
    async fetchProfile() {
      this.loading = true
      this.errors = {}
      
      try {
        const response = await api.get('/teacher/profile')
        this.profile = response.data
        return response.data
      } catch (error) {
        console.error('Fetch profile error:', error)
        this.errors = error.response?.data?.errors || { general: ['Failed to fetch profile'] }
        
        // Fallback demo data for development
        this.profile = {
          id: 3,
          name: 'Demo Teacher',
          email: 'teacher@example.com',
          phone: '+1234567891',
          employee_id: 'TCH001',
          department: 'Mathematics',
          specialization: 'Algebra and Calculus',
          hire_date: '2023-01-15',
          address: '123 Teacher Street, Education City',
          status: 'active',
          profile_image: null,
          has_profile_image: false,
          created_at: '2023-01-15T00:00:00.000000Z',
          updated_at: '2024-01-01T00:00:00.000000Z'
        }
        
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateProfile(profileData) {
      this.loading = true
      this.errors = {}
      
      try {
        const response = await api.put('/teacher/profile', profileData)
        this.profile = response.data
        return response.data
      } catch (error) {
        console.error('Update profile error:', error)
        this.errors = error.response?.data?.errors || { general: ['Failed to update profile'] }
        throw error
      } finally {
        this.loading = false
      }
    },

    async uploadProfileImage(imageFile) {
      this.uploading = true
      this.errors = {}
      
      try {
        // Convert file to base64
        const base64Data = await this.fileToBase64(imageFile)
        
        const response = await api.post('/teacher/profile/image', {
          image: base64Data,
          mime_type: imageFile.type
        })
        
        // Update profile with new image
        if (this.profile) {
          this.profile.profile_image = response.data.profile_image
          this.profile.has_profile_image = true
        }
        
        return response.data
      } catch (error) {
        console.error('Upload image error:', error)
        this.errors = error.response?.data?.errors || { general: ['Failed to upload image'] }
        throw error
      } finally {
        this.uploading = false
      }
    },

    async deleteProfileImage() {
      this.loading = true
      this.errors = {}
      
      try {
        await api.delete('/teacher/profile/image')
        
        // Update profile to remove image
        if (this.profile) {
          this.profile.profile_image = null
          this.profile.has_profile_image = false
        }
        
        return true
      } catch (error) {
        console.error('Delete image error:', error)
        this.errors = error.response?.data?.errors || { general: ['Failed to delete image'] }
        throw error
      } finally {
        this.loading = false
      }
    },

    // Helper method to convert file to base64
    fileToBase64(file) {
      return new Promise((resolve, reject) => {
        const reader = new FileReader()
        reader.readAsDataURL(file)
        reader.onload = () => {
          // Remove the data URL prefix (data:image/jpeg;base64,)
          const base64 = reader.result.split(',')[1]
          resolve(base64)
        }
        reader.onerror = error => reject(error)
      })
    },

    // Helper method to validate image file
    validateImageFile(file) {
      const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp']
      const maxSize = 5 * 1024 * 1024 // 5MB
      
      if (!validTypes.includes(file.type)) {
        throw new Error('Invalid file type. Please select a JPEG, PNG, GIF, or WebP image.')
      }
      
      if (file.size > maxSize) {
        throw new Error('File too large. Maximum size is 5MB.')
      }
      
      return true
    },

    clearErrors() {
      this.errors = {}
    },

    resetProfile() {
      this.profile = null
      this.errors = {}
      this.loading = false
      this.uploading = false
    }
  }
})
