<div class="neci-table-actions">
    @can('show_sales')
        @include('includes.neci-action-btn', [
            'href' => route('sales.show', $data->id),
            'icon' => 'bi-eye',
            'tone' => 'info',
            'title' => 'Details',
        ])
    @endcan
    @can('edit_sales')
        @include('includes.neci-action-btn', [
            'href' => route('sales.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
    @endcan
    @can('delete_sales')
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('sales.destroy', $data->id),
        ])
    @endcan
    @if(auth()->user()->can('access_sale_payments') || auth()->user()->can('show_sales'))
        <div class="btn-group dropleft neci-action-more">
            <button type="button" class="neci-action-btn neci-action-btn--neutral dropdown-toggle" data-toggle="dropdown" title="More actions" aria-expanded="false">
                <i class="bi bi-three-dots-vertical" aria-hidden="true"></i>
                <span class="sr-only">More actions</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a target="_blank" href="{{ route('sales.pos.pdf', $data->id) }}" class="dropdown-item">
                    <i class="bi bi-file-earmark-pdf mr-2 text-success"></i> POS Invoice
                </a>
                @can('access_sale_payments')
                    <a href="{{ route('sale-payments.index', $data->id) }}" class="dropdown-item">
                        <i class="bi bi-cash-coin mr-2 text-warning"></i> Show Payments
                    </a>
                    @if($data->due_amount > 0)
                        <a href="{{ route('sale-payments.create', $data->id) }}" class="dropdown-item">
                            <i class="bi bi-plus-circle-dotted mr-2 text-success"></i> Add Payment
                        </a>
                    @endif
                @endcan
            </div>
        </div>
    @endif
</div>
