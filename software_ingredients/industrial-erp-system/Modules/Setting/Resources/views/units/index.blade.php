@extends('layouts.app')

@section('title', 'Units')

@section('third_party_stylesheets')
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/datatables.min.css') }}">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">System Settings</a></li>
        <li class="breadcrumb-item active">Units</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        @include('includes.neci-page-header', [
                            'icon' => 'bi-calculator',
                            'title' => 'Units',
                            'subtitle' => 'Manage measurement units used by stock and products'
                        ])

                        <div class="neci-page-actions">
                            <a href="{{ route('units.create') }}" class="btn btn-primary">
                                Add Unit <i class="bi bi-plus"></i>
                            </a>
                        </div>

                        <hr>

                        <div class="table-responsive">
                            <table class="table table-bordered mb-0 text-center" id="data-table">
                                <thead>
                                <tr>
                                    <th class="align-middle">No.</th>
                                    <th class="align-middle">Name</th>
                                    <th class="align-middle">Short Name</th>
                                    <th class="align-middle">Operator</th>
                                    <th class="align-middle">Operation Value</th>
                                    <th class="align-middle">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($units as $key => $unit)
                                    <tr>
                                        <td class="align-middle">{{ $key + 1 }}</td>
                                        <td class="align-middle">{{ $unit->name }}</td>
                                        <td class="align-middle">{{ $unit->short_name }}</td>
                                        <td class="align-middle">{{ $unit->operator }}</td>
                                        <td class="align-middle">{{ $unit->operation_value }}</td>
                                        <td class="align-middle">
                                            <a href="{{ route('units.edit', $unit) }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @include('includes.neci-delete-action', [
                                                'formId' => 'destroy' . $unit->id,
                                                'action' => route('units.destroy', $unit),
                                                'variant' => 'inline',
                                            ])
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script type="text/javascript" src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
    <script>
        var table = $('#data-table').DataTable({
            dom: "<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4 justify-content-end'f>>tr<'row'<'col-md-5'i><'col-md-7 mt-2'p>>",
            "buttons": [
                {extend: 'excel',text: '<i class="bi bi-file-earmark-excel-fill"></i> Excel'},
                {extend: 'csv',text: '<i class="bi bi-file-earmark-excel-fill"></i> CSV'},
                {extend: 'print',
                    text: '<i class="bi bi-printer-fill"></i> Print',
                    title: "Units",
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    },
                    customize: function (win) {
                        $(win.document.body).find('h1').css('font-size', '15pt');
                        $(win.document.body).find('h1').css('text-align', 'center');
                        $(win.document.body).find('h1').css('margin-bottom', '20px');
                        $(win.document.body).css('margin', '35px 25px');
                    }
                },
            ],
            ordering: false,
        });
    </script>
@endpush
