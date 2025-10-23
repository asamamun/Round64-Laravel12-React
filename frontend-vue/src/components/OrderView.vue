<template>
  <div class="orders">
    <h2>My Orders</h2>
    
    <div v-if="orderStore.loading" class="text-center">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    
    <div v-else-if="orderStore.error" class="alert alert-danger" role="alert">
      {{ orderStore.error }}
    </div>
    
    <div v-else>
      <div v-if="orderStore.orders.length === 0" class="alert alert-info" role="alert">
        You haven't placed any orders yet.
      </div>
      
      <div v-else>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in orderStore.orders" :key="order.id">
                <td>{{ order.id }}</td>
                <td>{{ new Date(order.created_at).toLocaleDateString() }}</td>
                <td>
                  <span class="badge" :class="getStatusClass(order.status)">
                    {{ order.status }}
                  </span>
                </td>
                <td>${{ order.total_amount }}</td>
                <td>
                  <button class="btn btn-primary btn-sm">View Details</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useOrderStore } from '../stores/order'

const orderStore = useOrderStore()

onMounted(() => {
  orderStore.fetchOrders()
})

const getStatusClass = (status) => {
  const statusClasses = {
    'pending': 'bg-warning',
    'processing': 'bg-info',
    'shipped': 'bg-primary',
    'delivered': 'bg-success',
    'cancelled': 'bg-danger'
  }
  return statusClasses[status] || 'bg-secondary'
}
</script>