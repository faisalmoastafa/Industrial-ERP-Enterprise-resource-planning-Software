@extends('layouts.app')

@section('content')
    <div class="container-fluid neci-transaction-page mb-4">
        <div class="neci-transaction-shell">
            <header class="neci-transaction-header">
                <div class="neci-transaction-header__main">
                    @hasSection('transaction_icon')
                        <span class="neci-transaction-header__icon">@yield('transaction_icon')</span>
                    @endif
                    <div>
                        <h1 class="neci-transaction-header__title">@yield('transaction_title')</h1>
                        @hasSection('transaction_subtitle')
                            <p class="neci-transaction-header__subtitle">@yield('transaction_subtitle')</p>
                        @endif
                    </div>
                </div>
                @hasSection('transaction_header_actions')
                    <div class="neci-transaction-header__actions">@yield('transaction_header_actions')</div>
                @endif
            </header>

            @include('utils.alerts')

            <form
                id="@yield('transaction_form_id', 'transaction-form')"
                action="@yield('transaction_form_action')"
                method="@yield('transaction_form_method', 'POST')"
                class="neci-transaction-form"
                @hasSection('transaction_form_enctype')
                    enctype="@yield('transaction_form_enctype')"
                @endif
            >
                @csrf
                @yield('transaction_method')

                @yield('transaction_content')

                <footer class="neci-transaction-footer">
                    @yield('transaction_footer')
                </footer>
            </form>
        </div>
    </div>
@endsection
