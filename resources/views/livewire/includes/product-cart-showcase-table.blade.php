<div class="table-responsive position-relative neci-tx-cart-showcase">
    <div wire:loading.flex class="col-12 position-absolute justify-content-center align-items-center" style="top:0;right:0;left:0;bottom:0;background-color: rgba(255,255,255,0.5);z-index: 99;">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <table class="table table-bordered neci-tx-cart-showcase__table">
        <thead class="thead-dark">
        <tr>
            <th class="align-middle">Product</th>
            <th class="align-middle text-center">
                Unit Price
                <i class="bi bi-question-circle-fill text-info ml-1"
                   data-toggle="tooltip"
                   data-placement="top"
                   title="Purchase: cost you pay the supplier. Sale: price you charge the customer."></i>
            </th>
            <th class="align-middle text-center">Stock</th>
            <th class="align-middle text-center">Quantity</th>
            <th class="align-middle text-center">Discount</th>
            <th class="align-middle text-center">Tax</th>
            <th class="align-middle text-center">Sub Total</th>
            <th class="align-middle text-center">Action</th>
        </tr>
        </thead>
        <tbody>
        @if($cart_items->isNotEmpty())
            @foreach($cart_items as $cart_item)
                <tr wire:key="cart-row-{{ $cart_item->rowId }}">
                    <td class="align-middle">
                        {{ $cart_item->name }}<br>
                        <span class="badge badge-success">{{ $cart_item->options->code }}</span>
                    </td>
                    <td class="align-middle text-center">{{ format_currency($cart_item->price) }}</td>
                    <td class="align-middle text-center">
                        <span class="badge badge-info">{{ $cart_item->options->stock . ' ' . $cart_item->options->unit }}</span>
                    </td>
                    <td class="align-middle text-center">{{ $cart_item->qty }}</td>
                    <td class="align-middle text-center">{{ format_currency($cart_item->options->product_discount) }}</td>
                    <td class="align-middle text-center">{{ format_currency($cart_item->options->product_tax) }}</td>
                    <td class="align-middle text-center">{{ format_currency($cart_item->options->sub_total) }}</td>
                    <td class="align-middle text-center">
                        <button
                            type="button"
                            class="btn btn-link p-0 border-0"
                            title="Remove product"
                            wire:click.prevent="removeItem('{{ $cart_item->rowId }}')"
                        >
                            <i class="bi bi-x-circle font-2xl text-danger"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="8" class="text-center">
                    <span class="text-danger">Please search &amp; select products!</span>
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
