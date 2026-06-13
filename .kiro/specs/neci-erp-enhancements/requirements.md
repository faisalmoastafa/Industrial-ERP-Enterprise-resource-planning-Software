# Requirements Document

## Introduction

This document covers three enhancement areas for the NECI ERP system — a Laravel + Vite + Electron desktop ERP application. The enhancements are:

1. **Universal Enter Key Navigation** — extend the existing transaction-form-scoped Enter key navigation into a global mechanism that works across all forms in the application, with opt-out bypass for specific inputs like search bars and login forms.
2. **Dual-Logo and Company Name Branding System** — expand the Settings module to manage two separate logos (`logo_solid` for login/splash screens and `logo_transparent` for sidebar/invoices) plus a company name field, all dynamically consumed across the UI and Electron splash screen.
3. **Comprehensive Permissions Audit** — ensure every route, controller method, and view action button across all ERP modules is protected by Spatie permission guards, with every unique permission tag visible in the Roles settings page.

---

## Glossary

- **Enter_Navigator**: The global JavaScript module that intercepts the Enter key on form inputs and redirects focus to the next focusable field.
- **Transaction_Form**: A form bearing the CSS class `neci-transaction-form`, handled by the existing transaction-form-enter.js module.
- **Generic_Form**: Any `<form>` element in the application that is **not** a `Transaction_Form`.
- **Allow_Submit_Form**: A form or input carrying the CSS class `allow-submit-on-enter`, which must be excluded from Enter navigation and allowed to submit normally.
- **Focusable_Field**: A visible, enabled `<input>` (not hidden/submit/button type), `<select>`, or `<textarea>` element within a form.
- **Branding_Settings**: The section of the Settings module responsible for managing logos and company identity.
- **Logo_Solid**: A company logo image intended for use on backgrounds that provide their own color (login page left panel, Electron splash screen). Stored via Spatie Media Library in the `logo_solid` collection on the `Setting` model.
- **Logo_Transparent**: A company logo image intended for use on colored/dark backgrounds (sidebar, invoice/report print templates). Stored via Spatie Media Library in the `logo_transparent` collection on the `Setting` model.
- **Setting_Model**: The single-row Eloquent model `Modules\Setting\Entities\Setting` backed by the `settings` database table.
- **Permission_Guard**: An `abort_if(Gate::denies('permission_name'), 403)` call at the top of a controller method.
- **Route_Middleware**: A `middleware('permission:permission_name')` or `middleware('can:permission_name')` declaration on a route definition.
- **Can_Directive**: A Blade `@can('permission_name') ... @endcan` wrapper around a view action button or link.
- **Roles_Page**: The view rendered by `Modules\User\Http\Controllers\RolesController` that lists all permissions and allows assignment to roles.
- **Media_Library**: The Spatie Laravel Media Library package used for file storage, accessed via the `HasMedia` trait on Eloquent models.
- **Upload_Module**: The `Modules\Upload` module that provides a temp-file staging flow before final media attachment.
- **Spatie_Permission**: The Spatie Laravel Permission package (`spatie/laravel-permission`) used for role and permission management throughout the application.

---

## Requirements

### Requirement 1: Universal Enter Key Navigation — Generic Form Support

**User Story:** As an ERP user, I want pressing Enter on any form input to move focus to the next field instead of submitting the form, so that I can fill out data quickly without reaching for the mouse.

#### Acceptance Criteria

1. WHEN the user presses the Enter key while a `Focusable_Field` inside a `Generic_Form` is focused, THE `Enter_Navigator` SHALL move focus to the next visible and enabled `Focusable_Field` in DOM order within that form.
2. WHEN the user presses Enter on the last `Focusable_Field` in a `Generic_Form`, THE `Enter_Navigator` SHALL move focus to the form's primary submit button (first `button[type="submit"]`).
3. WHILE a `Focusable_Field` is inside a form that carries the `allow-submit-on-enter` class, THE `Enter_Navigator` SHALL allow the browser's default Enter behavior and SHALL NOT intercept the keydown event for that field.
4. WHILE an `<input>` carries the `allow-submit-on-enter` class directly, THE `Enter_Navigator` SHALL allow the browser's default Enter behavior for that specific input regardless of its parent form.
5. WHEN the user presses Enter inside a `<textarea>` without holding Shift, THE `Enter_Navigator` SHALL prevent the default newline insertion and SHALL move focus to the next `Focusable_Field`.
6. WHEN the user presses Enter while holding Shift inside a `<textarea>`, THE `Enter_Navigator` SHALL allow the default newline insertion and SHALL NOT move focus.
7. THE `Enter_Navigator` SHALL continue to apply all existing `Transaction_Form`-specific logic defined in `resources/js/core/transaction-form-enter.js` without modification.
8. WHEN the DOM is mutated by Livewire (via `livewire:navigated` or `livewire:update` events), THE `Enter_Navigator` SHALL re-evaluate the focusable field list without re-registering duplicate event listeners.
9. THE `Enter_Navigator` SHALL skip fields where `shouldSkipField()` returns `true` (not visible, read-only input, or inside excluded containers) when building the ordered field list for navigation.

---

### Requirement 2: Enter Key Bypass for Login and Search Inputs

**User Story:** As a developer, I want to designate specific forms and inputs where Enter should submit or perform a search action, so that login and search bar behavior is not broken by the global Enter navigation.

#### Acceptance Criteria

1. WHEN the user presses Enter on the login form (`resources/views/auth/login.blade.php`), THE `Enter_Navigator` SHALL NOT intercept the keydown event, allowing the form to submit normally.
2. THE `Enter_Navigator` SHALL treat any form with the CSS class `allow-submit-on-enter` as a bypass form and SHALL NOT intercept any Enter keydown events originating from within that form.
3. THE `Enter_Navigator` SHALL treat any individual input with the CSS class `allow-submit-on-enter` as a bypass field and SHALL NOT intercept Enter keydown events originating from that input.
4. WHERE a search bar input uses the `allow-submit-on-enter` class, THE `Enter_Navigator` SHALL allow the search bar's default Enter behavior (submit or trigger search) to proceed unmodified.

---

### Requirement 3: Dual-Logo Storage and Management in Settings

**User Story:** As an administrator, I want to upload and manage two separate company logos in Settings — one for solid/white-background contexts and one for transparent/dark-background contexts — so that the application displays the correct logo in each visual context.

#### Acceptance Criteria

1. THE `Setting_Model` SHALL implement the `HasMedia` interface and use the `InteractsWithMedia` trait from `Media_Library` to support media collections.
2. THE `Setting_Model` SHALL register two media collections: `logo_solid` (single file, accepting MIME types `image/jpeg`, `image/png`, `image/svg+xml`, `image/webp`) and `logo_transparent` (single file, same MIME types).
3. WHEN an administrator submits the Branding Settings form with a new logo file for `logo_solid`, THE `Branding_Settings` SHALL delete any previously stored `logo_solid` media from `Media_Library` and attach the new file to the `logo_solid` collection on the `Setting_Model`.
4. WHEN an administrator submits the Branding Settings form with a new logo file for `logo_transparent`, THE `Branding_Settings` SHALL delete any previously stored `logo_transparent` media from `Media_Library` and attach the new file to the `logo_transparent` collection on the `Setting_Model`.
5. THE `SettingController` SHALL use the `Upload_Module`'s temp-file staging flow (identical to the user profile picture flow in `ProfileController`) when processing logo uploads, staging files to `storage/temp/{folder}` before attaching to `Media_Library`.
6. WHEN no `logo_solid` media has been uploaded, THE `Branding_Settings` SHALL return the default asset path `images/logo.png` as the fallback URL for `logo_solid`.
7. WHEN no `logo_transparent` media has been uploaded, THE `Branding_Settings` SHALL return the default asset path `images/logo.png` as the fallback URL for `logo_transparent`.
8. THE Settings index view SHALL display a Branding Settings form section with two separate file upload controls labeled "Login / Splash Logo (Solid)" and "Sidebar / Invoice Logo (Transparent)", each showing a preview of the currently stored logo.
9. IF an uploaded logo file exceeds 5 MB, THEN THE `SettingController` SHALL reject the request and return a validation error message specifying the 5 MB limit.
10. IF an uploaded file has a MIME type other than `image/jpeg`, `image/png`, `image/svg+xml`, or `image/webp`, THEN THE `SettingController` SHALL reject the request and return a validation error message listing the accepted formats.

---

### Requirement 4: Dynamic Logo and Company Name in UI Templates

**User Story:** As an administrator, I want the sidebar, login page, Electron splash screen, and invoice/report print templates to automatically reflect the logos and company name stored in Settings, so that branding is consistent and centrally managed.

#### Acceptance Criteria

1. THE sidebar template (`resources/views/layouts/sidebar.blade.php`) SHALL render the `logo_transparent` URL from `Setting_Model` in the `<img>` `src` attributes for both the full and minimized sidebar logo images.
2. THE login page template (`resources/views/auth/login.blade.php`) SHALL render the `logo_solid` URL from `Setting_Model` in the branding panel `<img>` `src` attribute.
3. THE Electron splash screen (`software_ingredients/build-files/splash.html`) SHALL be replaced with a dynamic Blade template or an Electron-side mechanism that reads the `logo_solid` URL at build/launch time rather than using the hardcoded `logo.png` path.
4. WHEN an invoice or report print template renders a company logo, THE template SHALL use the `logo_transparent` URL from `Setting_Model`.
5. THE application SHALL expose the `Setting_Model` instance to all views via a View Composer or shared view data bound in a service provider, so that templates do not query the `settings` table independently.
6. WHEN the settings cache (keyed `settings`) is cleared after a branding update, THE next page load SHALL reflect the updated logos without requiring a server restart.
7. THE company name displayed in sidebar page titles and invoice headers SHALL read from the `company_name` field of `Setting_Model` rather than from the `APP_NAME` environment variable.

---

### Requirement 5: Comprehensive Route and Controller Permission Guards

**User Story:** As a security-conscious administrator, I want every route in each ERP module to be protected by a permission check, so that unauthorized users cannot access any resource by guessing URLs.

#### Acceptance Criteria

1. EVERY route defined in the `web.php` files of the Sales, Purchases, Expenses, HRM, Earnings (Income), Manufacturing, Quotations, People/Parties, and Products modules SHALL be protected by either a `Route_Middleware` permission declaration or a `Permission_Guard` in the corresponding controller method.
2. EVERY public-facing controller method in the modules listed in criterion 1 SHALL contain a `Permission_Guard` (`abort_if(Gate::denies('permission_name'), 403)`) as its first executable statement.
3. WHERE a route is already protected by both a `Route_Middleware` and a `Permission_Guard`, THE system SHALL accept the redundant guard as valid; no duplication removal is required.
4. IF an authenticated user attempts to access a route for which the user lacks the required permission, THEN THE application SHALL respond with HTTP 403.
5. EVERY permission tag referenced in `Permission_Guard` calls and `Route_Middleware` declarations across all audited modules SHALL exist as a record in the `permissions` database table (seeded via `PermissionsTableSeeder`).

---

### Requirement 6: View-Level Permission Guards on Action Buttons

**User Story:** As an administrator, I want action buttons (Create, Edit, Delete, Print) in every module's list and detail views to be wrapped in `@can` directives, so that users only see actions they are permitted to perform.

#### Acceptance Criteria

1. EVERY "Create" button or link in the index views of Sales, Purchases, Expenses, HRM, Earnings, Manufacturing, Quotations, People/Parties, and Products modules SHALL be wrapped in a `Can_Directive` using the appropriate `create_*` permission.
2. EVERY "Edit" button or link in the index and show views of the audited modules SHALL be wrapped in a `Can_Directive` using the appropriate `edit_*` permission.
3. EVERY "Delete" button or link in the index and show views of the audited modules SHALL be wrapped in a `Can_Directive` using the appropriate `delete_*` permission.
4. EVERY "Print" or "Download PDF" button in the audited modules SHALL be wrapped in a `Can_Directive` using either a dedicated `print_*` permission (if it exists) or the corresponding `show_*` permission.
5. WHEN a user lacks the permission for an action, THE view SHALL not render the corresponding button or link, preventing accidental or intentional discovery of protected URLs through the UI.

---

### Requirement 7: Permission Visibility in Roles Settings Page

**User Story:** As an administrator, I want every permission tag in the system to be visible and assignable on the Roles settings page, so that I can build custom roles with precise access control.

#### Acceptance Criteria

1. THE `Roles_Page` SHALL display every permission record from the `permissions` table, grouped by module (Sales, Purchases, Expenses, HRM, Earnings, Manufacturing, Quotations, People/Parties, Products, Settings, Users, System).
2. EVERY new permission added during the permissions audit (Requirement 5) SHALL be seeded via `PermissionsTableSeeder` using `Permission::firstOrCreate()` to prevent duplicate-key errors on re-seeding.
3. THE `Roles_Page` SHALL allow an administrator with the `access_user_management` permission to assign or revoke any listed permission on any role.
4. WHEN a new permission is added to `PermissionsTableSeeder` and the seeder is run, THE permission SHALL appear on the `Roles_Page` on the next page load without requiring a cache clear.
5. THE Super Admin role SHALL automatically receive every permission defined in `PermissionsTableSeeder` via `syncPermissions()`, including all newly added permissions from the audit.

---

### Requirement 8: Print Permission for Sales, Purchases, and Reports

**User Story:** As an administrator, I want dedicated print permissions for sales invoices, purchase receipts, and other printable documents, so that I can grant print access independently of view access.

#### Acceptance Criteria

1. THE `PermissionsTableSeeder` SHALL define unique `print_sales` and `print_purchases` permissions distinct from `show_sales` and `show_purchases`.
2. WHEN a user attempts to access a sale or purchase print/PDF route, THE `Permission_Guard` SHALL check `print_sales` or `print_purchases` respectively.
3. EVERY "Print Invoice" or "Download PDF" button on sale and purchase show/detail views SHALL be wrapped in a `Can_Directive` using `print_sales` or `print_purchases` accordingly.
4. THE Super Admin and Admin roles SHALL be granted `print_sales` and `print_purchases` by default in `PermissionsTableSeeder`.
