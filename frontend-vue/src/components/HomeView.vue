<template>
  <div class="home">
    <div class="jumbotron">
      <h1 class="display-4">Welcome to ShopDown Vue</h1>
      <p class="lead">Your e-commerce solution built with Vue.js and Laravel Sanctum</p>
      <hr class="my-4">
      <p>Browse our products and find what you need.</p>
      <button class="btn btn-primary btn-lg" @click="goToProducts">Browse Products</button>
    </div>
  </div>
</template>

<script setup>
const emit = defineEmits(['navigate'])

const goToProducts = () => {
  emit('navigate', 'products')
}
</script>

<style scoped>
.jumbotron {
  padding: 2rem 1rem;
  margin-bottom: 2rem;
  background-color: #e9ecef;
  border-radius: 0.3rem;
}
</style>