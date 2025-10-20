# Role-Based Order Management

This document explains how the role-based order management system works in the ShopDown e-commerce platform.

## User Roles

The system supports two user roles:
1. **Customer** - Regular users who can place orders
2. **Admin** - Administrators who can manage all orders

## Functionality by Role

### Customer Role
Customers have the following order-related capabilities:
- View their own orders only
- See order details including status and payment status
- Cannot modify order status

### Admin Role
Administrators have the following order-related capabilities:
- View all orders from all customers
- Update order status (pending, processing, shipped, delivered, cancelled)
- Update payment status (pending, paid, failed, refunded)

## Implementation Details

### Backend (Laravel)

1. **OrderController.php** - Modified to handle role-based access:
   - `index()` method returns all orders for admins, user's orders for customers
   - `show()` method allows admins to view any order, customers can only view their own
   - `update()` method restricted to admins only for updating order status

2. **API Routes** - Added PUT route for order updates:
   ```php
   Route::put('/orders/{order}', [OrderController::class, 'update']);
   ```

3. **Database** - Orders table already has the required fields:
   - `status` (enum: pending, processing, shipped, delivered, cancelled)
   - `payment_status` (enum: pending, paid, failed, refunded)

### Frontend (React)

1. **Orders.jsx** - Customer-facing order list:
   - Displays only the authenticated user's orders
   - Shows order status with color-coded badges
   - Redirects admins to the admin orders page

2. **AdminOrders.jsx** - Admin-facing order management:
   - Displays all orders from all customers
   - Provides modal interface for updating order status
   - Uses color-coded badges for quick status identification

3. **Navigation.jsx** - Conditional navigation:
   - Shows "Admin Orders" link only for users with admin role

4. **api.js** - Added updateOrder method:
   - PUT request to update order status and payment status

## Testing

To test the role-based functionality:

1. Create users with different roles:
   - Run the UserRoleSeeder: `php artisan db:seed --class=UserRoleSeeder`
   - Or manually set the `role` field in the users table

2. Place an order as a customer

3. Log in as an admin and view all orders

4. Update an order status as an admin

5. Log in as the customer and verify the status update is visible

## API Endpoints

### Get Orders
```
GET /api/orders
```
- Customers: Returns only their orders
- Admins: Returns all orders

### Get Order Details
```
GET /api/orders/{id}
```
- Customers: Can only view their own orders
- Admins: Can view any order

### Update Order Status
```
PUT /api/orders/{id}
```
- Restricted to admin users only
- Required fields:
  - `status` (pending, processing, shipped, delivered, cancelled)
  - `payment_status` (pending, paid, failed, refunded)