@extends('layouts.app')

@section('title', 'Edit Payment')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchase-returns.index') }}">Purchase Returns</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchase-returns.show', $purchase_return) }}">{{ $purchase_return->reference }}</a></li>
        <li class="breadcrumb-item active">Edit Payment</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <form id="payment-form" action="{{ route('purchase-return-payments.update', $purchaseReturnPayment) }}" method="POST">
            @csrf
            @method('patch')
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="reference">Reference <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="reference" required readonly value="{{ $purchaseReturnPayment->reference }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="date">Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="date" required value="{{ $purchaseReturnPayment->getAttributes()['date'] }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="due_amount">Due Amount <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="due_amount" required value="{{ format_currency($purchase_return->due_amount) }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="amount">Amount <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input id="amount" type="text" class="form-control" name="amount" required value="{{ old('amount') ?? $purchaseReturnPayment->amount }}" data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}" data-money-mask-on-load="true">
                                            <div class="input-group-append">
                                                <button id="getTotalAmount" class="btn btn-primary" type="button" data-money-fill-target="#amount" data-money-fill-value="{{ $purchase_return->due_amount }}">
                                                    <i class="bi bi-check-square"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="from-group">
                                        <div class="form-group">
                                            <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                                            <select class="form-control" name="payment_method" id="payment_method" required>
                                                <option {{ $purchaseReturnPayment->payment_method == 'Cash' ? 'selected' : '' }} value="Cash">Cash</option>
                                                <option {{ $purchaseReturnPayment->payment_method == 'Credit Card' ? 'selected' : '' }} value="Credit Card">Credit Card</option>
                                                <option {{ $purchaseReturnPayment->payment_method == 'Bank Transfer' ? 'selected' : '' }} value="Bank Transfer">Bank Transfer</option>
                                                <option {{ $purchaseReturnPayment->payment_method == 'Cheque' ? 'selected' : '' }} value="Cheque">Cheque</option>
                                                <option {{ $purchaseReturnPayment->payment_method == 'Other' ? 'selected' : '' }} value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="note">Note</label>
                                <textarea class="form-control" rows="4" name="note">{{ old('note') ?? $purchaseReturnPayment->note }}</textarea>
                            </div>

                            <input type="hidden" value="{{ $purchase_return->id }}" name="purchase_return_id">
                            <div class="form-group d-flex justify-content-start mt-3 mb-0">
                                <button type="submit" class="btn btn-primary">Update Payment <i class="bi bi-check"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

