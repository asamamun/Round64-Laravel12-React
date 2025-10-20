import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Table, Spinner, Alert, Card, Button, Form, Modal } from 'react-bootstrap';
import { orderApi } from '../lib/api';
import { useAuth } from '../lib/AuthContext';
import { toast } from 'react-toastify';

const AdminOrders = () => {
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [showModal, setShowModal] = useState(false);
  const [selectedOrder, setSelectedOrder] = useState(null);
  const [status, setStatus] = useState('');
  const [paymentStatus, setPaymentStatus] = useState('');
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

    if (user && user.role === 'admin') {
      fetchOrders();
    } else {
      setLoading(false);
    }
  }, [user]);

  const handleUpdateStatus = (order) => {
    setSelectedOrder(order);
    setStatus(order.status);
    setPaymentStatus(order.payment_status);
    setShowModal(true);
  };

  const handleSaveStatus = async () => {
    try {
      const response = await orderApi.updateOrder(selectedOrder.id, {
        status,
        payment_status: paymentStatus
      });
      
      if (response.success) {
        // Update the order in the state
        setOrders(orders.map(order => 
          order.id === selectedOrder.id 
            ? { ...order, status, payment_status: paymentStatus } 
            : order
        ));
        
        toast.success('Order status updated successfully!');
        setShowModal(false);
      } else {
        throw new Error(response.message || 'Failed to update order status');
      }
    } catch (err) {
      toast.error(err.message);
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
          Please <a href="/login">login</a> to view orders.
        </Alert>
      </Container>
    );
  }

  if (user.role !== 'admin') {
    return (
      <Container>
        <Alert variant="danger">
          Access denied. You must be an administrator to view this page.
        </Alert>
      </Container>
    );
  }

  if (orders.length === 0) {
    return (
      <Container>
        <Row>
          <Col>
            <h2>All Orders</h2>
            <Alert variant="info">No orders found.</Alert>
          </Col>
        </Row>
      </Container>
    );
  }

  return (
    <Container>
      <Row>
        <Col>
          <h2>All Orders</h2>
        </Col>
      </Row>
      <Row>
        <Col>
          <Table striped bordered hover responsive>
            <thead>
              <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Status</th>
                <th>Payment Status</th>
                <th>Total</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {orders.map(order => (
                <tr key={order.id}>
                  <td>{order.order_number}</td>
                  <td>{order.user?.name || 'N/A'}</td>
                  <td>{new Date(order.created_at).toLocaleDateString()}</td>
                  <td>
                    <span className={`badge bg-${
                      order.status === 'pending' ? 'warning' : 
                      order.status === 'processing' ? 'info' : 
                      order.status === 'shipped' ? 'primary' : 
                      order.status === 'delivered' ? 'success' : 'danger'
                    }`}>
                      {order.status}
                    </span>
                  </td>
                  <td>
                    <span className={`badge bg-${
                      order.payment_status === 'pending' ? 'warning' : 
                      order.payment_status === 'paid' ? 'success' : 
                      order.payment_status === 'failed' ? 'danger' : 'secondary'
                    }`}>
                      {order.payment_status}
                    </span>
                  </td>
                  <td>${order.total}</td>
                  <td>
                    <Button 
                      variant="primary" 
                      size="sm" 
                      onClick={() => handleUpdateStatus(order)}
                    >
                      Update Status
                    </Button>
                  </td>
                </tr>
              ))}
            </tbody>
          </Table>
        </Col>
      </Row>

      {/* Update Status Modal */}
      <Modal show={showModal} onHide={() => setShowModal(false)}>
        <Modal.Header closeButton>
          <Modal.Title>Update Order Status</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          {selectedOrder && (
            <>
              <p><strong>Order #:</strong> {selectedOrder.order_number}</p>
              <p><strong>Customer:</strong> {selectedOrder.user?.name || 'N/A'}</p>
              <Form>
                <Form.Group className="mb-3">
                  <Form.Label>Status</Form.Label>
                  <Form.Select 
                    value={status} 
                    onChange={(e) => setStatus(e.target.value)}
                  >
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                  </Form.Select>
                </Form.Group>
                <Form.Group className="mb-3">
                  <Form.Label>Payment Status</Form.Label>
                  <Form.Select 
                    value={paymentStatus} 
                    onChange={(e) => setPaymentStatus(e.target.value)}
                  >
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="failed">Failed</option>
                    <option value="refunded">Refunded</option>
                  </Form.Select>
                </Form.Group>
              </Form>
            </>
          )}
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={() => setShowModal(false)}>
            Cancel
          </Button>
          <Button variant="primary" onClick={handleSaveStatus}>
            Save Changes
          </Button>
        </Modal.Footer>
      </Modal>
    </Container>
  );
};

export default AdminOrders;