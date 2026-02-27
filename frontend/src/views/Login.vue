<template>
	<div class="login-wrapper">
		<div class="login-card">
			<h1>Mini Inventory</h1>
			<p class="subtitle">Sign in to continue</p>

			<div v-if="auth.error" class="error-msg">{{ auth.error }}</div>

			<form @submit.prevent="handleLogin">
				<div class="field">
					<label for="email">Email</label>
					<input id="email" v-model="email" type="email" required placeholder="test@test.com" />
				</div>
				<div class="field">
					<label for="password">Password</label>
					<input id="password" v-model="password" type="password" required placeholder="••••••" />
				</div>
				<button type="submit" :disabled="auth.loading">
					{{ auth.loading ? 'Signing in...' : 'Sign In' }}
				</button>
			</form>
		</div>
	</div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const router = useRouter()
const email = ref('')
const password = ref('')

async function handleLogin() {
	try {
		await auth.login(email.value, password.value)
		router.push('/')
	} catch {
		// error is already in store
	}
}
</script>

<style scoped>
.login-wrapper {
	min-height: 100vh;
	display: flex;
	align-items: center;
	justify-content: center;
	background: #f5f5f5;
}
.login-card {
	background: white;
	padding: 2.5rem;
	border-radius: 8px;
	box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
	width: 100%;
	max-width: 380px;
}
h1 { margin: 0 0 0.25rem; font-size: 1.5rem; }
.subtitle { color: #666; margin: 0 0 1.5rem; font-size: 0.9rem; }
.field { margin-bottom: 1rem; }
label { display: block; margin-bottom: 0.3rem; font-size: 0.85rem; font-weight: 600; }
input {
	width: 100%;
	padding: 0.6rem 0.75rem;
	border: 1px solid #ddd;
	border-radius: 6px;
	font-size: 0.95rem;
	box-sizing: border-box;
}
input:focus { outline: none; border-color: #4f46e5; }
button {
	width: 100%;
	padding: 0.7rem;
	background: #4f46e5;
	color: white;
	border: none;
	border-radius: 6px;
	font-size: 1rem;
	cursor: pointer;
	margin-top: 0.5rem;
}
button:hover { background: #4338ca; }
button:disabled { opacity: 0.6; cursor: not-allowed; }
.error-msg {
	background: #fef2f2;
	color: #dc2626;
	padding: 0.6rem;
	border-radius: 6px;
	margin-bottom: 1rem;
	font-size: 0.85rem;
}
</style>
