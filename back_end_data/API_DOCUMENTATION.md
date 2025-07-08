# Student Performance Tracker API

## Overview
This Laravel API provides endpoints for managing student performance, including grades, attendance, and feedback. It uses Laravel Sanctum for authentication and implements role-based access control.

## Authentication

### Register
```http
POST /api/register
```

**Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "student"
}
```

### Login
```http
POST /api/login
```

**Body:**
```json
{
    "email": "admin@gmail.com",
    "password": "password"
}
```

**Response:**
```json
{
    "access_token": "token_here",
    "token_type": "Bearer",
    "user": {
        "id": 1,
        "name": "Admin User",
        "email": "admin@gmail.com",
        "role": "admin"
    }
}
```

### Get Current User
```http
GET /api/me
Authorization: Bearer {token}
```

### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

## Admin APIs (Role: admin)

### Students Management

#### Get All Students
```http
GET /api/admin/students
Authorization: Bearer {token}
```

#### Create Student
```http
POST /api/admin/students
Authorization: Bearer {token}
```

**Body:**
```json
{
    "name": "Student Name",
    "email": "student@example.com",
    "password": "password123",
    "class": "Grade 10A",
    "dob": "2008-05-15",
    "gender": "male",
    "status": "active"
}
```

#### Get Student Details
```http
GET /api/admin/students/{id}
Authorization: Bearer {token}
```

#### Update Student
```http
PUT /api/admin/students/{id}
Authorization: Bearer {token}
```

#### Delete Student
```http
DELETE /api/admin/students/{id}
Authorization: Bearer {token}
```

### Grades Management

#### Get All Grades
```http
GET /api/admin/grades
Authorization: Bearer {token}
```

#### Create Grade
```http
POST /api/admin/grades
Authorization: Bearer {token}
```

**Body:**
```json
{
    "student_id": 1,
    "subject_id": 1,
    "term": "Semester 1",
    "score": 85.5,
    "remarks": "Good performance"
}
```

#### Get Grade Details
```http
GET /api/admin/grades/{id}
Authorization: Bearer {token}
```

#### Update Grade
```http
PUT /api/admin/grades/{id}
Authorization: Bearer {token}
```

#### Delete Grade
```http
DELETE /api/admin/grades/{id}
Authorization: Bearer {token}
```

### Attendance Management

#### Get All Attendance
```http
GET /api/admin/attendance
Authorization: Bearer {token}
```

#### Create Attendance
```http
POST /api/admin/attendance
Authorization: Bearer {token}
```

**Body:**
```json
{
    "student_id": 1,
    "date": "2024-01-15",
    "status": "present"
}
```

#### Get Attendance Details
```http
GET /api/admin/attendance/{id}
Authorization: Bearer {token}
```

#### Update Attendance
```http
PUT /api/admin/attendance/{id}
Authorization: Bearer {token}
```

#### Delete Attendance
```http
DELETE /api/admin/attendance/{id}
Authorization: Bearer {token}
```

### Analytics

#### Get Overview Dashboard
```http
GET /api/admin/analytics/overview
Authorization: Bearer {token}
```

**Response:**
```json
{
    "total_students": 25,
    "total_subjects": 5,
    "average_grade": 78.5,
    "attendance_rate": 92.3,
    "attendance_stats": {
        "present": 450,
        "absent": 30,
        "late": 20
    },
    "top_performers": [...],
    "subject_performance": [...]
}
```

## Student APIs (Role: student)

### Get My Grades
```http
GET /api/student/my-grades
Authorization: Bearer {token}
```

### Get My Attendance
```http
GET /api/student/my-attendance
Authorization: Bearer {token}
```

### Get My Feedback
```http
GET /api/student/my-feedback
Authorization: Bearer {token}
```

## Database Schema

### Users
- id, name, email, password, role (admin/student)

### Students
- id, user_id, class, dob, gender, status

### Subjects
- id, name, code, teacher_name

### Grades
- id, student_id, subject_id, term, score, remarks

### Attendance
- id, student_id, date, status (present/absent/late)

### Feedbacks
- id, student_id, comment, created_by, date

## Default Credentials

**Admin:**
- Email: admin@gmail.com
- Password: password

**Student:**
- Email: student@example.com
- Password: password

## Setup Instructions

1. Install dependencies: `composer install`
2. Set up your `.env` file with database credentials
3. Run migrations: `php artisan migrate`
4. Seed the database: `php artisan db:seed`
5. Start the server: `php artisan serve`

## Notes

- All API responses are in JSON format
- Authentication is required for all endpoints except register and login
- Role-based access control is implemented
- CORS is configured for frontend integration
- Input validation is implemented on all endpoints
