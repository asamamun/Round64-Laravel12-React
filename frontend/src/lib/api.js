import ApiConfig from '../Api';
import axios from 'axios';

// Create axios instance with default configuration
const apiClient = axios.create({
  baseURL: ApiConfig,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true, // Important for Sanctum authentication
});

// Request interceptor to add authorization header
apiClient.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('authToken');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor to handle errors
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    console.error('API Error:', error.response || error.message);
    return Promise.reject(error);
  }
);

// Function to get CSRF cookie from Laravel
export const getCSRFCookie = async () => {
  try {
    await apiClient.get('sanctum/csrf-cookie');
  } catch (error) {
    console.error('Failed to get CSRF cookie:', error);
  }
};

// Auth API functions
export const authApi = {
  login: async (credentials) => {
    await getCSRFCookie();
    const response = await apiClient.post('api/login', credentials);
    return response.data;
  },
  
  register: async (userData) => {
    await getCSRFCookie();
    const response = await apiClient.post('api/register', userData);
    return response.data;
  },
  
  logout: async () => {
    const response = await apiClient.post('api/logout');
    return response.data;
  },
};

// Category API functions
export const categoryApi = {
  getAll: async () => {
    const response = await apiClient.get('api/public/categories');
    return response.data;
  },
  getById: async (id) => {
    const response = await apiClient.get(`api/public/categories/${id}`);
    return response.data;
  },
};

// Brand API functions
export const brandApi = {
  getAll: async () => {
    const response = await apiClient.get('api/public/brands');
    return response.data;
  },
  getById: async (id) => {
    const response = await apiClient.get(`api/public/brands/${id}`);
    return response.data;
  },
};

// Product API functions
export const productApi = {
  getAll: async (params = {}) => {
    const response = await apiClient.get('api/public/products', { params });
    return response.data;
  },
  getById: async (id) => {
    const response = await apiClient.get(`api/public/products/${id}`);
    return response.data;
  },
};

// Cart API functions
export const cartApi = {
  getCart: async () => {
    const response = await apiClient.get('api/cart');
    return response.data;
  },
  
  addToCart: async (productId, quantity = 1) => {
    const response = await apiClient.post('api/cart/add', { 
      product_id: productId, 
      quantity 
    });
    return response.data;
  },
  
  updateCart: async (cartItemId, quantity) => {
    const response = await apiClient.post('api/cart/update', {
      cart_item_id: cartItemId,
      quantity
    });
    return response.data;
  },
  
  removeFromCart: async (cartItemId) => {
    const response = await apiClient.post('api/cart/remove', {
      cart_item_id: cartItemId
    });
    return response.data;
  },
};

// Order API functions
export const orderApi = {
  getOrders: async () => {
    const response = await apiClient.get('api/orders');
    return response.data;
  },
  
  createOrder: async (orderData) => {
    const response = await apiClient.post('api/orders', orderData);
    return response.data;
  },
  
  getOrder: async (orderId) => {
    const response = await apiClient.get(`api/orders/${orderId}`);
    return response.data;
  },
  
  updateOrder: async (orderId, orderData) => {
    const response = await apiClient.put(`api/orders/${orderId}`, orderData);
    return response.data;
  },
};