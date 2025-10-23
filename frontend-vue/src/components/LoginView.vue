<template>
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center">Login</h3>
        </div>
        <div class="card-body">
          <form @submit.prevent="handleLogin">
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input 
                type="email" 
                class="form-control" 
                id="email" 
                v-model="form.email" 
                required
              >
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input 
                type="password" 
                class="form-control" 
                id="password" 
                v-model="form.password" 
                required
              >
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary" :disabled="loading">
                <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Login
              </button>
            </div>
          </form>
          <div v-if="error" class="alert alert-danger mt-3" role="alert">
            {{ error }}
          </div>
          <div class="mt-3 text-center">
            <p>Don't have an account? <a href="#" @click="$emit('update:view', 'register')">Register here</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'

const emit = defineEmits(['login-success', 'update:view'])

const authStore = useAuthStore()

const form = ref({
  email: '',
  password: ''
})

const loading = ref(false)
const error = ref('')

const handleLogin = async () => {
  loading.value = true
  error.value = ''
  
  try {
    const result = await authStore.login(form.value)
    
    if (result.success) {
      emit('login-success')
    } else {
      error.value = result.message
    }
  } catch (err) {
    error.value = 'An error occurred during login'
    console.error(err)
  } finally {
    loading.value = false
  }
}
</script>