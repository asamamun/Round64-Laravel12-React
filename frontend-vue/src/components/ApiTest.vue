<template>
  <div class="api-test">
    <h2>API Test</h2>
    <div class="mb-3">
      <button class="btn btn-primary me-2" @click="testPublicApi">Test Public API</button>
      <button class="btn btn-secondary" @click="testProtectedApi" :disabled="!authStore.isAuthenticated">Test Protected API</button>
    </div>
    
    <div v-if="loading" class="alert alert-info" role="alert">
      Loading...
    </div>
    
    <div v-if="result" class="alert" :class="result.success ? 'alert-success' : 'alert-danger'" role="alert">
      <h5>Result:</h5>
      <pre>{{ JSON.stringify(result, null, 2) }}</pre>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import api from '../services/api'

const authStore = useAuthStore()
const loading = ref(false)
const result = ref(null)

const testPublicApi = async () => {
  loading.value = true
  result.value = null
  
  try {
    const response = await api.getProducts()
    result.value = {
      success: true,
      data: response.data,
      message: 'Public API test successful'
    }
  } catch (error) {
    result.value = {
      success: false,
      error: error.message,
      message: 'Public API test failed'
    }
  } finally {
    loading.value = false
  }
}

const testProtectedApi = async () => {
  loading.value = true
  result.value = null
  
  try {
    const response = await api.get('/api/user')
    result.value = {
      success: true,
      data: response.data,
      message: 'Protected API test successful'
    }
  } catch (error) {
    result.value = {
      success: false,
      error: error.message,
      message: 'Protected API test failed'
    }
  } finally {
    loading.value = false
  }
}
</script>