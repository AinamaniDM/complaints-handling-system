# Online Complaints Handling System 

**Student:** Ainamani Dickson
**Course:** Selected Topics in Software Engineering
**Framework:** Laravel 10+ (PHP)
**Database:** PostgreSQL

---

## User Roles

| Role  | Permissions |
|-------|-------------|
| **User**  | Register, login, submit complaints,delete complaints, view & track own complaints only |
| **Admin** | Login, view ALL complaints, update status, manage admin accounts |

> Admins cannot submit complaints. Users cannot access the admin panel.
> After login, each role is automatically redirected to their correct dashboard.

---

## System Features

| Feature | Status |
|---------|--------|
| User registration & login | ✅ |
| Admin login | ✅ |
| Role-based access control (admin / user) | ✅ |
| User dashboard — own complaints + stats | ✅ |
| Admin dashboard — all complaints + stats | ✅ |
| Submit complaint (users only) | ✅ |
| View complaint details | ✅ |
| Search & filter complaints (admin) | ✅ |
| Update complaint status | ✅ |
| Delete complaint | ✅ |
| Pagination | ✅ |
| Manage admin accounts (create / delete) | ✅ |
| Input validation on all forms | ✅ |
| Professional sidebar UI | ✅ |

---

## Setup Instructions

### Requirements
- PHP 8.1+
- Composer
- Laravel 10+
- PostgreSQL
- Laragon (recommended on Windows)

---

### Step 1 — Create a fresh Laravel project
```bash
composer create-project laravel/laravel complaints-system
cd complaints-system
```

### Step 2 — Copy files from Source_Code into your project

| From Source_Code | Copy to project |
|---|---|
| `app/Models/User.php` | `app/Models/` |
| `app/Models/Complaint.php` | `app/Models/` |
| `app/Http/Controllers/AuthController.php` | `app/Http/Controllers/` |
| `app/Http/Controllers/ComplaintController.php` | `app/Http/Controllers/` |
| `app/Http/Controllers/AdminController.php` | `app/Http/Controllers/` |
| `app/Http/Controllers/Controller.php` | `app/Http/Controllers/` |
| `app/Http/Middleware/RoleMiddleware.php` | `app/Http/Middleware/` |
| `resources/views/` (entire folder) | `resources/views/` |
| `routes/web.php` | `routes/` |
| `database/migrations/` (both files) | `database/migrations/` |
| `database/seeders/AdminSeeder.php` | `database/seeders/` |
| `bootstrap/app.php` | `bootstrap/` |
| `.env.example` → rename to `.env` | project root |

### Step 3 — Install dependencies
```bash
composer install
```

### Step 4 — Configure .env
Open `.env` and update the database section:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=complaints_db
DB_USERNAME=postgres
DB_PASSWORD=
```

### Step 5 — Generate app key
```bash
php artisan key:generate
```

### Step 6 — Run migrations
```bash
php artisan migrate
```

### Step 7 — Seed the default admin
```bash
php artisan db:seed --class=AdminSeeder
```

### Step 8 — Start the server
```bash
php artisan serve
```

Open http://127.0.0.1:8000 in your browser.

---

## Default Admin Credentials
| Field    | Value                |
|----------|----------------------|
| Email    | admin@complaints.com |
| Password | admin123             |

## Normal User
Register at http://127.0.0.1:8000/register

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
| Manage admins | `/admin/admins` | Admins only |

---

## Troubleshooting

**`Route [dashboard] not defined`**
Replace `app/Http/Controllers/AuthController.php` with the one from Source_Code.

**`Controller.php not found`**
Create `app/Http/Controllers/Controller.php` with:
```php
<?php
namespace App\Http\Controllers;
abstract class Controller {}
```

**`relation "cache" does not exist`**
Run `php artisan migrate` to create missing tables.

**`vendor/autoload.php not found`**
Run `composer install` first.

**`bootstrap/cache not writable`**
Run `mkdir bootstrap/cache`

---

## Technologies
- **Language:** PHP 8.1+
- **Framework:** Laravel 10+
- **Database:** PostgreSQL
- **Frontend:** Bootstrap 5.3, Font Awesome 6
- **Tools:** VS Code, Laragon

  
