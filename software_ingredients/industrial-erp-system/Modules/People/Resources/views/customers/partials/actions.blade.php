<div class="neci-table-actions">
    @can('show_customers')
        @include('includes.neci-action-btn', [
            'href' => route('customers.show', $data->id),
            'icon' => 'bi-eye',
            'tone' => 'info',
            'title' => 'Details',
        ])
    @endcan
    @can('edit_customers')
        @include('includes.neci-action-btn', [
            'href' => route('customers.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
    @endcan
    @can('delete_customers')
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('customers.destroy', $data->id),
        ])
    @endcan
</div>
