# NECI Industrial ERP System — Release Notes

## Version 1.0.0 — Initial Release

**Release Date:** June 2025

---

## 🎉 What's New

This is the first public release of the **NECI Industrial ERP System** — a full-featured, open-source Enterprise Resource Planning solution built for industrial and manufacturing businesses.

---

## ✅ Features Included in v1.0

### Core Modules
- **Sales Management** — Create and manage sales orders, invoices, and payments
- **Purchase Management** — Handle purchase orders, supplier payments, and returns
- **Inventory / Products** — Product catalog, categories, stock tracking, barcode generation
- **Manufacturing** — Production batch management with inputs, outputs, and conversion expenses
- **HRM (Human Resource Management)** — Employees, attendance, overtime, bonuses, and payroll
- **Expense Management** — Record and categorize business expenses
- **Income Management** — Track non-sale income streams
- **Quotations** — Create and email quotations to customers
- **Party Ledger** — Full customer and supplier ledger with opening balance support
- **Reports** — Profit & loss, sales, purchases, payments, and party balance reports
- **POS (Point of Sale)** — Quick sales interface for walk-in customers
- **User Management** — Role-based access control with granular permissions
- **Settings** — Branding (logo, company identity), SMTP email, units of measure

### Technical Highlights
- Built with **Laravel 10** and **PHP 8.3**
- Livewire 3 for reactive UI components
- Spatie Laravel Permission for role management
- Maatwebsite Excel for import/export
- DataTables with server-side processing
- Responsive design using CoreUI

---

## 📦 Installation

### Option 1 — Windows Desktop App (Offline)
Download and run the installer from the [Releases](../../releases) page. No server setup required — PHP 8.3 is bundled.

### Option 2 — Server / Cloud Deployment
1. Clone the repository
2. Copy `industrial-erp-system/.env` and configure your database
3. Run `composer install` and `php artisan migrate --seed`
4. Visit the app and log in with the default superadmin credentials

### Default Login
> **Email:** `admin@admin.com`
> **Password:** `password`
> ⚠️ Change these immediately after first login.

---

## 🐛 Known Issues in v1.0

- PDF generation requires `wkhtmltopdf` on Linux/cloud deployments (bundled for Windows)
- Activity log module may show empty on fresh installs until first user action

---

## 🚀 What's Coming in v2.0

- Multi-warehouse inventory support
- Customer portal with order tracking
- Advanced production scheduling
- Mobile-friendly PWA mode
- Multi-currency support enhancements

---

## 💌 Dedication

> *This project is dedicated with love and gratitude to:*
>
> **HWC Family** — for the constant support and belief in this vision.
>
> **All My Friends** — for the encouragement, the late-night debugging sessions, and the laughs along the way.
>
> **Mamun Sir** — whose mentorship, patience, and wisdom shaped the foundation of this work. Thank you for always pushing me further.
>
> **Kalyan Roy Chowdhury Sir** — for the technical insights, the real-world perspective, and the guidance that made this system what it is. This would not exist without you.
>
> *"Build something that lasts. Build something that helps."*

---

## 📄 License

This project is open source under the [MIT License](LICENSE).

---

## 🙏 Acknowledgements

- [Laravel](https://laravel.com) — The PHP framework for web artisans
- [Spatie](https://spatie.be) — For the amazing Laravel packages
- [Yajra DataTables](https://yajrabox.com/docs/laravel-datatables) — Server-side DataTables
- [CoreUI](https://coreui.io) — The admin template
- All open-source contributors whose packages power this system
