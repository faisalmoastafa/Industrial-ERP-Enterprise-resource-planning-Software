@php
    $variant = $variant ?? 'inline';
    $label = $label ?? 'Delete';
    $formId = $formId ?? ('destroy' . ($id ?? ''));
@endphp

@if ($variant === 'dropdown')
    <button type="button" class="dropdown-item text-danger" data-neci-confirm data-neci-confirm-type="danger" data-neci-confirm-form="{{ $formId }}">
        <i class="bi bi-trash mr-2" style="line-height: 1;"></i> {{ $label }}
    </button>
@else
    <button type="button" class="neci-action-btn neci-action-btn--danger" title="{{ $label }}" data-neci-confirm data-neci-confirm-type="danger" data-neci-confirm-form="{{ $formId }}">
        <i class="bi bi-trash" aria-hidden="true"></i>
        <span class="sr-only">{{ $label }}</span>
    </button>
@endif
<form id="{{ $formId }}" class="d-none" action="{{ $action }}" method="POST">
    @csrf
    @method('delete')
</form>
