# Industrial ERP System

A full-featured desktop ERP application for industrial businesses — built with **Laravel + Electron + SQLite**. Runs entirely offline on Windows. No server, no cloud, no subscription.

---

## What's Included

| Module | Features |
|---|---|
| Products & Inventory | Products, categories, barcodes, stock levels |
| Purchases | Supplier orders, payments, purchase returns |
| Sales & Billing | Invoices, POS, sale returns, PDF printing |
| Quotations | Price quotes with one-click conversion to sale |
| Customers & Suppliers | Profiles, ledger, opening balances |
| Expenses & Income | Categorized financial entries |
| Manufacturing | Production batches, raw material consumption |
| HRM & Payroll | Employees, attendance, overtime, bonuses, payroll |
| Reports | Profit & Loss, stock, cash flow, and more |
| Settings | Branding, logo, currency, units |
| User Management | Roles, permissions, activity log |
| Backup & Restore | One-click SQLite backup with restore |

---

## Default Login

| Field | Value |
|---|---|
| Email | `superadmin@erp.com` |
| Password | `superadmin` |
| Role | Super Admin (full access) |

> **Change the password** immediately after first login.

---

## Requirements (to build the installer)

- **Windows 10/11** (64-bit)
- **Node.js** v18+ — [nodejs.org](https://nodejs.org)
- **PHP 8.3 for Windows** — download `php-8.3.x-Win32-vs16-x64.zip` from [windows.php.net](https://windows.php.net/download/) and extract it to:
  ```
  Industrial-ERP-Software\php-8.3.31-Win32-vs16-x64\
  ```
  (The folder name must match exactly)

> PHP is not included in the repo because of GitHub's 100MB file size limit.

---

## How to Build the Installer

### Step 1 — Clone the repo

```bash
git clone https://github.com/yourusername/industrial-erp-system.git
cd industrial-erp-system
```

### Step 2 — Install PHP

Download PHP 8.3 (Thread Safe, x64) from [windows.php.net](https://windows.php.net/download/) and extract it to:
```
industrial-erp-system\Industrial-ERP-Software\php-8.3.31-Win32-vs16-x64\
```

Copy the `php.ini` from `Industrial-ERP-Software\build-files\php.ini` into the PHP folder.

### Step 3 — Install Laravel dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### Step 4 — Run the build

Double-click `Industrial-ERP-Software\build.bat` (or run it as Administrator).

It will:
1. Create a clean Laravel copy at `Industrial-ERP-Software\industrial-erp-system\`
2. Copy all Electron files to `C:\Industrial-ERP-Builder\electron\`
3. Run `npm install`
4. Run `electron-builder` to produce the installer
5. Open `C:\Industrial-ERP-Builder\dist\` with the final `.exe`

---

## Project Structure

```
industrial-erp-system/
├── app/                        # Laravel application code
├── Modules/                    # Feature modules (Sales, Purchases, HRM, etc.)
├── database/
│   ├── migrations/             # All database migrations
│   └── seeders/                # Seeders (superadmin user + defaults)
├── Industrial-ERP-Software/
│   ├── build-files/            # Electron shell source
│   │   ├── main.js             # Electron main process
│   │   ├── splash.html         # Startup splash screen
│   │   ├── user-manual.html    # Built-in user manual
│   │   ├── package.json        # Electron + electron-builder config
│   │   ├── icon.ico            # App icon
│   │   └── logo.png            # App logo
│   ├── build.bat               # Full build script (run this to build)
│   └── php-8.3.31-Win32-vs16-x64/  # [NOT IN GIT — download separately]
├── prepare-build.bat           # Prep script (optional — build.bat does everything)
└── README.md
```

---

## Tech Stack

- **Backend**: Laravel 10, PHP 8.3, SQLite
- **Frontend**: Blade templates, Livewire, Alpine.js, Vite
- **Desktop shell**: Electron 28
- **Key packages**: Spatie Permissions, Spatie MediaLibrary, Laravel Modules, Yajra DataTables, Maatwebsite Excel, Milon Barcode

---

## License

MIT — free to use, modify, and distribute.
