@extends('errors.illustrated-layout')

@section('code', '500 🤕')

@section('title', __('Server Error'))

@section('image')
    <div style="background-image: url({{ asset('images/login-background.png') }});" class="absolute pin bg-no-repeat md:bg-left lg:bg-center bg-cover"></div>
@endsection

@section('message', __('Something went wrong. Call the dev!!!'))
