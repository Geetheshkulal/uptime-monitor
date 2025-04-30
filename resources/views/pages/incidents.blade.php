@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

<style>
    /* Consistent with your monitor table styling */
    .card {
        border: none;
        border-radius: 0.35rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        font-weight: 600;
        padding: 1rem 1.35rem;
    }
    
    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table thead th {
        border: none;
        font-weight: 700;
        color: #858796;
        padding: 1rem;
        background: #f8f9fc;
    }
    
    .table td {
        padding: 1rem;
        vertical-align: middle;
        border-top: 1px solid #e3e6f0;
    }
    
    /* Status badges matching your design */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.65rem;
        border-radius: 0.35rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    
    .badge-up {
        background-color: rgba(28, 200, 138, 0.1);
        color: #1cc88a;
    }
    
    .badge-down {
        background-color: rgba(231, 74, 59, 0.1);
        color: #e74a3b;
    }
    
    .badge-paused {
        background-color: rgba(54, 185, 204, 0.1);
        color: #36b9cc;
    }
    
    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        margin-right: 0.35rem;
    }
    
    /* URL styling */
    .monitor-url {
        color: #4e73df;
        font-weight: 500;
    }
    
    .monitor-name {
        font-size: 0.8rem;
        color: #858796;
        display: block;
    }
    
    /* Empty state styling */
    .empty-state {
        text-align: center;
        padding: 2rem;
    }
    
    .empty-state i {
        font-size: 2rem;
        color: #dddfeb;
        margin-bottom: 1rem;
    }
     .custom-spacing {
        margin-left: 1.5rem; /* Or whatever matches your existing spacing */
        margin-right: 1.5rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table-responsive {
            border: 0;
        }
    .custom-spacing {
        margin-left: 1.5rem; /* Or whatever matches your existing spacing */
        margin-right: 1.5rem;
    } 
    }
</style>
@endpush

<div class="page-content">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 ml-4">
        <h1 class="h3 mb-0 text-gray-800 font-600 bold">Incident History</h1>
    </div>

    <!-- Content Row -->
    <div class="custom-spacing">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4" data-aos="fade-up">
                
                <!-- Card Body -->
                <div class="card-body skeleton">
                    <div class="table-responsive ">
                        <table class="table" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Monitor</th>
                                    <th>Root Cause</th>
                                    <th>Start Time</th>
                                    <th>Resolved</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody id="incidentTableBody">
                                @if ($incidents->isEmpty())
                                    <tr>
                                        <td colspan="6" class="empty-state">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <p class="text-muted">No incidents found</p>
                                        </td>
                                    </tr>
                                @else
                                @foreach ($incidents as $incident)
                                    <tr>
                                        <td>
                                            @if ($incident->monitor->paused == 1)
                                                <span class="status-badge badge-paused">
                                                    <span class="status-dot" style="background: #36b9cc;"></span>
                                                    Paused
                                                </span>
                                            @elseif ($incident->status === 'down')
                                                <span class="status-badge badge-down">
                                                    <span class="status-dot" style="background: #e74a3b;"></span>
                                                    Down
                                                </span>
                                            @else
                                                <span class="status-badge badge-up">
                                                    <span class="status-dot" style="background: #1cc88a;"></span>
                                                    Up
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ $incident->monitor->url }}" target="_blank" class="monitor-url">
                                                {{ parse_url($incident->monitor->url, PHP_URL_HOST) }}
                                            </a>
                                            <small class="monitor-name">{{ $incident->monitor->name }}</small>
                                        </td>
                                        <td>{{ $incident->root_cause ?? 'Not specified' }}</td>
                                        <td>{{ $incident->start_timestamp->format('M d, Y h:i A') }}</td>
                                        <td>
                                            @if ($incident->end_timestamp)
                                                {{ $incident->end_timestamp->format('M d, Y h:i A') }}
                                            @else
                                                <span class="badge badge-warning">Ongoing</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($incident->end_timestamp)
                                                {{ $incident->start_timestamp->diff($incident->end_timestamp)->format('%Hh %Im %Ss') }}
                                            @else
                                                {{ $incident->start_timestamp->diffForHumans(null, true) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

<script>
    $(document).ready(function () {
        // Initialize animations
        AOS.init({
            duration: 400,
            easing: 'ease-out',
            once: true
        });

        // Initialize DataTable with same options as monitors table
        $('#dataTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "order": [[3, "desc"]], // Sort by start time by default
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search incidents...",
                "lengthMenu": "Show _MENU_",
                "info": "Showing _START_ to _END_ of _TOTAL_"
            }
        });
    });

    function fetchIncidents() {
        $.ajax({
            url: "{{ route('incidents.fetch') }}",
            type: "GET",
            dataType: "json",
            success: function(response) {
                let incidents = response.incidents;
                let html = '';
                
                if (incidents.length === 0) {
                    html = `
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fas fa-exclamation-circle"></i>
                                <p class="text-muted">No incidents found</p>
                            </td>
                        </tr>`;
                } else {
                    incidents.forEach(function(incident) {
                        let statusBadge = '';
                        if (incident.monitor.paused === 1) {
                            statusBadge = `
                                <span class="status-badge badge-paused">
                                    <span class="status-dot" style="background: #36b9cc;"></span>
                                    Paused
                                </span>`;
                        } else if (incident.status === 'down') {
                            statusBadge = `
                                <span class="status-badge badge-down">
                                    <span class="status-dot" style="background: #e74a3b;"></span>
                                    Down
                                </span>`;
                        } else {
                            statusBadge = `
                                <span class="status-badge badge-up">
                                    <span class="status-dot" style="background: #1cc88a;"></span>
                                    Up
                                </span>`;
                        }

                        let url = new URL(incident.monitor.url);
                        let resolvedTime = incident.end_timestamp ? 
                            new Date(incident.end_timestamp).toLocaleDateString('en-US', { 
                                month: 'short', 
                                day: 'numeric', 
                                year: 'numeric', 
                                hour: '2-digit', 
                                minute: '2-digit' 
                            }) : 
                            '<span class="badge badge-warning">Ongoing</span>';
                        
                        let duration = incident.end_timestamp ? 
                            formatDuration(new Date(incident.start_timestamp), new Date(incident.end_timestamp)) : 
                            moment(incident.start_timestamp).fromNow(true);
                        
                        html += `
                            <tr>
                                <td>${statusBadge}</td>
                                <td>
                                    <a href="${incident.monitor.url}" target="_blank" class="monitor-url">
                                        ${url.hostname}
                                    </a>
                                    <small class="monitor-name">${incident.monitor.name}</small>
                                </td>
                                <td>${incident.root_cause || 'Not specified'}</td>
                                <td>${new Date(incident.start_timestamp).toLocaleDateString('en-US', { 
                                    month: 'short', 
                                    day: 'numeric', 
                                    year: 'numeric', 
                                    hour: '2-digit', 
                                    minute: '2-digit' 
                                })}</td>
                                <td>${resolvedTime}</td>
                                <td>${duration}</td>
                            </tr>
                        `;
                    });
                }
                $('#incidentTableBody').html(html);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching incidents:", error);
            }
        });
    }

    function formatDuration(start, end) {
        let diff = Math.abs(end - start) / 1000;
        const hours = Math.floor(diff / 3600);
        diff -= hours * 3600;
        const minutes = Math.floor(diff / 60);
        diff -= minutes * 60;
        const seconds = Math.floor(diff);
        
        return `${hours}h ${minutes}m ${seconds}s`;
    }

    // Update incidents every 30 seconds
    setInterval(fetchIncidents, 30000);
</script>
@endpush

@endsection