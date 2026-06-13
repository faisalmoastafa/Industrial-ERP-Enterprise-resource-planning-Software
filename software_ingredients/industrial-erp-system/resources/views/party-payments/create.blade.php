@extends('layouts.app')

@section('title', 'Create Payment Entry')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('party-payments.index') }}">Payments</a></li>
        <li class="breadcrumb-item active">Add</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-wallet2',
            'title' => 'Create Payment Entry',
            'subtitle' => 'Record prepay or pay-later transactions for customers and suppliers'
        ])

        <form action="{{ route('party-payments.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">@include('utils.alerts')</div>
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            @include('party-payments.form')
                            <div class="form-group d-flex justify-content-start mt-3 mb-0">
                                <button type="submit" class="btn btn-primary">Create Payment <i class="bi bi-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
