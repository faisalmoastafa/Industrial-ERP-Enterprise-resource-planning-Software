<div class="neci-table-actions">
    @can('edit_income_categories')
        @include('includes.neci-action-btn', [
            'href' => route('income-categories.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
    @endcan
    @can('delete_income_categories')
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('income-categories.destroy', $data->id),
        ])
    @endcan
</div>
