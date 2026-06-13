# Requirements — Industrial ERP System Publish

## Overview

Rebrand and prepare the existing NECI ERP application for general public release as **Industrial ERP System**. This involves updating every place the old brand name appears (Electron shell, Laravel app, database seed, splash screen, build scripts) and resetting the database to a clean slate with a single superadmin user.

---

## Requirements

### REQ-1: Application Name & Branding
- **REQ-1.1** The product name must be changed to **"Industrial ERP System"** everywhere it appears:
  - Electron window title (`main.js` → `title:`)
  - Electron About dialog (`main.js` → `dialog.showMessageBox`)
  - Electron app menu labels (`main.js` → Menu template)
  - Electron `package.json` → `name`, `description`, `productName`, `copyright`, `nsis.shortcutName`, `nsis.uninstallDisplayName`, `appId`
  - Laravel `.env` → `APP_NAME`
  - Build `.env` template (`build-files/app.env`) → `APP_NAME`
  - Setting seeder (`SettingDatabaseSeeder.php`) → `company_name`, `footer_text`, `app_title`, `app_tagline`
  - App identity migration default values → `app_title`, `app_tagline`
  - `config/app.php` fallback → `'name'`
  - Log file name in `main.js` (`neci-erp.log` → `industrial-erp.log`)
  - Startup log message in `main.js`

- **REQ-1.2** The `appId` in `package.json` must be changed to `com.industrial.erp`.

### REQ-2: Icon
- **REQ-2.1** The build icon file used by Electron must be `software_ingredients/icon.ico` (already the correct source file at that path).
- **REQ-2.2** The file `software_ingredients/build-files/icon.ico` must be replaced/overwritten with a copy of `software_ingredients/icon.ico`.
- **REQ-2.3** All references to the icon in `main.js`, `package.json`, and the NSIS config must continue to point to `icon.ico` (no path changes needed, just ensure the file is the correct one).

### REQ-3: Logo
- **REQ-3.1** The logo file used on the splash screen and in the application must be `software_ingredients/ulogo.png`.
- **REQ-3.2** The file `software_ingredients/build-files/logo.png` must be replaced/overwritten with a copy of `software_ingredients/ulogo.png`.
- **REQ-3.3** References to `logo.png` in `splash.html` and `main.js` remain unchanged (they already reference `logo.png` by filename — the content of that file is what needs updating).

### REQ-4: Superadmin User
- **REQ-4.1** The `SuperUserSeeder.php` must be updated so the single seeded user has:
  - `name`: `superadmin`
  - `email`: `superadmin@erp.com`
  - `password`: `superadmin` (hashed with `Hash::make`)
  - `is_active`: `1`
- **REQ-4.2** The role assigned must remain `Super Admin` (which already receives all permissions via `PermissionsTableSeeder`).
- **REQ-4.3** No other users must be seeded.

### REQ-5: Clean Database
- **REQ-5.1** The SQLite database file (`database/database.sqlite`) must be fully reset — all tables dropped and recreated via fresh migrations.
- **REQ-5.2** After migration, the database seeder must be run so only the following data exists:
  - All permissions and roles (from `PermissionsTableSeeder`)
  - The single superadmin user (from `SuperUserSeeder`)
  - Default currency (from `CurrencyDatabaseSeeder`)
  - Default settings row with the new "Industrial ERP System" branding (from `SettingDatabaseSeeder`)
  - Default product units (from `ProductDatabaseSeeder`)
- **REQ-5.3** No customer, supplier, sale, purchase, product, or any other business data must exist in the final database.

### REQ-6: Settings Seeder Branding Values
- **REQ-6.1** `SettingDatabaseSeeder` must seed:
  - `company_name`: `Industrial ERP System`
  - `app_title`: `Industrial ERP`
  - `app_tagline`: `Industrial ERP System`
  - `footer_text`: `Industrial ERP System &copy; 2026`
  - Other fields (email, phone, currency, address) may keep neutral placeholder values.

### REQ-7: Build Scripts
- **REQ-7.1** `prepare-build.bat` must be updated — any display strings and title referencing "NECI ERP" must reference "Industrial ERP System".
- **REQ-7.2** `software_ingredients/build.bat` must be updated similarly — title, echo labels, and the `del` command that clears `%APPDATA%\neci-erp\` must be updated to `%APPDATA%\industrial-erp\`.
- **REQ-7.3** The Electron `userData` folder name is derived from the Electron `app.getName()` which reads from `package.json` `name` field — this will be `industrial-erp` after REQ-1.2, so `build.bat` cleanup line must match.

### REQ-8: Splash Screen Fallback Text
- **REQ-8.1** The hardcoded fallback text in `splash.html` must be updated:
  - `<h1>` default text: `Industrial ERP`
  - `<p>` default text: `Industrial ERP System`
  - `<img alt>`: `Industrial ERP`
