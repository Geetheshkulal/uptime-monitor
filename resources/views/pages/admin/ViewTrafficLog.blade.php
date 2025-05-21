@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<style>
    .traffic-item {
        border: 1px solid rgba(0,0,0,0.1);
        transition: transform 0.2s;
        border-radius: 8px;
    }

    .traffic-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .text-truncate {
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-agent {
        font-size: 0.85em;
        word-break: break-word;
        white-space: pre-wrap;
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 4px;
        margin-top: 10px;
    }

    .user-agent code {
        background: none;
        color: #333;
        padding: 0;
        font-size: 13px;
    }

    .card-body .row + .row {
        margin-top: 10px;
    }
</style>
@endpush

<div class="container-fluid">
    <h4 class="mb-4">Visitor Traffic Logs</h4>

    <div class="row g-3">
        @foreach($trafficLogs as $log)
        <div class="col-12">
            <div class="card shadow-sm traffic-item mb-3">
                <div class="card-body">
                    <div class="row text-sm">
                        <div class="col-md-2">
                            <strong>ID:</strong> {{ $log->id }}
                        </div>
                        <div class="col-md-2">
                            <strong>IP:</strong> <span class="text-primary">{{ $log->ip }}</span>
                        </div>
                        <div class="col-md-2">
                            <strong>Browser:</strong> {{ $log->browser }}
                        </div>
                        <div class="col-md-2">
                            <strong>Platform:</strong> {{ $log->platform }}
                        </div>
                        <div class="col-md-2">
                            <strong>Method:</strong>
                            <span class="badge bg-primary">{{ $log->method }}</span>
                        </div>
                        <div class="col-md-2">
                            <strong>Time:</strong> {{ $log->created_at->format('Y-m-d H:i') }}
                        </div>
                    </div>

                    <div class="row mt-2 text-sm">
                        <div class="col-md-6">
                            <strong>URL:</strong>
                            <div class="text-truncate">{{ $log->url }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Referrer:</strong>
                            <div class="text-truncate">{{ $log->referrer ?? 'Direct access' }}</div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <strong>User Agent:</strong>
                            <div class="user-agent"><code>{{ $log->user_agent }}</code></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $trafficLogs->links() }}
    </div>
</div>

@endsection
