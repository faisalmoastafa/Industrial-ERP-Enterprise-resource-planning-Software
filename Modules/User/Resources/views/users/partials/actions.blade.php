<div class="neci-table-actions">
    @include('includes.neci-action-btn', [
        'href' => route('users.edit', $data->id),
        'icon' => 'bi-pencil',
        'tone' => 'warning',
        'title' => 'Edit',
    ])
    @include('includes.neci-delete-action', [
        'formId' => 'destroy' . $data->id,
        'action' => route('users.destroy', $data->id),
    ])
</div>
