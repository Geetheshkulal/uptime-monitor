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
        margin-bottom: 20px;
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
    
    .search-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .search-box {
        position: relative;
        min-width: 290px;
        flex-grow: 1;
        max-width: 400px;
    }
    
    .search-box input {
        padding-right: 40px;
    }
    
    .search-box .btn {
        position: absolute;
        right: 0;
        top: 0;
        height: 100%;
        border: none;
        background: transparent;
        color: #6c757d;
    }
    
    .search-box .btn:hover {
        color: #495057;
    }
    
    .search-box .btn i {
        font-size: 16px;
    }
    
    .small-placeholder::placeholder {
        font-size: 0.7rem;
        color: #6c757d; 
    }
    
    .log-detail-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }
    
    .log-detail-col {
        padding: 5px 10px;
    }
    
    .ip-block-section {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }
    
    .flag-img {
        margin-left: 5px;
        vertical-align: middle;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>

<div class="container-fluid">
    <div class="search-header">
        <h1 class="h3 mb-0 text-gray-800">Visitor Traffic Logs</h1>
       <div class="card shadow-sm p-2 mb-4">
    <form method="GET" action="" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control small-placeholder"
                   value="{{ request('search') }}"
                   placeholder="Search IP, Browser, URL, Platform or Method">
        </div>

        <div class="col-md-3">
            <input type="date" name="from_date" class="form-control"
                   value="{{ request('from_date') }}" title="From Date">
        </div>

        <div class="col-md-3">
            <input type="date" name="to_date" class="form-control"
                   value="{{ request('to_date') }}" title="To Date">
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search me-1"></i> Filter
            </button>
        </div>
    </form>
</div>
    </div>

    <div class="row">
        @foreach($trafficLogs as $log)
        <div class="col-12">
            <div class="card shadow-sm traffic-item">
                <div class="card-body">
                    <div class="log-detail-row">
                        {{-- <div class="log-detail-col">
                            <strong>ID:</strong> {{ $log->id }}
                        </div> --}}
                        <div class="log-detail-col">
                            <strong>IP:</strong> 
                            <span class="text-primary">{{ $log->ip }}</span>
                            @if (!empty($log->country))
                                <img src="https://flagcdn.com/24x18/{{ strtolower($log->country) }}.png" 
                                     alt="{{ $log->country_code }}" 
                                     class="flag-img">
                            @endif
                        </div>
                        <div class="log-detail-col">
                            <strong>Browser:</strong> {{ $log->browser }}
                        </div>
                        <div class="log-detail-col">
                            <strong>Platform:</strong> {{ $log->platform }}
                        </div>
                        <div class="log-detail-col">
                            <strong>Time:</strong> {{ $log->created_at->format('Y-m-d H:i') }}
                        </div>
                        <div class="log-detail-col ip-block-section">
                            <strong>ISP:</strong> {{ $log->isp }}
                            @if(in_array($log->ip, $blocked_ips))
                                <form method="POST" action="{{ route('unblock.ip', $log->ip) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">Unblock IP</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('block.ip', $log->ip) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Block IP</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="log-detail-row">
                        <div class="log-detail-col" style="flex: 1 1 50%; min-width: 300px;">
                            <strong>URL:</strong>
                            <div class="text-truncate">{{ $log->url }}</div>
                        </div>
                        <div class="log-detail-col" style="flex: 1 1 50%; min-width: 300px;">
                            <strong>Referrer:</strong>
                            <div class="text-truncate">{{ $log->referrer ?? 'Direct access' }}</div>
                        </div>
                    </div>

                    <div class="log-detail-row">
                        <div class="log-detail-col" style="width: 100%;">
                            <strong>User Agent:</strong>
                            <div class="user-agent"><code>{{ $log->user_agent }}</code></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $trafficLogs->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>

@push('scripts')
@if(session('success'))
    <script>
        toastr.success("{{ session('success') }}");
    </script>
  @endif

  @if ($errors->any())
    <script>
        toastr.error("{{ $errors->first() }}");
    </script>
  @endif
@endpush


@endsection
