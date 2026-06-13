<section class="neci-tx-panel {{ $class ?? '' }}">
    @if (!empty($title))
        <div class="neci-tx-panel__head">
            <h2 class="neci-tx-panel__title">
                <i class="bi {{ $icon ?? 'bi-ui-checks-grid' }}"></i>
                {{ $title }}
            </h2>
        </div>
    @endif
    <div class="neci-tx-panel__body">
