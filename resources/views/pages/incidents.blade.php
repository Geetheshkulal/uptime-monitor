@extends('dashboard')
@section('content')

@push('styles')
<!-- External CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@endpush

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Incident History</h1>
    </div>

    <!-- Incident Table Card -->
    <div class="card shadow mb-4">
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="incidentsTable" width="100%" cellspacing="0">
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
                    <tbody>
                        @forelse ($incidents as $incident)
                        <tr>
                            <td>
                                @if ($incident->monitor->paused)
                                    <span class="badge badge-paused">Paused</span>
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
                            <td>
                                <a href="{{ $incident->monitor->url }}" target="_blank" class="text-primary">
                                    {{ Str::limit($incident->monitor->url, 30) }}
                                </a>
                                <small class="d-block text-muted">{{ $incident->monitor->name }}</small>
                            </td>
                            <td>{{ $incident->root_cause ?? 'Not specified' }}</td>
                            <td>{{ $incident->start_timestamp->format('M d, Y h:i A') }}</td>
                            <td>
                                @if ($incident->end_timestamp)
                                    {{ $incident->end_timestamp->format('M d, Y h:i A') }}
                                @else
                                    <span class="badge bg-warning text-dark">Ongoing</span>
                                @endif
                            </td>
                            <td>
                                @if ($incident->end_timestamp)
                                    {{ $incident->start_timestamp->diffForHumans($incident->end_timestamp, true) }}
                                @else
                                    {{ $incident->start_timestamp->diffForHumans(null, true) }}
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                                <p class="mb-0">No incidents found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- External JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        const table = $('#incidentsTable').DataTable({
            order: [[3, 'desc']],
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search incidents...",
                zeroRecords: "No matching incidents found",
                info: "Showing _START_ to _END_ of _TOTAL_ incidents",
                infoEmpty: "No incidents available",
                paginate: {
                    previous: "<i class='fas fa-chevron-left'></i>",
                    next: "<i class='fas fa-chevron-right'></i>"
                }
            }
        });

        // Refresh incidents
        $('#refreshIncidents').click(function(e) {
            e.preventDefault();
            fetchIncidents();
            toastr.info('Incidents refreshed', '', {timeOut: 1000});
        });

        // Auto-refresh every 30 seconds
        setInterval(fetchIncidents, 30000);
    });

    function fetchIncidents() {
        $.ajax({
            url: "{{ route('incidents.fetch') }}",
            type: "GET",
            dataType: "json",
            success: function(response) {
                const table = $('#incidentsTable').DataTable();
                table.clear();

                if (response.incidents.length === 0) {
                    table.row.add([
                        '', 
                        '<div class="text-center py-4 text-muted">' +
                        '<i class="fas fa-check-circle fa-2x mb-2 text-success"></i>' +
                        '<p class="mb-0">No incidents found</p>' +
                        '</div>', 
                        '', '', '', ''
                    ]).draw();
                } else {
                    response.incidents.forEach(incident => {
                        const statusBadge = getStatusBadge(incident);
                        const monitorLink = getMonitorLink(incident);
                        const resolutionTime = getResolutionTime(incident);
                        
                        table.row.add([
                            statusBadge,
                            monitorLink,
                            incident.root_cause || 'Not specified',
                            new Date(incident.start_timestamp).toLocaleString(),
                            incident.end_timestamp ? 
                                new Date(incident.end_timestamp).toLocaleString() : 
                                '<span class="badge bg-warning text-dark">Ongoing</span>',
                            resolutionTime
                        ]).draw(false);
                    });
                }
            },
            error: function(xhr) {
                console.error("Error fetching incidents:", xhr.responseText);
                toastr.error('Failed to load incidents');
            }
        });
    }

    function getStatusBadge(incident) {
        if (incident.monitor.paused) {
            return '<span class="badge bg-purple text-white">Paused</span>';
        }
        return incident.status === 'down' ?
            '<span class="badge bg-danger"><i class="fas fa-times-circle mr-1"></i> Down</span>' :
            '<span class="badge bg-success"><i class="fas fa-check-circle mr-1"></i> Up</span>';
    }

    function getMonitorLink(incident) {
        return `<a href="${incident.monitor.url}" target="_blank" class="text-primary">
                ${incident.monitor.url.substring(0, 30)}${incident.monitor.url.length > 30 ? '...' : ''}
                </a>
                <small class="d-block text-muted">${incident.monitor.name}</small>`;
    }

    function getResolutionTime(incident) {
        const start = moment(incident.start_timestamp);
        return incident.end_timestamp ? 
            start.from(moment(incident.end_timestamp), true) : 
            start.fromNow(true);
    }
</script>
@endpush

@endsection