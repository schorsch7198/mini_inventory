import { defineStore } from 'pinia'
import api from '../axios'

export const useAuthStore = defineStore('auth', {
	state: () => ({
		user: null,
		token: localStorage.getItem('token') || null,
		loading: false,
		error: null,
	}),
	
	getters: {
		isAuthenticated: (state) => !!state.token,  // !! - token string converted to boolean
	},
	
	actions: {
		async login(email, password) {
			this.loading = true
			this.error = null
			try {
				const { data } = await api.post('/login', { email, password })
				this.token = data.token
				this.user = data.user
				localStorage.setItem('token', data.token)
			} catch (err) {
				this.error = err.response?.data?.message || 'Login failed.'
				throw err
			} finally {
				this.loading = false
			}
		},
		
		async logout() {
			try {
				await api.post('/logout')
			} catch {
				// token might already be invalid, that's fine
			} finally {
				this.token = null
				this.user = null
				localStorage.removeItem('token')
			}
		},
		
		async fetchUser() {
			try {
				const { data } = await api.get('/user')
				this.user = data
			} catch {
				this.logout()
			}
		},
	},
})