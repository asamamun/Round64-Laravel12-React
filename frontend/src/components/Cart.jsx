import React from 'react';
import { Container, Row, Col, Table, Button, Alert, Spinner } from 'react-bootstrap';
import { useCart } from '../lib/CartContext';
import { useAuth } from '../lib/AuthContext';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';

const Cart = () => {
  const { cart, loading, updateCartItem, removeFromCart, cartTotal } = useCart();
  const { isAuthenticated } = useAuth();
  const navigate = useNavigate();

  const handleQuantityChange = async (cartItemId, newQuantity) => {
    if (newQuantity < 1) {
      handleRemoveItem(cartItemId);
    } else {
      const result = await updateCartItem(cartItemId, newQuantity);
      if (result.success) {
        toast.success(result.message || 'Cart updated successfully!');
      } else {
        toast.error(result.error || 'Failed to update cart');
      }
    }
  };

  const handleRemoveItem = async (cartItemId) => {
    const result = await removeFromCart(cartItemId);
    if (result.success) {
      toast.success(result.message || 'Item removed from cart!');
    } else {
      toast.error(result.error || 'Failed to remove item from cart');
    }
  };

  const handleCheckout = () => {
    if (isAuthenticated) {
      navigate('/checkout');
    } else {
      navigate('/login');
    }
  };

  if (loading) {
    return (
      <Container className="d-flex justify-content-center align-items-center" style={{ height: '200px' }}>
        <Spinner animation="border" role="status">
          <span className="visually-hidden">Loading...</span>
        </Spinner>
      </Container>
    );
  }

  if (cart.length === 0) {
    return (
      <Container>
        <Row>
          <Col>
            <h2>Your Cart</h2>
            <Alert variant="info">Your cart is empty</Alert>
          </Col>
        </Row>
      </Container>
    );
  }

  return (
    <Container>
      <Row>
        <Col>
          <h2>Your Cart</h2>
        </Col>
      </Row>
      <Row>
        <Col xs={12} lg={8}>
          <Table responsive striped bordered hover>
            <thead>
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {cart.map((item) => (
                <tr key={item.id || item.product.id}>
                  <td>
                    <div className="d-flex align-items-center">
                      {item.product.images && item.product.images.length > 0 && (
                        <img 
                          src={item.product.images[0].image_url} 
                          alt={item.product.name}
                          style={{ width: '60px', height: '60px', objectFit: 'cover', marginRight: '15px' }}
                        />
                      )}
                      <div>
                        <div>{item.product.name}</div>
                        <div className="text-muted">{item.product.category.name}</div>
                      </div>
                    </div>
                  </td>
                  <td>${item.product.price}</td>
                  <td>
                    <div className="d-flex align-items-center">
                      <Button 
                        variant="outline-secondary" 
                        size="sm"
                        onClick={() => handleQuantityChange(item.id || item.product.id, item.quantity - 1)}
                      >
                        -
                      </Button>
                      <span className="mx-2">{item.quantity}</span>
                      <Button 
                        variant="outline-secondary" 
                        size="sm"
                        onClick={() => handleQuantityChange(item.id || item.product.id, item.quantity + 1)}
                      >
                        +
                      </Button>
                    </div>
                  </td>
                  <td>${(item.product.price * item.quantity).toFixed(2)}</td>
                  <td>
                    <Button 
                      variant="danger" 
                      size="sm"
                      onClick={() => handleRemoveItem(item.id || item.product.id)}
                    >
                      Remove
                    </Button>
                  </td>
                </tr>
              ))}
            </tbody>
          </Table>
        </Col>
        <Col xs={12} lg={4}>
          <div className="border p-3">
            <h4>Cart Summary</h4>
            <div className="d-flex justify-content-between mb-2">
              <span>Subtotal:</span>
              <span>${cartTotal.toFixed(2)}</span>
            </div>
            <div className="d-flex justify-content-between mb-2">
              <span>Shipping:</span>
              <span>$0.00</span>
            </div>
            <div className="d-flex justify-content-between mb-3">
              <span><strong>Total:</strong></span>
              <span><strong>${cartTotal.toFixed(2)}</strong></span>
            </div>
            <Button 
              variant="success" 
              className="w-100"
              onClick={handleCheckout}
            >
              Proceed to Checkout
            </Button>
          </div>
        </Col>
      </Row>
    </Container>
  );
};

export default Cart;