@if($focus_product_id && isset($quantity[$focus_product_id]))
    <div class="neci-tx-line-item mb-3" wire:key="line-editor-{{ $focus_product_id }}">
        <div class="neci-tx-line-item__head">
            <span class="neci-tx-line-item__label"><i class="bi bi-pencil-square"></i> Adjust last added product</span>
            <strong class="neci-tx-line-item__name">{{ $focus_product_name }}</strong>
        </div>
        <div class="form-row align-items-end">
            <div class="col-md-4">
                <div class="form-group mb-md-0">
                    <label for="neci-line-quantity">Quantity</label>
                    <input
                        id="neci-line-quantity"
                        wire:model="quantity.{{ $focus_product_id }}"
                        data-neci-line="quantity"
                        type="number"
                        class="form-control"
                        min="0.001"
                        step="any"
                    >
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-md-0">
                    <label for="neci-line-price">
                        Unit Price
                        <small class="text-muted">(per unit, excl. tax)</small>
                    </label>
                    <input
                        id="neci-line-price"
                        wire:model="unit_price.{{ $focus_product_id }}"
                        data-neci-line="price"
                        type="number"
                        class="form-control"
                        min="0"
                        step="any"
                    >
                </div>
            </div>
            <div class="col-md-4">
                <button
                    type="button"
                    class="btn btn-info btn-block"
                    data-neci-line="apply"
                    wire:click="applyFocusLine"
                >
                    <i class="bi bi-check-lg"></i> Apply to line
                </button>
            </div>
        </div>
    </div>
@endif
