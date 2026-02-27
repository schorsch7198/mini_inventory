import { defineStore } from 'pinia'
import api from '../axios'

export const useProductStore = defineStore('products', {
  state: () => ({
	products: [],
	pagination: {
	  currentPage: 1,
	  lastPage: 1,
	  perPage: 10,
	  total: 0,
	},
	search: '',
	sort: { field: 'name', direction: 'asc' },
	loading: false,
	error: null,
  }),

  actions: {
	async fetchProducts(page = 1) {
	  this.loading = true
	  this.error = null
	  try {
		const { data } = await api.get('/products', {
		  params: {
			page,
			per_page: this.pagination.perPage,
			search: this.search || undefined,
			sort: this.sort.field,
			direction: this.sort.direction,
		  },
		})
		this.products = data.data  // data.data = product array (data key)
		this.pagination = {
		  currentPage: data.current_page,
		  lastPage: data.last_page,
		  perPage: data.per_page,
		  total: data.total,
		}
	  } catch (err) {
		this.error = err.response?.data?.message || 'Failed to load products.'
	  } finally {
		this.loading = false
	  }
	},

	setSearch(value) {
	  this.search = value
	  this.fetchProducts(1)
	},

	setSort(field) {
	  if (this.sort.field === field) {
		this.sort.direction = this.sort.direction === 'asc' ? 'desc' : 'asc'  // toggle function
	  } else {
		this.sort.field = field
		this.sort.direction = 'asc'
	  }
	  this.fetchProducts(1)
	},
  },
})