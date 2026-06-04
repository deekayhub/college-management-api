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


## Quick Links

- [API Documentation](docs/API_DOCUMENTATION.md)
- [Postman Collection](College%20Management%20API.postman_collection.json)

---  

# Author

Developer: Deepak kumar

Laravel Version: 12.x

PHP Version: 8.2+
