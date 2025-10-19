# Architecture Notes

- Developer: Md. Mir Hossain | Reviewed: 2025-10-19

This project follows a modular, event-driven design with the following layers and patterns.

## Layers
- Controller: Validates requests and delegates to services.
- Service: Orchestrates use-cases, handles transactions, emits domain events.
- Repository: Data-access for aggregates (Products, Orders) with row-level stock checks.
- Model: Eloquent models with relationships, observers for domain invariants.
- Event/Listener: Decouple side-effects (balances, notifications, audit logs).
- Policy: Authorization rules; buyers see purchases, sellers see sales.

## Required Components
- Http/Controllers/Api/OrderController.php
- Services/OrderService.php
- Repositories/OrderRepository.php, ProductRepository.php
- Observers/OrderObserver.php
- Events/OrderPlaced.php
- Listeners/UpdateSellerBalanceListener.php
- Listeners/SendOrderConfirmationListener.php
- Listeners/AuditTrailListener.php
- Jobs/GenerateInvoiceJob.php
- Console/Commands/InvoiceDailyCommand.php
- Policies/OrderPolicy.php (and gate wiring)

## Transaction & Error Handling
- Use DB::transaction in OrderService for creating orders and items atomically.
- Use SELECT ... FOR UPDATE via Eloquent lockForUpdate() when decrementing stock.
- Return meaningful error responses; never partially create orders.

## Security
- Laravel Sanctum for API tokens.
- Policies enforced in controllers.

## Database Schema (Summary)
- users: id, name, email, password, role (buyer|seller)
- products: id, user_id (seller), name, price, stock_quantity
- orders: id, order_number, buyer_id, total_amount, status (pending|paid|cancelled)
- order_items: id, order_id, product_id, seller_id, quantity, price, subtotal

## Events
- OrderPlaced: dispatched after successful order transaction.
- Listeners update aggregates and append to storage logs.

## Invoices
- Artisan command scans paid, uninvoiced orders and writes JSON invoice files.

## Notes
- All PHP files carry the header comment.
