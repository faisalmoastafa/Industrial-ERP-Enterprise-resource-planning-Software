<div class="neci-table-actions">
    @can('access_bonuses')
        @include('includes.neci-action-btn', [
            'href' => route('bonuses.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
    @endcan
    @can('access_bonuses')
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('bonuses.destroy', $data->id),
        ])
    @endcan
</div>
