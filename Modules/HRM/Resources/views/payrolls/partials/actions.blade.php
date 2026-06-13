<div class="neci-table-actions">
    @can('access_payrolls')
        @include('includes.neci-action-btn', [
            'href' => route('payrolls.show', $data->id),
            'icon' => 'bi-eye',
            'tone' => 'info',
            'title' => 'View',
        ])
    @endcan
    @can('edit_payrolls')
        @include('includes.neci-action-btn', [
            'href' => route('payrolls.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
    @endcan
    @can('delete_payrolls')
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('payrolls.destroy', $data->id),
        ])
    @endcan
</div>
