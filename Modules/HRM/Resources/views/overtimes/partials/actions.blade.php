<div class="neci-table-actions">
    @can('access_overtimes')
        @include('includes.neci-action-btn', [
            'href' => route('overtimes.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
    @endcan
    @can('access_overtimes')
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('overtimes.destroy', $data->id),
        ])
    @endcan
</div>
