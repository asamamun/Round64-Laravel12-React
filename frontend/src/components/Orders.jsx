import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Table, Spinner, Alert, Card } from 'react-bootstrap';
import { orderApi } from '../lib/api';
import { useAuth } from '../lib/AuthContext';

const Orders = () => {
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const { user } = useAuth();

  useEffect(() => {
    const fetchOrders = async () => {
      try {
        setLoading(true);
        setError(null);
        
        const response = await orderApi.getOrders();
        
        if (response.success) {
          setOrders(response.data);
        } else {
          throw new Error(response.message || 'Failed to fetch orders');
        }
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    if (user) {
      fetchOrders();
    } else {
      setLoading(false);
    }
  }, [user]);

  if (loading) {
    return (
      <Container className="d-flex justify-content-center align-items-center" style={{ height: '200px' }}>
        <Spinner animation="border" role="status">
          <span className="visually-hidden">Loading...</span>
        </Spinner>
      </Container>
    );
  }

  if (error) {
    return (
      <Container>
        <Alert variant="danger">{error}</Alert>
      </Container>
    );
  }

  if (!user) {
    return (
      <Container>
        <Alert variant="warning">
          Please <a href="/login">login</a> to view your orders.
        </Alert>
      </Container>
    );
  }

  if (user.role === 'admin') {
    return (
      <Container>
        <Alert variant="info">
          You are an administrator. <a href="/admin/orders">View all orders</a> to manage customer orders.
        </Alert>
      </Container>
    );
  }

  if (orders.length === 0) {
    return (
      <Container>
        <Row>
          <Col>
            <h2>My Orders</h2>
            <Alert variant="info">You haven't placed any orders yet.</Alert>
          </Col>
        </Row>
      </Container>
    );
  }

  return (
    <Container>
      <Row>
        <Col>
          <h2>My Orders</h2>
        </Col>
      </Row>
      <Row>
        <Col>
          {orders.map(order => (
            <Card key={order.id} className="mb-4">
              <Card.Header>
                <div className="d-flex justify-content-between">
                  <span>Order #{order.order_number}</span>
                  <span>{new Date(order.created_at).toLocaleDateString()}</span>
                </div>
              </Card.Header>
              <Card.Body>
                <Row>
                  <Col md={8}>
                    <Table responsive>
                      <thead>
                        <tr>
                          <th>Product</th>
                          <th>Quantity</th>
                          <th>Price</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        {order.items && order.items.map(item => (
                          <tr key={item.id}>
                            <td>{item.product.name}</td>
                            <td>{item.quantity}</td>
                            <td>${item.price}</td>
                            <td>${(item.price * item.quantity).toFixed(2)}</td>
                          </tr>
                        ))}
                      </tbody>
                    </Table>
                  </Col>
                  <Col md={4}>
                    <Card>
                      <Card.Body>
                        <h5>Order Summary</h5>
                        <div className="d-flex justify-content-between mb-2">
                          <span>Subtotal:</span>
                          <span>${order.subtotal}</span>
                        </div>
                        <div className="d-flex justify-content-between mb-2">
                          <span>Shipping:</span>
                          <span>${order.shipping}</span>
                        </div>
                        <div className="d-flex justify-content-between mb-2">
                          <span>Tax:</span>
                          <span>${order.tax}</span>
                        </div>
                        <div className="d-flex justify-content-between mb-2">
                          <span>Discount:</span>
                          <span>${order.discount}</span>
                        </div>
                        <hr />
                        <div className="d-flex justify-content-between">
                          <strong>Total:</strong>
                          <strong>${order.total}</strong>
                        </div>
                        <div className="mt-3">
                          <strong>Status:</strong>{' '}
                          <span className={`badge bg-${
                            order.status === 'pending' ? 'warning' : 
                            order.status === 'processing' ? 'info' : 
                            order.status === 'shipped' ? 'primary' : 
                            order.status === 'delivered' ? 'success' : 'danger'
                          }`}>
                            {order.status}
                          </span>
                        </div>
                        <div className="mt-2">
                          <strong>Payment Status:</strong>{' '}
                          <span className={`badge bg-${
                            order.payment_status === 'pending' ? 'warning' : 
                            order.payment_status === 'paid' ? 'success' : 
                            order.payment_status === 'failed' ? 'danger' : 'secondary'
                          }`}>
                            {order.payment_status}
                          </span>
                        </div>
                      </Card.Body>
                    </Card>
                  </Col>
                </Row>
              </Card.Body>
            </Card>
          ))}
        </Col>
      </Row>
    </Container>
  );
};

export default Orders;