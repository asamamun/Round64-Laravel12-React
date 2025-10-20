import React from 'react';
import { Navbar, Nav, Container, Badge } from 'react-bootstrap';
import { LinkContainer } from 'react-router-bootstrap';
import { useCart } from '../lib/CartContext';
import { useAuth } from '../lib/AuthContext';

const Navigation = () => {
  const { cartItemCount } = useCart();
  const { user, logout } = useAuth();

  return (
    <Navbar bg="dark" variant="dark" expand="lg">
      <Container>
        <LinkContainer to="/">
          <Navbar.Brand>ShopHub</Navbar.Brand>
        </LinkContainer>
        <Navbar.Toggle aria-controls="basic-navbar-nav" />
        <Navbar.Collapse id="basic-navbar-nav">
          <Nav className="me-auto">
            <LinkContainer to="/">
              <Nav.Link>Home</Nav.Link>
            </LinkContainer>
            <LinkContainer to="/categories">
              <Nav.Link>Categories</Nav.Link>
            </LinkContainer>
            <LinkContainer to="/brands">
              <Nav.Link>Brands</Nav.Link>
            </LinkContainer>
            <LinkContainer to="/products">
              <Nav.Link>Products</Nav.Link>
            </LinkContainer>
          </Nav>
          <Nav>
            <LinkContainer to="/cart">
              <Nav.Link>
                Cart
                {cartItemCount > 0 && (
                  <Badge bg="danger" className="ms-1">
                    {cartItemCount}
                  </Badge>
                )}
              </Nav.Link>
            </LinkContainer>
            {user ? (
              <>
                <LinkContainer to="/orders">
                  <Nav.Link>Orders</Nav.Link>
                </LinkContainer>
                {user.role === 'admin' && (
                  <LinkContainer to="/admin/orders">
                    <Nav.Link>Admin Orders</Nav.Link>
                  </LinkContainer>
                )}
                <Nav.Link onClick={logout}>Logout</Nav.Link>
              </>
            ) : (
              <>
                <LinkContainer to="/login">
                  <Nav.Link>Login</Nav.Link>
                </LinkContainer>
                <LinkContainer to="/register">
                  <Nav.Link>Register</Nav.Link>
                </LinkContainer>
              </>
            )}
          </Nav>
        </Navbar.Collapse>
      </Container>
    </Navbar>
  );
};

export default Navigation;