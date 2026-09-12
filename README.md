# TalentFlow API

TalentFlow is a RESTful recruitment management API built with Laravel. It manages jobs, candidates, resumes, job applications, technical tasks, interviews, and recruitment workflow.

The application uses Laravel Sanctum for API authentication and supports role-based access for Admin, Recruiter, and Candidate users.

## Tech Stack

- PHP 8.2
- Laravel 12
- MySQL
- Laravel Sanctum
- Eloquent ORM
- Laravel Queues
- Laravel Scheduler
- Postman

## Main Features

- Token-based authentication using Laravel Sanctum
- Role-based access for Admin, Recruiter, and Candidate
- Job creation, listing, updating, and deletion
- Resume upload and background processing
- Candidate scoring service
- Job application management
- Duplicate application prevention
- Application status history
- Technical task assignment and status tracking
- Automatic overdue technical task handling
- Interview scheduling and updates
- Interview reminder scheduling
- Recruitment dashboard API
- Form Request validation
- API Resources
- Database transactions

## Installation

Clone the repository:

```bash
git clone <repository-url>
cd talentflow
```

Install dependencies:

```bash
composer install
```

Create the environment file:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Configure your MySQL database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=talentflow
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and seeders:

```bash
php artisan migrate
php artisan db:seed
```

Start the development server:

```bash
php artisan serve
```

The application will normally run at:

```text
http://127.0.0.1:8000
```

## Authentication

Login:

```text
POST /api/login
```

Get authenticated user:

```text
GET /api/me
```

Logout:

```text
POST /api/logout
```

Protected endpoints require a Sanctum Bearer Token:

```text
Authorization: Bearer <token>
```

## API Endpoints

### Jobs

```text
GET     /api/jobs
POST    /api/jobs
GET     /api/jobs/{id}
PUT     /api/jobs/{id}
DELETE  /api/jobs/{id}
```

### Resumes

```text
POST    /api/resumes
```

Resume upload uses `multipart/form-data` with the `resume` file field.

### Applications

```text
POST    /api/jobs/{jobId}/apply
GET     /api/applications
PUT     /api/applications/{id}/status
```

### Interviews

```text
GET     /api/interviews
POST    /api/interviews
PUT     /api/interviews/{id}
```

### Technical Tasks

```text
POST    /api/technical-tasks
PUT     /api/technical-tasks/{id}/status
```

### Dashboard

```text
GET     /api/dashboard
```

## Resume Processing

Resume processing is handled through a Laravel queued job so that processing can happen outside the main HTTP request.

Run the queue worker:

```bash
php artisan queue:work
```

The resume processing workflow updates the processing status after the queued job completes.

## Scheduler

Laravel Scheduler is used for scheduled operations such as:

- Marking overdue technical tasks
- Processing interview reminders

View configured scheduled tasks:

```bash
php artisan schedule:list
```

Run the scheduler locally:

```bash
php artisan schedule:work
```

## Testing

Run the automated test suite with:

```bash
php artisan test
```

## Postman

The APIs have been tested using Postman and organized into the following collection structure:

```text
TalentFlow API
├── Authentication
├── Jobs
├── Resumes
├── Applications
├── Interviews
├── Technical Tasks
└── Dashboard
```

Use the Login endpoint to obtain the appropriate authentication token before testing protected endpoints.

## Useful Commands

```bash
php artisan serve
php artisan migrate
php artisan db:seed
php artisan queue:work
php artisan schedule:list
php artisan schedule:work
php artisan test
```

## Architecture

The project follows Laravel conventions and uses:

- Controllers for handling API requests
- Models and Eloquent relationships for data access
- Form Requests for validation
- API Resources for response formatting
- Policies and role checks for authorization
- Service classes for business logic
- Queue Jobs for background processing
- Database transactions for related operations
- Scheduled commands for automated tasks