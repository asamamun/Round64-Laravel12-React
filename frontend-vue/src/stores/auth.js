import { defineStore } from 'pinia'
import api from '../services/api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('authToken') || null,
    isAuthenticated: false
  }),

  getters: {
    getUser: (state) => state.user,
    getToken: (state) => state.token,
    isLoggedIn: (state) => state.isAuthenticated
  },

  actions: {
    async login(credentials) {
      try {
        // Get CSRF cookie first
        await api.getCSRFCookie()
        
        const response = await api.login(credentials)
        
        if (response.success) {
          this.user = response.user
          this.token = response.token
          this.isAuthenticated = true
          localStorage.setItem('authToken', response.token)
          return { success: true, message: response.message }
        } else {
          return { success: false, message: response.message }
        }
      } catch (error) {
        return { success: false, message: error.response?.data?.message || 'Login failed' }
      }
    },

    async register(userData) {
      try {
        // Get CSRF cookie first
        await api.getCSRFCookie()
        
        const response = await api.register(userData)
        
        if (response.success) {
          return { success: true, message: response.message }
        } else {
          return { success: false, message: response.message }
        }
      } catch (error) {
        return { success: false, message: error.response?.data?.message || 'Registration failed' }
      }
    },

    async logout() {
      try {
        if (this.token) {
          await api.logout()
        }
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        this.user = null
        this.token = null
        this.isAuthenticated = false
        localStorage.removeItem('authToken')
      }
    },

    async checkAuthStatus() {
      if (this.token) {
        try {
          // Try to fetch user data to verify token
          const response = await api.get('/api/user')
          this.user = response.data
          this.isAuthenticated = true
        } catch (error) {
          // Token is invalid, clear auth data
          this.logout()
        }
      }
    }
  }
})