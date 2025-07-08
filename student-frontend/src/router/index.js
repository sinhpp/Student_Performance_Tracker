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

  try {
    const { useUnifiedAuthStore } = await import('@/stores/unifiedAuth')
    const authStore = useUnifiedAuthStore()
    
    const token = localStorage.getItem('auth_token')
    console.log('🔑 Token check:', !!token)
    
    // If no token and route requires auth, go to login
    if (to.meta.requiresAuth && !token) {
      console.log('❌ No token for protected route, redirecting to login')
      if (from.path === '/login') {
        console.log('⚠️  Already from login, allowing to prevent loop')
        next()
        return
      }
      next('/login')
      return
    }
    
    // If going to login and have token, check authentication
    if (to.path === '/login' && token) {
      console.log('🔍 Login page with token, checking auth state')
      
      // Initialize auth store if needed
      if (!authStore.user && token) {
        authStore.token = token
        try {
          await authStore.fetchUser()
          console.log('✅ User fetched:', authStore.user?.role)
        } catch (error) {
          console.log('❌ Invalid token, logging out')
          authStore.logout()
          next()
          return
        }
      }
      
      if (authStore.isAuthenticated && authStore.user) {
        const userRole = authStore.user.role
        console.log('🔀 Authenticated user role:', userRole)
        
        if (userRole === 'admin') {
          console.log('👨‍💼 Admin user, redirecting to admin frontend')
          window.location.href = 'http://localhost:5173'
          return
        } else if (userRole === 'student') {
          console.log('🎓 Student user, redirecting to dashboard')
          next('/dashboard')
          return
        }
      }
    }
    
    // For protected routes with token, verify role
    if (to.meta.role && token) {
      if (!authStore.user) {
        authStore.token = token
        try {
          await authStore.fetchUser()
        } catch (error) {
          console.log('❌ Token invalid during role check, logging out')
          authStore.logout()
          next('/login')
          return
        }
      }
      
      const userRole = authStore.user?.role
      if (userRole !== to.meta.role) {
        console.log('❌ Role mismatch:', userRole, 'vs required', to.meta.role)
        if (userRole === 'admin') {
          window.location.href = 'http://localhost:5173'
          return
        }
        authStore.logout()
        next('/login')
        return
      }
    }
    
    console.log('✅ Navigation allowed')
    next()
  } catch (error) {
    console.error('❌ Navigation guard error:', error)
    next()
  }
})

export default router
