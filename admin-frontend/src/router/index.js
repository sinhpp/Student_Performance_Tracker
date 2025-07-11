import { createRouter, createWebHistory } from 'vue-router'
import RoleSelection from '@/views/RoleSelection.vue'
import Login from '@/views/Login.vue'
import Register from '@/views/Register.vue'
import Dashboard from '@/views/Dashboard.vue'

const routes = [
    {
        path: '/',
        redirect: (to) => {
            const token = localStorage.getItem('auth_token')
            if (!token) return '/role-selection'
            // Will be handled by navigation guard
            return '/dashboard'
        }
    },
    { 
        path: '/role-selection', 
        name: 'RoleSelection', 
        component: RoleSelection 
    },
    { 
        path: '/login', 
        name: 'Login', 
        component: Login 
    },
    { 
        path: '/register', 
        name: 'Register', 
        component: Register 
    },

    // Admin Routes
    {
        path: '/admin/dashboard',
        component: Dashboard,
        meta: { requiresAuth: true, role: 'admin' }
    },
    {
        path: '/dashboard',
        redirect: '/admin/dashboard'
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

    // Teacher Routes
    {
        path: '/teacher/dashboard',
        component: () => import('@/views/Teacher/Dashboard.vue'),
        meta: { requiresAuth: true, role: 'teacher' }
    },
    {
        path: '/teacher/classes',
        component: () => import('@/views/Teacher/Classes.vue'),
        meta: { requiresAuth: true, role: 'teacher' }
    },
    {
        path: '/teacher/students',
        component: () => import('@/views/Teacher/Students.vue'),
        meta: { requiresAuth: true, role: 'teacher' }
    },
    {
        path: '/teacher/grades',
        component: () => import('@/views/Teacher/Grades.vue'),
        meta: { requiresAuth: true, role: 'teacher' }
    },
    {
        path: '/teacher/profile',
        component: () => import('@/views/Teacher/Profile.vue'),
        meta: { requiresAuth: true, role: 'teacher' }
    },

    // Student Routes
    {
        path: '/student/dashboard',
        component: () => import('@/views/Student/Dashboard.vue'),
        meta: { requiresAuth: true, role: 'student' }
    },
    {
        path: '/student/grades',
        component: () => import('@/views/Student/Grades.vue'),
        meta: { requiresAuth: true, role: 'student' }
    },
    {
        path: '/student/attendance',
        component: () => import('@/views/Student/Attendance.vue'),
        meta: { requiresAuth: true, role: 'student' }
    },
    {
        path: '/student/profile',
        component: () => import('@/views/Student/Profile.vue'),
        meta: { requiresAuth: true, role: 'student' }
    },

    // Add a catch-all route for undefined paths
    { path: '/:pathMatch(.*)*', redirect: '/role-selection' }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

// Navigation guard
router.beforeEach(async (to, from, next) => {
    console.log('🔄 Navigation guard:', {
        to: to.path,
        from: from.path,
        timestamp: new Date().toISOString()
    })

    try {
        const { useUnifiedAuthStore } = await import('@/stores/unifiedAuth')
        const authStore = useUnifiedAuthStore()

        const token = localStorage.getItem('auth_token')
        console.log('🔑 Token check:', !!token)

        // If no token and route requires auth, go to role selection
        if (to.meta.requiresAuth && !token) {
        console.log('❌ No token for protected route, redirecting to role selection')
        if (from.path === '/role-selection') {
        console.log('⚠️  Already from role selection, allowing to prevent loop')
        next()
        return
        }
        next('/role-selection')
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
                    console.log('🎓 Student user, redirecting to student dashboard')
                    next('/student/dashboard')
                    return
                } else if (userRole === 'teacher') {
                    console.log('👨‍🏫 Teacher user, redirecting to teacher dashboard')
                    next('/teacher/dashboard')
                    return
                } else if (userRole === 'admin') {
                    console.log('👨‍💼 Admin user, redirecting to admin dashboard')
                    next('/admin/dashboard')
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
                    console.log('🎓 Redirecting student to student dashboard')
                    next('/student/dashboard')
                    return
                } else if (userRole === 'teacher') {
                    console.log('👨‍🏫 Redirecting teacher to teacher dashboard')
                    next('/teacher/dashboard')
                    return
                } else if (userRole === 'admin') {
                    console.log('👨‍💼 Redirecting admin to admin dashboard')
                    next('/admin/dashboard')
                    return
                }
                // Invalid role, logout
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
