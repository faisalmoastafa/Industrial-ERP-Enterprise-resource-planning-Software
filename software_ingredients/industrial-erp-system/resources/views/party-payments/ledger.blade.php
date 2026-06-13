@extends('layouts.app')

@section('title', 'Payment Ledger')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('party-payments.index') }}">Payments</a></li>
        <li class="breadcrumb-item active">Payment Ledger</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-journal-text',
            'title' => 'Payment Ledger',
            'subtitle' => 'Review individual customer or supplier payment ledger entries'
        ])

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('party-payments.ledger') }}" method="GET">
                    <div class="form-row">
                        <div class="col-lg-3"><div class="form-group"><label>Date From</label><input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}"></div></div>
                        <div class="col-lg-3"><div class="form-group"><label>Date To</label><input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}"></div></div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Party Type</label>
                                <select class="form-control" name="party_type" id="ledgerPartyType">
                                    <option value="">Select Type</option>
                                    <option value="customer" @selected(request('party_type') === 'customer')>Customer</option>
                                    <option value="supplier" @selected(request('party_type') === 'supplier')>Supplier</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Payment Type</label>
                                <select class="form-control" name="payment_type">
                                    <option value="">Any Type</option>
                                    <option value="prepay" @selected(request('payment_type') === 'prepay')>Prepay</option>
                                    <option value="pay_later" @selected(request('payment_type') === 'pay_later')>Pay Later</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-lg-8 ledger-party-select" data-party-group="customer">
                            <div class="form-group">
                                <label>Name of Customer</label>
                                <select class="form-control" name="party_id_customer">
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" @selected(request('party_type') === 'customer' && (int) request('party_id') === $customer->id)>{{ $customer->customer_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-8 ledger-party-select" data-party-group="supplier">
                            <div class="form-group">
                                <label>Name of Supplier</label>
                                <select class="form-control" name="party_id_supplier">
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @selected(request('party_type') === 'supplier' && (int) request('party_id') === $supplier->id)>{{ $supplier->supplier_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="party_id" id="ledgerPartyId" value="{{ request('party_id') }}">
                    </div>
                    <div class="form-group d-flex justify-content-start mt-2 mb-0">
                        <button type="submit" class="btn btn-primary">Show Report <i class="bi bi-file-earmark-text"></i></button>
                        <a href="{{ route('party-payments.ledger') }}" class="btn neci-report-reset ml-2">Reset <i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </form>
            </div>
        </div>

        @if($party)
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="border rounded p-3 mb-3">
                        <h5 class="mb-1">{{ request('party_type') === 'customer' ? $party->customer_name : $party->supplier_name }}</h5>
                        <div class="text-muted">{{ request('party_type') === 'customer' ? $party->customer_phone : $party->supplier_phone }} | {{ $party->address }}</div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0 neci-themed-table" id="payment-ledger-table">
                            <thead><tr><th>Date</th><th>Transaction Name</th><th>Amount</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td>-</td>
                                    <td>Opening Balance</td>
                                    <td>{{ format_currency(abs($openingBalance)) }} {{ $openingBalance < 0 ? 'Payable' : 'Receivable' }}</td>
                                </tr>
                                @forelse($entries as $entry)
                                    <tr>
                                        <td>{{ $entry['display_date'] }}</td>
                                        <td>{{ $entry['name'] }}</td>
                                        <td>{{ $entry['amount'] < 0 ? '-' : '+' }} {{ format_currency(abs($entry['amount'])) }}</td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <div class="border rounded px-3 py-2 font-weight-bold">
                            Total = {{ $total < 0 ? '-' : '+' }} {{ format_currency(abs($total)) }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const type = document.getElementById('ledgerPartyType');
            const partyId = document.getElementById('ledgerPartyId');
            const groups = document.querySelectorAll('.ledger-party-select');
            function syncLedgerParty() {
                groups.forEach(group => {
                    const visible = group.dataset.partyGroup === type.value;
                    group.style.display = visible ? '' : 'none';
                    if (visible) {
                        const select = group.querySelector('select');
                        partyId.value = select.value;
                        select.onchange = () => partyId.value = select.value;
                    }
                });
            }
            type && type.addEventListener('change', syncLedgerParty);
            syncLedgerParty();

            if ($('#payment-ledger-table').length) {
                $('#payment-ledger-table').DataTable({
                    dom: "<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>>" +
                         "<'row'<'col-md-12'tr>>" +
                         "<'row'<'col-md-5'i><'col-md-7 mt-2'p>>",
                    buttons: [
                        { extend: 'excelHtml5', text: '<i class="bi bi-file-earmark-excel-fill"></i> Excel', className: 'btn btn-secondary btn-sm' },
                        { extend: 'print', text: '<i class="bi bi-printer-fill"></i> Print', className: 'btn btn-secondary btn-sm' },
                        { text: '<i class="bi bi-x-circle"></i> Reset', className: 'btn btn-secondary btn-sm', action: function ( e, dt, node, config ) { dt.search('').draw(); } },
                        { text: '<i class="bi bi-arrow-repeat"></i> Reload', className: 'btn btn-secondary btn-sm', action: function ( e, dt, node, config ) { window.location.reload(); } }
                    ]
                });
            }
        });
    </script>
@endpush
