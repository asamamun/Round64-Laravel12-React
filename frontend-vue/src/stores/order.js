import { defineStore } from 'pinia'
import api from '../services/api'

export const useOrderStore = defineStore('order', {
  state: () => ({
    orders: [],
    currentOrder: null,
    loading: false,
    error: null
  }),

  getters: {
    orderCount: (state) => state.orders.length
  },

  actions: {
    async fetchOrders() {
      this.loading = true
      this.error = null
      
      try {
        const response = await api.getOrders()
        this.orders = response.data || []
      } catch (error) {
        this.error = error.message
        console.error('Failed to fetch orders:', error)
      } finally {
        this.loading = false
      }
    },

    async fetchOrder(orderId) {
      this.loading = true
      this.error = null
      this.currentOrder = null
      
      try {
        const response = await api.getOrder(orderId)
        this.currentOrder = response.data
      } catch (error) {
        this.error = error.message
        console.error('Failed to fetch order:', error)
      } finally {
        this.loading = false
      }
    },

    async createOrder(orderData) {
      this.loading = true
      this.error = null
      
      try {
        const response = await api.createOrder(orderData)
        // Refresh orders list
        await this.fetchOrders()
        return response
      } catch (error) {
        this.error = error.message
        console.error('Failed to create order:', error)
        throw error
      } finally {
        this.loading = false
      }
    }
  }
})