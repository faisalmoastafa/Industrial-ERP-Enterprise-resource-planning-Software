@extends('layouts.app')

@section('title', 'Edit Settings')

@section('third_party_stylesheets')
    @include('includes.filepond-css')
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">System Settings</a></li>
        <li class="breadcrumb-item active">General Settings</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-gear',
            'title' => 'System Settings',
            'subtitle' => 'Manage company defaults, currency, logos, and mail configuration'
        ])

        <div class="row">
            <div class="col-lg-12">
                @include('utils.alerts')

                {{-- ── General Settings ──────────────────────────────────────────────── --}}
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">General Settings</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="company_name">Company Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="company_name" value="{{ $settings->company_name }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="company_email">Company Email <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="company_email" value="{{ $settings->company_email }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="company_phone">Company Phone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="company_phone" value="{{ $settings->company_phone }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="default_currency_id">Default Currency <span class="text-danger">*</span></label>
                                        <select name="default_currency_id" id="default_currency_id" class="form-control" required>
                                            @foreach(\Modules\Currency\Entities\Currency::all() as $currency)
                                                <option {{ $settings->default_currency_id == $currency->id ? 'selected' : '' }} value="{{ $currency->id }}">{{ $currency->currency_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="default_currency_position">Default Currency Position <span class="text-danger">*</span></label>
                                        <select name="default_currency_position" id="default_currency_position" class="form-control" required>
                                            <option {{ $settings->default_currency_position == 'prefix' ? 'selected' : '' }} value="prefix">Prefix</option>
                                            <option {{ $settings->default_currency_position == 'suffix' ? 'selected' : '' }} value="suffix">Suffix</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="notification_email">Notification Email <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="notification_email" value="{{ $settings->notification_email }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="company_address">Company Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="company_address" value="{{ $settings->company_address }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="footer_text">
                                            Footer Text
                                            <i class="bi bi-question-circle-fill text-info ml-1"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="Shown at the bottom of every page. HTML is allowed, e.g. Company Name &copy; 2025"></i>
                                        </label>
                                        <input type="text" class="form-control" name="footer_text"
                                               value="{{ $settings->footer_text }}"
                                               placeholder="e.g. NECI ERP &copy; 2025 — All rights reserved">
                                        <small class="text-muted">This text appears in the footer bar on every screen.</small>
                                    </div>
                                </div>
                            </div>

                            {{-- ── Splash / App Identity ─────────────────────────────────────────── --}}
                            <hr class="my-3">
                            <p class="font-weight-bold mb-2"><i class="bi bi-stars mr-1 text-primary"></i> Splash Screen Identity</p>
                            <p class="text-muted small mb-3">These two fields control what is displayed on the startup splash screen when the app first loads.</p>
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="app_title">
                                            App Title
                                            <span class="badge badge-info ml-1">Splash heading</span>
                                        </label>
                                        <input type="text" class="form-control" name="app_title"
                                               value="{{ $settings->app_title ?? 'NECI ERP' }}"
                                               placeholder="e.g. NECI ERP">
                                        <small class="text-muted">Big text shown on the splash screen.</small>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="app_tagline">
                                            App Tagline
                                            <span class="badge badge-info ml-1">Splash subtitle</span>
                                        </label>
                                        <input type="text" class="form-control" name="app_tagline"
                                               value="{{ $settings->app_tagline ?? 'NEC Super and Cables Industries' }}"
                                               placeholder="e.g. NEC Super and Cables Industries">
                                        <small class="text-muted">Smaller line shown beneath the title on the splash screen.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 mt-4">
                @if (session()->has('settings_smtp_message'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <div class="alert-body">
                            <span>{{ session('settings_smtp_message') }}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Mail Settings</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.smtp.update') }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="mail_mailer">MAIL_MAILER <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="mail_mailer" value="{{ env('MAIL_MAILER') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="mail_host">MAIL_HOST <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="mail_host" value="{{ env('MAIL_HOST') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="mail_port">MAIL_PORT <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="mail_port" value="{{ env('MAIL_PORT') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="mail_mailer">MAIL_MAILER</label>
                                        <input type="text" class="form-control" name="mail_mailer" value="{{ env('MAIL_MAILER') }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="mail_username">MAIL_USERNAME</label>
                                        <input type="text" class="form-control" name="mail_username" value="{{ env('MAIL_USERNAME') }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="mail_password">MAIL_PASSWORD</label>
                                        <input type="password" class="form-control" name="mail_password" value="{{ env('MAIL_PASSWORD') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="mail_encryption">MAIL_ENCRYPTION</label>
                                        <input type="text" class="form-control" name="mail_encryption" value="{{ env('MAIL_ENCRYPTION') }}">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label for="mail_from_address">MAIL_FROM_ADDRESS</label>
                                        <input type="email" class="form-control" name="mail_from_address" value="{{ env('MAIL_FROM_ADDRESS') }}">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label for="mail_from_name">MAIL_FROM_NAME <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="mail_from_name" value="{{ env('MAIL_FROM_NAME') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ── Branding (Logos) — moved to bottom ──────────────────────────────── --}}
            <div class="col-lg-12 mt-4">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-palette mr-2"></i>Branding</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf
                            @method('patch')

                            {{-- Hidden folder inputs — filled by FilePond on upload --}}
                            <input type="hidden" name="logo_solid_folder"       id="logo_solid_folder">
                            <input type="hidden" name="logo_transparent_folder" id="logo_transparent_folder">

                            {{-- Pass through required fields so validation passes --}}
                            <input type="hidden" name="company_name"              value="{{ $settings->company_name }}">
                            <input type="hidden" name="company_email"             value="{{ $settings->company_email }}">
                            <input type="hidden" name="company_phone"             value="{{ $settings->company_phone }}">
                            <input type="hidden" name="notification_email"        value="{{ $settings->notification_email }}">
                            <input type="hidden" name="company_address"           value="{{ $settings->company_address }}">
                            <input type="hidden" name="default_currency_id"       value="{{ $settings->default_currency_id }}">
                            <input type="hidden" name="default_currency_position" value="{{ $settings->default_currency_position }}">
                            <input type="hidden" name="footer_text"               value="{{ $settings->footer_text }}">

                            <div class="form-row">
                                {{-- Login / Splash Logo (Solid) --}}
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="bi bi-shield-lock mr-1 text-primary"></i>
                                            Login / Splash Logo <span class="badge badge-secondary ml-1">Solid Background</span>
                                        </label>
                                        <p class="text-muted small mb-2">Shown on the login page left panel and the Electron splash screen.</p>
                                        <div class="mb-2 text-center">
                                            <img id="preview_logo_solid"
                                                 src="{{ $settings->getLogoSolidUrl() }}"
                                                 alt="Current Login Logo"
                                                 style="max-height: 80px; max-width: 220px; border-radius: 6px; border: 1px solid #dee2e6; padding: 6px; background: #fff;">
                                        </div>
                                        <input type="file" id="logo_solid_input" name="logo_solid_file" accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp">
                                    </div>
                                </div>

                                {{-- Sidebar / Invoice Logo (Transparent) --}}
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="bi bi-layout-sidebar mr-1 text-primary"></i>
                                            Sidebar / Invoice Logo <span class="badge badge-secondary ml-1">Transparent Background</span>
                                        </label>
                                        <p class="text-muted small mb-2">Shown in the sidebar navigation and on printed invoices & reports.</p>
                                        <div class="mb-2 text-center">
                                            <img id="preview_logo_transparent"
                                                 src="{{ $settings->getLogoTransparentUrl() }}"
                                                 alt="Current Sidebar Logo"
                                                 style="max-height: 80px; max-width: 220px; border-radius: 6px; border: 1px solid #dee2e6; padding: 6px; background: #f8f9fa;">
                                        </div>
                                        <input type="file" id="logo_transparent_input" name="logo_transparent_file" accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Save Logos</button>
                                <small class="text-muted ml-2">Max 5 MB · PNG, JPG, SVG, WebP</small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('page_scripts')
    @include('includes.filepond-css')
    <script src="{{ asset('vendor/filepond/filepond-plugin-image-preview.js') }}"></script>
    <script src="{{ asset('vendor/filepond/filepond-plugin-file-validate-size.js') }}"></script>
    <script src="{{ asset('vendor/filepond/filepond-plugin-file-validate-type.js') }}"></script>
    <script src="{{ asset('vendor/filepond/filepond.js') }}"></script>
    <script>
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType
        );

        const fpOptions = {
            maxFileSize: '5MB',
            acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml', 'image/webp'],
            server: {
                process: "{{ route('filepond.upload') }}",
                revert:  "{{ route('filepond.delete') }}",
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
            }
        };

        // Logo Solid pond
        const pondSolid = FilePond.create(
            document.getElementById('logo_solid_input'),
            {
                ...fpOptions,
                labelIdle: 'Drag & drop or <span class="filepond--label-action">Browse</span> (Login / Splash Logo)',
                onprocessfile: (error, file) => {
                    if (!error) {
                        document.getElementById('logo_solid_folder').value = file.serverId;
                    }
                },
                onremovefile: () => {
                    document.getElementById('logo_solid_folder').value = '';
                }
            }
        );

        // Logo Transparent pond
        const pondTransparent = FilePond.create(
            document.getElementById('logo_transparent_input'),
            {
                ...fpOptions,
                labelIdle: 'Drag & drop or <span class="filepond--label-action">Browse</span> (Sidebar / Invoice Logo)',
                onprocessfile: (error, file) => {
                    if (!error) {
                        document.getElementById('logo_transparent_folder').value = file.serverId;
                    }
                },
                onremovefile: () => {
                    document.getElementById('logo_transparent_folder').value = '';
                }
            }
        );
    </script>
@endpush

