@extends('errors.illustrated-layout')

@section('code', '419 👾')

@section('title', __('Page Expired'))

@section('image')
    <div style="background-image: url({{ asset('images/login-background.png') }});" class="absolute pin bg-no-repeat md:bg-left lg:bg-center bg-cover"></div>
@endsection

@section('message', __('Maybe, the CSRF token is missing.'))
