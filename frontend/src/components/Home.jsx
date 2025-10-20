import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Card, Button, Spinner, Alert, Carousel } from 'react-bootstrap';
import { Link } from 'react-router-dom';
import { toast } from 'react-toastify';
import { categoryApi, brandApi, productApi } from '../lib/api';
import { useCart } from '../lib/CartContext';

const Home = () => {
  const [categories, setCategories] = useState([]);
  const [brands, setBrands] = useState([]);
  const [featuredProducts, setFeaturedProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const { addToCart } = useCart();

  const handleAddToCart = async (product) => {
    const result = await addToCart(product, 1);
    if (result.success) {
      toast.success(result.message);
    } else {
      toast.error('Failed to add product to cart: ' + result.error);
    }
  };

  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);
        setError(null);

        // Fetch categories, brands, and featured products in parallel
        const [categoriesRes, brandsRes, productsRes] = await Promise.all([
          categoryApi.getAll(),
          brandApi.getAll(),
          productApi.getAll({ featured: 1 })
        ]);

        if (categoriesRes.success) {
          setCategories(categoriesRes.data);
        }

        if (brandsRes.success) {
          setBrands(brandsRes.data);
        }

        if (productsRes.success) {
          setFeaturedProducts(productsRes.data.data);
        }
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

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

  return (
    <Container>
      {/* Hero Carousel */}
      <Row className="mb-5">
        <Col>
          <Carousel>
            <Carousel.Item>
              <div className="d-flex align-items-center justify-content-center" style={{ height: '400px', backgroundColor: '#f8f9fa' }}>
                <div className="text-center">
                  <h1>Welcome to ShopHub</h1>
                  <p className="lead">Your one-stop shop for all your needs</p>
                  <Link to="/products" className="btn btn-primary btn-lg">
                    Shop Now
                  </Link>
                </div>
              </div>
            </Carousel.Item>
            <Carousel.Item>
              <div className="d-flex align-items-center justify-content-center" style={{ height: '400px', backgroundColor: '#e9ecef' }}>
                <div className="text-center">
                  <h1>Featured Products</h1>
                  <p className="lead">Check out our latest arrivals</p>
                  <Link to="/products?featured=1" className="btn btn-success btn-lg">
                    View Featured
                  </Link>
                </div>
              </div>
            </Carousel.Item>
          </Carousel>
        </Col>
      </Row>

      {/* Categories Section */}
      <Row className="mb-5">
        <Col>
          <div className="d-flex justify-content-between align-items-center mb-4">
            <h2>Categories</h2>
            <Link to="/categories" className="btn btn-outline-primary">
              View All
            </Link>
          </div>
          <Row>
            {categories.slice(0, 6).map((category) => (
              <Col key={category.id} xs={6} sm={4} md={2} className="mb-4">
                <Card className="h-100 text-center">
                  {category.image && (
                    <Card.Img 
                      variant="top" 
                      src={category.image} 
                      alt={category.name}
                      style={{ height: '100px', objectFit: 'cover' }}/>)}
                  <Card.Body>
                    <Card.Title>
                      <Link to={`/categories`} className="text-decoration-none">
                        {category.name}
                      </Link>
                    </Card.Title>
                  </Card.Body>
                </Card>
              </Col>
            ))}
          </Row>
        </Col>
      </Row>

      {/* Featured Products Section */}
      <Row className="mb-5">
        <Col>
          <div className="d-flex justify-content-between align-items-center mb-4">
            <h2>Featured Products</h2>
            <Link to="/products?featured=1" className="btn btn-outline-primary">
              View All
            </Link>
          </div>
          <Row>
            {featuredProducts.slice(0, 8).map((product) => (
              <Col key={product.id} xs={12} sm={6} md={4} lg={3} className="mb-4">
                <Card className="h-100">
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
                  <Card.Body className="d-flex flex-column">
                    <Card.Title>
                      <Link to={`/product/${product.id}`} className="text-decoration-none">
                        {product.name}
                      </Link>
                    </Card.Title>
                    <Card.Text>
                      {product.description && product.description.substring(0, 50)}...
                    </Card.Text>
                    <div className="mt-auto">
                      <div className="d-flex justify-content-between align-items-center">
                        <span className="fw-bold">${product.price}</span>
                        {product.original_price && (
                          <span className="text-decoration-line-through text-muted">${product.original_price}</span>
                        )}
                      </div>
                      <div className="mt-2">
                        <span className="badge bg-primary">{product.category.name}</span>
                      </div>
                      <div className="d-flex gap-2 mt-3">
                        <Button 
                          variant="success" 
                          size="sm" 
                          onClick={() => handleAddToCart(product)}
                          disabled={!product.in_stock}
                          className="flex-grow-1"
                        >
                          Add to Cart
                        </Button>
                        <Link to={`/product/${product.id}`} className="btn btn-primary btn-sm flex-grow-1">
                          View Details
                        </Link>
                      </div>
                    </div>
                  </Card.Body>
                </Card>
              </Col>
            ))}
          </Row>
        </Col>
      </Row>
    </Container>
  );
};

export default Home;