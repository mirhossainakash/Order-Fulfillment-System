# Order Fulfillment System

This repository implements a modular, event-driven Order Fulfillment System built with Laravel 10, following a layered architecture with Repositories, Services, Events, Listeners, and Policies.

Key highlights:
- Sanctum-based API authentication
- Strict controller -> service -> repository flow
- Eloquent models with observers and domain events
- Transactional order creation with stock locking
- Asynchronous-style listeners (queueable-ready)
- Authorization policies ensuring buyers see purchases and sellers see sales

See `ARCHITECTURE_NOTES.md` for detailed decisions and patterns.

## Quick start

1. Install dependencies
2. Configure your database in `.env`
3. Run migrations and seeders
4. Generate a token with the `/api/login` endpoint
5. Place an order with `/api/orders`
6. List orders using `/api/orders?type=purchases|sales`

Refer to the Try it section below after finishing all steps and migrations.

## Try it (API Outline)
- POST /api/login { email, password }
- POST /api/orders { items: [{ product_id, quantity }, ...] } (requires Bearer token)
- GET  /api/orders?type=purchases|sales (requires Bearer token)
- GET  /api/orders/{order}
- POST /api/logout

Seeded demo users:
- buyer@example.com / password
- seller@example.com / password

## Invoicing
- Run artisan command to generate invoices for paid, uninvoiced orders; JSON files are written to `storage/app/invoices`.

## Tests
Feature tests validate order placement and authorization. See `tests/Feature`.
Run tests:

```
php artisan test --testsuite=Feature
```

## Developer Notes
- All PHP files include a header comment:
// Developer: Md. Mir Hossain | Reviewed: 2025‑10‑19
