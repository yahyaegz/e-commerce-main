# Symfony E-Commerce Project

Small e-commerce web application for the EHEI Symfony module.

## What Is Implemented

- Dynamic Symfony routes/controllers for home, login, register, profile, categories, category products, product details, and cart.
- Twig layout converted from the starter HTML with shared navbar, footer, and product card partials.
- Doctrine entities and repositories for `Category`, `Product`, and `User`.
- Database fixtures with 5 categories, 12 products, and one demo user.
- Session-based cart using `CartInterface`, `SessionCart`, `ApiCartFake`, and `CartHandler`.
- Symfony form login/logout and registration with password hashing and validation.
- Protected `/profile` route requiring `ROLE_USER`.

## Local Setup

```shell
composer install
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
php -S 127.0.0.1:8000 -t public
```

Open `http://127.0.0.1:8000`.

Demo account:

```text
Email: student@example.com
Password: password123
```

## Verification Commands

```shell
php bin/console cache:clear
php bin/console lint:twig templates
php bin/console lint:container
php bin/console doctrine:schema:validate
```

The project uses SQLite by default through `DATABASE_URL` in `.env`, so no MySQL or PostgreSQL server is required for local testing.
