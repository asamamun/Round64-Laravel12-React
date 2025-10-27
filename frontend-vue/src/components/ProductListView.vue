<template>
  <div class="product-list">
    <h2>Products</h2>
    <div v-if="loading" class="text-center">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <div v-else-if="error" class="alert alert-danger" role="alert">
      {{ error }}
    </div>
    <div v-else>
      <div v-if="products.length === 0" class="alert alert-info" role="alert">
        No products available at the moment.
      </div>
      <div v-else class="row">
        <div v-for="product in products" :key="product.id" class="col-md-4 mb-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">{{ product.name }}</h5>
              <p class="card-text">{{ product.description }}</p>
              <p class="card-text"><strong>${{ product.price }}</strong></p>
              <button class="btn btn-primary" @click="addToCart(product.id)">Add to Cart</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useCartStore } from '../stores/cart'
import api from '../services/api'

const cartStore = useCartStore()
const products = ref([])
const loading = ref(false)
const error = ref('')

// Add a flag to prevent multiple simultaneous loads
const isLoading = ref(false)

onMounted(async () => {
  await loadProducts()
})

// Watch for changes that might trigger re-fetching
watch(products, (newProducts) => {
  // Add any logic here if needed when products change
}, { deep: false })

const loadProducts = async () => {
  // Prevent multiple simultaneous loads
  if (isLoading.value) return
  
  isLoading.value = true
  loading.value = true
  error.value = ''
  
  try {
    const response = await api.getProducts()
    products.value = response.data || []
  } catch (err) {
    error.value = 'Failed to load products'
    console.error(err)
  } finally {
    loading.value = false
    isLoading.value = false
  }
}

const addToCart = async (productId) => {
  try {
    await cartStore.addToCart(productId)
    alert('Product added to cart!')
  } catch (err) {
    alert('Failed to add product to cart')
    console.error(err)
  }
}
</script>