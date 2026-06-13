@extends('layouts.app')

@section('title', 'Edit User')

@section('third_party_stylesheets')
    <link href="{{ asset('vendor/filepond/filepond.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/filepond/filepond-plugin-image-preview.css') }}"
          rel="stylesheet">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        @include('includes.neci-page-header', [
            'icon' => 'bi-person-gear',
            'title' => 'Edit User',
            'subtitle' => 'Update account details and access roles'
        ])

        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="name" required value="{{ $user->name }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input class="form-control" type="email" name="email" required value="{{ $user->email }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="role">Role <span class="text-danger">*</span></label>
                                <select class="form-control" name="role" id="role" required>
                                    @foreach(\Spatie\Permission\Models\Role::where('name', '!=', 'Super Admin')->get() as $role)
                                        <option {{ $user->hasRole($role->name) ? 'selected' : '' }} value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="is_active">Status <span class="text-danger">*</span></label>
                                <select class="form-control" name="is_active" id="is_active" required>
                                    <option value="1" {{ $user->is_active == 1 ? 'selected' : ''}}>Active</option>
                                    <option value="2" {{ $user->is_active == 2 ? 'selected' : ''}}>Deactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="image">Profile Image <span class="text-danger">*</span></label>
                                <img style="width: 100px;height: 100px;" class="d-block mx-auto img-thumbnail img-fluid rounded-circle mb-2" src="{{ $user->getFirstMediaUrl('avatars') }}" alt="Profile Image">
                                <input id="image" type="file" name="image" data-max-file-size="500KB">
                            </div>
                            <div class="form-group d-flex justify-content-start mt-3 mb-0">
                                <button type="submit" class="btn btn-primary">Update User <i class="bi bi-check"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('third_party_scripts')
    <script src="{{ asset('vendor/filepond/filepond-plugin-image-preview.js') }}"></script>
    <script src="{{ asset('vendor/filepond/filepond-plugin-file-validate-size.js') }}"></script>
    <script src="{{ asset('vendor/filepond/filepond-plugin-file-validate-type.js') }}"></script>
    <script src="{{ asset('vendor/filepond/filepond.js') }}"></script>
@endsection

@push('page_scripts')
    <script>
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType
        );
        const fileElement = document.querySelector('input[id="image"]');
        const pond = FilePond.create(fileElement, {
            acceptedFileTypes: ['image/png', 'image/jpg', 'image/jpeg'],
        });
        FilePond.setOptions({
            server: {
                url: "{{ route('filepond.upload') }}",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            }
        });
    </script>
@endpush


