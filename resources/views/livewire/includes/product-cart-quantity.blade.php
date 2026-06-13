<div class="input-group d-flex justify-content-center">
    <input wire:model="quantity.{{ $cart_item->id }}" data-neci-enter="quantity" style="min-width: 40px;max-width: 90px;" type="number" class="form-control" min="0.001" step="any">
    <div class="input-group-append">
        <button type="button" wire:click="updateQuantity('{{ $cart_item->rowId }}', {{ $cart_item->id }})" class="btn btn-info">
            <i class="bi bi-check"></i>
        </button>
    </div>
</div>
