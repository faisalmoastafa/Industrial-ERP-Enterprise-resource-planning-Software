@extends('layouts.app')

@section('title', 'System Restore')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item">System Utilities</li>
        <li class="breadcrumb-item active">Restore System</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid neci-restore-page mb-4">
        <div class="neci-restore-shell">
            <header class="neci-restore-hero">
                <div class="neci-restore-hero__icon">
                    <i class="bi bi-cloud-arrow-up"></i>
                </div>
                <div class="neci-restore-hero__text">
                    <span class="neci-restore-eyebrow">System utilities</span>
                    <h1>NECI Disaster Recovery</h1>
                    <p>Restore database, configuration, and product images from a verified <code>.zip</code> backup archive.</p>
                </div>
            </header>

            @include('utils.alerts')

            <div class="row neci-restore-grid align-items-stretch">
                <div class="col-lg-7">
                    <section class="neci-restore-panel neci-restore-panel--upload">
                        <h2 class="neci-restore-panel__title">
                            <i class="bi bi-file-earmark-zip"></i> Upload backup
                        </h2>

                        <form
                            action="{{ route('restore.process') }}"
                            method="POST"
                            enctype="multipart/form-data"
                            class="neci-restore-form"
                            data-neci-confirm-submit
                            data-neci-submit-loading
                            data-neci-confirm-title="Restore the system?"
                            data-neci-confirm-text="Are you absolutely sure? This will replace your active sales data, stock, users, and images with the backup."
                            data-neci-confirm-type="warning"
                            data-neci-confirm-yes="Yes, restore"
                            data-neci-confirm-no="No, cancel"
                        >
                            @csrf

                            <label class="neci-restore-dropzone" for="backup_file">
                                <span class="neci-restore-dropzone__icon"><i class="bi bi-cloud-upload"></i></span>
                                <span class="neci-restore-dropzone__title">Drop your backup ZIP here</span>
                                <span class="neci-restore-dropzone__hint">Select a backup ZIP file to restore.</span>
                                <span class="neci-restore-dropzone__file btn btn-sm btn-outline-info mt-2" data-restore-file-label style="pointer-events: none; display: inline-block;">
                                    <i class="bi bi-file-earmark-zip"></i> Choose Backup ZIP
                                </span>
                                <input
                                    type="file"
                                    name="backup_file"
                                    class="neci-restore-file-input"
                                    id="backup_file"
                                    accept=".zip"
                                    required
                                    onclick="event.preventDefault(); fetch('{{ route('restore.open_folder') }}', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}})"
                                >
                            </label>

                            <div class="neci-restore-actions">
                                <button type="submit" class="btn neci-tx-outline-warning" data-neci-submit-btn>
                                    <span class="neci-restore-submit-label neci-restore-submit-label--default">
                                        <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Start restoration
                                    </span>
                                    <span class="neci-restore-submit-label neci-restore-submit-label--loading" aria-live="polite">
                                        <span class="spinner-border spinner-border-sm neci-restore-submit-spinner" role="status" aria-hidden="true"></span>
                                        Restoring…
                                    </span>
                                </button>
                                @include('includes.neci-tx-cancel', ['href' => route('home'), 'class' => 'neci-restore-btn-cancel'])
                            </div>
                        </form>
                    </section>
                </div>

                <div class="col-lg-5">
                    <div class="row neci-restore-aside-row">
                        <div class="col-md-6 d-flex">
                            <section class="neci-restore-panel neci-restore-panel--warning flex-fill">
                                <h2 class="neci-restore-panel__title">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Before you continue
                                </h2>
                                <ul class="neci-restore-checklist">
                                    <li><i class="bi bi-x-circle-fill"></i> Overwrites sales, stock, and user data</li>
                                    <li><i class="bi bi-x-circle-fill"></i> Replaces product images and settings</li>
                                    <li><i class="bi bi-lock-fill"></i> Super Admin permission required</li>
                                    <li><i class="bi bi-eye-slash-fill"></i> Keep backup files private (may contain credentials)</li>
                                </ul>
                            </section>
                        </div>
                        <div class="col-md-6 d-flex">
                            <section class="neci-restore-panel neci-restore-panel--steps flex-fill">
                                <h2 class="neci-restore-panel__title">
                                    <i class="bi bi-list-ol"></i> Recovery steps
                                </h2>
                                <ol class="neci-restore-steps">
                                    <li>Export or locate your latest NECI backup ZIP.</li>
                                    <li>Confirm no other users are actively using the system.</li>
                                    <li>Upload the file and confirm restoration.</li>
                                    <li>Wait for the process to finish — do not close the browser.</li>
                                </ol>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('backup_file');
        const label = document.querySelector('[data-restore-file-label]');

        if (!input || !label) {
            return;
        }

        input.addEventListener('change', function () {
            const name = input.files && input.files.length ? input.files[0].name : 'Choose file...';
            label.textContent = name;
            label.classList.toggle('is-selected', Boolean(input.files && input.files.length));
        });
    });
</script>
@endpush

