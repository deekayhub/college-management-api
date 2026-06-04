# College Management API Documentation

## Base URL

```text
http://localhost:8000/api
```

---

# Authentication

The API uses Laravel Sanctum Token Authentication.

Protected routes require:

```http
Authorization: Bearer {token}
Accept: application/json
```

---

# Login

Authenticate user and generate access token.

## Endpoint

```http
POST /login
```

## Request

```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

## Success Response

```json
{
    "success": true,
    "message": "Login successful",
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxx"
}
```

## Validation Errors

```json
{
    "message": "Invalid credentials"
}
```

---

# Logout

Revoke current access token.

## Endpoint

```http
POST /logout
```

## Headers

```http
Authorization: Bearer {token}
```

## Success Response

```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

---

# Students

## Student Object

```json
{
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "9876543210",
    "date_of_birth": "2000-05-15"
}
```

---

# Get Students

Returns paginated list of students.

## Endpoint

```http
GET /students
```

## Query Parameters

| Parameter | Type    | Required | Description                                   |
| --------- | ------- | -------- | --------------------------------------------- |
| search    | string  | No       | Search by first name, last name, email, phone |
| page      | integer | No       | Page number                                   |
| limit     | integer | No       | Records per page (Default: 10)                |

---

## Search Examples

Search by first name:

```http
GET /students?search=john
```

Search by phone:

```http
GET /students?search=9876543210
```

Search by email:

```http
GET /students?search=john@example.com
```

---

## Pagination Example

```http
GET /students?page=1&limit=10
```

---

## Success Response

```json
{
    "success": true,
    "message": "Students fetched successfully",
    "current_page": 1,
    "total_records": 50,
    "total_pages": 5,
    "data": [
        {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "email": "john@example.com",
            "phone": "9876543210",
            "date_of_birth": "2000-05-15"
        }
    ]
}
```

---

# Get Student Details

Retrieve a specific student.

## Endpoint

```http
GET /students/{id}
```

## Success Response

```json
{
    "success": true,
    "data": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com",
        "phone": "9876543210",
        "date_of_birth": "2000-05-15"
    }
}
```

---

# Create Student

Create a new student.

## Endpoint

```http
POST /students
```

## Request

```json
{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "9876543210",
    "date_of_birth": "2000-05-15"
}
```

## Validation Rules

| Field         | Rules                   |
| ------------- | ----------------------- |
| first_name    | required                |
| last_name     | required                |
| email         | required, email, unique |
| phone         | required                |
| date_of_birth | required, date          |

## Success Response

```json
{
    "success": true,
    "message": "Student created successfully"
}
```

---

# Update Student

Update existing student.

## Endpoint

```http
PUT /students/{id}
```

## Request

```json
{
    "first_name": "John",
    "last_name": "Smith",
    "email": "john@example.com",
    "phone": "9876543210",
    "date_of_birth": "2000-05-15"
}
```

## Success Response

```json
{
    "success": true,
    "message": "Student updated successfully"
}
```

---

# Delete Student

Delete a student.

## Endpoint

```http
DELETE /students/{id}
```

## Success Response

```json
{
    "success": true,
    "message": "Student deleted successfully"
}
```

---

# Courses

## Course Object

```json
{
    "id": 1,
    "course_name": "Bachelor of Computer Applications",
    "course_code": "BCA101",
    "description": "Computer Applications Program"
}
```

---

# Get Courses

## Endpoint

```http
GET /courses
```

## Success Response

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "course_name": "Bachelor of Computer Applications",
            "course_code": "BCA101",
            "description": "Computer Applications Program"
        }
    ]
}
```

---

# Get Course Details

## Endpoint

```http
GET /courses/{id}
```

---

# Create Course

## Endpoint

```http
POST /courses
```

## Request

```json
{
    "course_name": "Bachelor of Computer Applications",
    "course_code": "BCA101",
    "description": "Computer Applications Program"
}
```

---

# Update Course

## Endpoint

```http
PUT /courses/{id}
```

---

# Delete Course

## Endpoint

```http
DELETE /courses/{id}
```

---

# Enrollments

## Enrollment Object

```json
{
    "id": 1,
    "student_id": 1,
    "course_id": 2,
    "created_at": "2025-01-01 10:00:00"
}
```

---

# Get Enrollments

Returns all enrollments with student and course details.

## Endpoint

```http
GET /enrollments
```

## Success Response

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "student": {
                "id": 1,
                "first_name": "John",
                "last_name": "Doe"
            },
            "course": {
                "id": 2,
                "course_name": "Bachelor of Computer Applications"
            }
        }
    ]
}
```

---

# Create Enrollment

Enroll a student into a course.

## Endpoint

```http
POST /enrollments
```

## Request

```json
{
    "student_id": 1,
    "course_id": 2
}
```

## Success Response

```json
{
    "success": true,
    "message": "Student enrolled successfully"
}
```

---

# Delete Enrollment

Remove student enrollment.

## Endpoint

```http
DELETE /enrollments/{id}
```

## Success Response

```json
{
    "success": true,
    "message": "Enrollment deleted successfully"
}
```

---

# Database Schema

## users

| Column   | Type    |
| -------- | ------- |
| id       | bigint  |
| name     | varchar |
| email    | varchar |
| password | varchar |

---

## students

| Column        | Type    |
| ------------- | ------- |
| id            | bigint  |
| first_name    | varchar |
| last_name     | varchar |
| email         | varchar |
| phone         | varchar |
| date_of_birth | date    |

---

## courses

| Column      | Type    |
| ----------- | ------- |
| id          | bigint  |
| course_name | varchar |
| course_code | varchar |
| description | text    |

---

## enrollments

| Column     | Type      |
| ---------- | --------- |
| id         | bigint    |
| student_id | bigint    |
| course_id  | bigint    |
| created_at | timestamp |

---

# Relationships

Student → Many Enrollments

Course → Many Enrollments

Enrollment → Belongs To Student

Enrollment → Belongs To Course

---

# Security Features

* Password Hashing using Hash::make()
* Laravel Sanctum Authentication
* Authentication Middleware
* Request Validation
* SQL Injection Protection via Eloquent ORM
* API Rate Limiting
* Protected API Routes

---

# HTTP Status Codes

| Code | Description       |
| ---- | ----------------- |
| 200  | Success           |
| 201  | Created           |
| 401  | Unauthorized      |
| 404  | Not Found         |
| 422  | Validation Error  |
| 429  | Too Many Requests |
| 500  | Server Error      |
