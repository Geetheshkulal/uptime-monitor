@extends('dashboard')
@section('content')
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        @push('styles')
        <style>
            :root {
                --primary: #4e73df;
                --success: #1cc88a;
                --danger: #e74a3b;
                --warning: #f6c23e;
                --info: #36b9cc;
            }
            
            .tablerow:hover {
                background-color: #f8f9fc;
                transform: scale(1.005);
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
                transition: all 0.3s ease;
            }
            
            .dataTables_wrapper {
                overflow-x: hidden !important;
            }
            
            #dataTable {
                width: 100% !important;
                border-collapse: separate;
                border-spacing: 0 8px;
            }
            
            #dataTable thead th {
                background-color: #f8fafc;
                color: #4a5568;
                font-weight: 700;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.5px;
                border: none;
                padding: 12px 15px;
            }
            
            #dataTable tbody tr {
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
            }
            
            #dataTable tbody td {
                padding: 15px;
                vertical-align: middle;
                border-top: none;
                border-bottom: 1px solid #f1f5f9;
            }
            
            .badge {
                padding: 6px 10px;
                font-weight: 600;
                letter-spacing: 0.5px;
                font-size: 0.75rem;
                border-radius: 50px;
            }
            
            .badge-success {
                background-color: rgba(28, 200, 138, 0.1);
                color: var(--success);
            }
            
            .badge-danger {
                background-color: rgba(231, 74, 59, 0.1);
                color: var(--danger);
            }
            
            .badge-warning {
                background-color: rgba(246, 194, 62, 0.1);
                color: var(--warning);
            }
            
            .page-title {
                font-weight: 700;
                color: #2d3748;
                margin-bottom: 1.5rem;
            }
            
            /* Responsive adjustments */
            @media (max-width: 768px) {
                #dataTable thead {
                    display: none;
                }
                
                #dataTable tbody tr {
                    display: block;
                    margin-bottom: 15px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                }
                
                #dataTable tbody td {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 10px 15px;
                    border-bottom: 1px solid #f1f5f9;
                }
                
                #dataTable tbody td::before {
                    content: attr(data-label);
                    font-weight: 600;
                    color: #4a5568;
                    margin-right: 15px;
                }
            }
        </style>
        @endpush

        <!-- Main Content -->
        <div id="content">
            <!-- Begin Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800 page-title">Incident History</h1>
                </div>

                <!-- Data Table -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead class="thead-light">
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
                                    @foreach ($incidents as $incident)
                                        <tr class="tablerow">
                                            <td data-label="Status">
                                                @if ($incident->monitor->paused == 1)
                                                    <span class="badge" style="background-color: purple; color: white;">Paused</span>
                                                @elseif ($incident->status === 'down')
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-times-circle mr-1"></i> Down
                                                    </span>
                                                @else
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle mr-1"></i> Up
                                                    </span>
                                                @endif
                                            </td>
                                            <td data-label="Monitor">
                                                <a href="{{ $incident->monitor->url }}" target="_blank" class="text-primary">
                                                    {{ Str::limit($incident->monitor->url, 30) }}
                                                </a>
                                                <small class="d-block text-muted">{{ $incident->monitor->name }}</small>
                                            </td>
                                            <td data-label="Root Cause">{{ $incident->root_cause }}</td>
                                            <td data-label="Start Time">
                                                {{ $incident->start_timestamp->format('M d, Y h:i A') }}
                                            </td>
                                            <td data-label="Resolved">
                                                @if ($incident->end_timestamp)
                                                    {{ $incident->end_timestamp->format('M d, Y h:i A') }}
                                                @else
                                                    <span class="badge badge-warning">Ongoing</span>
                                                @endif
                                            </td>
                                            <td data-label="Duration">
                                                @if ($incident->end_timestamp)
                                                    {{ $incident->start_timestamp->diffForHumans($incident->end_timestamp, true) }}
                                                @else
                                                    {{ $incident->start_timestamp->diffForHumans(null, true) }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->
    </div>

    <!-- Scripts -->
    @push('scripts')
    <script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    
   
    @endpush
    @push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    function fetchIncidents() {
        $.ajax({
            url: "{{ route('incidents.fetch') }}",
            type: "GET",
            dataType: "json",
            success: function(response) {
                let incidents = response.incidents;
                let html = '';

                incidents.forEach(function(incident) {
                    let statusBadge = '';

                    if (incident.monitor.paused === 1) {
                        statusBadge = '<span class="badge" style="background-color: purple; color: white;">Paused</span>';
                    } else if (incident.status === 'down') {
                        statusBadge = '<span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i> Down</span>';
                    } else {
                        statusBadge = '<span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Up</span>';
                    }

                    html += `
                        <tr class="tablerow">
                            <td data-label="Status">${statusBadge}</td>
                            <td data-label="Monitor">
                                <a href="${incident.monitor.url}" target="_blank" class="text-primary">
                                    ${incident.monitor.url.substring(0, 30)}
                                </a>
                                <small class="d-block text-muted">${incident.monitor.name}</small>
                            </td>
                            <td data-label="Root Cause">${incident.root_cause}</td>
                            <td data-label="Start Time">${new Date(incident.start_timestamp).toLocaleString()}</td>
                            <td data-label="Resolved">
                                ${incident.end_timestamp ? new Date(incident.end_timestamp).toLocaleString() : '<span class="badge badge-warning">Ongoing</span>'}
                            </td>
                            <td data-label="Duration">
                                ${incident.end_timestamp ? moment(incident.start_timestamp).from(incident.end_timestamp, true) : moment(incident.start_timestamp).fromNow(true)}
                            </td>
                        </tr>
                    `;
                });

                $('#incidentTableBody').html(html);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching incidents:", error);
            }
        });
    }

    // Auto-refresh the incidents table every 10 seconds
    setInterval(fetchIncidents, 10000);
</script>
@endpush

@endsection