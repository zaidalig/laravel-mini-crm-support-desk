# laravel-mini-crm-support-desk

A Laravel SQLite Mini CRM and Support Desk system with companies, clients, projects, tickets, tasks, staff management, dashboard reports, and activity logs.

This project is built as a clean GitHub portfolio and testing app using Laravel MVC, Blade templates, Bootstrap 5, Font Awesome, Form Request validation, migrations, seeders, and SQLite.

## Tech Stack

- PHP 8.5
- Laravel 13
- SQLite
- Blade templates
- Bootstrap 5
- Font Awesome
- MVC controllers, Eloquent models, migrations, Form Requests, and seeders

## Features

- Dashboard cards for companies, clients, active projects, open tickets, pending tasks, and overdue tasks.
- Recent projects, recent support tickets, and latest activity logs on the dashboard.
- Company CRUD with search, status filter, and detail pages showing related clients, projects, and tickets.
- Client CRUD with search, company filter, and status filter.
- Project CRUD with status filter, company filter, progress display, and overdue indicators.
- Support ticket CRUD with priority badges, status badges, assigned staff, due dates, and detail pages.
- Ticket comments with public replies and internal notes.
- Task CRUD for project tasks, ticket tasks, and general tasks with status/priority filters and overdue badges.
- Staff CRUD for assignment to tickets and tasks.
- Search and filter forms across list pages.
- Bootstrap tables, forms, cards, badges, alerts, sidebar navigation, top navbar, and delete confirmation modal.
- Activity logs created for record create, update, and delete events.
- Sample companies, clients, staff, projects, tickets, ticket comments, tasks, and activity logs.

## Main Modules

- Dashboard
- Companies
- Clients
- Projects
- Support Tickets
- Ticket Comments
- Tasks
- Staff
- Activity Logs

## Database Tables

- `companies`
- `clients`
- `projects`
- `tickets`
- `ticket_comments`
- `tasks`
- `staff`
- `activity_logs`

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
mkdir -p database
touch database/database.sqlite
```

Set the database connection in `.env`:

```env
DB_CONNECTION=sqlite
```

Run migrations and seeders:

```bash
php artisan migrate --seed
php artisan serve
```

Open the app at:

```text
http://127.0.0.1:8000
```

## Verification

Useful commands for checking the project:

```bash
php artisan migrate:fresh --seed
php artisan route:list
php artisan test
```

## Routes

Resource routes are included for:

- `companies`
- `clients`
- `projects`
- `tickets`
- `tasks`
- `staff`

Extra routes:

- `/` dashboard
- `/dashboard` dashboard
- `POST /tickets/{ticket}/comments` ticket comments
