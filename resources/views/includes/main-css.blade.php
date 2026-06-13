<!-- Dropezone CSS -->
<link rel="stylesheet" href="{{ asset('css/dropzone.css') }}">
<!-- CoreUI CSS -->
@vite('resources/sass/app.scss')
<link href="{{ asset('vendor/datatables/css/datatables.min.css') }}" rel="stylesheet">
<!-- Bootstrap Icons (local) -->
<link rel="stylesheet" href="{{ asset('fonts/bootstrap-icons/bootstrap-icons.css') }}">

@yield('third_party_stylesheets')

@stack('page_css')

@livewireStyles
