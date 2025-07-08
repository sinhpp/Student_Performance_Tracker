import axios from "axios";

const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    withCredentials: true, // needed for Sanctum
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    }
})

// Add request interceptor to include token
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('auth_token')
        
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    },
    (error) => {
        return Promise.reject(error)
    }
)

// Add response interceptor to handle errors
api.interceptors.response.use(
    (response) => {
        return response
    },
    (error) => {
        if (error.response?.status === 401) {
            // Token expired or invalid
            localStorage.removeItem('auth_token')
            localStorage.removeItem('token')
            localStorage.removeItem('role')
            localStorage.removeItem('student_token')
            window.location.href = '/login'
        }
        return Promise.reject(error)
    }
)

export default api;
