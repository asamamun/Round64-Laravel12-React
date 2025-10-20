import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Card, Button, Spinner, Alert, Carousel, Badge, Table } from 'react-bootstrap';
import { useParams } from 'react-router-dom';
import { toast } from 'react-toastify';
import { productApi } from '../lib/api';
import { useCart } from '../lib/CartContext';

const ProductDetail = () => {
  const { id } = useParams();
  const [product, setProduct] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [selectedImage, setSelectedImage] = useState(0);
  const [addingToCart, setAddingToCart] = useState(false);
  const { addToCart } = useCart();

  useEffect(() => {
    const fetchProduct = async () => {
      try {
        setLoading(true);
        setError(null);
        
        const response = await productApi.getById(id);
        
        if (response.success) {
          setProduct(response.data);
        } else {
          throw new Error(response.message || 'Failed to fetch product');
        }
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchProduct();
  }, [id]);

  const handleAddToCart = async () => {
    if (product) {
      setAddingToCart(true);
      const result = await addToCart(product, 1);
      setAddingToCart(false);
      
      if (result.success) {
        toast.success(result.message);
      } else {
        toast.error('Failed to add to cart: ' + (result.error || 'Unknown error'));
      }
    }
  };

  if (loading) {
    return (
      <Container className="d-flex justify-content-center align-items-center" style={{ height: '400px' }}>
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

  if (!product) {
    return (
      <Container>
        <Alert variant="warning">Product not found</Alert>
      </Container>
    );
  }

  return (
    <Container>
      <Row>
        <Col md={6}>
          {product.images && product.images.length > 0 ? (
            <>
              <Carousel 
                activeIndex={selectedImage} 
                onSelect={(selectedIndex) => setSelectedImage(selectedIndex)}
                interval={null}
              >
                {product.images.map((image, index) => (
                  <Carousel.Item key={index}>
                    <img
                      className="d-block w-100"
                      src={image.image_url}
                      alt={product.name}
                      style={{ height: '400px', objectFit: 'cover' }}
                    />
                  </Carousel.Item>
                ))}
              </Carousel>
              <div className="d-flex mt-2">
                {product.images.map((image, index) => (
                  <img
                    key={index}
                    src={image.image_url}
                    alt={`Thumbnail ${index}`}
                    onClick={() => setSelectedImage(index)}
                    className={`me-2 ${selectedImage === index ? 'border border-primary' : ''}`}
                    style={{ width: '80px', height: '80px', objectFit: 'cover', cursor: 'pointer' }}
                  />
                ))}
              </div>
            </>
          ) : (
            <div className="d-flex justify-content-center align-items-center" style={{ height: '400px' }}>
              <div className="bg-light border d-flex align-items-center justify-content-center" style={{ width: '100%', height: '100%' }}>
                <span className="text-muted">No image available</span>
              </div>
            </div>
          )}
        </Col>
        <Col md={6}>
          <h2>{product.name}</h2>
          <div className="mb-3">
            <Badge bg="primary" className="me-2">{product.category.name}</Badge>
            <Badge bg="secondary">{product.brand.name}</Badge>
          </div>
          <div className="mb-3">
            <span className="h4 text-primary">${product.price}</span>
            {product.original_price && (
              <span className="ms-2 text-decoration-line-through text-muted">${product.original_price}</span>
            )}
          </div>
          <div className="mb-3">
            <span className={`badge ${product.in_stock ? 'bg-success' : 'bg-danger'}`}>
              {product.in_stock ? 'In Stock' : 'Out of Stock'}
            </span>
            {product.featured && (
              <span className="badge bg-warning text-dark ms-2">Featured</span>
            )}
          </div>
          
          <p className="mt-3">{product.description}</p>
          
          <div className="mt-4">
            <Button 
              variant="primary" 
              size="lg"
              onClick={handleAddToCart}
              disabled={!product.in_stock || addingToCart}
              className="me-2"
            >
              {addingToCart ? 'Adding...' : 'Add to Cart'}
            </Button>
          </div>
        </Col>
      </Row>
      
      {/* Product Specifications */}
      {product.specifications && product.specifications.length > 0 && (
        <Row className="mt-5">
          <Col>
            <Card>
              <Card.Header>Specifications</Card.Header>
              <Card.Body>
                <Table striped bordered hover>
                  <tbody>
                    {product.specifications.map((spec, index) => (
                      <tr key={index}>
                        <td><strong>{spec.spec_key}</strong></td>
                        <td>{spec.spec_value}</td>
                      </tr>
                    ))}
                  </tbody>
                </Table>
              </Card.Body>
            </Card>
          </Col>
        </Row>
      )}
      
      {/* Product Reviews */}
      {product.reviews && product.reviews.length > 0 && (
        <Row className="mt-5">
          <Col>
            <Card>
              <Card.Header>Reviews</Card.Header>
              <Card.Body>
                {product.reviews.map((review, index) => (
                  <div key={index} className="mb-3 pb-3 border-bottom">
                    <div className="d-flex justify-content-between">
                      <strong>{review.user.name}</strong>
                      <span className="text-muted">{new Date(review.created_at).toLocaleDateString()}</span>
                    </div>
                    <div className="text-warning">
                      {'★'.repeat(review.rating) + '☆'.repeat(5 - review.rating)}
                    </div>
                    <p className="mt-2">{review.comment}</p>
                  </div>
                ))}
              </Card.Body>
            </Card>
          </Col>
        </Row>
      )}
    </Container>
  );
};

export default ProductDetail;