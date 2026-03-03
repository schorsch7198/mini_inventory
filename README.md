# Mini Inventory + Order Process

A fullstack inventory management system with order processing.
Built with **Laravel 11** (API) and **Vue 3** (SPA).

## Tech Stack

- **Backend:** Laravel 11, Sanctum (token auth), SQLite
- **Frontend:** Vue 3, Vite, Pinia (state), Vue Router, Axios
- **Key patterns:** Atomic stock reduction with DB transactions, feature-sliced architecture

## Setup

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+

### Backend
```bash
git clone <your-repo-url>
cd mini-inventory
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
```

Set `DB_CONNECTION=sqlite` in `.env`, then:
```bash
php artisan migrate --seed
php artisan serve
```

API runs on `http://localhost:8000`.

**Demo account:** `demo@demo.com` / `password`

### Frontend
```bash
cd frontend
npm install
npm run dev
```

App runs on `http://localhost:5173`.

## API Endpoints

| Method | Endpoint          | Auth | Description              |
|--------|-------------------|------|--------------------------|
| POST   | /api/register     | No   | Register new user        |
| POST   | /api/login        | No   | Login, returns token     |
| POST   | /api/logout       | Yes  | Revoke current token     |
| GET    | /api/user         | Yes  | Current user info        |
| GET    | /api/products     | Yes  | List (search, sort, paginate) |
| POST   | /api/products     | Yes  | Create product           |
| GET    | /api/products/{id}| Yes  | Show product             |
| PUT    | /api/products/{id}| Yes  | Update product           |
| DELETE | /api/products/{id}| Yes  | Delete product           |
| POST   | /api/orders       | Yes  | Place order              |
| GET    | /api/orders       | Yes  | Order history            |

## Architecture Decisions

**Why feature-sliced development?**
I built each feature end-to-end (auth → products → orders) rather than all backend then all frontend. This mirrors how startups ship incrementally and ensured I caught integration issues early. Each commit represents a working, demoable state.

**Why atomic stock reduction with `lockForUpdate()`?**
A simple `if stock > 0 then decrement` has a race condition — two concurrent requests could both pass the check. Using `lockForUpdate()` inside a DB transaction ensures only one request can modify a product's stock at a time. The tradeoff is a brief lock on the row, which is acceptable for this scale.

**Why Pinia over Vuex?**
Pinia is the officially recommended state manager for Vue 3. It has better TypeScript support, simpler syntax, and no mutations boilerplate.

**Why SQLite?**
Simplest setup for reviewers — no database server needed. The `lockForUpdate()` behavior still works correctly with SQLite for demo purposes, though PostgreSQL/MySQL would be preferred in production.

**Why token auth (Sanctum) over session/cookie?**
Decoupled SPA + API is the standard for modern fullstack apps. Token-based auth keeps the frontend and backend truly independent — they could be deployed on different domains.