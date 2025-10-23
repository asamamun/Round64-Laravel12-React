import { defineStore } from 'pinia'
import api from '../services/api'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
    loading: false,
    error: null
  }),

  getters: {
    cartTotal: (state) => {
      return state.items.reduce((total, item) => total + (item.price * item.quantity), 0)
    },
    itemCount: (state) => {
      return state.items.reduce((count, item) => count + item.quantity, 0)
    }
  },

  actions: {
    async fetchCart() {
      this.loading = true
      this.error = null
      
      try {
        const response = await api.getCart()
        this.items = response.data.items || []
      } catch (error) {
        this.error = error.message
        console.error('Failed to fetch cart:', error)
      } finally {
        this.loading = false
      }
    },

    async addToCart(productId, quantity = 1) {
      this.loading = true
      this.error = null
      
      try {
        const response = await api.addToCart(productId, quantity)
        // Refresh the cart after adding an item
        await this.fetchCart()
        return response
      } catch (error) {
        this.error = error.message
        console.error('Failed to add to cart:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateCartItem(cartItemId, quantity) {
      this.loading = true
      this.error = null
      
      try {
        const response = await api.updateCart(cartItemId, quantity)
        // Refresh the cart after updating an item
        await this.fetchCart()
        return response
      } catch (error) {
        this.error = error.message
        console.error('Failed to update cart item:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async removeFromCart(cartItemId) {
      this.loading = true
      this.error = null
      
      try {
        const response = await api.removeFromCart(cartItemId)
        // Refresh the cart after removing an item
        await this.fetchCart()
        return response
      } catch (error) {
        this.error = error.message
        console.error('Failed to remove from cart:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    clearCart() {
      this.items = []
    }
  }
})