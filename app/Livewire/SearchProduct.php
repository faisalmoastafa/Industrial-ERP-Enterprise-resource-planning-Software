<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Modules\Product\Entities\Product;

class SearchProduct extends Component
{

    public $query;
    public $search_results;
    public $how_many;
    public $productType;

    public function mount($productType = null) {
        $this->query = '';
        $this->how_many = 5;
        $this->productType = $productType;
        $this->search_results = Collection::empty();
    }

    public function render() {
        return view('livewire.search-product');
    }

    public function updatedQuery() {
        $this->search_results = Product::query()
            ->when($this->productType === Product::TYPE_FINISHED, fn ($query) => $query->finished())
            ->when($this->productType === Product::TYPE_RAW_MATERIAL, fn ($query) => $query->rawMaterial())
            ->where(function ($query) {
                $query->where('product_name', 'like', '%' . $this->query . '%')
                    ->orWhere('product_code', 'like', '%' . $this->query . '%');
            })
            ->take($this->how_many)->get();
    }

    public function loadMore() {
        $this->how_many += 5;
        $this->updatedQuery();
    }

    public function resetQuery() {
        $this->query = '';
        $this->how_many = 5;
        $this->search_results = Collection::empty();
    }

    public function selectProduct($product) {
        $this->dispatch('productSelected', $product);
        $this->resetQuery();
        $this->dispatch('neci-product-added');
    }
}
