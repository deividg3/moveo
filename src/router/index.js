import { createRouter, createWebHistory } from 'vue-router'
 
const routes = [
  
  {
    path: '/', 
    redirect: () => {
      
      if (window.innerWidth >= 1024) return '/home'
      
      return localStorage.getItem('user_authenticated') ? '/home' : '/onboarding'
    }
  },
  {
    path: '/onboarding',
    name: 'Onboarding',
    component: () => import('@/views/onboarding/OnboardingFlow.vue'),
    meta: { hideNav: true },
  },
 
  
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/views/LoginView.vue'),
    meta: { hideNav: true },
  },
  {
    path: '/home',
    name: 'Home',
    component: () => import('@/views/HomeView.vue'),
  },
  {
    path: '/test',
    name: 'TestSelector',
    component: () => import('@/views/test/TestSelector.vue'),
  },
  {
    path: '/test/session',
    name: 'TestSession',
    component: () => import('@/views/test/TestSession.vue'),
    meta: { hideNav: true },
  },
  {
    path: '/test/results',
    name: 'TestResults',
    component: () => import('@/views/test/TestResults.vue'),
    meta: { hideNav: true },
  },
  {
    path: '/test/review',
    name: 'ReviewSession',
    component: () => import('@/views/test/ReviewSession.vue'),
    meta: { hideNav: true },
  },
  {
    path: '/stats',
    name: 'Stats',
    component: () => import('@/views/stats/StatsView.vue'),
  },
  {
    path: '/streak-lost',
    name: 'StreakLost',
    component: () => import('@/views/StreakLost.vue'),
  },
 
  
  { path: '/admin', redirect: '/admin/login' },
  {
    path: '/admin/login',
    name: 'AdminLogin',
    component: () => import('@/views/admin/AdminLogin.vue'),
    meta: { hideNav: true },
  },
  {
    path: '/admin/dashboard',
    name: 'AdminDashboard',
    component: () => import('@/views/admin/AdminDashboard.vue'),
    meta: { hideNav: true, requiresAdmin: true },
  },
  {
    path: '/admin/users',
    name: 'AdminUsers',
    component: () => import('@/views/admin/AdminUsers.vue'),
    meta: { hideNav: true, requiresAdmin: true },
  },
  {
    path: '/admin/questions',
    name: 'AdminQuestions',
    component: () => import('@/views/admin/AdminQuestions.vue'),
    meta: { hideNav: true, requiresAdmin: true },
  },

  
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('@/views/NotFoundView.vue'),
    meta: { hideNav: true },
  },
]
 
const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior: () => ({ top: 0 }),
})
 
router.beforeEach((to) => {
  
  if (to.meta.requiresAdmin) {
    if (!sessionStorage.getItem('admin_token')) {
      return { path: '/admin/login' }
    }
  }

  
  const appPublicRoutes = ['/login', '/onboarding', '/']
  if (!appPublicRoutes.includes(to.path) && !to.path.startsWith('/admin') && to.name !== 'NotFound') {
    
    if (!localStorage.getItem('user_authenticated') && window.innerWidth < 1024) {
      return { path: '/login' }
    }
  }
})
 
export default router
 