@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .traffic-card {
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-bottom: 1rem;
        overflow: hidden;
    }
    
    /* New Header Styling */
    .traffic-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
    }
    
    .ip-display {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .ip-address {
        font-family: 'Roboto Mono', monospace;
        font-size: 0.875rem;
        color: #3182ce;
        font-weight: 500;
    }
    
    .flag {
        width: 20px;
        height: 15px;
        border-radius: 2px;
    }
    
    .header-time {
        font-size: 0.75rem;
        color: #64748b;
    }
    
    /* Rest of the existing styles remain exactly the same */
    .traffic-body {
        padding: 1rem;
    }
    
    .client-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 0.75rem;
        align-items: center;
    }
    
    .request-row {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 0.75rem;
    }
    
    .user-agent-row {
        margin-bottom: 0.75rem;
    }
    
    .detail-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .detail-label {
        font-size: 17px;
        color: #64748b;
        font-weight: 700;
    }
    
    .detail-value {
        font-size: 0.8125rem;
        color: #1a202c;
    }
    
    .url-group, .referrer-group {
        flex: 1;
        min-width: 0;
    }
    
    .url-value, .referrer-value, .user-agent {
        font-family: 'Roboto Mono', monospace;
        font-size: 0.75rem;
        background: #f8fafc;
        padding: 0.5rem;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        word-break: break-all;
        white-space: pre-wrap;
    }
    
    .action-buttons {
        display: flex;
        justify-content: flex-end;
        margin-top: 0.5rem;
    }
    
    .action-btn {
        border: none;
        padding: 0.375rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .block-btn {
        background: #e53e3e;
        color: white;
    }
    
    .unblock-btn {
        background: #38a169;
        color: white;
    }
    .container-fluid {
        min-height: calc(100vh - 200px);
        padding-bottom: 60px;
    }

    .traffic-list {
        min-height: 300px; 
    }

    
    @media (max-width: 768px) {
        .client-row, .request-row {
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-start;
        }
        
        .url-group, .referrer-group {
            width: 100%;
        }
        
        .traffic-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>
@endpush

<div class="container-fluid">
    <h1 class="h3 mb-0 text-gray-800">Visitor Traffic Logs</h1><br>
    <div class="search-header">
        
    
                <!-- Filter Section -->
            <div class="card filter-card">
                <div class="card-body">
                    <form method="GET" action="">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Search</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search fa-xs"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control form-control-sm" 
                                        value="{{ request('search') }}" 
                                        placeholder="IP, Browser, URL ,Platform...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">From Date</label>
                                <input type="date" name="from_date" class="form-control form-control-sm" 
                                    value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">To Date</label>
                                <input type="date" name="to_date" class="form-control form-control-sm" 
                                    value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-sm btn-primary w-100">
                                    <i class="fas fa-filter fa-xs me-1"></i> Filter
                                </button>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <a href="{{ url()->current() }}" class="btn btn-sm btn-secondary w-100">
                                    Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


    <!-- Logs List -->
                <div class="traffic-list">
                   @forelse($trafficLogs as $log)
                    <div class="traffic-card">
                    <!-- New Header with IP and Time -->
                    <div class="traffic-header">
                        <div class="ip-display">
                            <span class="detail-label">IP:</span>
                            <span class="ip-address">{{ $log->ip }}</span>
                            @if($log->country !== 'Unknown')
                            <img src="https://flagcdn.com/16x12/{{ strtolower($log->country) }}.png" class="flag">
                        @endif
                        
                    </div>
                    <div class="header-time">
                        {{ $log->created_at->format('M j, Y H:i:s') }}
                    </div>
                    </div>
                    
            <div class="traffic-body">
                <!-- First Row: Browser, Platform, ISP -->
                <div class="client-row">
                    <div class="detail-group">
                        <span class="detail-label">Browser:</span>
                        <span class="detail-value">{{ $log->browser }}</span>
                    </div>
                    <div class="detail-group">
                        <span class="detail-label">Platform:</span>
                        <span class="detail-value">{{ $log->platform }}</span>
                    </div>
                    <div class="detail-group">
                        <span class="detail-label">ISP:</span>
                        <span class="detail-value">{{ $log->isp ?? 'Unknown' }}</span>
                    </div>
                    <div class="detail-group">
                        @if (!empty($log->email))
                                        <strong class="detail-label">User email :</strong> 
                                        <span class="detail-value">{{ $log->email }}</span>
                                        @endif
                    </div>
                    <div class="detail-group">
                        @if (!empty($log->status))
                                        <strong class="detail-label">Status :</strong> 
                                        <span class="detail-value">{{ $log->status }}</span>
                                        @endif
                    </div>
                    <div class="detail-group">
                        @if (!empty($log->reason))
                                        <strong class="detail-label">Reason:</strong><span class="detail-value"> {{ $log->reason }}</span>
                                        @endif
            
                    </div>
                </div>
                
                <!-- Second Row: URL and Referrer -->
                <div class="request-row">
                    <div class="url-group">
                        <span class="detail-label">URL:</span>
                        <div class="url-value">{{ $log->url }}</div>
                    </div>
                    <div class="referrer-group">
                        <span class="detail-label">Referrer:</span>
                        <div class="referrer-value">{{ $log->referrer ?? 'Direct access' }}</div>
                    </div>
                </div>

               
                <!-- Third Row: User Agent -->
                <div class="user-agent-row">
                    <span class="detail-label">User Agent:</span>
                    <div class="user-agent">{{ $log->user_agent }}</div>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    @if(in_array($log->ip, $blocked_ips))
                        <form method="POST" action="{{ route('unblock.ip', $log->ip) }}">
                            @csrf
                            <button type="submit" class="action-btn unblock-btn">
                                <i class="fas fa-unlock fa-xs"></i> Unblock
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('block.ip', $log->ip) }}">
                            @csrf
                            <button type="submit" class="action-btn block-btn">
                                <i class="fas fa-ban fa-xs"></i> Block
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
           @empty
            <div class="text-center p-4 bg-light border rounded">
        <p>No traffic logs available.</p>
    </div>
@endforelse
        </div>

    <!-- Pagination -->
    <div class="mt-3 d-flex justify-content-center">
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