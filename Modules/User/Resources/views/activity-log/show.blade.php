@extends('layouts.app')

@section('title', 'Activity Details')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('activity-log.index') }}">Activity Log</a></li>
        <li class="breadcrumb-item active">Details</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                            <div>
                                <h4 class="mb-1">{{ $logEntry['message'] }}</h4>
                                <p class="text-muted mb-0">{{ $logEntry['timestamp'] }}</p>
                            </div>
                            <a class="btn btn-outline-primary mt-3 mt-md-0" href="{{ route('activity-log.index') }}">
                                Back
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <div class="border rounded p-3 h-100">
                                    <h6 class="text-muted text-uppercase">Account</h6>
                                    <div class="font-weight-bold">{{ $logEntry['actor_name'] }}</div>
                                    @if($logEntry['actor_email'])
                                        <div class="text-muted">{{ $logEntry['actor_email'] }}</div>
                                    @endif
                                    <hr>
                                    <h6 class="text-muted text-uppercase">Role</h6>
                                    <div>{{ $logEntry['actor_roles'] }}</div>
                                    <hr>
                                    <h6 class="text-muted text-uppercase">Level</h6>
                                    <span class="badge badge-{{ $logEntry['level'] === 'ERROR' ? 'danger' : ($logEntry['level'] === 'WARNING' ? 'warning' : 'info') }}">
                                        {{ $logEntry['level'] }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-lg-8 mb-3">
                                <div class="border rounded p-3 h-100">
                                    <h6 class="text-muted text-uppercase">Full Details</h6>
                                    @php($details = collect($logEntry['context'])->except(['user_id']))
                                    @if($details->isNotEmpty())
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered mb-0">
                                                <tbody>
                                                    @foreach($details as $key => $value)
                                                        <tr>
                                                            <th class="text-capitalize" style="width: 220px;">
                                                                {{ str_replace('_', ' ', $key) }}
                                                            </th>
                                                            <td>
                                                                @if(is_array($value))
                                                                    <pre class="mb-0 small">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                                @else
                                                                    {{ $value }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">No extra details were stored for this activity.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
