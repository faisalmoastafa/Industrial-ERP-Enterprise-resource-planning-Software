<div class="neci-table-actions">
    @can('access_product_categories')
        @include('includes.neci-action-btn', [
            'href' => route('product-categories.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('product-categories.destroy', $data->id),
        ])
    @endcan
</div>
