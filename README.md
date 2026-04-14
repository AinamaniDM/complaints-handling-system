# Online Complaints Handling System

**Student:** Ainamani Dickson 
**Course:** Selected Topics in Software Engineering
**Framework:** Laravel 10+ (PHP) | **Database:** PostgreSQL

## System Description

A web-based complaint management platform that allows users to submit complaints digitally and enables administrators to manage, respond to, and resolve them efficiently. The system enforces strict role-based access control across three user levels.


## User Roles

| Role | Access | Capabilities |
|------|--------|-------------|
| **Normal User** | Basic | Register, login, submit complaints with attachments, track status, reply to admin comments, delete own complaints |
| **Category Admin** | Restricted | View and manage complaints in assigned category only, reply with evidence, update status |
| **Super Admin** | Full | All complaints, manage categories, create/delete/assign admin roles, export data |

### Admin Sub-Roles

| Admin Role | Category Visibility |
|---|---|
| Super Admin | All categories — no restriction |
| Finance Admin | Financial complaints only |
| HR Admin | Staff Conduct complaints only |
| Academic Admin | Academic complaints only |
| Facilities Admin | Facilities complaints only |
| IT Admin | IT / Technology complaints only |
| Accommodation Admin | Accommodation complaints only |
| Other Admin | Other complaints only |

---

## Features

- User registration and login with role-based redirect
- Submit complaints with file attachments (images, PDF, audio, video — max 20MB)
- Track complaint status: Pending → In Progress → Resolved
- Two-way comments with file evidence attachments
- Email notifications on submission and status change
- Admin sub-roles — category-restricted complaint visibility
- Category management (super admin)
- Search and filter complaints by keyword, status, category
- Export complaints to CSV and PDF
- Pagination across all complaint lists
- Professional sidebar UI with role-aware navigation

---

## Setup Instructions

### Requirements
- PHP 8.1+
- Composer
- Laravel 10+
- PostgreSQL
- Laragon (recommended on Windows)

### Step 1 — Create a fresh Laravel project
```bash
composer create-project laravel/laravel complaints-system
cd complaints-system
```

### Step 2 — Copy all files from Source_Code into your project

**New files to create:**

| File | Location |
|------|---------|
| `app/Models/Category.php` | `app/Models/` |
| `app/Models/Comment.php` | `app/Models/` |
| `app/Mail/ComplaintSubmitted.php` | `app/Mail/` |
| `app/Mail/StatusUpdated.php` | `app/Mail/` |
| `app/Http/Controllers/CategoryController.php` | `app/Http/Controllers/` |
| `app/Http/Middleware/RoleMiddleware.php` | `app/Http/Middleware/` |
| `resources/views/categories/index.blade.php` | `resources/views/categories/` |
| `resources/views/emails/complaint_submitted.blade.php` | `resources/views/emails/` |
| `resources/views/emails/status_updated.blade.php` | `resources/views/emails/` |
| `resources/views/complaints/pdf.blade.php` | `resources/views/complaints/` |

**Files to replace:**

| File | Replace in project |
|------|--------------------|
| `app/Models/User.php` | `app/Models/` |
| `app/Models/Complaint.php` | `app/Models/` |
| `app/Http/Controllers/AuthController.php` | `app/Http/Controllers/` |
| `app/Http/Controllers/ComplaintController.php` | `app/Http/Controllers/` |
| `app/Http/Controllers/AdminController.php` | `app/Http/Controllers/` |
| `app/Http/Controllers/Controller.php` | `app/Http/Controllers/` |
| `resources/views/layouts/app.blade.php` | `resources/views/layouts/` |
| `resources/views/auth/login.blade.php` | `resources/views/auth/` |
| `resources/views/auth/register.blade.php` | `resources/views/auth/` |
| `resources/views/complaints/create.blade.php` | `resources/views/complaints/` |
| `resources/views/complaints/show.blade.php` | `resources/views/complaints/` |
| `resources/views/complaints/index.blade.php` | `resources/views/complaints/` |
| `resources/views/complaints/edit.blade.php` | `resources/views/complaints/` |
| `resources/views/dashboard/user.blade.php` | `resources/views/dashboard/` |
| `resources/views/dashboard/admin.blade.php` | `resources/views/dashboard/` |
| `resources/views/admin/admins.blade.php` | `resources/views/admin/` |
| `database/migrations/` (all files) | `database/migrations/` |
| `database/seeders/AdminSeeder.php` | `database/seeders/` |
| `database/seeders/CategorySeeder.php` | `database/seeders/` |
| `database/seeders/DatabaseSeeder.php` | `database/seeders/` |
| `routes/web.php` | `routes/` |
| `bootstrap/app.php` | `bootstrap/` |

### Step 3 — Install dependencies
```bash
composer install
composer require barryvdh/laravel-dompdf
```

### Step 4 — Configure .env
Copy `.env.example` to `.env` and update the database section:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=complaints_db
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### Step 5 — Generate app key
```bash
php artisan key:generate
```

### Step 6 — Create storage symlink
```bash
php artisan storage:link
```

### Step 7 — Run migrations
```bash
php artisan migrate
```

### Step 8 — Seed default data
```bash
php artisan db:seed
```
This creates the default admin account and 7 complaint categories.

### Step 9 — Start the server
```bash
php artisan serve
```

Open **http://127.0.0.1:8000** in your browser.

---

## Default Credentials

| Role | Email | Password |
|------|-------|---------|
| Super Admin | admin@complaints.com | admin123 |

Normal users register at **/register**.

---

## URL Reference

| Page | URL | Access |
|------|-----|--------|
| Login | `/login` | Everyone |
| Register | `/register` | Guests only |
| User dashboard | `/my-complaints` | Users only |
| Submit complaint | `/complaints/create` | Users only |
| Admin dashboard | `/admin/dashboard` | Admins only |
| All complaints | `/admin/complaints` | Admins only |
| Categories | `/admin/categories` | Super admin only |
| Manage admins | `/admin/admins` | Super admin only |


## Database Schema

```
users          — id, name, email, password, role, admin_role, timestamps
categories     — id, name, description, timestamps
complaints     — id, user_id, category_id, description, status, attachment, attachment_type, timestamps
comments       — id, complaint_id, user_id, body, attachment, attachment_type, timestamps
```

## Technologies

- **Language:** PHP 8.1+
- **Framework:** Laravel 10+
- **Database:** PostgreSQL
- **Frontend:** Bootstrap 5.3, Font Awesome 6
- **PDF Export:** barryvdh/laravel-dompdf
- **Tools:** VS Code, Laragon
