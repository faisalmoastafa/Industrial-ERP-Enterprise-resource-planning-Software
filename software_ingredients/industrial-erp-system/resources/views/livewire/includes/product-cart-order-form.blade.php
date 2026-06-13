<div class="neci-tx-order-totals-form form-row neci-tx-products-enter">
    <div class="col-lg-4">
        <div class="form-group">
            <label for="tax_percentage">Tax (%)</label>
            <input wire:model.blur="global_tax" type="number" class="form-control" name="tax_percentage" id="tax_percentage" min="0" max="100" required>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label for="discount_percentage">Discount (%)</label>
            <input wire:model.blur="global_discount" type="number" class="form-control" name="discount_percentage" id="discount_percentage" min="0" max="100" required>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label for="shipping_amount">Shipping</label>
            <input wire:model.blur="shipping" type="number" class="form-control" name="shipping_amount" id="shipping_amount" min="0" step="0.01" required>
        </div>
    </div>
</div>
