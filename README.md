# TinyLink API

A clean, beginner-friendly Laravel REST API for shortening long URLs, tracking visitor clicks, and managing user-specific links.

- **Live API URL:** [https://infosis-production.up.railway.app](https://infosis-production.up.railway.app)
- **Example Live Short Link:** [https://infosis-production.up.railway.app/laravel-docs](https://infosis-production.up.railway.app/laravel-docs)
- **Postman Collection:** [`postman/TinyLink_API.postman_collection.json`](postman/TinyLink_API.postman_collection.json)

---

## Features

- **Authentication:** Token-based authentication powered by Laravel Sanctum (`register`, `login`, `logout`, `me`).
- **URL Management:** Authenticated users can create, list with pagination, view, and delete their own short URLs.
- **Ownership Authorization:** Protected via Laravel's native `UrlPolicy` so users can never access or delete links belonging to others.
- **Public Redirection:** Fast public redirect route (`/{shortCode}`) that atomically increments the visit count.
- **Validation:** Clean, dedicated Form Request classes ensuring proper inputs and consistent error responses.
- **Consistent Responses:** All responses return a structured `{ success, message, data }` JSON format.
- **Automated Tests:** Feature and unit tests covering authentication, CRUD, authorization, and redirects.

---

## Pre-seeded Demo Credentials

When the database is seeded (`php artisan migrate --seed`), a ready-to-test user and sample link are created:

- **Email:** `test@example.com`
- **Password:** `password`
- **Sample Short Code:** `laravel-docs` (redirects to `https://laravel.com/docs`)

---

## Tech Stack & Requirements

- **PHP:** 8.2 or higher
- **Framework:** Laravel 11
- **Authentication:** Laravel Sanctum
- **Database:** MySQL 8+ (or PostgreSQL / SQLite)
- **Dependency Manager:** Composer

---

## Local Setup Instructions

Follow these step-by-step instructions to run the project locally on your machine:

### 1. Clone the repository
```bash
git clone https://github.com/Mahim25800/Infosis.git
cd Infosis
```

### 2. Install PHP dependencies
```bash
composer install
```

### 3. Configure the environment
Copy `.env.example` to create your local `.env` file:

- **Windows (Command Prompt / PowerShell):**
  ```powershell
  copy .env.example .env
  ```
- **macOS / Linux / Git Bash:**
  ```bash
  cp .env.example .env
  ```

Open `.env` and set your database connection credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tinylink
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

### 4. Generate application key
```bash
php artisan key:generate
```

### 5. Create the database and run migrations & seed
Create an empty database named `tinylink` in your MySQL server (via phpMyAdmin, MySQL Workbench, or CLI):
```sql
CREATE DATABASE tinylink;
```

Then run the database migrations along with the seeder:
```bash
php artisan migrate --seed
```

### 6. Start the local server
```bash
php artisan serve
```
The application will run at **`http://127.0.0.1:8000`**.

---

## Authentication Guide

All protected endpoints require a Sanctum Bearer Token. 

1. Register via `POST /api/register` or login via `POST /api/login`.
2. Copy the `token` string returned in the response.
3. Attach it to all protected requests in the HTTP header:
   ```http
   Authorization: Bearer YOUR_TOKEN_HERE
   Accept: application/json
   ```

---

## API Endpoints Reference

| Method | Endpoint | Auth Required | Description |
| :--- | :--- | :---: | :--- |
| `GET` | `/` | No | API status and welcome health check |
| `POST` | `/api/register` | No | Register a new user account |
| `POST` | `/api/login` | No | Log in and receive an API token |
| `POST` | `/api/logout` | **Yes** | Revoke current access token |
| `GET` | `/api/me` | **Yes** | Get authenticated user profile |
| `POST` | `/api/urls` | **Yes** | Shorten a new long URL |
| `GET` | `/api/urls?page=1&per_page=10` | **Yes** | List user's URLs with pagination |
| `GET` | `/api/urls/{id}` | **Yes** | View details of a specific URL |
| `DELETE` | `/api/urls/{id}` | **Yes** | Delete a specific URL |
| `GET` | `/{shortCode}` | No | Public redirect to original URL |

---

## Example Requests & Responses

### 1. Register User
**Request:** `POST /api/register`
```json
{
  "name": "Amina Rahman",
  "email": "amina@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```
**Response (201 Created):**
```json
{
  "success": true,
  "message": "Registration successful",
  "data": {
    "user": {
      "id": 2,
      "name": "Amina Rahman",
      "email": "amina@example.com",
      "created_at": "2026-09-18T05:00:00.000000Z"
    },
    "token": "2|NMRU4jIyxIssS1AFIGSNICrWbPzIBbsqTHiH1hOzc07ca33e"
  }
}
```

---

### 2. Login
**Request:** `POST /api/login`
```json
{
  "email": "test@example.com",
  "password": "password"
}
```
**Response (200 OK):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "Test User",
      "email": "test@example.com"
    },
    "token": "1|nIjsO3enhu1YGqLaZCEMP5vy6BUw2nNJ3acWuqCf0b23a244"
  }
}
```

---

### 3. Create Short URL
**Headers:** `Authorization: Bearer <token>`  
**Request:** `POST /api/urls`
```json
{
  "url": "https://laravel.com/docs/11.x/routing"
}
```
**Response (201 Created):**
```json
{
  "success": true,
  "message": "URL shortened successfully",
  "data": {
    "id": 2,
    "original_url": "https://laravel.com/docs/11.x/routing",
    "short_code": "k8Xp2Q",
    "short_url": "http://127.0.0.1:8000/k8Xp2Q",
    "click_count": 0,
    "created_at": "2026-09-18T05:01:00.000000Z"
  }
}
```

---

### 4. List User's URLs (Paginated)
**Headers:** `Authorization: Bearer <token>`  
**Request:** `GET /api/urls?page=1&per_page=10`  
**Response (200 OK):**
```json
{
  "success": true,
  "message": "URLs retrieved successfully",
  "data": {
    "items": [
      {
        "id": 2,
        "original_url": "https://laravel.com/docs/11.x/routing",
        "short_code": "k8Xp2Q",
        "short_url": "http://127.0.0.1:8000/k8Xp2Q",
        "click_count": 0,
        "created_at": "2026-09-18T05:01:00.000000Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 10,
      "total": 1,
      "last_page": 1
    }
  }
}
```

---

### 5. Public Redirect
**Request:** `GET /{shortCode}` (e.g. `GET /laravel-docs`)  
- Returns HTTP status `302 Found` with `Location: https://laravel.com/docs`.
- Atomically increments the `click_count` column by 1 using `$url->increment('click_count')`.

---

## Testing with Postman

A complete Postman collection is included in this repository:
[`postman/TinyLink_API.postman_collection.json`](postman/TinyLink_API.postman_collection.json)

### How to use it:
1. Open Postman and click **Import** (top left).
2. Select the `postman/TinyLink_API.postman_collection.json` file.
3. In the collection's **Variables** tab:
   - For **local testing**, set `base_url` to `http://127.0.0.1:8000`.
   - For **live testing**, set `base_url` to `https://infosis-production.up.railway.app`.
4. Run the **Login** or **Register** request, copy the token, and paste it into the `token` variable.
5. All protected requests will automatically authenticate.

---

## Automated Tests

To run the automated PHPUnit/Pest test suite:

```bash
php artisan test
```

The tests use an isolated in-memory SQLite database and test:
- Endpoint authentication guards (401 when unauthenticated)
- User registration and token generation
- Creating short links and initial click counts
- Ownership authorization via `UrlPolicy` (403 when User B tries to access User A's link)
- Public link redirection (302) and automatic click count incrementing

---

## Database Architecture

- **`users` table:** Standard Laravel user table (`id`, `name`, `email`, `password`, `timestamps`).
- **`urls` table:**
  - `id`: Primary key
  - `user_id`: Foreign key linked to `users.id` with `cascadeOnDelete()`
  - `original_url`: `TEXT` storing the original long destination
  - `short_code`: `VARCHAR(50)` with a `UNIQUE` database index
  - `click_count`: `BIGINT UNSIGNED` defaulting to 0
  - `created_at`, `updated_at`: Standard timestamps

### Eloquent Relationships:
- `User` model: `urls()` $\rightarrow$ `hasMany(Url::class)`
- `Url` model: `user()` $\rightarrow$ `belongsTo(User::class)`

---

## Project Assumptions & Design Decisions

1. **Simplicity & Clean Code:** Built strictly using Laravel best practices (Controllers, FormRequests, Models, Policies) without over-engineering or unnecessary layers so any junior/intern developer can clearly explain every line of code.
2. **Short Code Generation:** Codes are 6-character random alphanumeric strings (`Str::random(6)`). The generator checks the database before saving to prevent collisions, and the database unique index acts as the final guarantee.
3. **Multiple Short URLs:** A user is permitted to create multiple distinct short URLs pointing to the same long URL (e.g. for different marketing campaigns).
4. **Visit Tracking:** Each visit to a short link increases `click_count` by 1. Unique visitor IP tracking or geo-analytics was left out of scope for simplicity.
5. **Token Lifetime:** Tokens do not expire automatically; calling `POST /api/logout` revokes the specific token used in that request.
