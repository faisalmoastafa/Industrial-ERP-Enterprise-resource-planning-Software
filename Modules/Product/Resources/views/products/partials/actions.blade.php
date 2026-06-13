<div class="neci-table-actions">
    @can('show_products')
        @include('includes.neci-action-btn', [
            'href' => route('products.show', $data->id),
            'icon' => 'bi-eye',
            'tone' => 'info',
            'title' => 'Details',
        ])
    @endcan
    @can('edit_products')
        @include('includes.neci-action-btn', [
            'href' => route('products.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
    @endcan
    @can('delete_products')
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('products.destroy', $data->id),
        ])
    @endcan
</div>
