# POS System API

A simple Point of Sale (POS) backend API built with Laravel that manages shops, products, and orders with stock control and concurrency safety.

The system demonstrates a clean layered architecture using:

* Controller → Service → Repository pattern
* Transaction-safe order processing
* Database-level locking to prevent stock inconsistencies
* Feature and Unit testing

POS systems typically handle inventory, billing, and order processing for retail or service businesses, allowing efficient transaction management and stock tracking.

## Features

* Shop management
* Product management
* Order creation with multiple items
* Automatic stock deduction
* Order cancellation with stock restoration
* Concurrency-safe order placement
* RESTful API design
* Unit & Feature tests

## Tech Stack

* **Laravel 10**
* **PHP 8.1+**
* **MySQL**
* **PHPUnit**

## Architecture

This project follows a layered architecture to maintain separation of concerns.

```
Controller
   ↓
Service
   ↓
Repository
   ↓
Database

```

### Controller

Handles HTTP requests and responses.

### Service

Contains business logic such as:

* order processing
* stock validation
* transaction handling

### Repository

Handles database operations such as:

* retrieving products
* locking rows
* updating stock

## Database Design

Main tables:

* shops
* products
* order_status
* orders
* order_items

### Relationships

**Shop**
└── **Products**

**Shop**
└── **Orders**
└── **Order Items**
└── **Product**

## Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/rizvisharis/pos-system.git
cd pos-system

```

### 2. Install Dependencies

```bash
composer install

```

### 3. Environment Configuration

Copy the environment file.

```bash
cp .env.example .env

```

Generate application key.

```bash
php artisan key:generate

```

### 4. Configure Database

Update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos-system
DB_USERNAME=root
DB_PASSWORD=

```

### 5. Database Migration

Run migrations:

```bash
php artisan migrate

```

Run seeders:

```bash
php artisan db:seed

```

Seeded data includes:

* **order_status**
* pending
* completed
* cancelled



### 6. Run the Application

```bash
php artisan serve

```

Server will run at: `http://127.0.0.1:8000`

### `or Using Docker`

---

## 🛠 Prerequisites

* **Docker** installed (Desktop or Engine)
* **Docker Compose** (included with Docker Desktop)

---

## 🚀 Setup and Start

### 1. Configure Environment

Before starting, ensure your `.env` file reflects the Docker service names. The `DB_HOST` must match the service name in `docker-compose.yml`.

```env
DB_CONNECTION=mysql
DB_HOST=laravel_mysql
DB_PORT=3306
DB_DATABASE=pos_system
DB_USERNAME=root
DB_PASSWORD=secret

# Used by docker-compose for the MySQL container
MYSQL_ROOT_PASSWORD=secret

```

### 2. Build and Start Containers

Run the following command to build the image and start the services in detached mode:

```bash
docker-compose up -d --build

```

### 3. Application Initialization

Execute these commands inside the running `app` container to set up the Laravel environment:

```bash
# Install PHP dependencies
docker-compose exec app composer install

# Generate App Key
docker-compose exec app php artisan key:generate

# Run Migrations and Seeders
docker-compose exec app php artisan migrate --seed

```

### 4. Accessing the Application

* **API Base URL:** `http://localhost:8000`
* **Database Host:** `127.0.0.1` (from host machine) or `laravel_mysql` (from within containers)

---

## 📂 Docker File Structure

```text
.
├── docker-compose.yml     # Service definitions
├── Dockerfile             # PHP-FPM build instructions
└── docker/
    └── nginx/
        └── default.conf   # Nginx virtual host configuration

```

---

## 🔧 Useful Commands

| Action | Command |
| --- | --- |
| **Stop Containers** | `docker-compose down` |
| **View Logs** | `docker-compose logs -f app` |
| **Run Tests** | `docker-compose exec app php artisan test` |
| **Bash Access** | `docker-compose exec app bash` |
| **Restart Services** | `docker-compose restart` |

---


## API Endpoints

### Create Order

`POST /api/orders`

Example request:

```json
{
  "shop_id": 1,
  "items": [
    {
      "product_id": 1,
      "qty": 2
    }
  ]
}

```

### Get Orders

`GET /api/orders`
Supports pagination and filtering.

### Get Order by ID

`GET /api/orders/{id}`

### Cancel Order

`POST /api/orders/{id}/cancel`
Cancelling an order restores product stock.

## Running Tests

Run all tests:

```bash
php artisan test

```

Or:

```bash
vendor/bin/phpunit

```

Test suite includes:

* **Feature Tests**
* Create order
* Prevent negative stock
* Order API behaviour


* **Unit Tests**
* OrderService business logic
* Total calculation
* Stock decrement calls



## Concurrency Handling

A critical edge case in POS systems is simultaneous orders for the same product.
Example scenario:

* Product stock = 1
* User A → orders 1
* User B → orders 1 at same time

Without protection: stock = -1 ❌

This system prevents that using:
**Row-Level Locking**
`SELECT ... FOR UPDATE`

Implemented via:
`ProductRepository::lockProduct()`

Inside a database transaction:
`DB::transaction()`

This guarantees:

* stock never becomes negative
* race conditions are avoided

### Testing Concurrency

Feature test included: `OrderConcurrencyTest`

Scenario tested:

1. Product stock = 1
2. First order succeeds
3. Second order fails
4. Stock remains valid

Expected result:

* HTTP 201 → first order
* HTTP 422 → second order
* stock = 0

## Assumptions

The following assumptions were made during development:

* Each order belongs to one shop
* Products belong to a shop
* Product names are unique within a shop
* Stock is deducted immediately when the order is placed
* Order status is tracked via the `order_status` table

## Design Decisions

### Repository Pattern

Used to:

* decouple database logic
* allow easier testing
* support mocking in unit tests

Example:

* `OrderRepositoryInterface`
* `ProductRepositoryInterface`

### Service Layer

All business logic resides in the Service layer.
Example: `OrderService`

Responsibilities:

* calculate totals
* validate stock
* manage transactions
* handle order cancellation

### Database Transactions

Used in:

* `OrderService::createOrder()`
* `OrderService::cancelOrder()`

Purpose:

* maintain data consistency
* rollback on failure

### Row-Level Locking

Used to prevent:

* race conditions
* overselling stock

## Project Structure

```
app
 ├── Http
 │    ├── Controllers
 │    └── Resources
 │
 ├── Services
 │
 ├── Repositories
 │    ├── Contracts
 │    └── Eloquent
 │
 ├── Models
 │
tests
 ├── Feature
 └── Unit

```

## Possible Improvements

Future improvements could include:

* authentication
* shop management APIs
* product CRUD APIs
* caching
* order analytics
* rate limiting
* event-driven inventory updates

## Author

**Mohamed Rizvi**
Software Engineer
GitHub: [https://github.com/rizvisharis](https://github.com/rizvisharis)

