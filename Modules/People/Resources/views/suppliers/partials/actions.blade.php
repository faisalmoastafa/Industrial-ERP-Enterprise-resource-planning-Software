<div class="neci-table-actions">
    @can('show_suppliers')
        @include('includes.neci-action-btn', [
            'href' => route('suppliers.show', $data->id),
            'icon' => 'bi-eye',
            'tone' => 'info',
            'title' => 'Details',
        ])
    @endcan
    @can('edit_suppliers')
        @include('includes.neci-action-btn', [
            'href' => route('suppliers.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
    @endcan
    @can('delete_suppliers')
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('suppliers.destroy', $data->id),
        ])
    @endcan
</div>
