<div class="neci-table-actions">
    @can('show_adjustments')
        @include('includes.neci-action-btn', [
            'href' => route('adjustments.show', $data->id),
            'icon' => 'bi-eye',
            'tone' => 'info',
            'title' => 'Details',
        ])
    @endcan
    @can('edit_adjustments')
        @include('includes.neci-action-btn', [
            'href' => route('adjustments.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
    @endcan
    @can('delete_adjustments')
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('adjustments.destroy', $data->id),
        ])
    @endcan
</div>
