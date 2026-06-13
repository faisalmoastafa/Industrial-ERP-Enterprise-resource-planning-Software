<div class="neci-table-actions">
    @can('access_expenses')
        @include('includes.neci-action-btn', [
            'href' => route('expenses.show', $data->id),
            'icon' => 'bi-eye',
            'tone' => 'info',
            'title' => 'View',
        ])
    @endcan
    @can('edit_expenses')
        @include('includes.neci-action-btn', [
            'href' => route('expenses.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
    @endcan
    @can('delete_expenses')
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('expenses.destroy', $data->id),
        ])
    @endcan
</div>
