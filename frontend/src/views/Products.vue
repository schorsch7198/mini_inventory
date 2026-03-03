<template>
	<div class="layout">
		<header class="topbar">
			<h1>Mini Inventory</h1>
			<div class="topbar-right">
				<router-link to="/cart" class="cart-link">
					🛒 Cart ({{ cart.itemCount }})
				</router-link>
				<span>{{ auth.user?.name }}</span>
				<button class="btn-secondary" @click="handleLogout">Logout</button>
			</div>
		</header>
		
		<main class="container">
			<!-- Search -->
			<div class="toolbar">
				<input type="text" placeholder="Search by name or SKU..." :value="productStore.search"
				@input="onSearch($event.target.value)" />
			</div>
			
			<!-- Error -->
			<div v-if="productStore.error" class="error-msg">{{ productStore.error }}</div>
			
			<!-- Loading -->
			<div v-if="productStore.loading" class="loading">Loading products...</div>
			
			<!-- Table -->
			<table v-else-if="productStore.products.length">
				<thead>
					<tr>
						<th @click="productStore.setSort('name')" class="sortable">
							Name {{ sortIcon('name') }}
						</th>
						<th @click="productStore.setSort('sku')" class="sortable">
							SKU {{ sortIcon('sku') }}
						</th>
						<th @click="productStore.setSort('price')" class="sortable">
							Price {{ sortIcon('price') }}
						</th>
						<th @click="productStore.setSort('stock')" class="sortable">
							Stock {{ sortIcon('stock') }}
						</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="product in productStore.products" :key="product.id">
						<td>{{ product.name }}</td>
						<td><code>{{ product.sku }}</code></td>
						<td>${{ Number(product.price).toFixed(2) }}</td>
						<td>
							<span :class="product.stock <= 5 ? 'stock-low' : ''">
								{{ product.stock }}
							</span>
						</td>
						<td>
							<button class="btn-sm" @click="addToCart(product)">+ Cart</button>
						</td>
					</tr>
				</tbody>
			</table>
			
			<!-- Empty -->
			<div v-else class="empty">No products found.</div>
			
			<!-- Pagination -->
			<div v-if="productStore.pagination.lastPage > 1" class="pagination">
				<button :disabled="productStore.pagination.currentPage <= 1"
				@click="productStore.fetchProducts(productStore.pagination.currentPage - 1)">
				Previous
			</button>
			<span>
				Page {{ productStore.pagination.currentPage }} of {{ productStore.pagination.lastPage }}
				({{ productStore.pagination.total }} items)
			</span>
			<button :disabled="productStore.pagination.currentPage >= productStore.pagination.lastPage"
				@click="productStore.fetchProducts(productStore.pagination.currentPage + 1)">
				Next
			</button>
		</div>
	</main>
</div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useProductStore } from '../stores/products'
import { useCartStore } from '../stores/cart'

const auth = useAuthStore()
const productStore = useProductStore()
const cart = useCartStore()
const router = useRouter()

let searchTimeout = null

onMounted(() => {
	auth.fetchUser()
	productStore.fetchProducts()
})

function onSearch(value) {
	clearTimeout(searchTimeout)
	searchTimeout = setTimeout(() => {
		productStore.setSearch(value)
	}, 300)
}

function sortIcon(field) {
	if (productStore.sort.field !== field) return ''
	return productStore.sort.direction === 'asc' ? '↑' : '↓'
}

function addToCart(product) {
	cart.addItem(product)
}

async function handleLogout() {
	await auth.logout()
	router.push('/login')
}
</script>

<style scoped>
.layout {
	min-height: 100vh;
	background: #1a1a2e;
	color: #e0e0e0;
}

.topbar {
	background: #16213e;
	padding: 0.75rem 1.5rem;
	display: flex;
	justify-content: space-between;
	align-items: center;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.topbar h1 {
	margin: 0;
	font-size: 1.25rem;
}

.topbar-right {
	display: flex;
	align-items: center;
	gap: 1rem;
	font-size: 0.9rem;
}

.container {
	max-width: 960px;
	margin: 1.5rem auto;
	padding: 0 1rem;
}

.cart-link {
	color: #4f46e5;
	text-decoration: none;
	font-weight: 600;
	font-size: 0.9rem;
}
.cart-link:hover { text-decoration: underline; }

.toolbar {
	margin-bottom: 1rem;
}

.toolbar input {
	background: #16213e;
	width: 100%;
	padding: 0.6rem 0.75rem;
	border: 1px solid #2a2a4a;
	color: #e0e0e0;
	border-radius: 6px;
	font-size: 0.95rem;
	box-sizing: border-box;
}

.toolbar input:focus {
	outline: none;
	border-color: #4f46e5;
}

table {
	width: 100%;
	border-collapse: collapse;
	background: #16213e;
	border-radius: 8px;
	overflow: hidden;
	box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
}

th,
td {
	padding: 0.75rem 1rem;
	text-align: left;
	border-bottom: 1px solid #2a2a4a;
}

th {
	background: #0f3460;
	font-size: 0.8rem;
	text-transform: uppercase;
	color: #ccc;
}

.sortable {
	cursor: pointer;
	user-select: none;
}

.sortable:hover {
	color: #4f46e5;
}

code {
	background: #2a2a4a;
	color: #e0e0e0;
	padding: 0.15rem 0.4rem;
	border-radius: 4px;
	font-size: 0.85rem;
}

.stock-low {
	color: #dc2626;
	font-weight: 600;
}

.btn-sm {
	padding: 0.3rem 0.7rem;
	background: #4f46e5;
	color: white;
	border: none;
	border-radius: 4px;
	cursor: pointer;
	font-size: 0.8rem;
}

.btn-sm:hover {
	background: #4338ca;
}

.btn-secondary {
	padding: 0.4rem 0.8rem;
	background: #2a2a4a;
	color: #e0e0e0;
	border: none;
	border-radius: 6px;
	cursor: pointer;
	font-size: 0.85rem;
}

.btn-secondary:hover {
	background: #3a3a5a;
}

.pagination {
	display: flex;
	justify-content: center;
	align-items: center;
	gap: 1rem;
	margin-top: 1rem;
	font-size: 0.9rem;
}

.pagination button {
	padding: 0.4rem 0.8rem;
	border: 1px solid #ddd;
	background: #16213e; 
	border-color: #2a2a4a;
	color: #e0e0e0;
	border-radius: 4px;
	cursor: pointer;
}

.pagination button:disabled {
	opacity: 0.4;
	cursor: not-allowed;
}

.pagination button:hover:not(:disabled) {
	background: #2a2a4a;
}

.loading {
	text-align: center;
	padding: 2rem;
	color: #999;
}

.empty {
	text-align: center;
	padding: 2rem;
	color: #666;
}

.error-msg {
	background: #3b1111;
	color: #dc2626;
	padding: 0.6rem;
	border-radius: 6px;
	margin-bottom: 1rem;
	font-size: 0.85rem;
}
</style>