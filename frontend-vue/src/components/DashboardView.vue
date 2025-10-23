<template>
  <div class="dashboard">
    <h2>Dashboard</h2>
    <div class="row">
      <div class="col-md-6">
        <UserProfile />
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h5>Quick Actions</h5>
          </div>
          <div class="card-body">
            <button class="btn btn-primary me-2" @click="goToProducts">Browse Products</button>
            <button class="btn btn-secondary">View Orders</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import UserProfile from './UserProfile.vue'

const emit = defineEmits(['navigate'])

const goToProducts = () => {
  emit('navigate', 'products')
}
</script>