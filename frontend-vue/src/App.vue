<template>
  <div id="app">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="#">ShopDown Vue</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a class="nav-link" href="#" @click="currentView = 'home'">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" @click="currentView = 'products'">Products</a>
            </li>
            <li class="nav-item" v-if="authStore.isAuthenticated">
              <a class="nav-link" href="#" @click="currentView = 'dashboard'">Dashboard</a>
            </li>
            <li class="nav-item" v-if="authStore.isAuthenticated">
              <a class="nav-link" href="#" @click="currentView = 'orders'">Orders ({{ orderStore.orderCount }})</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" @click="currentView = 'cart'">Cart ({{ cartStore.itemCount }})</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" @click="currentView = 'api-test'">API Test</a>
            </li>
          </ul>
          <ul class="navbar-nav">
            <template v-if="!authStore.isAuthenticated">
              <li class="nav-item">
                <a class="nav-link" href="#" @click="currentView = 'login'">Login</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" @click="currentView = 'register'">Register</a>
              </li>
            </template>
            <template v-else>
              <li class="nav-item">
                <a class="nav-link" href="#" @click="logout">Logout</a>
              </li>
            </template>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container mt-4">
      <HomeView v-if="currentView === 'home'" @navigate="handleNavigation" />
      <ProductListView v-else-if="currentView === 'products'" />
      <DashboardView v-else-if="currentView === 'dashboard' && authStore.isAuthenticated" @navigate="handleNavigation" />
      <CartView v-else-if="currentView === 'cart'" />
      <OrderView v-else-if="currentView === 'orders' && authStore.isAuthenticated" />
      <ApiTest v-else-if="currentView === 'api-test'" />
      <LoginView v-else-if="currentView === 'login'" @login-success="handleLoginSuccess" />
      <RegisterView v-else-if="currentView === 'register'" @register-success="handleRegisterSuccess" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from './stores/auth'
import { useCartStore } from './stores/cart'
import { useOrderStore } from './stores/order'
import HomeView from './components/HomeView.vue'
import ProductListView from './components/ProductListView.vue'
import DashboardView from './components/DashboardView.vue'
import CartView from './components/CartView.vue'
import OrderView from './components/OrderView.vue'
import ApiTest from './components/ApiTest.vue'
import LoginView from './components/LoginView.vue'
import RegisterView from './components/RegisterView.vue'

const authStore = useAuthStore()
const cartStore = useCartStore()
const orderStore = useOrderStore()
const currentView = ref('home')

onMounted(() => {
  // Check if user is already authenticated
  authStore.checkAuthStatus()
})

const handleLoginSuccess = () => {
  currentView.value = 'dashboard'
}

const handleRegisterSuccess = () => {
  currentView.value = 'login'
}

const handleNavigation = (view) => {
  currentView.value = view
}

const logout = async () => {
  await authStore.logout()
  currentView.value = 'home'
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
</style>