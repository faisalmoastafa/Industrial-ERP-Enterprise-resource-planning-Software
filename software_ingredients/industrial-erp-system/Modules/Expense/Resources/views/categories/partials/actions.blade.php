<div class="neci-table-actions">
    @can('edit_expense_categories')
        @include('includes.neci-action-btn', [
            'href' => route('expense-categories.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
    @endcan
    @can('delete_expense_categories')
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('expense-categories.destroy', $data->id),
        ])
    @endcan
</div>
