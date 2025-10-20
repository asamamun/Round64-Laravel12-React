import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Card, Button, Spinner, Alert } from 'react-bootstrap';
import { Link } from 'react-router-dom';
import { toast } from 'react-toastify';
import { productApi } from '../lib/api';
import { useCart } from '../lib/CartContext';

const ProductList = () => {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const { addToCart } = useCart();

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        setLoading(true);
        setError(null);
        
        const response = await productApi.getAll();
        
        if (response.success) {
          setProducts(response.data.data || response.data);
        } else {
          throw new Error(response.message || 'Failed to fetch products');
        }
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchProducts();
  }, []);

  const handleAddToCart = async (product) => {
    const result = await addToCart(product, 1);
    if (result.success) {
      toast.success(result.message);
    } else {
      toast.error('Failed to add product to cart: ' + result.error);
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

  return (
    <Container>
      <Row>
        <Col>
          <h2>Products</h2>
        </Col>
      </Row>
      <Row>
        {products.map((product) => (
          <Col key={product.id} xs={12} sm={6} md={4} lg={3} className="mb-4">
            <Card>
              {product.images && product.images.length > 0 && (
                <Link to={`/product/${product.id}`}>
                  <Card.Img 
                    variant="top" 
                    src={product.images[0].image_url} 
                    alt={product.name}
                    style={{ height: '200px', objectFit: 'cover' }}
                  />
                </Link>
              )}
              <Card.Body>
                <Card.Title>
                  <Link to={`/product/${product.id}`} className="text-decoration-none">
                    {product.name}
                  </Link>
                </Card.Title>
                <Card.Text>
                  {product.description && product.description.substring(0, 100)}...
                </Card.Text>
                <div className="d-flex justify-content-between align-items-center">
                  <span className="fw-bold">${product.price}</span>
                  {product.original_price && (
                    <span className="text-decoration-line-through text-muted">${product.original_price}</span>
                  )}
                </div>
                <div className="mt-2">
                  <span className="badge bg-primary">{product.category.name}</span>
                  <span className="badge bg-secondary ms-2">{product.brand.name}</span>
                </div>
                <div className="mt-3">
                  <Button 
                    variant="success" 
                    className="w-100 mb-2"
                    onClick={() => handleAddToCart(product)}
                    disabled={!product.in_stock}
                  >
                    Add to Cart
                  </Button>
                  <Link to={`/product/${product.id}`} className="btn btn-outline-primary w-100">
                    View Details
                  </Link>
                </div>
              </Card.Body>
            </Card>
          </Col>
        ))}
      </Row>
    </Container>
  );
};

export default ProductList;