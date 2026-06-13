# NECI ERP 2.0 — Working Plan
**Project:** NECI ERP 2.0 (Laravel · XAMPP · Offline)  
**DB:** `neci_erp_2` · **URL:** `http://localhost/neci-erp/public`  
**Super Admin:** `adminfaisal@neci.com` / `necishop`

---

## Legend
- `[ ]` Not started
- `[~]` In progress
- `[x]` Done
- `[!]` Blocked / needs attention

---

## ~~Phase 1 — Stabilize Database~~ ✅ DONE
## ~~Phase 2 — Test Core Workflow~~ ✅ DONE
## ~~Phase 3 — Fix Remaining Errors~~ ✅ DONE

---

## ~~Phase 4 — Login Page Redesign~~ ⏸ DEFERRED (next version)

---

## ~~Phase 5 — Offline Asset Cleanup~~ ✅ DONE

**All CDN dependencies removed. Zero external requests. Fully offline.**

| Asset | Source | Local Path |
|-------|--------|------------|
| Bootstrap Icons CSS + fonts | cdn.jsdelivr.net | `public/fonts/bootstrap-icons/` |
| GSAP 3.12.5 | cdn.jsdelivr.net / cdnjs | `public/js/vendor/gsap.min.js` |
| jQuery 3.7.0 | code.jquery.com | `public/js/vendor/jquery-3.7.0.min.js` |
| DataTables bundle (CSS+JS) | cdn.datatables.net | `public/vendor/datatables/` |
| pdfmake 0.2.7 + vfs_fonts | cdnjs | `public/js/vendor/pdfmake.min.js`, `vfs_fonts.js` |
| perfect-scrollbar | cdnjs | `public/js/vendor/perfect-scrollbar.js` |
| Chart.js 3.5.0 | cdnjs | `public/js/vendor/chart.min.js` |
| SweetAlert2 v11 | cdn.jsdelivr.net | `public/vendor/sweetalert/sweetalert2.all.min.js` |
| FilePond + 3 plugins (CSS+JS) | unpkg.com | `public/vendor/filepond/` |
| Font Awesome 5.14.0 | cdnjs | `public/vendor/font-awesome/` |
| Nunito font | fonts.googleapis.com | Local system font fallback CSS |

**Files updated:**
- `resources/views/includes/main-css.blade.php`
- `resources/views/includes/main-js.blade.php`
- `resources/views/includes/filepond-css.blade.php`
- `resources/views/includes/filepond-js.blade.php`
- `resources/views/home.blade.php`
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/passwords/reset.blade.php`
- `resources/views/auth/passwords/email.blade.php`
- `resources/views/errors/illustrated-layout.blade.php`
- `Modules/User/Resources/views/users/create.blade.php`
- `Modules/User/Resources/views/users/edit.blade.php`
- All 19 module index blade files (DataTables CSS)

---

## Phase 6 — Package & Deploy

**Goal:** Clean portable bundle — "install and play" local software. No internet required after install.

### 6A — Packaging Preparation
- [ ] **6.1** Export clean SQL dump of current working database to `backup/neci_erp_2.sql`
- [ ] **6.2** Create clean project folder (exclude `node_modules`, `.git`, `storage/logs`)
- [ ] **6.3** Write **one-click launcher** → `start-neci-erp.bat`
  - Start XAMPP Apache + MySQL
  - Open browser to `http://localhost/neci-erp/public`
- [ ] **6.4** Write **database import script** → `db-import.bat`
  - Auto-import `backup/neci_erp_2.sql` into MySQL
- [ ] **6.5** Write **database reset script** → `db-reset.bat`
  - Drop + recreate DB, run migrations + seeders
- [ ] **6.6** Backup/restore scripts must never touch or overwrite `.env`
- [ ] **6.7** Write `INSTALL.md` — plain-language install guide for non-technical users

### 6B — Final Offline Package
- [ ] **6.8** Bundle: NECI ERP project folder (clean)
- [ ] **6.9** Bundle: database SQL dump + all scripts
- [ ] **6.10** Confirm XAMPP version requirement in `INSTALL.md`
- [ ] **6.11** Test full install on a clean XAMPP setup:
  - [ ] Import DB via `db-import.bat`
  - [ ] Launch via `start-neci-erp.bat`
  - [ ] Login works
  - [ ] All pages load with zero internet
- [ ] **6.12** Final review — sign off on zero CDN dependencies

---

## Notes & Decisions Log

| Date | Note |
|------|------|
| 2026-05-31 | Plan created. DB: `neci_erp_2`. Super Admin: `adminfaisal@neci.com`. Login CDN assets identified: bootstrap-icons, gsap. |
| 2026-05-31 | Phases 1, 2, 3 completed. Removed from active plan. Phases 6+7 merged into single Phase 6. |

---

## Quick Reference

```
Project root : C:\xampp\htdocs\neci-erp
URL          : http://localhost/neci-erp/public
DB name      : neci_erp_2
DB user      : root (no password)
Login email  : adminfaisal@neci.com
Password     : necishop
Role         : Super Admin
```

**Artisan commands (run from project root in XAMPP shell):**
```
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
```
