# College Management API

A RESTful API built with Laravel 12 for managing college students. The API provides authentication, student management, search functionality, pagination, validation, and secure access using Laravel Sanctum.

---

## Features

* User Authentication (Login/Logout)
* Laravel Sanctum Token Authentication
* Student CRUD Operations
* Student Search (Name, Email, Phone)
* Course CRUD Operations
* Course Search (Name, code)
* Pagination Support
* Input Validation
* Password Hashing
* Authentication Middleware
* SQL Injection Protection via Eloquent ORM
* API Rate Limiting
* JSON API Responses

---

## Technology Stack

* PHP 8.2+
* Laravel 12
* MySQL
* Laravel Sanctum
* Postman

---

# Installation Steps

## 1. Clone Repository

```bash
git clone https://github.com/your-username/college-management-api.git

cd college-management-api
```

## 2. Install Dependencies

```bash
composer update
```

## 3. Create Environment File

```bash
cp .env.example .env
```

## 4. Generate Application Key

```bash
php artisan key:generate
```

---

# Environment Setup

Update your `.env` file with database credentials:

```env
APP_NAME="College Management API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=college_management
DB_USERNAME=root
DB_PASSWORD=
```

---

# Database Migration Steps

## Create Database

Create a database manually:

```sql
CREATE DATABASE college_management;
```

## Run Migrations

```bash
php artisan migrate
```

## Run Seeders (Optional)

```bash
php artisan db:seed
```

or

```bash
php artisan migrate:fresh --seed
```

---

# Running the Project

Start the development server:

```bash
php artisan serve
```

Application URL:

```text
http://localhost:8000
```

API Base URL:

```text
http://localhost:8000/api
```

---

# API Documentation (Swagger)

This project includes interactive API documentation powered by L5-Swagger (OpenAPI).

## Generate Swagger Documentation

After adding or updating API annotations, generate the Swagger documentation:

```bash
php artisan l5-swagger:generate
```

## Access Swagger UI

Start the Laravel development server:

```bash
php artisan serve
```

Open the Swagger UI in your browser:

```text
http://localhost:8000/api/documentation
```

---

## Authentication

Most API endpoints are protected using Laravel Sanctum.

### Step 1: Register a User

Execute the following endpoint from Swagger:

```http
POST /api/register
```

Request Body:

```json
{
  "name": "Deepak Kumar",
  "email": "deepak@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Step 2: Login

Execute:

```http
POST /api/login
```

Request Body:

```json
{
  "email": "deepak@example.com",
  "password": "password123"
}
```

Successful Response:

```json
{
  "success": true,
  "token": "1|xxxxxxxxxxxxxxxxxxxxxxxx"
}
```

Copy the token from the response.

### Step 3: Authorize Swagger

1. Click the **Authorize** button at the top-right of the Swagger page.
2. Enter the token in the following format:

```text
Bearer 1|xxxxxxxxxxxxxxxxxxxxxxxx
```

3. Click **Authorize**.
4. Close the popup.

You can now access all protected endpoints.

---

## Testing APIs

Swagger provides a **Try it out** feature for all endpoints.

1. Expand an endpoint.
2. Click **Try it out**.
3. Fill in the required parameters or request body.
4. Click **Execute**.
5. View the request, response, and generated cURL command.

--- 

## Regenerating Documentation

Whenever you modify API annotations:

```bash
php artisan l5-swagger:generate
```

Clear cache if needed:

```bash
php artisan optimize:clear
```



## Quick Links

- [API Documentation](docs/api-documention.md)
- [Postman Collection](docs/College-management.postman_collection.json)

---  

# Author

Developer: Deepak kumar

Laravel Version: 12.x

PHP Version: 8.2+
