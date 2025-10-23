<template>
  <div class="cart">
    <h2>Shopping Cart</h2>
    
    <div v-if="cartStore.loading" class="text-center">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    
    <div v-else-if="cartStore.error" class="alert alert-danger" role="alert">
      {{ cartStore.error }}
    </div>
    
    <div v-else>
      <div v-if="cartStore.items.length === 0" class="alert alert-info" role="alert">
        Your cart is empty.
      </div>
      
      <div v-else>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in cartStore.items" :key="item.id">
                <td>{{ item.product.name }}</td>
                <td>${{ item.price }}</td>
                <td>
                  <input 
                    type="number" 
                    min="1" 
                    :value="item.quantity" 
                    @change="updateQuantity(item.id, $event.target.value)"
                    class="form-control"
                    style="width: 80px;"
                  >
                </td>
                <td>${{ (item.price * item.quantity).toFixed(2) }}</td>
                <td>
                  <button class="btn btn-danger btn-sm" @click="removeItem(item.id)">Remove</button>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="3">Total:</th>
                <th>${{ cartStore.cartTotal.toFixed(2) }}</th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>
        
        <div class="d-flex justify-content-end">
          <button class="btn btn-primary me-2">Continue Shopping</button>
          <button class="btn btn-success">Checkout</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useCartStore } from '../stores/cart'

const cartStore = useCartStore()

onMounted(() => {
  cartStore.fetchCart()
})

const updateQuantity = (cartItemId, quantity) => {
  if (quantity > 0) {
    cartStore.updateCartItem(cartItemId, parseInt(quantity))
  }
}

const removeItem = (cartItemId) => {
  cartStore.removeFromCart(cartItemId)
}
</script>