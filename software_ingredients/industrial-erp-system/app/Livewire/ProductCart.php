<?php

namespace App\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Gloudemans\Shoppingcart\CartItem;
use Livewire\Component;
use Modules\Product\Entities\Product;

class ProductCart extends Component
{

    public $listeners = ['productSelected', 'discountModalRefresh'];

    public $cart_instance;
    public $quantity = [];
    public $check_quantity = [];
    public $discount_type = [];
    public $item_discount = [];
    public $unit_price = [];
    public $data;

    public $global_discount = 0;

    public $global_tax = 0;

    public $shipping = 0.00;

    public $focus_product_id = null;

    public $focus_row_id = null;

    public $focus_product_name = null;

    private $product;

    public function mount($cartInstance, $data = null) {
        $this->cart_instance = $cartInstance;

        if ($data) {
            $this->data = $data;

            $this->global_discount = $data->discount_percentage;
            $this->global_tax = $data->tax_percentage;
            $this->shipping = $data->shipping_amount;
            $this->syncCartGlobals();

            $cart_items = Cart::instance($this->cart_instance)->content();

            foreach ($cart_items as $cart_item) {
                $this->check_quantity[$cart_item->id] = (float) ($cart_item->options->stock ?? 999999);
                $this->quantity[$cart_item->id] = $cart_item->qty;
                $this->unit_price[$cart_item->id] = $cart_item->price;
                $this->discount_type[$cart_item->id] = $cart_item->options->product_discount_type;
                if ($cart_item->options->product_discount_type == 'fixed') {
                    $this->item_discount[$cart_item->id] = $cart_item->options->product_discount;
                } elseif ($cart_item->options->product_discount_type == 'percentage') {
                    $this->item_discount[$cart_item->id] = round(100 * ($cart_item->options->product_discount / $cart_item->price));
                }
            }

            $last_item = $cart_items->last();

            if ($last_item) {
                $this->setFocusLine($last_item->rowId, $last_item->id, $last_item->name);
            }
        } else {
            $this->check_quantity = [];
            $this->quantity = [];
            $this->unit_price = [];
            $this->discount_type = [];
            $this->item_discount = [];
        }
    }

    public function updatedGlobalTax(): void
    {
        $this->syncCartGlobals();
    }

    public function updatedGlobalDiscount(): void
    {
        $this->syncCartGlobals();
    }

    protected function syncCartGlobals(): void
    {
        Cart::instance($this->cart_instance)->setGlobalTax((int) $this->global_tax);
        Cart::instance($this->cart_instance)->setGlobalDiscount((int) $this->global_discount);
    }

    public function render() {
        $cart = Cart::instance($this->cart_instance);
        $cart_items = $cart->content();
        $total_with_shipping = $cart->total() + (float) $this->shipping;

        return view('livewire.product-cart', [
            'cart_items' => $cart_items,
            'cart_tax' => $cart->tax(),
            'cart_discount' => $cart->discount(),
            'total_with_shipping' => $total_with_shipping,
        ]);
    }

    public function productSelected($product) {
        $cart = Cart::instance($this->cart_instance);

        $exists = $cart->search(function ($cartItem, $rowId) use ($product) {
            return $cartItem->id == $product['id'];
        });

        if ($exists->isNotEmpty()) {
            session()->flash('message', 'Product exists in the cart!');

            return;
        }

        $this->product = $product;

        $cart->add([
            'id'      => $product['id'],
            'name'    => $product['product_name'],
            'qty'     => 1,
            'price'   => $this->calculate($product)['price'],
            'weight'  => 1,
            'options' => [
                'product_discount'      => 0.00,
                'product_discount_type' => 'fixed',
                'sub_total'             => $this->calculate($product)['sub_total'],
                'code'                  => $product['product_code'],
                'stock'                 => $product['product_quantity'],
                'unit'                  => $product['product_unit'],
                'product_tax'           => $this->calculate($product)['product_tax'],
                'unit_price'            => $this->calculate($product)['unit_price']
            ]
        ]);

        $this->check_quantity[$product['id']] = (float) ($product['product_quantity'] ?? 999999);
        $this->quantity[$product['id']] = 1;
        $this->unit_price[$product['id']] = $this->calculate($product)['unit_price'];
        $this->discount_type[$product['id']] = 'fixed';
        $this->item_discount[$product['id']] = 0;

        foreach (Cart::instance($this->cart_instance)->content() as $cart_item) {
            if ((int) $cart_item->id === (int) $product['id']) {
                $this->setFocusLine($cart_item->rowId, $cart_item->id, $cart_item->name);
                break;
            }
        }

        $this->dispatch('neci-cart-changed');
        $this->dispatch('neci-product-added');
    }

    public function applyFocusLine(): void
    {
        $rowId = $this->resolveFocusRowId();

        if (!$rowId || !$this->focus_product_id) {
            return;
        }

        $this->updateQuantity($rowId, $this->focus_product_id);

        $rowId = $this->resolveFocusRowId();

        if ($rowId) {
            $this->updatePrice($rowId, $this->focus_product_id);
        }

        $this->dispatch('neci-cart-changed');
    }

    protected function setFocusLine(string $rowId, $productId, string $name): void
    {
        $this->focus_row_id = $rowId;
        $this->focus_product_id = $productId;
        $this->focus_product_name = $name;
    }

    protected function resolveFocusRowId(): ?string
    {
        $cart = Cart::instance($this->cart_instance);

        if ($this->focus_row_id && $cart->content()->has($this->focus_row_id)) {
            return $this->focus_row_id;
        }

        if (!$this->focus_product_id) {
            return null;
        }

        foreach ($cart->content() as $cart_item) {
            if ((int) $cart_item->id === (int) $this->focus_product_id) {
                $this->focus_row_id = $cart_item->rowId;

                return $cart_item->rowId;
            }
        }

        return null;
    }

    protected function syncFocusRowId(CartItem $cartItem): void
    {
        if ($this->focus_product_id && (int) $cartItem->id === (int) $this->focus_product_id) {
            $this->focus_row_id = $cartItem->rowId;
        }
    }

    protected function cartUpdate(string $rowId, mixed $payload): CartItem
    {
        $cartItem = Cart::instance($this->cart_instance)->update($rowId, $payload);
        $this->syncFocusRowId($cartItem);

        return $cartItem;
    }

    public function removeItem($row_id) {
        if ($this->focus_row_id === $row_id) {
            $this->focus_row_id = null;
            $this->focus_product_id = null;
            $this->focus_product_name = null;
        }

        Cart::instance($this->cart_instance)->remove($row_id);
        $this->dispatch('neci-cart-changed');
    }

    public function updateQuantity($row_id, $product_id): bool
    {
        if (!$this->cartHasRow($row_id)) {
            $row_id = $this->resolveFocusRowId();
        }

        if (!$row_id) {
            return false;
        }

        // Guard: ensure check_quantity is set and numeric before comparing
        $available = isset($this->check_quantity[$product_id]) ? (float) $this->check_quantity[$product_id] : null;
        $requested = isset($this->quantity[$product_id]) ? (float) $this->quantity[$product_id] : null;

        if ($available === null || $requested === null) {
            return false;
        }

        if ($this->cart_instance == 'sale' || $this->cart_instance == 'purchase_return') {
            $cart_item = Cart::instance($this->cart_instance)->get($row_id);
            $current_qty = $cart_item ? (float) $cart_item->qty : 0;
            
            // Only strictly check stock if they are requesting MORE than what's currently in the cart
            if ($requested > $current_qty && $available < $requested) {
                session()->flash('message', 'Only ' . $available . ' available in stock!');

                // Revert the local quantity state back to the actual cart's quantity
                if ($this->cartHasRow($row_id)) {
                    $this->quantity[$product_id] = $current_qty;
                }

                return false;
            }
        }

        $cart_item = $this->cartUpdate($row_id, $this->quantity[$product_id]);

        $this->cartUpdate($cart_item->rowId, [
            'options' => [
                'sub_total'             => $cart_item->price * $cart_item->qty,
                'code'                  => $cart_item->options->code,
                'stock'                 => $cart_item->options->stock,
                'unit'                  => $cart_item->options->unit,
                'product_tax'           => $cart_item->options->product_tax,
                'unit_price'            => $cart_item->options->unit_price,
                'product_discount'      => $cart_item->options->product_discount,
                'product_discount_type' => $cart_item->options->product_discount_type,
            ],
        ]);

        $this->dispatch('neci-cart-changed');

        return true;
    }

    protected function cartHasRow(string $rowId): bool
    {
        return Cart::instance($this->cart_instance)->content()->has($rowId);
    }

    public function updatedDiscountType($value, $name) {
        $this->item_discount[$name] = 0;
    }

    public function discountModalRefresh($product_id, $row_id) {
        $this->updateQuantity($row_id, $product_id);
    }

    public function setProductDiscount($row_id, $product_id) {
        if (!$this->cartHasRow($row_id)) {
            return;
        }

        $cart_item = Cart::instance($this->cart_instance)->get($row_id);

        if ($this->discount_type[$product_id] == 'fixed') {
            $cart_item = $this->cartUpdate($row_id, [
                'price' => ($cart_item->price + $cart_item->options->product_discount) - $this->item_discount[$product_id],
            ]);

            $discount_amount = $this->item_discount[$product_id];

            $this->updateCartOptions($cart_item->rowId, $product_id, $cart_item, $discount_amount);
        } elseif ($this->discount_type[$product_id] == 'percentage') {
            $discount_amount = ($cart_item->price + $cart_item->options->product_discount) * ($this->item_discount[$product_id] / 100);

            $cart_item = $this->cartUpdate($row_id, [
                'price' => ($cart_item->price + $cart_item->options->product_discount) - $discount_amount,
            ]);

            $this->updateCartOptions($cart_item->rowId, $product_id, $cart_item, $discount_amount);
        }

        session()->flash('discount_message' . $product_id, 'Discount added to the product!');
        $this->dispatch('neci-cart-changed');
    }

    public function updatePrice($row_id, $product_id) {
        if (!$this->cartHasRow($row_id)) {
            $row_id = $this->resolveFocusRowId();
        }

        if (!$row_id) {
            return;
        }

        $product = Product::findOrFail($product_id);

        $cart_item = Cart::instance($this->cart_instance)->get($row_id);

        // Read the typed unit price; fall back to what's stored in cart options
        $unitPrice = $this->unit_price[$product_id]
            ?? $this->unit_price[$product->id]
            ?? $cart_item->options->unit_price;

        // Ensure unit_price state is kept in sync so stock check in updateQuantity is not confused
        $this->unit_price[$product_id] = $unitPrice;

        $calculated = $this->calculate($product->toArray(), (float) $unitPrice);

        $cart_item = $this->cartUpdate($row_id, ['price' => $calculated['price']]);

        $this->cartUpdate($cart_item->rowId, [
            'options' => [
                'sub_total'             => $calculated['sub_total'] * $cart_item->qty,
                'code'                  => $cart_item->options->code,
                'stock'                 => $cart_item->options->stock,
                'unit'                  => $cart_item->options->unit,
                'product_tax'           => $calculated['product_tax'],
                'unit_price'            => $calculated['unit_price'],
                'product_discount'      => $cart_item->options->product_discount,
                'product_discount_type' => $cart_item->options->product_discount_type,
            ],
        ]);

        $this->dispatch('neci-cart-changed');
    }

    public function calculate($product, $new_price = null) {
        if ($new_price) {
            $product_price = $new_price;
        } else {
            $this->unit_price[$product['id']] = $product['product_price'];
            if ($this->cart_instance == 'purchase' || $this->cart_instance == 'purchase_return') {
                $this->unit_price[$product['id']] = $product['product_cost'];
            }
            $product_price = $this->unit_price[$product['id']];
        }
        $price = 0;
        $unit_price = 0;
        $product_tax = 0;
        $sub_total = 0;

        if ($product['product_tax_type'] == 1) {
            $price = $product_price + ($product_price * ($product['product_order_tax'] / 100));
            $unit_price = $product_price;
            $product_tax = $product_price * ($product['product_order_tax'] / 100);
            $sub_total = $product_price + ($product_price * ($product['product_order_tax'] / 100));
        } elseif ($product['product_tax_type'] == 2) {
            $price = $product_price;
            $unit_price = $product_price - ($product_price * ($product['product_order_tax'] / 100));
            $product_tax = $product_price * ($product['product_order_tax'] / 100);
            $sub_total = $product_price;
        } else {
            $price = $product_price;
            $unit_price = $product_price;
            $product_tax = 0.00;
            $sub_total = $product_price;
        }

        return ['price' => $price, 'unit_price' => $unit_price, 'product_tax' => $product_tax, 'sub_total' => $sub_total];
    }

    public function updateCartOptions($row_id, $product_id, $cart_item, $discount_amount) {
        $this->cartUpdate($row_id, ['options' => [
            'sub_total'             => $cart_item->price * $cart_item->qty,
            'code'                  => $cart_item->options->code,
            'stock'                 => $cart_item->options->stock,
            'unit'                  => $cart_item->options->unit,
            'product_tax'           => $cart_item->options->product_tax,
            'unit_price'            => $cart_item->options->unit_price,
            'product_discount'      => $discount_amount,
            'product_discount_type' => $this->discount_type[$product_id],
        ]]);
    }
}
