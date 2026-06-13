<div class="neci-table-actions">
    @can('show_quotations')
        @include('includes.neci-action-btn', [
            'href' => route('quotations.show', $data->id),
            'icon' => 'bi-eye',
            'tone' => 'info',
            'title' => 'Details',
        ])
    @endcan
    @can('edit_quotations')
        @include('includes.neci-action-btn', [
            'href' => route('quotations.edit', $data->id),
            'icon' => 'bi-pencil',
            'tone' => 'warning',
            'title' => 'Edit',
        ])
    @endcan
    @can('delete_quotations')
        @include('includes.neci-delete-action', [
            'formId' => 'destroy' . $data->id,
            'action' => route('quotations.destroy', $data->id),
        ])
    @endcan
    @if(auth()->user()->can('create_quotation_sales') || auth()->user()->can('send_quotation_mails'))
        <div class="btn-group dropleft neci-action-more">
            <button type="button" class="neci-action-btn neci-action-btn--neutral dropdown-toggle" data-toggle="dropdown" title="More actions" aria-expanded="false">
                <i class="bi bi-three-dots-vertical" aria-hidden="true"></i>
                <span class="sr-only">More actions</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                @can('create_quotation_sales')
                    <a href="{{ route('quotation-sales.create', $data) }}" class="dropdown-item">
                        <i class="bi bi-check2-circle mr-2 text-success"></i> Make Sale
                    </a>
                @endcan
                @can('send_quotation_mails')
                    <a href="{{ route('quotation.email', $data) }}" class="dropdown-item">
                        <i class="bi bi-envelope mr-2 text-warning"></i> Send On Email
                    </a>
                @endcan
            </div>
        </div>
    @endif
</div>
