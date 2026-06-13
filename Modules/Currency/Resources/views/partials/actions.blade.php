<div class="neci-table-actions">
    @can('edit_currencies')
        @include('includes.neci-action-btn', [
            'href' => route('currencies.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
    @endcan
    @can('delete_currencies')
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('currencies.destroy', $data->id),
        ])
    @endcan
</div>
