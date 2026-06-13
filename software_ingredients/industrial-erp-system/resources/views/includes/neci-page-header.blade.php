<header class="neci-page-heading">
    @isset($icon)
        <span class="neci-page-heading__icon">
            @isset($svg)
                {!! $svg !!}
            @else
                <i class="bi {{ $icon }}"></i>
            @endisset
        </span>
    @endisset
    <div>
        <h1 class="neci-page-heading__title">{{ $title }}</h1>
        @isset($subtitle)
            <p class="neci-page-heading__subtitle">{{ $subtitle }}</p>
        @endisset
    </div>
</header>
