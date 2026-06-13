<div class="neci-table-actions">
    @can('access_employees')
        @include('includes.neci-action-btn', [
            'href' => route('employees.ledger', $data->id),
            'icon' => 'bi-eye',
            'tone' => 'info',
            'title' => 'View',
        ])
    @endcan
    @can('edit_employees')
        @include('includes.neci-action-btn', [
            'href' => route('employees.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
    @endcan
    @can('delete_employees')
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('employees.destroy', $data->id),
        ])
    @endcan
</div>
