@extends('layouts.public')

@section('content')
<style>
    :root {
        --primary-color: #4361ee;
        --primary-light: #e0e7ff;
        --success-color: #10b981;
        --success-light: #d1fae5;
        --warning-color: #f59e0b;
        --warning-light: #fef3c7;
        --danger-color: #ef4444;
        --danger-light: #fee2e2;
        --light-color: #f8fafc;
        --dark-color: #1e293b;
        --gray-color: #7a7d81;
        --gray-light: #e2e8f0;
        --card-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 4px 12px rgba(0, 0, 0, 0.02);
    }

    .status-header {
        background: linear-gradient(135deg, var(--primary-color), #4f46e5);
        color: white;
        border-radius: 12px;
        padding: 1.75rem 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--card-shadow);
        position: relative;
        overflow: hidden;
    }

    .status-header::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 100px;
        background: linear-gradient(90deg, rgba(255,255,255,0), rgba(255,255,255,0.1));
        transform: skewX(-20deg);
        transform-origin: top right;
    }

    .bar-segment {
        width: {{ $user->status === 'free' ? '12px' : '7.8px' }};
        height: 40px;
        margin-right: {{ $user->status === 'free' ? '25px' : '3.7px' }};
        display: inline-block;
        border-radius: 3px;
        transition: all 0.3s ease;
        background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.1));
    }

    .bar-segment-wrapper {
        position: relative;
        display: inline-block;
    }

    .bar-segment-wrapper:hover .bar-segment {
        transform: scaleY(1.2) scaleX(1.5);
        opacity: 0.9;
    }

    .tooltip-text {
        visibility: hidden;
        background-color: var(--dark-color);
        color: #fff;
        text-align: center;
        border-radius: 8px;
        padding: 8px 12px;
        position: absolute;
        z-index: 10;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        white-space: nowrap;
        font-size: 12px;
        opacity: 0;
        transition: opacity 0.2s ease, transform 0.2s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateX(-50%) translateY(5px);
        pointer-events: none;
        border-left: 3px solid var(--primary-color);
        line-height: 1.4;
    }

    .bar-segment-wrapper:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    .status-badge {
        font-size: 0.7rem;
        padding: 0.35rem 0.8rem;
        border-radius: 50px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
        border: none;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        background: white;
    }

    .card-header {
        border-bottom: 1px solid var(--gray-light);
        padding: 1.25rem 1.5rem;
        background: white;
        font-weight: 600;
        color: var(--dark-color);
    }

    .hover-shadow {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .hover-shadow:hover {
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.1);
        transform: translateY(-3px);
    }

    .monitor-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        border: 1px solid var(--gray-light);
        position: relative;
    }

    .monitor-name {
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark-color);
        margin-right: 0.75rem;
    }

    .monitor-url {
        font-size: 0.8rem;
        color: var(--gray-color);
        font-weight: 400;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 300px;
    }

    .uptime-legend {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-top: 0.75rem;
        font-size: 0.7rem;
        color: var(--gray-color);
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
    }

    .legend-color {
        width: 10px;
        height: 10px;
        border-radius: 2px;
        margin-right: 6px;
    }

    .monitor-stats {
        display: flex;
        justify-content: space-between;
        background: var(--light-color);
        padding: 0.9rem 1.2rem;
        border-radius: 8px;
        margin-top: 1rem;
        font-size: 0.85rem;
        color: var(--dark-color);
        border: 1px solid var(--gray-light);
    }

    .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.75rem;
        position: relative;
    }

    .status-indicator::after {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .status-indicator-up {
        background-color: var(--success-color);
    }

    .status-indicator-up::after {
        background-color: var(--success-color);
    }

    .status-indicator-down {
        background-color: var(--danger-color);
    }

    .status-indicator-down::after {
        background-color: var(--danger-color);
    }

    .status-indicator:hover::after {
        opacity: 0.3;
    }

    .stat-value {
        font-weight: 600;
        color: var(--dark-color);
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.25rem;
    }

    .page-subtitle {
        color: rgba(255,255,255,0.85);
        font-size: 0.9rem;
        max-width: 600px;
    }
    
    .bars-container {
        position: relative;
        padding: 12px 0;
        margin: 0 -5px;
    }
    
    .bars-container::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 1px;
        background: linear-gradient(to right, rgba(203, 213, 225, 0), rgba(203, 213, 225, 0.5), rgba(203, 213, 225, 0));
    }
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .monitor-stats {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .status-header {
            padding: 1.5rem;
        }
        
        .monitor-card {
            padding: 1.25rem;
        }

        .monitor-url {
            max-width: 200px;
        }

        .uptime-legend {
            justify-content: flex-start;
        }
    }

    @media (max-width: 576px) {
        .monitor-card {
            padding: 1rem;
        }

        .monitor-name, .monitor-url {
            display: block;
            width: 100%;
            margin-right: 0;
        }

        .monitor-name {
            margin-bottom: 0.25rem;
        }

        .monitor-url {
            max-width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>
<div class="container-fluid px-4 py-3">
    <!-- Header Section -->
    <div class="status-header rounded-lg mb-4">
        <h1 class="page-title">Status Page</h1>
        <p class="page-subtitle">Real-time monitoring status</p>
    </div>

    <!-- Monitors Section -->
    @if($monitors->count() > 0)
        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-body px-0 pt-0">
                        @foreach($monitors as $monitor)
                        <div class="monitor-card monitor-card-{{ $monitor->status }} hover-shadow mx-3 mb-3 animate-fade-in" style="animation-delay: {{ $loop->index * 0.05 }}s">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <span class="status-indicator status-indicator-{{ $monitor->status }}"></span>
                                    <span class="monitor-name">{{ $monitor->name }}</span>
                                    <span class="monitor-url">{{ $monitor->url }}</span>
                                </div>
                                <span class="status-badge bg-{{ $monitor->statusColor }}-100 text-{{ $monitor->statusColor }}-800">
                                    <i class="fas fa-{{ $monitor->statusIcon }} mr-1"></i>
                                    {{ ucfirst($monitor->status) }}
                                </span>
                            </div>
                            
                            <!-- Dynamic Day Bar Visualization -->
                            <div class="bars-container">
                                <div class="d-flex flex-wrap">
                                    @foreach($monitor->daysData as $day)
                                        <div class="bar-segment-wrapper">
                                            <div class="tooltip-text">
                                                {{ $day['formatted_date'] }}<br>
                                                @if ($day['total_checks'] === 0)
                                                    No records
                                                @else
                                                    {{ round($day['uptime_percentage'], 1) }}% Uptime<br>
                                                    {{ $day['success_checks'] }}/{{ $day['total_checks'] }} Checks
                                                @endif
                                            </div>
                                            <div class="bar-segment"
                                                 style="height: {{ $day['height'] }}px; background-color: {{ $day['color'] }};">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="uptime-legend">
                                <div class="legend-item">
                                    <div class="legend-color" style="background-color: #10b981;"></div>
                                    <span>100-95%</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-color" style="background-color: #f59e0b;"></div>
                                    <span>94-80%</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-color" style="background-color: #ef4444;"></div>
                                    <span><80%></span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-color" style="background-color: #e2e8f0;"></div>
                                    <span>No data</span>
                                </div>
                                <div class="legend-item ml-4">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    <span>{{ count($monitor->daysData) }}-day history</span>
                                </div>
                            </div>
                            
                            <div class="monitor-stats">
                                <div class="flex items-center">
                                    <span class="mr-4"><i class="fas fa-chart-line mr-1 text-primary"></i> Overall Uptime: <span class="stat-value">{{ $monitor->overallUptime }}%</span></span>
                                    &nbsp;
                                    <span><i class="fas fa-spinner fa-pulse mr-1 text-primary"></i>Total Checks: <span class="stat-value">{{ $monitor->totalChecks }}</span></span>
                                </div>
                            <div>
                                    <i class="far fa-clock mr-1 text-primary"></i>
                                    <span>Last checked: {{ $monitor->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning text-center py-4">
            <i class="fas fa-exclamation-triangle me-2"></i>
            No public monitors available
        </div>
    @endif
</div>



<script>
// Auto-refresh every 60 seconds
setTimeout(() => window.location.reload(), 60000);
</script>
@endsection