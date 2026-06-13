<div class="neci-tx-order-totals-summary row justify-content-md-end mt-3">
    <div class="col-md-5">
        <table class="table table-sm table-striped neci-tx-order-totals__summary mb-0">
            <tbody>
                <tr>
                    <th>Tax ({{ $global_tax }}%)</th>
                    <td class="text-right">(+) {{ format_currency($cart_tax) }}</td>
                </tr>
                <tr>
                    <th>Discount ({{ $global_discount }}%)</th>
                    <td class="text-right">(-) {{ format_currency($cart_discount) }}</td>
                </tr>
                <tr>
                    <th>Shipping</th>
                    <td class="text-right">(+) {{ format_currency($shipping) }}</td>
                </tr>
                <tr>
                    <th>Grand Total</th>
                    <th class="text-right">(=) {{ format_currency($total_with_shipping) }}</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<input type="hidden" name="total_amount" value="{{ $total_with_shipping }}">
