<script src="{{ asset('js/vendor/jquery-3.7.0.min.js') }}"></script>
<script src="{{ asset('js/vendor/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/vendor/vfs_fonts.js') }}"></script>
<script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('js/vendor/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
<script src="{{ asset('js/jquery-mask-money.js') }}"></script>

@include('sweetalert::alert')



@vite('resources/js/app.js')

@yield('third_party_scripts')

@stack('page_scripts')

@livewireScripts

