<div class="neci-tx-product-cart">
    @if (session()->has('message'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="alert-body">
                <span>{{ session('message') }}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
    @endif

    @include('livewire.includes.product-cart-line-editor')

    @include('livewire.includes.product-cart-order-form')

    @include('livewire.includes.product-cart-showcase-table')

    @include('livewire.includes.product-cart-order-summary')
</div>
