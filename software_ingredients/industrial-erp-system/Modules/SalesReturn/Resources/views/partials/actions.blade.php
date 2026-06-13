<div class="neci-table-actions">
    @can('show_sales')
        @include('includes.neci-action-btn', [
            'href' => route('sale-returns.show', $data->id),
            'icon' => 'bi-eye',
            'tone' => 'info',
            'title' => 'Details',
        ])
    @endcan
    @can('edit_sales')
        @include('includes.neci-action-btn', [
            'href' => route('sale-returns.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
    @endcan
    @can('delete_sales')
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('sale-returns.destroy', $data->id),
        ])
    @endcan
    @can('access_sale_payments')
        <div class="btn-group dropleft neci-action-more">
            <button type="button" class="neci-action-btn neci-action-btn--neutral dropdown-toggle" data-toggle="dropdown" title="More actions" aria-expanded="false">
                <i class="bi bi-three-dots-vertical" aria-hidden="true"></i>
                <span class="sr-only">More actions</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('sale-return-payments.index', $data->id) }}" class="dropdown-item">
                    <i class="bi bi-cash-coin mr-2 text-warning"></i> Show Payments
                </a>
                @if($data->due_amount > 0)
                    <a href="{{ route('sale-return-payments.create', $data->id) }}" class="dropdown-item">
                        <i class="bi bi-plus-circle-dotted mr-2 text-success"></i> Add Payment
                    </a>
                @endif
            </div>
        </div>
    @endcan
</div>
