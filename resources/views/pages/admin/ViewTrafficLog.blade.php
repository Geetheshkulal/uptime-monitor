@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<style>
    .traffic-item {
        transition: transform 0.2s;
        border: 1px solid rgba(0,0,0,0.125);
    }
    
    .traffic-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .text-truncate {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
    
@endpush

<div class="container-fluid">
    <div class="toolbar mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" style="width: 120px">
                        <option>25 rows</option>
                        <option>50 rows</option>
                        <option>100 rows</option>
                    </select>
                    <input type="text" class="form-control form-control-sm" placeholder="Search...">
                </div>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-sm btn-outline-success">Export</button>
            </div>
        </div>
    </div>

    <div class="row g-3">
        @foreach($trafficLogs as $log)
        <div class="col-12">
            <div class="card shadow-sm mb-3 traffic-item">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="text-muted small">ID</div>
                            <div class="fw-bold">{{ $log->id }}</div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-muted small">IP Address</div>
                            <div class="text-primary">{{ $log->ip }}</div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-muted small">Browser</div>
                            <div>{{ $log->browser }}</div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-muted small">Platform</div>
                            <div>{{ $log->platform }}</div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-muted small">Method</div>
                            <span class="badge bg-primary">{{ $log->method }}</span>
                        </div>
                        <div class="col-md-2">
                            <div class="text-muted small">Created At</div>
                            <div>{{ $log->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="text-muted small">URL</div>
                            <a href="{{ $log->url }}" class="text-truncate d-block">
                                {{ $log->url }}
                            </a>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Referrer</div>
                            <div class="text-truncate">
                                {{ $log->referrer ?? 'Direct access' }}
                            </div>
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