<template>
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="text-center">Register</h3>
        </div>
        <div class="card-body">
          <form @submit.prevent="handleRegister">
            <div class="mb-3">
              <label for="name" class="form-label">Full Name</label>
              <input 
                type="text" 
                class="form-control" 
                id="name" 
                v-model="form.name" 
                required
              >
            </div>
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
            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Confirm Password</label>
              <input 
                type="password" 
                class="form-control" 
                id="password_confirmation" 
                v-model="form.password_confirmation" 
                required
              >
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary" :disabled="loading">
                <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Register
              </button>
            </div>
          </form>
          <div v-if="error" class="alert alert-danger mt-3" role="alert">
            {{ error }}
          </div>
          <div class="mt-3 text-center">
            <p>Already have an account? <a href="#" @click="$emit('update:view', 'login')">Login here</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useAuthStore } from '../stores/auth'

const emit = defineEmits(['register-success', 'update:view'])

const authStore = useAuthStore()

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const loading = ref(false)
const error = ref('')

// Watch for password confirmation match
watch(() => form.value.password_confirmation, (newVal) => {
  if (newVal && newVal !== form.value.password) {
    error.value = 'Passwords do not match'
  } else {
    error.value = ''
  }
})

const handleRegister = async () => {
  if (form.value.password !== form.value.password_confirmation) {
    error.value = 'Passwords do not match'
    return
  }
  
  loading.value = true
  error.value = ''
  
  try {
    const result = await authStore.register(form.value)
    
    if (result.success) {
      emit('register-success')
    } else {
      error.value = result.message
    }
  } catch (err) {
    error.value = 'An error occurred during registration'
    console.error(err)
  } finally {
    loading.value = false
  }
}
</script>