@php
    $tone = $tone ?? 'info';
    $icon = $icon ?? 'bi-pencil';
    $title = $title ?? '';
    $target = $target ?? null;
@endphp
<a
    href="{{ $href }}"
    class="neci-action-btn neci-action-btn--{{ $tone }}"
    title="{{ $title }}"
    @if ($target) target="{{ $target }}" @endif
>
    <i class="bi {{ $icon }}" aria-hidden="true"></i>
    <span class="sr-only">{{ $title }}</span>
</a>
