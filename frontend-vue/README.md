# ShopDown Vue Frontend

This is the Vue.js frontend for the ShopDown e-commerce platform, built with:
- Vue 3 (Composition API)
- Pinia for state management
- Axios for HTTP requests
- Bootstrap 5.3 for styling

## Setup

1. Install dependencies:
   ```bash
   npm install
   ```

2. Run the development server:
   ```bash
   npm run dev
   ```

3. Build for production:
   ```bash
   npm run build
   ```

## Configuration

The frontend is configured to run on port 5175 and connect to the Laravel backend at:
`http://localhost/Project/shopdown/backend/public/`

## Features

- User authentication (login/register)
- Product browsing
- Shopping cart functionality
- Order management
- User dashboard
- API testing utilities

## Project Structure

```
src/
├── components/     # Vue components
├── services/       # API service layer
├── stores/         # Pinia stores
├── App.vue         # Main app component
└── main.js         # Application entry point
```

## Stores

- `auth.js` - Authentication state management
- `cart.js` - Shopping cart state management
- `order.js` - Order state management

## Development

The Vue frontend uses Vite as the build tool. Components are written using the Composition API with `<script setup>` syntax.