# Commit Log (8 Steps)

1. step(1/8): scaffold docs and architecture notes; confirm Sanctum and base routes
2. step(2/8): database schema and models (users role/balance, products, orders, order_items) with relationships
3. step(3/8): repositories/services + events/listeners/observer + policies scaffolding and controller auth wiring
4. step(4/8): routes and auth endpoints (login/logout), order endpoints index/show/store
5. step(5/8): events (OrderPlaced), listeners (UpdateSellerBalance, SendOrderConfirmation, AuditTrail), observer (OrderObserver) + provider wiring
6. step(6/8): policies and gates (OrderPolicy) + controller authorize calls and provider registration
7. step(7/8): artisan command invoice:daily and GenerateInvoiceJob writing JSON files
8. step(8/8): factories, seeders, and feature tests for order placement and authorization

Note: Some steps were bundled due to file relationships; see `git log --oneline` for exact SHAs.
