import React, { useState, useEffect } from 'react';
import { Card, Container, Row, Col, Spinner, Alert } from 'react-bootstrap';
import { brandApi } from '../lib/api';

const BrandList = () => {
  const [brands, setBrands] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchBrands = async () => {
      try {
        setLoading(true);
        setError(null);
        
        const response = await brandApi.getAll();
        
        if (response.success) {
          setBrands(response.data);
        } else {
          throw new Error(response.message || 'Failed to fetch brands');
        }
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchBrands();
  }, []);

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
          <h2>Brands</h2>
        </Col>
      </Row>
      <Row>
        {brands.map((brand) => (
          <Col key={brand.id} xs={12} sm={6} md={4} lg={3} className="mb-4">
            <Card>
              {brand.logo && (
                <Card.Img 
                  variant="top" 
                  src={brand.logo} 
                  alt={brand.name}
                  style={{ height: '100px', objectFit: 'contain', padding: '20px' }}
                />
              )}
              <Card.Body>
                <Card.Title>{brand.name}</Card.Title>
                <Card.Text>
                  {brand.products_count} products
                </Card.Text>
              </Card.Body>
            </Card>
          </Col>
        ))}
      </Row>
    </Container>
  );
};

export default BrandList;