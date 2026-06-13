# Tasks — Industrial ERP System Publish

## Task List

- [x] 1. Update SuperUserSeeder with new superadmin credentials
  - [x] 1.1 In `database/seeders/SuperUserSeeder.php`, change `name` to `superadmin`, `email` to `superadmin@erp.com`, and `password` to `Hash::make('superadmin')`
  - Requirement: REQ-4

- [x] 2. Update SettingDatabaseSeeder with new branding values
  - [x] 2.1 In `Modules/Setting/Database/Seeders/SettingDatabaseSeeder.php`, set `company_name` to `Industrial ERP System`, `app_title` to `Industrial ERP`, `app_tagline` to `Industrial ERP System`, and `footer_text` to `Industrial ERP System &copy; 2026`
  - Requirement: REQ-6

- [x] 3. Update app identity migration default values
  - [x] 3.1 In `database/migrations/2026_06_12_203215_add_app_identity_to_settings_table.php`, change the `DB::table('settings')->update()` defaults so `app_title` is `Industrial ERP` and `app_tagline` is `Industrial ERP System`
  - Requirement: REQ-1.1

- [x] 4. Update Laravel .env APP_NAME
  - [x] 4.1 In `.env`, change `APP_NAME` to `"Industrial ERP System"`
  - Requirement: REQ-1.1

- [x] 5. Update config/app.php fallback name
  - [x] 5.1 In `config/app.php`, change the `env('APP_NAME', ...)` fallback value to `'Industrial ERP System'`
  - Requirement: REQ-1.1

- [x] 6. Copy icon.ico and ulogo.png into build-files
  - [x] 6.1 Copy `software_ingredients/icon.ico` → `software_ingredients/build-files/icon.ico` (overwrite)
  - [x] 6.2 Copy `software_ingredients/ulogo.png` → `software_ingredients/build-files/logo.png` (overwrite)
  - Requirement: REQ-2, REQ-3

- [x] 7. Update Electron main.js branding
  - [x] 7.1 Change window `title` from `'NECI ERP 2.0'` to `'Industrial ERP System'`
  - [x] 7.2 Change About dialog `title`, `message`, and `detail` strings from NECI references to Industrial ERP System
  - [x] 7.3 Change File menu `label` "About NECI ERP" to "About Industrial ERP System"
  - [x] 7.4 Change startup log message from `=== NECI ERP 2.0 Starting...` to `=== Industrial ERP System Starting...`
  - [x] 7.5 Change log file name from `neci-erp.log` to `industrial-erp.log`
  - Requirement: REQ-1.1

- [x] 8. Update Electron package.json
  - [x] 8.1 Change `name` to `industrial-erp`
  - [x] 8.2 Change `description` to `Industrial ERP System`
  - [x] 8.3 Change `productName` to `Industrial ERP System`
  - [x] 8.4 Change `appId` to `com.industrial.erp`
  - [x] 8.5 Change `copyright` to `Copyright 2026 Industrial ERP System`
  - [x] 8.6 Change NSIS `shortcutName` to `Industrial ERP System`
  - [x] 8.7 Change NSIS `uninstallDisplayName` to `Industrial ERP System`
  - Requirement: REQ-1.1, REQ-1.2

- [x] 9. Update splash.html fallback branding
  - [x] 9.1 Change `<h1>` default text from `NECI ERP` to `Industrial ERP`
  - [x] 9.2 Change `<p>` default text from `NEC Super and Cables Industries` to `Industrial ERP System`
  - [x] 9.3 Change `<img alt>` from `NECI` to `Industrial ERP`
  - Requirement: REQ-8

- [x] 10. Update build-files/app.env APP_NAME
  - [x] 10.1 In `software_ingredients/build-files/app.env`, change `APP_NAME` to `"Industrial ERP System"`
  - Requirement: REQ-1.1

- [ ] 11. Update prepare-build.bat display strings
  - [ ] 11.1 Change `title` command value from `NECI ERP - Prepare Clean Build` to `Industrial ERP System - Prepare Clean Build`
  - [~] 11.2 Change all `echo` lines and heading strings that say "NECI ERP" to "Industrial ERP System"
  - Requirement: REQ-7.1

- [ ] 12. Update software_ingredients/build.bat display strings and userData path
  - [~] 12.1 Change `title` command value to `Industrial ERP System - Live Build Progress`
  - [~] 12.2 Change all echo/heading strings from "NECI ERP" to "Industrial ERP System"
  - [~] 12.3 Change the `del /F /Q "%APPDATA%\neci-erp\db_initialized"` line to `del /F /Q "%APPDATA%\industrial-erp\db_initialized"`
  - Requirement: REQ-7.2, REQ-7.3

- [ ] 13. Reset and reseed the SQLite database
  - [~] 13.1 Run `php artisan migrate:fresh --seed` from the project root to drop all tables, run all migrations, and run all seeders (producing a clean database with only the new superadmin user and default data)
  - Requirement: REQ-5
