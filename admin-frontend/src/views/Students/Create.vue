<template>
  <Layout>
    <div class="max-w-4xl mx-auto">
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-semibold text-gray-900">Add New Student</h2>
          <router-link
            to="/students"
            class="px-4 py-2 text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50"
          >
            Back to Students
          </router-link>
        </div>

        <form @submit.prevent="submitForm" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                Full Name *
              </label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'border-red-500': errors.name }"
              />
              <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</p>
            </div>

            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                Email Address *
              </label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'border-red-500': errors.email }"
              />
              <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
            </div>

            <!-- Password -->
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Password *
              </label>
              <input
                id="password"
                v-model="form.password"
                type="password"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'border-red-500': errors.password }"
              />
              <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password[0] }}</p>
            </div>

            <!-- Student ID -->
            <div>
              <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">
                Student ID
              </label>
              <input
                id="student_id"
                v-model="form.student_id"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'border-red-500': errors.student_id }"
              />
              <p v-if="errors.student_id" class="mt-1 text-sm text-red-600">{{ errors.student_id[0] }}</p>
            </div>

            <!-- Phone -->
            <div>
              <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                Phone Number
              </label>
              <input
                id="phone"
                v-model="form.phone"
                type="tel"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'border-red-500': errors.phone }"
              />
              <p v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone[0] }}</p>
            </div>

            <!-- Date of Birth -->
            <div>
              <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">
                Date of Birth
              </label>
              <input
                id="date_of_birth"
                v-model="form.date_of_birth"
                type="date"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'border-red-500': errors.date_of_birth }"
              />
              <p v-if="errors.date_of_birth" class="mt-1 text-sm text-red-600">{{ errors.date_of_birth[0] }}</p>
            </div>

            <!-- Gender -->
            <div>
              <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">
                Gender
              </label>
              <select
                id="gender"
                v-model="form.gender"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'border-red-500': errors.gender }"
              >
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
              <p v-if="errors.gender" class="mt-1 text-sm text-red-600">{{ errors.gender[0] }}</p>
            </div>

            <!-- Class -->
            <div>
              <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">
                Class
              </label>
              <select
                id="class_id"
                v-model="form.class_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'border-red-500': errors.class_id }"
              >
                <option value="">Select Class</option>
                <option v-for="cls in classes" :key="cls.id" :value="cls.id">
                  {{ cls.name }}
                </option>
              </select>
              <p v-if="errors.class_id" class="mt-1 text-sm text-red-600">{{ errors.class_id[0] }}</p>
            </div>

            <!-- Status -->
            <div>
              <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                Status
              </label>
              <select
                id="status"
                v-model="form.status"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="{ 'border-red-500': errors.status }"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
              <p v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status[0] }}</p>
            </div>
          </div>

          <!-- Address -->
          <div>
            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
              Address
            </label>
            <textarea
              id="address"
              v-model="form.address"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :class="{ 'border-red-500': errors.address }"
            ></textarea>
            <p v-if="errors.address" class="mt-1 text-sm text-red-600">{{ errors.address[0] }}</p>
          </div>

          <!-- Guardian Information -->
          <div class="border-t pt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Guardian Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="guardian_name" class="block text-sm font-medium text-gray-700 mb-1">
                  Guardian Name
                </label>
                <input
                  id="guardian_name"
                  v-model="form.guardian_name"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors.guardian_name }"
                />
                <p v-if="errors.guardian_name" class="mt-1 text-sm text-red-600">{{ errors.guardian_name[0] }}</p>
              </div>

              <div>
                <label for="guardian_phone" class="block text-sm font-medium text-gray-700 mb-1">
                  Guardian Phone
                </label>
                <input
                  id="guardian_phone"
                  v-model="form.guardian_phone"
                  type="tel"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors.guardian_phone }"
                />
                <p v-if="errors.guardian_phone" class="mt-1 text-sm text-red-600">{{ errors.guardian_phone[0] }}</p>
              </div>

              <div>
                <label for="guardian_email" class="block text-sm font-medium text-gray-700 mb-1">
                  Guardian Email
                </label>
                <input
                  id="guardian_email"
                  v-model="form.guardian_email"
                  type="email"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors.guardian_email }"
                />
                <p v-if="errors.guardian_email" class="mt-1 text-sm text-red-600">{{ errors.guardian_email[0] }}</p>
              </div>

              <div>
                <label for="guardian_relation" class="block text-sm font-medium text-gray-700 mb-1">
                  Relation
                </label>
                <select
                  id="guardian_relation"
                  v-model="form.guardian_relation"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors.guardian_relation }"
                >
                  <option value="">Select Relation</option>
                  <option value="father">Father</option>
                  <option value="mother">Mother</option>
                  <option value="guardian">Guardian</option>
                  <option value="other">Other</option>
                </select>
                <p v-if="errors.guardian_relation" class="mt-1 text-sm text-red-600">{{ errors.guardian_relation[0] }}</p>
              </div>
            </div>
          </div>

          <!-- Submit buttons -->
          <div class="flex justify-end space-x-4 pt-6 border-t">
            <router-link
              to="/students"
              class="px-6 py-2 text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50"
            >
              Cancel
            </router-link>
            <button
              type="submit"
              :disabled="loading"
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ loading ? 'Creating...' : 'Create Student' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useStudentStore } from '@/stores/student'
import Layout from '@/components/Layout.vue'

const router = useRouter()
const studentStore = useStudentStore()

const form = reactive({
  name: '',
  email: '',
  password: '',
  student_id: '',
  phone: '',
  date_of_birth: '',
  gender: '',
  class_id: '',
  status: 'active',
  address: '',
  guardian_name: '',
  guardian_phone: '',
  guardian_email: '',
  guardian_relation: ''
})

const classes = ref([])
const { loading, errors } = studentStore

const submitForm = async () => {
  studentStore.clearErrors()
  
  try {
    // Map form fields to backend expected format
    const studentData = {
      ...form,
      dob: form.date_of_birth, // Backend expects 'dob', form has 'date_of_birth'
      class: form.class_id      // Backend expects 'class', form has 'class_id'
    }
    await studentStore.createStudent(studentData)
    router.push('/students')
  } catch (error) {
    console.error('Error creating student:', error)
  }
}

const fetchClasses = async () => {
  try {
    // You might want to create a separate classes store
    // For now, we'll use mock data
    classes.value = [
      { id: 1, name: 'Grade 1' },
      { id: 2, name: 'Grade 2' },
      { id: 3, name: 'Grade 3' },
      { id: 4, name: 'Grade 4' },
      { id: 5, name: 'Grade 5' }
    ]
  } catch (error) {
    console.error('Error fetching classes:', error)
  }
}

onMounted(() => {
  fetchClasses()
})
</script>
