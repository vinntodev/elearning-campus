# E-Learning Campus API – Laravel 12

This project is an E-Learning platform backend developed using **Laravel 12**.  
It provides core functionalities such as authentication, course management, material uploads, assignments, grading, discussion forums, and reporting.  
The API is designed to support a structured academic workflow for lecturers and students.

---

## 1. Technology Stack

- **Framework**: Laravel 12  
- **Database**: MySQL  
- **Authentication**: Laravel Sanctum  
- **Storage**: Laravel Storage (Public Disk)  
- **Notification**: Email (SMTP)  
- **Real-time**: Laravel WebSockets  
- **Version Control**: Git & GitHub

---

## 2. Core Features

- **User Authentication**: Register, login, role-based access (lecturer & student)  
- **Course Management**: CRUD operations and enrollment  
- **Material Management**: Upload and download course materials  
- **Assignments & Grading**: Create assignments, student submissions, grading with email notification  
- **Discussion Forum**: Threaded discussion and replies with real-time support  
- **Reports & Statistics**: Course and assignment statistics, student performance data

---

## 3. API Endpoints Overview

| Feature                  | Method | Endpoint                          | Role          |
|---------------------------|--------|------------------------------------|---------------|
| Register / Login          | POST   | `/api/register`, `/api/login`      | Public        |
| Manage Courses            | CRUD   | `/api/courses`                     | Lecturer      |
| Enroll Course             | POST   | `/api/courses/{id}/enroll`         | Student       |
| Upload / Download Material| POST/GET | `/api/materials`                | Lecturer/Student |
| Create Assignment         | POST   | `/api/assignments`                | Lecturer      |
| Submit Assignment         | POST   | `/api/submissions`                | Student       |
| Grade Assignment          | POST   | `/api/submissions/{id}/grade`     | Lecturer      |
| Discussions & Replies     | POST   | `/api/discussions`                | Both          |
| Reports                   | GET    | `/api/reports/...`                | Lecturer      |

> All protected endpoints require a valid **Bearer Token** in the `Authorization` header.

---

## 4. Installation Guide

## 5. Clone Repository
bash
- git clone https://github.com/username/repository-name.git
- cd repository-name


## 6. Install Dependencies
- composer install
- cp .env.example .env
- php artisan key:generate

## 7. Configure .env
- Database configuration
- SMTP configuration (Mailtrap / Gmail)
- Storage link:
- php artisan storage:link

## 8. Migrate Database
- php artisan migrate

## 9. Start Development Server
- php artisan serve

## 10. Testing API with Postman
Set Accept: application/json in headers.
Use Bearer Token after login for protected endpoints.
Example flow:

- Register & Login
- Lecturer creates a course
- Student enrolls in a course
- Lecturer uploads material
- Student downloads material
Lecturer assigns task
- Student submits task
- Lecturer grades task
- View reports

## 11. Real-Time Discussion
Real-time functionality is implemented using Laravel WebSockets.
To start the websocket server:

- php artisan websockets:serve