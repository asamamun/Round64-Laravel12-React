import axios from 'axios'

// Create axios instance with default configuration
const apiClient = axios.create({
  baseURL: 'http://localhost/Project/shopdown/backend/public/',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true, // Important for Sanctum authentication
})

// Request interceptor to add authorization header
apiClient.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('authToken')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Response interceptor to handle errors
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    console.error('API Error:', error.response || error.message)
    
    // Handle network errors
    if (!error.response) {
      console.error('Network error - please check your connection')
    }
    
    return Promise.reject(error)
  }
)

// Function to get CSRF cookie from Laravel
const getCSRFCookie = async () => {
  try {
    await apiClient.get('sanctum/csrf-cookie')
  } catch (error) {
    console.error('Failed to get CSRF cookie:', error)
  }
}

// Auth API functions
const login = async (credentials) => {
  const response = await apiClient.post('api/login', credentials)
  return response.data
}

const register = async (userData) => {
  const response = await apiClient.post('api/register', userData)
  return response.data
}

const logout = async () => {
  const response = await apiClient.post('api/logout')
  return response.data
}

// Category API functions
const getCategories = async () => {
  const response = await apiClient.get('api/categories')
  return response.data
}

// Brand API functions
const getBrands = async () => {
  const response = await apiClient.get('api/brands')
  return response.data
}

// Product API functions
const getProducts = async (params = {}) => {
  try {
    const response = await apiClient.get('api/products', { params })
    return response.data
  } catch (error) {
    console.error('Error fetching products:', error)
    throw error
  }
}

const getProductById = async (id) => {
  const response = await apiClient.get(`api/products/${id}`)
  return response.data
}

// Cart API functions
const getCart = async () => {
  const response = await apiClient.get('api/cart')
  return response.data
}

const addToCart = async (productId, quantity = 1) => {
  const response = await apiClient.post('api/cart/add', { 
    product_id: productId, 
    quantity 
  })
  return response.data
}

const updateCart = async (cartItemId, quantity) => {
  const response = await apiClient.post('api/cart/update', {
    cart_item_id: cartItemId,
    quantity
  })
  return response.data
}

const removeFromCart = async (cartItemId) => {
  const response = await apiClient.post('api/cart/remove', {
    cart_item_id: cartItemId
  })
  return response.data
}

// Order API functions
const getOrders = async () => {
  const response = await apiClient.get('api/orders')
  return response.data
}

const createOrder = async (orderData) => {
  const response = await apiClient.post('api/orders', orderData)
  return response.data
}

const getOrder = async (orderId) => {
  const response = await apiClient.get(`api/orders/${orderId}`)
  return response.data
}

export default {
  // Base client
  client: apiClient,
  
  // CSRF
  getCSRFCookie,
  
  // Auth
  login,
  register,
  logout,
  
  // Categories
  getCategories,
  
  // Brands
  getBrands,
  
  // Products
  getProducts,
  getProductById,
  
  // Cart
  getCart,
  addToCart,
  updateCart,
  removeFromCart,
  
  // Orders
  getOrders,
  createOrder,
  getOrder,
  
  // Generic methods
  get: apiClient.get,
  post: apiClient.post,
  put: apiClient.put,
  delete: apiClient.delete
}