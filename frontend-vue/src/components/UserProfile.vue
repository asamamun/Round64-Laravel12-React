<template>
  <div class="card">
    <div class="card-header">
      <h5>User Profile</h5>
    </div>
    <div class="card-body">
      <p><strong>Name:</strong> {{ user.name }}</p>
      <p><strong>Email:</strong> {{ user.email }}</p>
      <p><strong>Role:</strong> {{ user.role }}</p>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()
const user = authStore.getUser
</script>