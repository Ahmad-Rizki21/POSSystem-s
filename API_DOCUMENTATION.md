# API Documentation - POS System

## Overview

POS System menyediakan RESTful API untuk mengelola operasi toko termasuk produk, kategori, transaksi, pengguna, dan laporan.

## Base URL
```
http://localhost:8000/api
```

## Authentication

API menggunakan Laravel Sanctum untuk autentikasi. Include token di header:

```
Authorization: Bearer YOUR_TOKEN_HERE
```

### Get Current User
```http
GET /api/user
Authorization: Bearer {token}
```

## API Endpoints

### Dashboard

#### Get Dashboard Statistics
```http
GET /api/dashboard/stats
Authorization: Bearer {token}
```

**Response:**
```json
{
  "total_products": 150,
  "total_categories": 25,
  "total_transactions": 1200,
  "total_revenue": 15000000,
  "today_transactions": 25,
  "today_revenue": 2500000,
  "low_stock_products": 5,
  "top_products": [
    {
      "id": 1,
      "name": "Product A",
      "total_sold": 50
    }
  ]
}
```

### Products

#### Get All Products
```http
GET /api/products
Authorization: Bearer {token}
```

**Query Parameters:**
- `page` (integer): Page number for pagination
- `per_page` (integer): Items per page (default: 15)
- `search` (string): Search products by name or SKU
- `category_id` (integer): Filter by category
- `sort_by` (string): Sort field (name, price, stock)
- `sort_order` (string): Sort direction (asc, desc)

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Product Name",
      "sku": "PRD-001",
      "description": "Product description",
      "purchase_price": 50000,
      "selling_price": 75000,
      "stock": 100,
      "unit": "pcs",
      "category_id": 1,
      "category": {
        "id": 1,
        "name": "Category Name"
      },
      "image": "products/image.jpg",
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 50,
    "last_page": 4
  }
}
```

#### Create Product
```http
POST /api/products
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "Product Name",
  "sku": "PRD-001",
  "description": "Product description",
  "purchase_price": 50000,
  "selling_price": 75000,
  "stock": 100,
  "unit": "pcs",
  "category_id": 1
}
```

**Response:**
```json
{
  "id": 1,
  "name": "Product Name",
  "sku": "PRD-001",
  "description": "Product description",
  "purchase_price": 50000,
  "selling_price": 75000,
  "stock": 100,
  "unit": "pcs",
  "category_id": 1,
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

#### Get Product by ID
```http
GET /api/products/{id}
Authorization: Bearer {token}
```

#### Update Product
```http
PUT /api/products/{id}
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:** Same as Create Product

#### Delete Product
```http
DELETE /api/products/{id}
Authorization: Bearer {token}
```

**Response:**
```json
{
  "message": "Product deleted successfully"
}
```

### Categories

#### Get All Categories
```http
GET /api/categories
Authorization: Bearer {token}
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Category Name",
      "description": "Category description",
      "products_count": 25,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
}
```

#### Create Category
```http
POST /api/categories
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "Category Name",
  "description": "Category description"
}
```

#### Get Category by ID
```http
GET /api/categories/{id}
Authorization: Bearer {token}
```

#### Update Category
```http
PUT /api/categories/{id}
Authorization: Bearer {token}
Content-Type: application/json
```

#### Delete Category
```http
DELETE /api/categories/{id}
Authorization: Bearer {token}
```

### Transactions

#### Get All Transactions
```http
GET /api/transactions
Authorization: Bearer {token}
```

**Query Parameters:**
- `page` (integer): Page number
- `per_page` (integer): Items per page
- `start_date` (date): Filter by start date (YYYY-MM-DD)
- `end_date` (date): Filter by end date (YYYY-MM-DD)
- `status` (string): Filter by status (completed, pending, cancelled)

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "invoice_number": "INV-2024-001",
      "total_amount": 150000,
      "payment_method": "cash",
      "payment_status": "paid",
      "status": "completed",
      "customer_name": "Customer Name",
      "items_count": 3,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "user": {
        "id": 1,
        "name": "Cashier Name"
      }
    }
  ]
}
```

#### Create Transaction
```http
POST /api/transactions
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "customer_name": "Customer Name",
  "payment_method": "cash",
  "items": [
    {
      "product_id": 1,
      "quantity": 2,
      "price": 75000
    },
    {
      "product_id": 2,
      "quantity": 1,
      "price": 100000
    }
  ]
}
```

**Response:**
```json
{
  "id": 1,
  "invoice_number": "INV-2024-001",
  "total_amount": 250000,
  "payment_method": "cash",
  "payment_status": "paid",
  "status": "completed",
  "customer_name": "Customer Name",
  "items": [
    {
      "id": 1,
      "product_id": 1,
      "product_name": "Product A",
      "quantity": 2,
      "price": 75000,
      "subtotal": 150000
    }
  ],
  "created_at": "2024-01-01T00:00:00.000000Z"
}
```

#### Get Transaction by ID
```http
GET /api/transactions/{transaction}
Authorization: Bearer {token}
```

#### Get Today's Summary
```http
GET /api/transactions/today-summary
Authorization: Bearer {token}
```

**Response:**
```json
{
  "total_transactions": 25,
  "total_revenue": 2500000,
  "total_items_sold": 150,
  "average_transaction_value": 100000,
  "payment_methods": {
    "cash": 2000000,
    "card": 500000
  }
}
```

### Users

#### Get All Users
```http
GET /api/users
Authorization: Bearer {token}
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "User Name",
      "email": "user@example.com",
      "role": "cashier",
      "is_active": true,
      "last_login": "2024-01-01T10:00:00.000000Z",
      "created_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
}
```

#### Create User
```http
POST /api/users
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "User Name",
  "email": "user@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "cashier"
}
```

#### Get User by ID
```http
GET /api/users/{id}
Authorization: Bearer {token}
```

#### Update User
```http
PUT /api/users/{id}
Authorization: Bearer {token}
Content-Type: application/json
```

#### Delete User
```http
DELETE /api/users/{id}
Authorization: Bearer {token}
```

### Roles & Permissions

#### Get All Roles
```http
GET /api/roles
Authorization: Bearer {token}
```

#### Create Role
```http
POST /api/roles
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "manager",
  "display_name": "Store Manager",
  "description": "Can manage all store operations",
  "permissions": ["view_products", "create_products", "edit_products", "delete_products"]
}
```

#### Get All Permissions
```http
GET /api/permissions
Authorization: Bearer {token}
```

## Error Responses

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": ["The name field is required."],
    "email": ["The email must be a valid email address."]
  }
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
  "message": "This action is unauthorized."
}
```

### Not Found (404)
```json
{
  "message": "Resource not found."
}
```

### Server Error (500)
```json
{
  "message": "Internal server error.",
  "error": "Detailed error message"
}
```

## Rate Limiting

API requests are limited to 60 requests per minute per authenticated user.

## Pagination

Most list endpoints support pagination with the following parameters:
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 15, max: 100)

## Search and Filtering

Endpoints that support search and filtering:
- Products: Search by name/SKU, filter by category
- Transactions: Filter by date range and status
- Users: Search by name/email

## File Upload

Product images can be uploaded using multipart/form-data:
```http
POST /api/products
Authorization: Bearer {token}
Content-Type: multipart/form-data

name: Product Name
sku: PRD-001
images: [file1, file2]
```

## WebSocket Events (Real-time)

The system supports real-time updates via WebSocket:

### Events:
- `transaction.created`: New transaction created
- `product.updated`: Product stock updated
- `low_stock_alert`: Product stock below threshold

## Testing

Use the following curl commands for testing:

```bash
# Get products
curl -H "Authorization: Bearer YOUR_TOKEN" \
     http://localhost:8000/api/products

# Create product
curl -X POST \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{"name":"Test Product","sku":"TEST-001","purchase_price":50000,"selling_price":75000,"stock":100,"unit":"pcs","category_id":1}' \
     http://localhost:8000/api/products
```

## Postman Collection

A complete Postman collection is available in the repository at `/docs/postman-collection.json`.

## SDK and Libraries

- **JavaScript/Node.js**: Available via npm package `pos-system-client`
- **PHP**: Available via Composer package `pos-system/php-client`