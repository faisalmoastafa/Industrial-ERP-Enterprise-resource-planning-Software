@extends('layouts.app')

@section('title', 'Activity Log')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Activity Log</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @include('utils.alerts')
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                            <div>
                                <h4 class="mb-1">Activity Log</h4>
                                <p class="text-muted mb-0">Recent security and inventory activity from system audit logs.</p>
                            </div>
                            <span class="badge badge-primary mt-2 mt-md-0">{{ $entries->count() }} records</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 170px;">Time</th>
                                        <th style="width: 180px;">Account</th>
                                        <th style="width: 150px;">Role</th>
                                        <th style="width: 190px;">Activity</th>
                                        <th>Summary</th>
                                        <th style="width: 110px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($entries as $entry)
                                        <tr>
                                            <td>
                                                <span class="font-weight-bold">{{ $entry['timestamp'] }}</span>
                                                <div>
                                                    <span class="badge badge-{{ $entry['level'] === 'ERROR' ? 'danger' : ($entry['level'] === 'WARNING' ? 'warning' : 'info') }}">
                                                        {{ $entry['level'] }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold">{{ $entry['actor_name'] }}</div>
                                                @if($entry['actor_email'])
                                                    <small class="text-muted">{{ $entry['actor_email'] }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $entry['actor_roles'] }}</td>
                                            <td>{{ $entry['message'] }}</td>
                                            <td>{{ $entry['summary'] }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary" href="{{ route('activity-log.show', $entry['id']) }}" title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-5">
                                                No activity has been recorded yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
