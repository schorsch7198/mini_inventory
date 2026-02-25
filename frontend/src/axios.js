import axios from 'axios'

const api = axios.create({
	base URL: 'http://localhost:8000/api',
	headers: {
		'Accept': 'application/json',
	},
})

export default api
