import { defineStore } from 'pinia'
import api from '../axios'

export const useCartStore = defineStore('cart', {
	state: () => ({
		items: [],
		loading: false,
		error: null,
		success: null,
	}),
	
	getters: {
		itemCount: (state) => state.items.reduce((sum, i) => sum + i.quantity, 0),
		
		total: (state) =>
			state.items.reduce((sum, i) => sum + i.price * i.quantity, 0),
	},
	
	actions: {
		addItem(product) {
			const existing = this.items.find((i) => i.product_id === product.id)
			if (existing) {
				if (existing.quantity < product.stock) {
					existing.quantity++
				}
			} else {
				this.items.push({
					product_id: product.id,
					name: product.name,
					price: Number(product.price),
					stock: product.stock,
					quantity: 1,
				})
			}
			this.success = null
			this.error = null
		},
		
		updateQuantity(productId, quantity) {
			const item = this.items.find((i) => i.product_id === productId)
			if (!item) return
			if (quantity <= 0) {
				this.removeItem(productId)
			} else if (quantity <= item.stock) {
				item.quantity = quantity
			}
		},
		
		removeItem(productId) {
			this.items = this.items.filter((i) => i.product_id !== productId)
		},
		
		clearCart() {
			this.items = []
		},
		
		async submitOrder() {
			if (this.items.length === 0) return
			
			this.loading = true
			this.error = null
			this.success = null
			
			try {
				const payload = {
					items: this.items.map((i) => ({
						product_id: i.product_id,
						quantity: i.quantity,
					})),
				}
				const { data } = await api.post('/orders', payload)
				this.clearCart()
				this.success = `Order #${data.order.id} placed! Total: $${Number(data.order.total).toFixed(2)}`
				
				// Refresh product list so stock is up to date
				const { useProductStore } = await import('./products')
				const productStore = useProductStore()
				productStore.fetchProducts(productStore.pagination.currentPage)
				
				return data
			} catch (err) {
				this.error = err.response?.data?.message || 'Order failed.'
				throw err
			} finally {
				this.loading = false
			}
		},
	},
})