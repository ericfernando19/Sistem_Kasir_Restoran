# Sistem-Kasir — Agent Guide

POS Restoran ("KasirKu") built on Laravel 13.x. PHP 8.3+, MySQL, Vite 8, Tailwind CSS v4, Alpine.js (CDN), Chart.js (CDN), PHPUnit 12.

## Commands

| Action | Command |
|--------|---------|
| Dev server (full stack) | `composer dev` — runs `php artisan serve` + queue:listen + pail + `npm run dev` concurrently |
| Dev backend only | `php artisan serve` |
| Vite HMR | `npm run dev` |
| Build assets | `npm run build` |
| Run all tests | `composer test` or `php artisan test` |
| Run single test | `php artisan test tests/Path/To/Test.php` |
| Lint PHP | `./vendor/bin/pint` |
| Fresh setup | `composer setup` — installs deps, copies `.env`, generates key, migrates, builds |
| Seed DB | `php artisan db:seed` |

## Non-obvious

- **`.npmrc` has `ignore-scripts=true`** — `npm install` skips postinstall scripts. Use `npm install --ignore-scripts=false` if a package needs them.
- **DB is MySQL** (`DB_CONNECTION=mysql`), database `kasir`. `.env.example` defaults to SQLite — the real `.env` differs.
- **Testing** uses SQLite `:memory:` (`phpunit.xml`), so tests never touch MySQL.
- **Two role values**: `admin` and `kasir`. `CheckRole` middleware (`bootstrap/app.php:16`) enforces `role:admin` on user management, menus, categories, tables, and reports routes.
- **Model style inconsistency**: `User` uses PHP 8 attribute syntax (`#[Fillable]`, `#[Hidden]`), other models use traditional `$fillable` / `$hidden` properties. Either is acceptable.
- **Frontend**: server-side Blade + Tailwind CSS v4. Alpine.js & Chart.js loaded from CDN in `resources/views/layouts/app.blade.php`.
- **Cart** is session-based (`session()->get('cart', [])`), keyed by `product_id`. See `OrderController`.
- **`menu` route-model binding** uses `Product` model — registered in `AppServiceProvider::boot()` via `Route::model('menu', Product::class)`.
- **JS entry** (`resources/js/app.js`) is a placeholder (`//`).
- **Seeders exist**: `UserSeeder`, `CategorySeeder`, `ProductSeeder`, `TableSeeder`. Run `php artisan db:seed` to populate sample data.
- **Only `UserFactory` exists** — other models lack factories.

## Architecture

- `routes/web.php:59` — all web routes; auth and role middleware applied inline
- `app/Models/` — User, Category, Product, Transaction, TransactionItem, Table
- `app/Http/Controllers/` — 10 controllers: Auth, Dashboard, Menu, Order, Category, Table, Report, User (plus Controller base)
- `app/Http/Middleware/CheckRole.php` — reusable role gate for admin routes
- `database/migrations/` — 11 migrations: 7 core tables (users, categories, products, transactions, transaction_items, cache, jobs) + 4 restaurant additions (tables table, transaction restaurant fields, item notes, product is_available)
- `resources/views/` — Blade views organized per resource: `auth/`, `dashboard/`, `menus/`, `orders/`, `tables/`, `categories/`, `reports/`, `users/`, `components/`, `layouts/`
- `app/Providers/AppServiceProvider.php` — registers `menu` → `Product` model binding
- `tests/Feature/ExampleTest.php` — integration tests using `RefreshDatabase`; covers login, auth guards, role access
- `tests/Unit/ExampleTest.php` — basic unit test scaffold

## Restaurant POS Details

- **Order statuses**: `pending` (menunggu), `processing` (diproses), `ready` (siap diantar), `completed` (selesai), `cancelled` (dibatalkan)
- **Table statuses**: `available`, `occupied`, `reserved`
- **Auto-calculations**: 10% tax (PPN) on subtotal via `Transaction::calculateTotals()`
- **Login creds (seeded)**: `admin@restoran.test` / `password` (role:admin), `kasir@restoran.test` / `password` (role:kasir)
- **Product model doubles as "Menu"** — `is_available` field controls menu availability; `selling_price` = menu price; stock/purchase_price/unit unused
