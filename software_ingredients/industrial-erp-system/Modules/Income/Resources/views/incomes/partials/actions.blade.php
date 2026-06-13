<div class="neci-table-actions">
    @can('access_incomes')
        @include('includes.neci-action-btn', [
            'href' => route('incomes.show', $data->id),
            'icon' => 'bi-eye',
            'tone' => 'info',
            'title' => 'View',
        ])
    @endcan
    @can('edit_incomes')
        @include('includes.neci-action-btn', [
            'href' => route('incomes.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
    @endcan
    @can('delete_incomes')
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('incomes.destroy', $data->id),
        ])
    @endcan
</div>
