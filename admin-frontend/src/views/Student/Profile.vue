<template>
  <StudentLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
            <p class="text-gray-600">View and manage your profile information</p>
          </div>
          <button
            @click="toggleEditMode"
            :disabled="profileStore.isLoading"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2 disabled:opacity-50"
          >
            <svg v-if="editMode" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            <span>{{ editMode ? 'Cancel' : 'Edit Profile' }}</span>
          </button>
        </div>
      </div>

      <!-- Profile Information -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <!-- Profile Image Section -->
        <div class="flex items-center space-x-6 mb-6">
          <div class="relative">
            <div v-if="profileStore.hasProfileImage" class="h-20 w-20 rounded-full overflow-hidden">
              <img 
                :src="profileStore.profileImageUrl" 
                :alt="profileStore.fullName"
                class="h-full w-full object-cover"
              />
            </div>
            <div v-else class="h-20 w-20 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center">
              <span class="text-white font-bold text-2xl">{{ profileStore.fullName?.charAt(0) || 'S' }}</span>
            </div>
            
            <!-- Image Upload/Delete Controls -->
            <div v-if="editMode" class="absolute -bottom-2 -right-2">
              <div class="flex space-x-1">
                <label class="cursor-pointer">
                  <input
                    type="file"
                    accept="image/*"
                    @change="handleImageUpload"
                    class="hidden"
                    :disabled="profileStore.uploading"
                  />
                  <div class="bg-blue-500 hover:bg-blue-600 text-white p-1.5 rounded-full shadow-lg transition-colors">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                  </div>
                </label>
                <button
                  v-if="profileStore.hasProfileImage"
                  @click="deleteImage"
                  :disabled="profileStore.loading"
                  class="bg-red-500 hover:bg-red-600 text-white p-1.5 rounded-full shadow-lg transition-colors disabled:opacity-50"
                >
                  <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </div>
            </div>
            
            <!-- Upload progress indicator -->
            <div v-if="profileStore.uploading" class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center">
              <svg class="animate-spin h-6 w-6 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </div>
          </div>
          
          <div>
            <h2 class="text-xl font-semibold text-gray-900">{{ profileStore.fullName }}</h2>
            <p class="text-gray-600">{{ profile?.email }}</p>
            <span class="inline-flex px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full mt-1">
              {{ profile?.status || 'Active' }} Student
            </span>
          </div>
        </div>

        <!-- Error Messages -->
        <div v-if="Object.keys(profileStore.errors).length > 0" class="mb-6">
          <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
              <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <div class="text-sm text-red-700">
                <div v-for="(errors, field) in profileStore.errors" :key="field">
                  <div v-for="error in (Array.isArray(errors) ? errors : [errors])" :key="error">
                    {{ error }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Profile Form -->
        <form @submit.prevent="saveProfile" class="space-y-6">

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Personal Information -->
            <div>
              <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Full Name</label>
                  <input
                    v-if="editMode"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  />
                  <p v-else class="mt-1 text-sm text-gray-900">{{ profile?.name }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Email</label>
                  <input
                    v-if="editMode"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  />
                  <p v-else class="mt-1 text-sm text-gray-900">{{ profile?.email }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Phone</label>
                  <input
                    v-if="editMode"
                    v-model="form.phone"
                    type="tel"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  />
                  <p v-else class="mt-1 text-sm text-gray-900">{{ profile?.phone || 'Not provided' }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Student ID</label>
                  <p class="mt-1 text-sm text-gray-900">{{ profile?.student_id }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Class</label>
                  <p class="mt-1 text-sm text-gray-900">{{ profile?.class }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                  <input
                    v-if="editMode"
                    v-model="form.dob"
                    type="date"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  />
                  <p v-else class="mt-1 text-sm text-gray-900">{{ profile?.dob || 'Not provided' }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Gender</label>
                  <select
                    v-if="editMode"
                    v-model="form.gender"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                  </select>
                  <p v-else class="mt-1 text-sm text-gray-900 capitalize">{{ profile?.gender || 'Not specified' }}</p>
                </div>
              </div>
            </div>

            <!-- Academic Information -->
            <div>
              <h3 class="text-lg font-medium text-gray-900 mb-4">Academic Information</h3>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Enrollment Date</label>
                  <p class="mt-1 text-sm text-gray-900">{{ profile?.created_at ? new Date(profile.created_at).toLocaleDateString() : 'N/A' }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Academic Year</label>
                  <p class="mt-1 text-sm text-gray-900">2024-2025</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Overall Grade</label>
                  <p class="mt-1 text-sm text-gray-900">A- (85.2%)</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Attendance Rate</label>
                  <p class="mt-1 text-sm text-gray-900">94%</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Status</label>
                  <span class="inline-flex px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                    {{ profile?.status || 'Active' }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Address -->
          <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700">Address</label>
            <textarea
              v-if="editMode"
              v-model="form.address"
              rows="3"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              placeholder="Enter your address"
            ></textarea>
            <p v-else class="mt-1 text-sm text-gray-900">{{ profile?.address || 'Not provided' }}</p>
          </div>

          <!-- Save/Cancel Buttons -->
          <div v-if="editMode" class="mt-6 flex justify-end space-x-3">
            <button
              type="button"
              @click="toggleEditMode"
              class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="profileStore.loading"
              class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
            >
              <span v-if="profileStore.loading" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Saving...
              </span>
              <span v-else>Save Changes</span>
            </button>
          </div>
        </form>
      </div>

      <!-- Guardian Information -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Guardian Information</h3>
        <form @submit.prevent="saveProfile">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Guardian Name</label>
              <input
                v-if="editMode"
                v-model="form.guardian_name"
                type="text"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              />
              <p v-else class="mt-1 text-sm text-gray-900">{{ profile?.guardian_name || 'Not provided' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Relationship</label>
              <input
                v-if="editMode"
                v-model="form.guardian_relation"
                type="text"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              />
              <p v-else class="mt-1 text-sm text-gray-900">{{ profile?.guardian_relation || 'Not provided' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Phone Number</label>
              <input
                v-if="editMode"
                v-model="form.guardian_phone"
                type="tel"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              />
              <p v-else class="mt-1 text-sm text-gray-900">{{ profile?.guardian_phone || 'Not provided' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input
                v-if="editMode"
                v-model="form.guardian_email"
                type="email"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              />
              <p v-else class="mt-1 text-sm text-gray-900">{{ profile?.guardian_email || 'Not provided' }}</p>
            </div>
          </div>
        </form>
      </div>

      <!-- Success Message -->
      <div v-if="showSuccessMessage" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <div class="flex items-center">
          <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          Profile updated successfully!
        </div>
      </div>
    </div>
  </StudentLayout>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { storeToRefs } from 'pinia'
import { useUnifiedAuthStore } from '@/stores/unifiedAuth'
import { useStudentProfileStore } from '@/stores/studentProfile'
import StudentLayout from '@/components/StudentLayout.vue'

const authStore = useUnifiedAuthStore()
const profileStore = useStudentProfileStore()

const editMode = ref(false)
const showSuccessMessage = ref(false)

// Form data
const form = ref({
  name: '',
  email: '',
  phone: '',
  dob: '',
  gender: '',
  address: '',
  guardian_name: '',
  guardian_phone: '',
  guardian_email: '',
  guardian_relation: ''
})

// Get profile from store
const { profile } = storeToRefs(profileStore)

// Initialize form data when profile is loaded
const initializeForm = () => {
  if (profile.value) {
    form.value = {
      name: profile.value.name || '',
      email: profile.value.email || '',
      phone: profile.value.phone || '',
      dob: profile.value.dob || '',
      gender: profile.value.gender || '',
      address: profile.value.address || '',
      guardian_name: profile.value.guardian_name || '',
      guardian_phone: profile.value.guardian_phone || '',
      guardian_email: profile.value.guardian_email || '',
      guardian_relation: profile.value.guardian_relation || ''
    }
  }
}

// Watch for profile changes
watch(profile, initializeForm, { immediate: true })

const toggleEditMode = () => {
  if (editMode.value) {
    // Cancel edit - reset form
    initializeForm()
    profileStore.clearErrors()
  }
  editMode.value = !editMode.value
}

const saveProfile = async () => {
  try {
    await profileStore.updateProfile(form.value)
    editMode.value = false
    showSuccessMessage.value = true
    
    // Hide success message after 3 seconds
    setTimeout(() => {
      showSuccessMessage.value = false
    }, 3000)
  } catch (error) {
    console.error('Failed to update profile:', error)
  }
}

const handleImageUpload = async (event) => {
  const file = event.target.files[0]
  if (!file) return
  
  try {
    // Validate file
    profileStore.validateImageFile(file)
    
    // Upload image
    await profileStore.uploadProfileImage(file)
    
    // Clear the input
    event.target.value = ''
    
    showSuccessMessage.value = true
    setTimeout(() => {
      showSuccessMessage.value = false
    }, 3000)
  } catch (error) {
    console.error('Failed to upload image:', error)
    // Clear the input
    event.target.value = ''
  }
}

const deleteImage = async () => {
  if (confirm('Are you sure you want to delete your profile image?')) {
    try {
      await profileStore.deleteProfileImage()
      showSuccessMessage.value = true
      setTimeout(() => {
        showSuccessMessage.value = false
      }, 3000)
    } catch (error) {
      console.error('Failed to delete image:', error)
    }
  }
}

// Load profile on component mount
onMounted(async () => {
  try {
    await profileStore.fetchProfile()
  } catch (error) {
    console.error('Failed to load profile:', error)
  }
})
</script>
