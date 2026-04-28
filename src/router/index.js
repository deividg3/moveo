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
    name: 'onboarding',
    component: () => import('@/views/onboarding/onboardingflow.vue'),
    meta: { hideNav: true },
  },
 
  
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/loginview.vue'),
    meta: { hideNav: true },
  },
  {
    path: '/home',
    name: 'home',
    component: () => import('@/views/homeview.vue'),
  },
  {
    path: '/test',
    name: 'testselector',
    component: () => import('@/views/test/testselector.vue'),
  },
  {
    path: '/test/session',
    name: 'testsession',
    component: () => import('@/views/test/testsession.vue'),
    meta: { hideNav: true },
  },
  {
    path: '/test/results',
    name: 'testresults',
    component: () => import('@/views/test/testresults.vue'),
    meta: { hideNav: true },
  },
  {
    path: '/test/review',
    name: 'reviewsession',
    component: () => import('@/views/test/reviewsession.vue'),
    meta: { hideNav: true },
  },
  {
    path: '/stats',
    name: 'stats',
    component: () => import('@/views/stats/statsview.vue'),
  },
  {
    path: '/streak-lost',
    name: 'streaklost',
    component: () => import('@/views/streaklost.vue'),
  },
 
  
  { path: '/admin', redirect: '/admin/login' },
  {
    path: '/admin/login',
    name: 'adminlogin',
    component: () => import('@/views/admin/adminlogin.vue'),
    meta: { hideNav: true },
  },
  {
    path: '/admin/dashboard',
    name: 'admindashboard',
    component: () => import('@/views/admin/admindashboard.vue'),
    meta: { hideNav: true, requiresAdmin: true },
  },
  {
    path: '/admin/users',
    name: 'adminusers',
    component: () => import('@/views/admin/adminusers.vue'),
    meta: { hideNav: true, requiresAdmin: true },
  },
  {
    path: '/admin/questions',
    name: 'adminquestions',
    component: () => import('@/views/admin/adminquestions.vue'),
    meta: { hideNav: true, requiresAdmin: true },
  },

  
  {
    path: '/:pathMatch(.*)*',
    name: 'notfound',
    component: () => import('@/views/notfoundview.vue'),
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
  if (!appPublicRoutes.includes(to.path) && !to.path.startsWith('/admin') && to.name !== 'notfound') {
    
    if (!localStorage.getItem('user_authenticated') && window.innerWidth < 1024) {
      return { path: '/login' }
    }
  }
})
 
export default router
 