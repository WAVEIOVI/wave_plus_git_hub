export const authMiddleware = (to, from, next) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
  const isAuthenticated = isUserLoggedIn()
  
  if (requiresAuth && !isAuthenticated) {
    next('/login')
  } else {
    next()
  }
}
