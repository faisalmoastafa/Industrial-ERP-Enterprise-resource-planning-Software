<div class="neci-table-actions">
    @can('access_purchase_payments')
        @include('includes.neci-action-btn', [
            'href' => route('purchase-payments.edit', [$data->purchase->id, $data->id]),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('purchase-payments.destroy', $data->id),
        ])
    @endcan
</div>
