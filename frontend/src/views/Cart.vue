<template>
	<div class="layout">
		<header class="topbar">
			<h1>Mini Inventory</h1>
			<div class="topbar-right">
				<router-link to="/" class="nav-link">← Products</router-link>
				<span>{{ auth.user?.name }}</span>
			</div>
		</header>
		
		<main class="container">
			<h2>Shopping Cart</h2>
			
			<!-- Success -->
			<div v-if="cart.success" class="success-msg">
				{{ cart.success }}
				<router-link to="/">Continue shopping</router-link>
			</div>
			
			<!-- Error -->
			<div v-if="cart.error" class="error-msg">{{ cart.error }}</div>
			
			<!-- Empty -->
			<div v-if="cart.items.length === 0 && !cart.success" class="empty">
				Your cart is empty.
				<router-link to="/">Browse products</router-link>
			</div>
			
			<!-- Cart items -->
			<table v-if="cart.items.length > 0">
				<thead>
					<tr>
						<th>Product</th>
						<th>Price</th>
						<th>Quantity</th>
						<th>Subtotal</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="item in cart.items" :key="item.product_id">
						<td>{{ item.name }}</td>
						<td>${{ item.price.toFixed(2) }}</td>
						<td class="qty-cell">
							<button class="qty-btn" @click="cart.updateQuantity(item.product_id, item.quantity - 1)">−</button>
							<input
							type="number"
							:value="item.quantity"
							min="1"
							:max="item.stock"
							@change="cart.updateQuantity(item.product_id, Number($event.target.value))"
							class="qty-input"
							/>
							<button class="qty-btn" @click="cart.updateQuantity(item.product_id, item.quantity + 1)" :disabled="item.quantity >= item.stock">+</button>
							<span class="stock-hint">max {{ item.stock }}</span>
						</td>
						<td>${{ (item.price * item.quantity).toFixed(2) }}</td>
						<td>
							<button class="btn-remove" @click="cart.removeItem(item.product_id)">✕</button>
						</td>
					</tr>
				</tbody>
			</table>
			
			<!-- Footer -->
			<div v-if="cart.items.length > 0" class="cart-footer">
				<div class="cart-total">
					Total: <strong>${{ cart.total.toFixed(2) }}</strong>
				</div>
				<div class="cart-actions">
					<button class="btn-secondary" @click="cart.clearCart()">Clear Cart</button>
					<button class="btn-primary" @click="cart.submitOrder()" :disabled="cart.loading">
						{{ cart.loading ? 'Placing Order...' : 'Place Order' }}
					</button>
				</div>
			</div>
		</main>
	</div>
</template>

<script setup>
import { useAuthStore } from '../stores/auth'
import { useCartStore } from '../stores/cart'

const auth = useAuthStore()
const cart = useCartStore()
</script>

<style scoped>
.layout { min-height: 100vh; background: #1a1a2e; color: #e0e0e0; }

.topbar {
	background: #16213e;
	padding: 0.75rem 1.5rem;
	display: flex;
	justify-content: space-between;
	align-items: center;
	box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.topbar h1 { margin: 0; font-size: 1.25rem; }
.topbar-right { display: flex; align-items: center; gap: 1rem; font-size: 0.9rem; }
.nav-link { color: #4f46e5; text-decoration: none; }
.nav-link:hover { text-decoration: underline; }

.container { max-width: 960px; margin: 1.5rem auto; padding: 0 1rem; }
h2 { margin: 0 0 1rem; font-size: 1.3rem; }

table { width: 100%; border-collapse: collapse; background: #16213e; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.06); }
th, td { padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid #2a2a4a; }
th { background: #0f3460; font-size: 0.8rem; text-transform: uppercase; color: #ccc; }

.qty-cell { display: flex; align-items: center; gap: 0.4rem; }
.qty-btn {
	width: 28px; height: 28px;
	border: 1px solid #2a2a4a;
	background: #16213e;
	color: #e0e0e0;
	border-radius: 4px;
	cursor: pointer;
	font-size: 1rem;
	display: flex; align-items: center; justify-content: center;
}
.qty-btn:hover:not(:disabled) { background: #2a2a4a; }
.qty-btn:disabled { opacity: 0.3; cursor: not-allowed; }
.qty-input {
	width: 48px;
	text-align: center;
	border: 1px solid #2a2a4a;
	background: #16213e;
	color: #e0e0e0;
	border-radius: 4px;
	padding: 0.25rem;
	font-size: 0.9rem;
}
.stock-hint { font-size: 0.75rem; color: #666; }

.btn-remove {
	background: none;
	border: none;
	color: #dc2626;
	cursor: pointer;
	font-size: 1.1rem;
}
.btn-remove:hover { color: #b91c1c; }

.cart-footer {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-top: 1.25rem;
	padding: 1rem;
	background: #16213e;
	border-radius: 8px;
	box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.cart-total { font-size: 1.1rem; }
.cart-actions { display: flex; gap: 0.75rem; }

.btn-primary {
	padding: 0.6rem 1.25rem;
	background: #4f46e5;
	color: white;
	border: none;
	border-radius: 6px;
	cursor: pointer;
	font-size: 0.95rem;
}
.btn-primary:hover { background: #4338ca; }
.btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }

.btn-secondary {
	padding: 0.6rem 1.25rem;
	background: #2a2a4a;
	color: #e0e0e0;
	border: none;
	border-radius: 6px;
	cursor: pointer;
	font-size: 0.95rem;
}
.btn-secondary:hover { background: #3a3a5a; }

.success-msg {
	background: #0a2e1a;
	color: #16a34a;
	padding: 1rem;
	border-radius: 6px;
	margin-bottom: 1rem;
	display: flex;
	justify-content: space-between;
	align-items: center;
}
.success-msg a { color: #4f46e5; }

.error-msg {
	background: #3b1111;
	color: #dc2626;
	padding: 0.6rem;
	border-radius: 6px;
	margin-bottom: 1rem;
	font-size: 0.85rem;
}

.empty { text-align: center; padding: 3rem; color: #666; }
.empty a { color: #4f46e5; }
</style>