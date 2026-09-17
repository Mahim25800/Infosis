# TinyLink API

A small Laravel REST API for shortening URLs. Registered users can create, list, view, and delete their own links. Anyone can open a short link and will be redirected to the original URL.

## Features

- Laravel Sanctum token authentication
- Registration, login, logout, and current-user endpoints
- Authenticated URL creation, listing with pagination, viewing, and deletion
- Ownership authorization through `UrlPolicy`
- Automatic random short codes
- Public redirects that safely increment the click count
- Form Request validation and a consistent JSON response shape
- Seeder and feature tests

## Requirements

- PHP 8.2 or newer
- Composer
- MySQL 8+ (or another Laravel-supported database)

## Setup

1. Install PHP dependencies:

   ```bash
   composer install
   ```

2. Copy `.env.example` to `.env` and update the database values:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tinylink
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. Create the `tinylink` database in MySQL, generate the application key, and create the tables:

   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```

4. Start the API:

   ```bash
   php artisan serve
   ```

   The default base URL is `http://127.0.0.1:8000`.

## Authentication

`POST /api/register` and `POST /api/login` return a token. Add it to protected requests:

```http
Authorization: Bearer YOUR_TOKEN
Accept: application/json
```

Example registration body:

```json
{
  "name": "Amina Rahman",
  "email": "amina@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

## Endpoints

| Method | Endpoint | Authentication | Purpose |
| --- | --- | --- | --- |
| POST | `/api/register` | No | Create an account and receive a token |
| POST | `/api/login` | No | Log in and receive a token |
| POST | `/api/logout` | Yes | Revoke the current token |
| GET | `/api/me` | Yes | Show the logged-in user |
| POST | `/api/urls` | Yes | Create a short URL |
| GET | `/api/urls?page=1&per_page=10` | Yes | List the user's URLs |
| GET | `/api/urls/{id}` | Yes | Show one of the user's URLs |
| DELETE | `/api/urls/{id}` | Yes | Delete one of the user's URLs |
| GET | `/{shortCode}` | No | Redirect to the original URL |

## Create a short URL

```http
POST /api/urls
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "url": "https://example.com/this-is-a-very-long-url"
}
```

Example response:

```json
{
  "success": true,
  "message": "URL shortened successfully",
  "data": {
    "id": 1,
    "original_url": "https://example.com/this-is-a-very-long-url",
    "short_code": "A9xT3b",
    "short_url": "http://127.0.0.1:8000/A9xT3b",
    "click_count": 0,
    "created_at": "2026-09-17T10:00:00.000000Z"
  }
}
```

## Response and authorization notes

Successful API responses include `success`, `message`, and, when needed, `data`. Laravel returns validation messages when request data is invalid.

`UrlPolicy` checks the URL owner before a user can view or delete a link. This is important: knowing another URL's database ID does not grant access to it.

## Database design

The `urls` table belongs to `users` through `user_id`. The relationship is represented in code as:

```php
// User model
public function urls() { return $this->hasMany(Url::class); }

// Url model
public function user() { return $this->belongsTo(User::class); }
```

The `short_code` column has a database unique index. The code generator checks before creating a code, and the unique index remains the final protection against duplicates.

## Seed data

`php artisan migrate --seed` creates:

- `test@example.com` (the password is the default factory password: `password`)
- A `laravel-docs` short code pointing to Laravel's documentation

## Tests

Run the feature tests with:

```bash
php artisan test
```

The tests use an in-memory SQLite database and cover URL creation, ownership protection, and redirect click counting.

## Assumptions

- A logged-in user may create more than one short URL for the same original URL.
- A deleted short URL stops working immediately.
- Each redirect counts as one visit; unique visitor tracking is outside this assessment's scope.
- Tokens do not expire by default; logging out revokes the token used for that request.
