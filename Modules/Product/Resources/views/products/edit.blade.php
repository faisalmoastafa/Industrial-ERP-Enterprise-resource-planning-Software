@extends('layouts.app')

@section('title', 'Edit Product')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ $product->isRawMaterial() ? route('raw-materials.index') : route('products.index') }}">{{ $product->isRawMaterial() ? 'Raw Materials' : 'Products' }}</a></li>
        <li class="breadcrumb-item active">{{ $product->isRawMaterial() ? 'Edit Raw Material' : 'Edit Product' }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        @include('includes.neci-page-header', [
            'icon' => $product->isRawMaterial() ? 'bi-building' : 'bi-box-seam',
            'title' => $product->isRawMaterial() ? 'Edit Raw Material' : 'Edit Product',
            'subtitle' => $product->isRawMaterial() ? 'Update raw material stock, cost, and unit details' : 'Update product stock, price, and barcode details'
        ])

        <form id="product-form" action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <input type="hidden" name="product_type" value="{{ old('product_type', $product->product_type ?? \Modules\Product\Entities\Product::TYPE_FINISHED) }}">
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="product_name">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="product_name" required value="{{ $product->product_name }}">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="product_code">Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="product_code" required value="{{ $product->product_code }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category_id">Category <span class="text-danger">*</span></label>
                                        <select class="form-control" name="category_id" id="category_id" required>
                                            @foreach(\Modules\Product\Entities\Category::all() as $category)
                                                <option {{ $category->id == $product->category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="barcode_symbology">Barcode Symbology <span class="text-danger">*</span></label>
                                        <select class="form-control" name="product_barcode_symbology" id="barcode_symbology" required>
                                            <option {{ $product->product_barcode_symbology == 'C128' ? 'selected' : '' }} value="C128">Code 128</option>
                                            <option {{ $product->product_barcode_symbology == 'C39' ? 'selected' : '' }} value="C39">Code 39</option>
                                            <option {{ $product->product_barcode_symbology == 'UPCA' ? 'selected' : '' }} value="UPCA">UPC-A</option>
                                            <option {{ $product->product_barcode_symbology == 'UPCE' ? 'selected' : '' }} value="UPCE">UPC-E</option>
                                            <option {{ $product->product_barcode_symbology == 'EAN13' ? 'selected' : '' }} value="EAN13">EAN-13</option>
                                            <option {{ $product->product_barcode_symbology == 'EAN8' ? 'selected' : '' }} value="EAN8">EAN-8</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_cost">Cost <small class="text-muted">(Purchase Price)</small> <span class="text-danger">*</span></label>
                                        <input id="product_cost" type="text" class="form-control" min="0" name="product_cost" required value="{{ $product->product_cost }}" data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}" data-money-mask-on-load="true">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_price">Price <small class="text-muted">(Selling Price)</small> <span class="text-danger">*</span></label>
                                        <input id="product_price" type="text" class="form-control" min="0" name="product_price" required value="{{ $product->product_price }}" data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}" data-money-mask-on-load="true">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_quantity">Quantity <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="product_quantity" required value="{{ $product->product_quantity }}" min="0" step="any">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_stock_alert">Alert Quantity <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="product_stock_alert" required value="{{ $product->product_stock_alert }}" min="0" step="any">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="product_order_tax">Tax (%)</label>
                                        <input type="number" class="form-control" name="product_order_tax" value="{{ $product->product_order_tax }}" min="0" max="100">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="product_tax_type">Tax type</label>
                                        <select class="form-control" name="product_tax_type" id="product_tax_type">
                                            <option value="" selected>None</option>
                                            <option {{ $product->product_tax_type == 1 ? 'selected' : '' }}  value="1">Exclusive</option>
                                            <option {{ $product->product_tax_type == 2 ? 'selected' : '' }} value="2">Inclusive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="product_unit">Unit <i class="bi bi-question-circle-fill text-info" data-toggle="tooltip" data-placement="top" title="This short text will be placed after Product Quantity."></i> <span class="text-danger">*</span></label>
                                        <select class="form-control" name="product_unit" id="product_unit" required>
                                            <option value="" selected >Select Unit</option>
                                            @foreach(\Modules\Setting\Entities\Unit::all() as $unit)
                                                <option {{ $product->product_unit == $unit->short_name ? 'selected' : '' }} value="{{ $unit->short_name }}">{{ $unit->name . ' | ' . $unit->short_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="product_note">Note</label>
                                <textarea name="product_note" id="product_note" rows="4 " class="form-control">{{ $product->product_note }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="image">Product Images <i class="bi bi-question-circle-fill text-info" data-toggle="tooltip" data-placement="top" title="Max Files: 3, Max File Size: 1MB, Image Size: 400x400"></i></label>
                                <div class="dropzone d-flex flex-wrap align-items-center justify-content-center" id="document-dropzone">
                                    <div class="dz-message" data-dz-message>
                                        <i class="bi bi-cloud-arrow-up"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group d-flex justify-content-start mt-3 mb-0">
                                <button type="submit" class="btn btn-primary">Update Product <i class="bi bi-check"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('third_party_scripts')
    <script src="{{ asset('js/dropzone.js') }}"></script>
@endsection

@push('page_scripts')
    <script>
        var uploadedDocumentMap = {}
        Dropzone.options.documentDropzone = {
            url: '{{ route('dropzone.upload') }}',
            maxFilesize: 1,
            acceptedFiles: '.jpg, .jpeg, .png',
            maxFiles: 3,
            addRemoveLinks: true,
            dictRemoveFile: "<i class='bi bi-x-circle text-danger'></i> remove",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function (file, response) {
                $('form').append('<input type="hidden" name="document[]" value="' + response.name + '">');
                uploadedDocumentMap[file.name] = response.name;
            },
            removedfile: function (file) {
                file.previewElement.remove();
                var name = '';
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name;
                } else {
                    name = uploadedDocumentMap[file.name];
                }
                $('form').find('input[name="document[]"][value="' + name + '"]').remove();
            },
            init: function () {
                @if(isset($product) && $product->getMedia('images'))
                var files = {!! json_encode($product->getMedia('images')) !!};
                for (var i in files) {
                    var file = files[i];
                    this.options.addedfile.call(this, file);
                    this.options.thumbnail.call(this, file, file.original_url);
                    file.previewElement.classList.add('dz-complete');
                    $('form').append('<input type="hidden" name="document[]" value="' + file.file_name + '">');
                }
                @endif
            }
        }
    </script>
@endpush
