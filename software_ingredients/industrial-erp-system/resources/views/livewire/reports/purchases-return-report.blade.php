<div>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form wire:submit="generateReport">
                        <div class="form-row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Start Date <span class="text-danger">*</span></label>
                                    <input wire:model="start_date" type="date" class="form-control" name="start_date">
                                    @error('start_date')
                                    <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>End Date <span class="text-danger">*</span></label>
                                    <input wire:model="end_date" type="date" class="form-control" name="end_date">
                                    @error('end_date')
                                    <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Supplier</label>
                                    <select wire:model="supplier_id" class="form-control" name="supplier_id">
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select wire:model="purchase_return_status" class="form-control" name="purchase_return_status">
                                        <option value="">Select Status</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Shipped">Shipped</option>
                                        <option value="Completed">Completed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Payment Status</label>
                                    <select wire:model="payment_status" class="form-control" name="payment_status">
                                        <option value="">Select Payment Status</option>
                                        <option value="Paid">Paid</option>
                                        <option value="Unpaid">Unpaid</option>
                                        <option value="Partial">Partial</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0 d-flex flex-wrap align-items-center">
                            <button type="submit" class="btn btn-primary">
                                <span wire:target="generateReport" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <i wire:target="generateReport" wire:loading.remove class="bi bi-shuffle"></i>
                                Filter Report
                            </button>
                            <button type="button" wire:click="resetFilters" class="btn neci-report-reset ml-2">
                                Reset <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered table-hover mb-0 neci-themed-table" id="neci-report-table">
                        <div wire:loading.flex class="col-12 position-absolute justify-content-center align-items-center" style="top:0;right:0;left:0;bottom:0;background-color: rgba(255,255,255,0.5);z-index: 99;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>Supplier</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Payment Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($purchase_returns as $purchase_return)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($purchase_return->date)->format('d M, Y') }}</td>
                                <td>{{ $purchase_return->reference }}</td>
                                <td>{{ $purchase_return->supplier_name }}</td>
                                <td>
                                    @if ($purchase_return->status == 'Pending')
                                        <span class="badge badge-info">
                                            {{ $purchase_return->status }}
                                        </span>
                                            @elseif ($purchase_return->status == 'Shipped')
                                                <span class="badge badge-primary">
                                            {{ $purchase_return->status }}
                                        </span>
                                            @else
                                                <span class="badge badge-success">
                                            {{ $purchase_return->status }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{ format_currency($purchase_return->total_amount) }}</td>
                                <td>{{ format_currency($purchase_return->paid_amount) }}</td>
                                <td>{{ format_currency($purchase_return->due_amount) }}</td>
                                <td>
                                    @if ($purchase_return->payment_status == 'Partial')
                                        <span class="badge badge-warning">
                                    {{ $purchase_return->payment_status }}
                                </span>
                                    @elseif ($purchase_return->payment_status == 'Paid')
                                        <span class="badge badge-success">
                                    {{ $purchase_return->payment_status }}
                                </span>
                                    @else
                                        <span class="badge badge-danger">
                                    {{ $purchase_return->payment_status }}
                                </span>
                                    @endif

                                </td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        function initDT() {
            if ($.fn.DataTable.isDataTable('#neci-report-table')) {
                $('#neci-report-table').DataTable().destroy();
            }
            if ($('#neci-report-table').length) {
                $('#neci-report-table').DataTable({
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
        }
        initDT();
        Livewire.hook('commit', ({ succeed }) => {
            succeed(() => {
                setTimeout(initDT, 50);
            });
        });
    });
</script>
@endpush
