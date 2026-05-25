# laravel-mini-crm-support-desk

A Laravel SQLite Mini CRM and Support Desk system with companies, clients, projects, tickets, tasks, staff management, dashboard reports, and activity logs.

## Features

- Dashboard cards for companies, clients, active projects, open tickets, pending tasks, and overdue tasks.
- Company, client, project, support ticket, task, and staff CRUD modules.
- Ticket comments with public replies and internal notes.
- Search and filters for list pages.
- SQLite migrations and sample seed data.
- Laravel Blade templates with Bootstrap 5 and Font Awesome.
- Activity logs created for record create, update, and delete events.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
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

## GitHub Commands

```bash
echo "# laravel-mini-crm-support-desk" >> README.md
git init
git add README.md
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/zaidalig/laravel-mini-crm-support-desk.git
git push -u origin main
```
