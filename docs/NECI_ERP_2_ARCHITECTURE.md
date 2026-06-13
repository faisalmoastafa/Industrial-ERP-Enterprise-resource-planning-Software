# NECI ERP 2.0 Architecture

NECI ERP 2.0 is a modular Laravel ERP built around the company's real copper wire business flow.

## Architecture Type

The system is a modular monolith:

- One Laravel application
- Separate modules for each business area
- Shared product stock and party records
- Server-rendered Blade and Livewire screens
- Spatie permissions for access control
- MySQL as the main database

This keeps the software practical for a production company while avoiding unnecessary microservice complexity.

## Business Modules

- `Product`: stock items, raw material items, finished wire items, categories, barcode
- `Purchase`: raw material and stock purchase records
- `Sale`: invoices, sales terminal, sale payments
- `Expense`: general expense tracking
- `People`: customers and suppliers
- `Reports`: business reporting
- `User`: users, roles, permissions, activity log
- `Manufacturing`: production batches, conversion cost, output stock, wastage

## Manufacturing Model

Production is tracked by batch.

Each production batch records:

- raw material input products and kg
- input cost per kg
- conversion expenses such as labor, electricity, enamel, and machine cost
- finished output products by kg and wire size
- wastage/loss kg
- total batch cost
- cost per finished kg

When a batch is completed:

- raw material stock decreases
- finished goods stock increases
- finished product cost is updated by weighted average
- the batch remains as the costing record

## Stock Rule

Inventory movement must always happen inside a transaction:

```text
purchase completed      → stock increases
sale completed/shipped  → stock decreases
production completed    → raw input decreases and finished output increases
batch delete            → stock reversal, only if finished stock is still available
```

## Permissions

Manufacturing permissions:

- `access_manufacturing`
- `create_production_batches`
- `show_production_batches`
- `delete_production_batches`

## Naming Standard

The product name is NECI ERP 2.0. Old source branding should not appear in user-facing screens, seed data, docs, or installer text.
