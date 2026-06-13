<div class="neci-table-actions">
    @can('access_attendances')
        @include('includes.neci-action-btn', [
            'href' => route('attendances.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
    @endcan
    @can('access_attendances')
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('attendances.destroy', $data->id),
        ])
    @endcan
</div>
