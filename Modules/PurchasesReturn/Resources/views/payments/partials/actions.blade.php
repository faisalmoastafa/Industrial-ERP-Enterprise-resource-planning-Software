<div class="neci-table-actions">
    @can('access_purchase_payments')
        @include('includes.neci-action-btn', [
            'href' => route('purchase-return-payments.edit', [$data->purchaseReturn->id, $data->id]),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('purchase-return-payments.destroy', $data->id),
        ])
    @endcan
</div>
