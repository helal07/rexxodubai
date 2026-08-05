# ReXxo Bd — Project Setup

Stack: **Laravel** (API backend) + **MySQL** + **React** (frontend). Run backend and frontend as two separate projects in one repo folder.

```
rexxo-bd/
├── backend/     (Laravel API)
└── frontend/    (React app)
```

---

## 1. Prerequisites

```bash
php -v        # PHP 8.2+
composer -V
node -v       # Node 18+
mysql --version
```

---

## 2. Backend — Laravel API

```bash
mkdir rexxo-bd && cd rexxo-bd

composer create-project laravel/laravel backend
cd backend

# Auth (token-based, for the React SPA + admin dashboard)
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# CORS is built into Laravel 11 — just edit config/cors.php later to allow your React origin
```

### 2.1 Configure MySQL

```bash
mysql -u root -p
```
```sql
CREATE DATABASE rexxo_bd CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'rexxo_user'@'localhost' IDENTIFIED BY 'change_this_password';
GRANT ALL PRIVILEGES ON rexxo_bd.* TO 'rexxo_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Edit `backend/.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rexxo_bd
DB_USERNAME=rexxo_user
DB_PASSWORD=change_this_password

SANCTUM_STATEFUL_DOMAINS=localhost:3000
SESSION_DOMAIN=localhost
FRONTEND_URL=http://localhost:3000
```

### 2.2 Models & migrations

```bash
php artisan make:model MenuItem -mcr
php artisan make:model Category -mcr
php artisan make:model Product -mcr
php artisan make:model ProductImage -m
php artisan make:model Order -mcr
php artisan make:model OrderItem -m
php artisan make:model Admin -m
```

Edit the `menu_items` migration (this is the editable/addable menu table):
```php
Schema::create('menu_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('parent_id')->nullable()->constrained('menu_items')->nullOnDelete();
    $table->string('label');
    $table->string('url')->nullable();
    $table->enum('column_group', ['left', 'highlights'])->nullable();
    $table->string('image_url')->nullable();
    $table->unsignedInteger('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

Run migrations:
```bash
php artisan migrate
```

### 2.3 Menu API routes

`routes/api.php`:
```php
use App\Http\Controllers\MenuItemController;

Route::get('/menu', [MenuItemController::class, 'publicTree']);        // public: nested tree for the navbar

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('admin/menu-items', MenuItemController::class); // CRUD for the admin dashboard
});
```

`MenuItemController@publicTree` should return top-level items with `children` nested (order by `sort_order`, filter `is_active = true`) — that single endpoint is what the React navbar calls to render the mega-menu, so editing a menu item in the admin instantly reflects on the live site with zero redeploy.

### 2.4 Seed an admin user + sample menu

```bash
php artisan make:seeder AdminSeeder
php artisan make:seeder MenuSeeder
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=MenuSeeder
```

### 2.5 Run the backend

```bash
php artisan serve   # http://localhost:8000
```

---

## 3. Frontend — React

```bash
cd rexxo-bd
npm create vite@latest frontend -- --template react
cd frontend
npm install
npm install react-router-dom axios
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

`tailwind.config.js` — point `content` at `./index.html` and `./src/**/*.{js,jsx}`, then add the design tokens from `ReXxo-Bd-DESIGN.md` under `theme.extend.colors` (ink, paper, bone, amber, smoke, hairline) and `theme.extend.fontFamily` (display: Fraunces, sans: Inter).

`.env`:
```
VITE_API_URL=http://localhost:8000/api
```

Suggested structure:
```
frontend/src/
├── components/  (Header, MegaMenu, ProductCard, CartDrawer, Footer...)
├── pages/       (Home, PLP, PDP, Cart, Checkout, admin/*)
├── api/         (axios instance + menu.js, products.js, cart.js)
└── admin/       (MenuBuilder.jsx — drag-reorder list calling the CRUD API)
```

Run it:
```bash
npm run dev   # http://localhost:5173 (or :3000 if you set the port)
```

---

## 4. Admin Menu Builder (frontend piece)

The `/admin/menus` page should:
1. `GET /admin/menu-items` on load → render as a nested, drag-reorderable list (use `@dnd-kit/sortable` or `react-beautiful-dnd`).
2. "Add item" opens a small form: label, URL, parent (dropdown of existing items), column_group, image (for Highlights tiles).
3. Reordering updates `sort_order` via `PATCH /admin/menu-items/{id}`.
4. Delete calls `DELETE /admin/menu-items/{id}` — cascades to children per the migration's `nullOnDelete`/or block delete if it has children, your call.

This is the one screen that makes the whole nav editable without touching code — everything else in the design spec renders from what this screen produces.

---

## 5. Order of Work

1. Backend: migrations → models → menu API → auth (Sanctum) → seed data.
2. Frontend: Tailwind tokens → Header/MegaMenu wired to `/api/menu` → Home → PLP → PDP → Cart.
3. Admin: login → Menu Builder → Product CRUD → Orders view.
4. Polish pass against `ReXxo-Bd-DESIGN.md` — motion, focus states, mobile.

Hand `ReXxo-Bd-DESIGN.md` to your Antigravity agent alongside this file — the design doc defines *what* to build, this one defines *how the project is wired together*.
