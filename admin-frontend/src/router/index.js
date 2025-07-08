import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/views/Login.vue'
import Dashboard from '@/views/Dashboard.vue'

const routes = [
  { 
    path: '/', 
    redirect: '/login'
  },
  { path: '/login', component: Login },
  { 
    path: '/dashboard', 
    component: Dashboard, 
    meta: { requiresAuth: true, role: 'admin' }
  },
  {
    path: '/students',
    component: () => import('@/views/Students/Index.vue'),
    meta: { requiresAuth: true, role: 'admin' }
  },
  {
    path: '/students/create',
    component: () => import('@/views/Students/Create.vue'),
    meta: { requiresAuth: true, role: 'admin' }
  },
  {
    path: '/students/:id/edit',
    component: () => import('@/views/Students/Edit.vue'),
    meta: { requiresAuth: true, role: 'admin' }
  },
  {
    path: '/grades',
    component: () => import('@/views/Grades/Index.vue'),
    meta: { requiresAuth: true, role: 'admin' }
  },
  {
    path: '/attendance',
    component: () => import('@/views/Attendance/Index.vue'),
    meta: { requiresAuth: true, role: 'admin' }
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
  console.log('🔄 ADMIN Navigation guard:', { 
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
        
        if (userRole === 'student') {
          console.log('🎓 Student user, redirecting to student frontend')
          window.location.href = 'http://localhost:5174'
          return
        } else if (userRole === 'admin') {
          console.log('👨‍💼 Admin user, redirecting to dashboard')
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
        if (userRole === 'student') {
          window.location.href = 'http://localhost:5174'
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
