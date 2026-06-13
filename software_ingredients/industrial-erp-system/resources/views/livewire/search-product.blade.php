<div class="neci-product-search position-relative">
    <div class="neci-product-search__bar">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>
            </div>
            <input
                wire:keydown.escape="resetQuery"
                wire:model.live.debounce.500ms="query"
                type="text"
                class="form-control"
                placeholder="Type product name or code..."
                autocomplete="off"
            >
        </div>
    </div>

    @if(!empty($query))
        <div wire:click="resetQuery" class="neci-product-search__backdrop" aria-hidden="true"></div>
    @endif

    <div wire:loading class="neci-product-search__dropdown neci-product-search__dropdown--loading">
        <div class="d-flex justify-content-center align-items-center py-3">
            <div class="spinner-border text-primary spinner-border-sm mr-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <span class="text-muted">Searching products...</span>
        </div>
    </div>

    @if(!empty($query))
        @if($search_results->isNotEmpty())
            <div class="neci-product-search__dropdown neci-product-search__dropdown--results">
                <div class="list-group list-group-flush">
                    @foreach($search_results as $result)
                        <button
                            type="button"
                            wire:click.prevent="selectProduct({{ $result }})"
                            class="list-group-item list-group-item-action neci-product-search__item"
                        >
                            <div class="neci-product-search__thumb">
                                <img src="{{ $result->getFirstMediaUrl('images') }}" alt="{{ $result->product_name }}">
                            </div>
                            <div class="text-left">
                                <h6 class="mb-0 font-weight-bold">{{ $result->product_name }}</h6>
                                <small class="text-muted"><i class="bi bi-upc-scan mr-1"></i> {{ $result->product_code }}</small>
                            </div>
                        </button>
                    @endforeach
                    @if($search_results->count() >= $how_many)
                        <div class="list-group-item text-center neci-product-search__load-more">
                            <button type="button" wire:click.prevent="loadMore" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                Load More <i class="bi bi-chevron-down ml-1"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="neci-product-search__dropdown neci-product-search__dropdown--empty">
                <div class="text-center py-4">
                    <div class="text-warning mb-2" style="font-size: 2rem;">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <h6 class="text-muted mb-0">No Products Found</h6>
                </div>
            </div>
        @endif
    @endif
</div>
