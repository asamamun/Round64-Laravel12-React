import React, { createContext, useState, useContext, useEffect } from 'react';
import { toast } from 'react-toastify';
import { cartApi } from './api';
import { useAuth } from './AuthContext';

// Create the Cart Context
const CartContext = createContext();

// Cart Provider Component
export const CartProvider = ({ children }) => {
  const [cart, setCart] = useState([]);
  const [loading, setLoading] = useState(false);
  const { isAuthenticated } = useAuth();

  // Load cart data
  const loadCart = async () => {
    if (!isAuthenticated) {
      // For non-authenticated users, use localStorage
      const localCart = localStorage.getItem('cart');
      if (localCart) {
        try {
          setCart(JSON.parse(localCart));
        } catch (err) {
          console.error('Failed to parse local cart:', err);
          localStorage.removeItem('cart');
        }
      }
      return;
    }

    try {
      setLoading(true);
      
      const response = await cartApi.getCart();
      
      if (response.success) {
        setCart(response.data.items || []);
      }
    } catch (err) {
      toast.error('Failed to load cart: ' + err.message);
      console.error('Failed to load cart:', err);
    } finally {
      setLoading(false);
    }
  };

  // Load cart when component mounts or when authentication status changes
  useEffect(() => {
    loadCart();
  }, [isAuthenticated]);

  // Add item to cart
  const addToCart = async (product, quantity = 1) => {
    if (!isAuthenticated) {
      // For non-authenticated users, use localStorage
      const newCart = [...cart];
      const existingItem = newCart.find(item => item.product.id === product.id);
      
      if (existingItem) {
        existingItem.quantity += quantity;
      } else {
        // For localStorage, we create a simplified cart item structure
        newCart.push({ 
          id: 'local_' + Date.now() + '_' + product.id, // Unique ID for localStorage items
          product, 
          quantity 
        });
      }
      
      setCart(newCart);
      localStorage.setItem('cart', JSON.stringify(newCart));
      return { success: true, message: 'Item added to cart (saved locally). Please log in to sync your cart.', fallback: true };
    }

    // Check if we have an auth token
    const token = localStorage.getItem('authToken');
    if (!token) {
      // User appears authenticated but no token, fallback to localStorage
      const newCart = [...cart];
      const existingItem = newCart.find(item => item.product.id === product.id);
      
      if (existingItem) {
        existingItem.quantity += quantity;
      } else {
        // For localStorage, we create a simplified cart item structure
        newCart.push({ 
          id: 'local_' + Date.now() + '_' + product.id, // Unique ID for localStorage items
          product, 
          quantity 
        });
      }
      
      setCart(newCart);
      localStorage.setItem('cart', JSON.stringify(newCart));
      return { success: true, message: 'Item added to cart (saved locally). Please log in to sync your cart.', fallback: true };
    }

    try {
      setLoading(true);
      
      const response = await cartApi.addToCart(product.id, quantity);
      
      if (response.success) {
        await loadCart(); // Reload cart to get updated data
        return { success: true, message: 'Item added to cart successfully!' };
      } else {
        throw new Error(response.message || 'Failed to add item to cart');
      }
    } catch (err) {
      // If it's a network error or authentication error, fallback to localStorage
      const errorMsg = err.message.toLowerCase();
      const shouldFallback = err.message === 'Failed to fetch' || 
                           errorMsg.includes('network') || 
                           errorMsg.includes('unauthenticated') ||
                           errorMsg.includes('401');
      
      if (shouldFallback) {
        const newCart = [...cart];
        const existingItem = newCart.find(item => item.product.id === product.id);
        
        if (existingItem) {
          existingItem.quantity += quantity;
        } else {
          // For localStorage, we create a simplified cart item structure
          newCart.push({ 
            id: 'local_' + Date.now() + '_' + product.id, // Unique ID for localStorage items
            product, 
            quantity 
          });
        }
        
        setCart(newCart);
        localStorage.setItem('cart', JSON.stringify(newCart));
        return { success: true, message: 'Item added to cart (saved locally). Please log in to sync your cart.', fallback: true };
      }
      
      return { success: false, error: err.message };
    } finally {
      setLoading(false);
    }
  };

  // Update item quantity in cart
  const updateCartItem = async (cartItemId, quantity) => {
    if (!isAuthenticated) {
      // For non-authenticated users, use localStorage
      const newCart = [...cart];
      const itemIndex = newCart.findIndex(item => item.id === cartItemId);
      
      if (itemIndex !== -1) {
        if (quantity <= 0) {
          // Remove item if quantity is 0 or less
          newCart.splice(itemIndex, 1);
        } else {
          newCart[itemIndex].quantity = quantity;
        }
        
        setCart(newCart);
        localStorage.setItem('cart', JSON.stringify(newCart));
        return { success: true, message: 'Cart updated successfully!' };
      }
      return { success: true };
    }

    try {
      setLoading(true);
      
      const response = await cartApi.updateCart(cartItemId, quantity);
      
      if (response.success) {
        await loadCart(); // Reload cart to get updated data
        return { success: true, message: 'Cart updated successfully!' };
      } else {
        throw new Error(response.message || 'Failed to update cart item');
      }
    } catch (err) {
      return { success: false, error: err.message };
    } finally {
      setLoading(false);
    }
  };

  // Remove item from cart
  const removeFromCart = async (cartItemId) => {
    if (!isAuthenticated) {
      // For non-authenticated users, use localStorage
      const newCart = [...cart];
      const itemIndex = newCart.findIndex(item => item.id === cartItemId);
      
      if (itemIndex !== -1) {
        newCart.splice(itemIndex, 1);
        setCart(newCart);
        localStorage.setItem('cart', JSON.stringify(newCart));
        return { success: true, message: 'Item removed from cart!' };
      }
      return { success: true };
    }

    try {
      setLoading(true);
      
      const response = await cartApi.removeFromCart(cartItemId);
      
      if (response.success) {
        await loadCart(); // Reload cart to get updated data
        return { success: true, message: 'Item removed from cart!' };
      } else {
        throw new Error(response.message || 'Failed to remove item from cart');
      }
    } catch (err) {
      return { success: false, error: err.message };
    } finally {
      setLoading(false);
    }
  };

  // Calculate cart total
  const cartTotal = () => {
    return cart.reduce((total, item) => {
      return total + (item.product.price * item.quantity);
    }, 0);
  };

  // Calculate total items in cart
  const cartItemCount = () => {
    return cart.reduce((count, item) => count + item.quantity, 0);
  };

  // Context value
  const value = {
    cart,
    loading,
    loadCart,
    addToCart,
    updateCartItem,
    removeFromCart,
    cartTotal: cartTotal(),
    cartItemCount: cartItemCount(),
  };

  return (
    <CartContext.Provider value={value}>
      {children}
    </CartContext.Provider>
  );
};

// Custom hook to use the Cart Context
export const useCart = () => {
  const context = useContext(CartContext);
  
  if (context === undefined) {
    throw new Error('useCart must be used within a CartProvider');
  }
  
  return context;
};

export default CartContext;