# NECI ERP 2.0 Cleanup Review

The cleanup pass removed old source branding, obsolete Docker deployment files, the generated transcript dump, visual prototype folders, scratch scripts, the old public storage copy, and generated Laravel compiled views.

Removed project clutter:

- `scratch/`
- `revenue card/`
- `yeti login/`
- `yeti page selector/`
- `public/storage_legacy_before_link_20260519_214615/`
- generated files inside `storage/framework/views/`

Kept required runtime folders:

- `storage/framework/views/`

Laravel needs that folder to exist, so it now contains only `.gitignore`.
