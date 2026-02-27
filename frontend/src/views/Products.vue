<template>
	<div>
		<h1>Products</h1>
		<p>Welcome, {{ auth.user?.name }}!</p>
		<button @click="handleLogout">Logout</button>
	</div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const router = useRouter()

auth.fetchUser()

async function handleLogout() {
	await auth.logout()
	router.push('/login')
}
</script>