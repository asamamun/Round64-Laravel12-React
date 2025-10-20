import React, { useState, useEffect } from 'react';
import { Card, Container, Row, Col, Spinner, Alert } from 'react-bootstrap';
import { categoryApi } from '../lib/api';

const CategoryList = () => {
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchCategories = async () => {
      try {
        setLoading(true);
        setError(null);
        
        const response = await categoryApi.getAll();
        
        if (response.success) {
          setCategories(response.data);
        } else {
          throw new Error(response.message || 'Failed to fetch categories');
        }
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchCategories();
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
          <h2>Categories</h2>
        </Col>
      </Row>
      <Row>
        {categories.map((category) => (
          <Col key={category.id} xs={12} sm={6} md={4} lg={3} className="mb-4">
            <Card>
              {category.image && (
                <Card.Img 
                  variant="top" 
                  src={category.image} 
                  alt={category.name}
                  style={{ height: '150px', objectFit: 'cover' }}
                />
              )}
              <Card.Body>
                <Card.Title>{category.name}</Card.Title>
                <Card.Text>
                  {category.products_count} products
                </Card.Text>
              </Card.Body>
            </Card>
          </Col>
        ))}
      </Row>
    </Container>
  );
};

export default CategoryList;