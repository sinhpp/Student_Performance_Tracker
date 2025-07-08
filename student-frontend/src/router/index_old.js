import { createRouter, createWebHistory } from 'vue-router'
import StudentLogin from '@/views/StudentLogin.vue'
import StudentDashboard from '@/views/StudentDashboard.vue'

const routes = [
  { 
    path: '/', 
    redirect: '/login'
  },
  { path: '/login', component: StudentLogin },
  { 
    path: '/dashboard', 
    component: StudentDashboard, 
    meta: { requiresAuth: true, role: 'student' }
  },
  {
    path: '/grades',
    component: () => import('@/views/StudentGrades.vue'),
    meta: { requiresAuth: true, role: 'student' }
  },
  {
    path: '/attendance',
    component: () => import('@/views/StudentAttendance.vue'),
    meta: { requiresAuth: true, role: 'student' }
  },
  {
    path: '/feedback',
    component: () => import('@/views/StudentFeedback.vue'),
    meta: { requiresAuth: true, role: 'student' }
  },
  {
    path: '/profile',
    component: () => import('@/views/StudentProfile.vue'),
    meta: { requiresAuth: true, role: 'student' }
  },
  // Add a catch-all route for undefined paths
  { path: '/:pathMatch(.*)*', redirect: '/login' }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Navigation guard
router.beforeEach(async (to, from, next) => {
  console.log('🔄 STUDENT Navigation guard:', { 
    to: to.path, 
    from: from.path, 
    timestamp: new Date().toISOString() 
  })
  
  const token = localStorage.getItem('auth_token')
  
  // If no token and route requires auth, go to login
  if (to.meta.requiresAuth && !token) {
    console.log('Route requires auth but no token, redirecting to login')
    // Avoid infinite loops by checking if we're already coming from login
    if (from.path === '/login') {
      console.log('Already coming from login, possible race condition. Allowing navigation to prevent loop.')
      next()
      return
    }
    next('/login')
    return
  }
  
  // If going to login and have token, try to redirect to dashboard
  if (to.path === '/login' && token) {
    console.log('Login page accessed with token, checking authentication status')
    // Import here to avoid circular dependency
    try {
      const { useStudentAuthStore } = await import('@/stores/studentAuth')
      const authStore = useStudentAuthStore()
      
      // If no user in store but have token, fetch user first
      if (!authStore.student && token) {
        authStore.token = token
        try {
          await authStore.fetchProfile()
        } catch (error) {
          console.log('Token invalid, removing and allowing login')
          authStore.logout()
          next()
          return
        }
      }
      
      if (authStore.isAuthenticated && authStore.student) {
        console.log('Already logged in, redirecting to dashboard')
        next('/dashboard')
        return
      }
    } catch (error) {
      console.error('Error accessing auth store:', error)
    }
  }
  
  // Check role requirements (simplified since role is explicit from login)
  if (to.meta.role && token) {
    try {
      const { useUnifiedAuthStore } = await import('@/stores/unifiedAuth')
      const authStore = useUnifiedAuthStore()
      
      // If we have a token but no user in store, initialize the store
      if (!authStore.user) {
        authStore.token = token
        try {
          await authStore.fetchUser()
        } catch (error) {
          console.log('Fetch user failed, logging out')
          authStore.logout()
          next('/login')
          return
        }
      }
      
      const userRole = authStore.user?.role
      console.log('Student auth check:', { token: !!token, userRole, hasUser: !!authStore.user })
      
      // Since role is explicit from login, this should rarely happen
      if (userRole !== to.meta.role) {
        console.log('Role mismatch - user is', userRole, 'but route requires', to.meta.role)
        console.log('Logging out and redirecting to login')
        authStore.logout()
        next('/login')
        return
      }
    } catch (error) {
      console.error('Error in role check:', error)
      // If there's an error with store access, just allow navigation
    }
  }
  
  console.log('Allowing navigation')
  next()
})

export default router
