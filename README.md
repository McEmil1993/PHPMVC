# PHP MVC CRUD Starter

This project is a lightweight PHP MVC application with:

- Custom routing
- Custom MVC structure
- Service and repository layers
- PDO-based database access
- AdminLTE-based web views
- JSON API endpoints for AJAX CRUD
- Docker and non-Docker local setup

## Requirements

### Docker setup

- Docker
- Docker Compose

### Non-Docker setup

- PHP 8.3+ recommended
- Extensions: `pdo`, `pdo_mysql`, `mysqli`
- MySQL or MariaDB

## Project Structure

```text
app/
  Controllers/
  Models/
  Repositories/
  Requests/
  Resources/
  Services/
  views/
bootstrap/
config/
db/
docker/
public/
routes/
storage/
```

## Run With Docker

1. Make sure your database server is reachable using the values in `docker-compose.yml`.
2. Build and start the containers:

```bash
docker compose up --build
```

3. Open the app:

```text
http://localhost:8088
```

4. Import the users table and seed:

```bash
mysql -h 172.26.192.1 -P 3309 -u newuser1 -p app < db/users.sql
```

If your database connection is different, update these files first:

- `docker-compose.yml`
- `config/config.php`

## Run Without Docker

1. Make sure PHP has these extensions enabled:

```text
pdo
pdo_mysql
mysqli
```

2. Configure the database connection in `config/config.php`.
3. Import the users table and seed:

```bash
mysql -h 172.26.192.1 -P 3309 -u newuser1 -p app < db/users.sql
```

4. Start the built-in PHP server from the project root:

```bash
php -S localhost:8088 -t public
```

5. Open:

```text
http://localhost:8088
```

## Default Login

- Email: `admin@example.com`
- Password: `ChangeMe123!`

## Current Routes

### Web routes

- `GET /`
- `GET /home`
- `GET /login`
- `POST /login`
- `GET /logout`
- `POST /logout`
- `GET /users`

### API routes

- `GET /api/users`
- `POST /api/users`
- `PUT /api/users/{id}`
- `DELETE /api/users/{id}`

## How To Create a New CRUD Module

Use the existing `users` module as the reference implementation.

### Example target module

If you want to add a `products` module, the expected result is:

- Web page: `/products`
- API endpoints:
  - `GET /api/products`
  - `POST /api/products`
  - `PUT /api/products/{id}`
  - `DELETE /api/products/{id}`

## CRUD Module Steps

### 1. Create the database table

Add a new SQL file in `db/`, for example:

```text
db/products.sql
```

Use the same soft delete pattern if needed:

```sql
CREATE TABLE `products` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2. Create the model

Add:

```text
app/Models/Product.php
```

The model should be a simple data object similar to `app/Models/User.php`.

### 3. Create the repository

Add:

```text
app/Repositories/ProductRepository.php
```

The repository should handle:

- `all()`
- `find(int $id)`
- `create(...)`
- `update(int $id, array $fields)`
- `softDelete(int $id)`

Use PDO queries and filter active rows with:

```sql
WHERE deleted_at IS NULL
```

### 4. Create the service

Add:

```text
app/Services/ProductService.php
```

The service should contain business logic and call the repository.

### 5. Create request validators

Add request classes in:

```text
app/Requests/
```

Typical files:

- `CreateProductRequest.php`
- `UpdateProductRequest.php`

Validation rules should:

- reject empty required fields
- return field-specific errors
- throw `ValidationException` for invalid input

### 6. Create the resource formatter

Add:

```text
app/Resources/ProductResource.php
```

Use it to convert model objects into API response arrays.

### 7. Create the controller

Add:

```text
app/Controllers/ProductController.php
```

It should expose these methods:

- `index()`
- `store()`
- `update(string $id)`
- `destroy(string $id)`

The controller should only handle:

- request/response
- validation errors
- JSON output

### 8. Register API routes

Edit:

```text
routes/api.php
```

Follow the `users` pattern:

```php
$router->get('/api/products', ...);
$router->post('/api/products', ...);
$router->put('/api/products/{id}', ...);
$router->delete('/api/products/{id}', ...);
```

### 9. Create the web view

Add:

```text
app/views/products/index.php
```

Use the `users` page as the base template. The page should:

- extend `app/views/layouts/main.php`
- render the AdminLTE page wrapper
- load data from `/api/products`
- include modal forms for create/edit
- use a Bootstrap modal for delete confirmation

### 10. Register the web route

Edit:

```text
routes/web.php
```

Add a protected web route:

```php
$router->get('/products', $requireAuth(static function (): void {
    View::render('products/index', []);
}));
```

### 11. Add the menu link

Edit:

```text
app/views/layouts/sidenav.php
```

Add a new sidebar item pointing to `/products`.

### 12. Update active menu highlighting

Inside `app/views/products/index.php`, add the correct active class:

```javascript
$('#products').addClass('active');
```

Then set the matching menu item id in `sidenav.php`.

### 13. Test the full flow

Check all of these:

1. Open `/products`
2. Create a record
3. Verify the table reloads
4. Edit a record
5. Soft delete a record
6. Confirm deleted rows no longer appear in the active list
7. Confirm the database row still exists and only `deleted_at` changed

## Recommended File Template for a New Module

For a new module named `products`, create:

```text
app/Controllers/ProductController.php
app/Models/Product.php
app/Repositories/ProductRepository.php
app/Requests/CreateProductRequest.php
app/Requests/UpdateProductRequest.php
app/Resources/ProductResource.php
app/Services/ProductService.php
app/views/products/index.php
db/products.sql
```

## Development Notes

- `public/index.php` is the only public entrypoint.
- `bootstrap/app.php` loads both `routes/web.php` and `routes/api.php`.
- Web pages should render views through `Bootstrap\View`.
- API endpoints should return JSON through `Bootstrap\Response` and `ApiResponse`.
- Current authentication is session-based.
- Delete operations should use soft delete when the table has a `deleted_at` column.

## Reference Module

Use the `users` module as the implementation reference:

- `app/Controllers/UserController.php`
- `app/Repositories/UserRepository.php`
- `app/Requests/CreateUserRequest.php`
- `app/Requests/UpdateUserRequest.php`
- `app/Resources/UserResource.php`
- `app/Services/UserService.php`
- `app/views/users/index.php`
- `routes/api.php`
- `routes/web.php`
