# 📌 Online Complaints Handling System

**Ainamani Dickson**\
CSE 4200 -- Selected Topics in Software Engineering\
Uganda Technology and Management University (UTAMU)

**Tech Stack:** Laravel 10+, PostgreSQL

------------------------------------------------------------------------

## 🚀 Overview

A web-based system for submitting, tracking, and resolving complaints in
a structured, role-based environment.

It replaces inefficient manual methods (emails, calls, suggestion boxes)
with a centralized platform that ensures: - Accountability\
- Transparency\
- Efficient complaint resolution

------------------------------------------------------------------------

## 👥 User Roles

  -----------------------------------------------------------------------
  Role                    Description
  ----------------------- -----------------------------------------------
  **User**                Submit complaints, track status, and
                          communicate with admins

  **Category Admin**      Manage complaints within a specific category

  **Super Admin**         Full system control (users, categories, all
                          complaints)
  -----------------------------------------------------------------------

### Admin Categories

Finance, HR, Academic, Facilities, IT, Accommodation, Other

------------------------------------------------------------------------

## ✨ Key Features

-   🔐 Role-based authentication & access control\
-   📎 Complaint submission with file attachments\
-   🔄 Status tracking (Pending → In Progress → Resolved)\
-   💬 Two-way comments with attachments\
-   📧 Email notifications (submission & status updates)\
-   🗂️ Category management (Super Admin)\
-   🔎 Search & filtering (status, category, keywords)\
-   📊 Export to CSV & PDF\
-   📱 Responsive UI (Bootstrap 5)

------------------------------------------------------------------------

## ⚙️ Setup

### Requirements

-   PHP 8.1+\
-   Composer\
-   PostgreSQL

### Installation

``` bash
git clone <your-repo-url>
cd complaints-system

composer install
cp .env.example .env
php artisan key:generate
```

### Configure Database (.env)

    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=complaints_db
    DB_USERNAME=postgres
    DB_PASSWORD=your_password

### Run Application

``` bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Visit: http://127.0.0.1:8000

------------------------------------------------------------------------

## 🔑 Default Admin

  Email                  Password
  ---------------------- ----------
  admin@complaints.com   admin123

------------------------------------------------------------------------

## 🗄️ Database Structure

    users
    categories
    complaints
    comments

------------------------------------------------------------------------

## 🛠️ Tech Stack

-   **Backend:** Laravel 10 (PHP)\
-   **Database:** PostgreSQL\
-   **Frontend:** Bootstrap 5, Font Awesome\
-   **PDF Export:** DOMPDF

------------------------------------------------------------------------

## 📌 Highlights

-   Clean MVC architecture\
-   Strong role-based system design\
-   Scalable and production-ready structure\
-   Practical use of Laravel features (ORM, Mail, Middleware, Storage)
