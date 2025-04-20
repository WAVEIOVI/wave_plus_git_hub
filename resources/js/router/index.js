import ability from '@/plugins/casl/ability'
import { useAuthStore } from '@/stores/auth'
import { setupLayouts } from 'virtual:generated-layouts'
import { createRouter, createWebHistory } from 'vue-router'
import routes from '~pages'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    // Root route that redirects based on user role
    {
      path: '/',
      redirect: to => {
        const userData = JSON.parse(localStorage.getItem('userData') || '{}')
        const roles = userData.role_names || []
        if (roles.includes('Super Admin')) return { name: 'dashboard-super-admin-dashboard' }
        if (roles.includes('Admin')) return { name: 'dashboard-admin-dashboard' }
        if (roles.includes('Operations Manager')) return { name: 'dashboard-operations-manager-dashboard' }
        if (roles.includes('Finance Manager')) return { name: 'dashboard-finance-manager-dashboard' }
        if (roles.includes('Technician')) return { name: 'dashboard-technician-dashboard' }
        if (roles.includes('Client') || roles.includes('Group Client')) return { name: 'dashboard-client-dashboard' }
        
        return { name: 'login', query: to.query }
      },
    },
    ...setupLayouts(routes),
  ],
})

// Global navigation guard
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  const publicRoutes = ['/login', '/register']
  const isPublicRoute = publicRoutes.includes(to.path)

  if (!authStore.user && !isPublicRoute) {
    return next('/login')
  }

  if (authStore.user && isPublicRoute) {
    return next('/')
  }

  if (to.meta.action && to.meta.subject) {
    const subjects = Array.isArray(to.meta.subject)
      ? to.meta.subject
      : [to.meta.subject]
    
    // Allow access if at least one subject passes the check
    const canAccess = subjects.some(subject => ability.can(to.meta.action, subject))
    if (!canAccess) {
      return next({ name: 'not-authorized' })
    }
  }

  next()
})

export default router
