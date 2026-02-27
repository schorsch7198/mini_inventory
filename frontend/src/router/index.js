import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

import Login from '../views/Login.vue'
import Products from '../views/Products.vue'

const routes = [
	{ path: '/login', name: 'Login', component: Login },
	{ path: '/', name: 'Products', component: Products, meta: { requiresAuth: true } },
]

const router = createRouter({
	history: createWebHistory(),
	routes,
})

// Navigation guard -> kind of security check
router.beforeEach((to) => {
	const auth = useAuthStore()
	if (to.meta.requiresAuth && !auth.isAuthenticated) {
		return { name: 'Login' }
	}
	if (to.name === 'Login' && auth.isAuthenticated) {
		return { name: 'Products' }
	}
})

export default router