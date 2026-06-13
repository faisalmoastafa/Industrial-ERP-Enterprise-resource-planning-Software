@extends('layouts.app')

@section('title', 'Backup')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item">System Utilities</li>
        <li class="breadcrumb-item active">Backup</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-cloud-arrow-down',
            'title' => 'Backup',
            'subtitle' => 'Create a backup archive with database, settings, and system files'
        ])

        @include('utils.alerts')

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('backup.store') }}" method="POST">
                    @csrf
                    <p class="text-muted mb-3">Use this page before major updates, relaunch work, or data changes.</p>
                    <div class="neci-page-actions">
                        <button type="submit" class="btn btn-primary">
                            Create Backup <i class="bi bi-cloud-arrow-down"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
