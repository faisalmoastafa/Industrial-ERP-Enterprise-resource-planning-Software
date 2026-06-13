<div class="neci-table-actions">
    @can('access_sale_payments')
        @include('includes.neci-action-btn', [
            'href' => route('sale-payments.edit', [$data->sale->id, $data->id]),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('sale-payments.destroy', $data->id),
        ])
    @endcan
</div>
