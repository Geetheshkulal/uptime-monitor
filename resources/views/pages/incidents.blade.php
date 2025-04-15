@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@endpush

<div class="page-content">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mx-3 mt-3">
        <h1 class="h3 mb-0 text-gray-800">Incident History</h1>
    </div>

    <!-- Content Row -->
    <div class="row mx-3">
        <div class="col-12">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white px-4">
                    <h6 class="m-0 font-weight-bold text-primary">All Incidents</h6>
                </div>
                
                <!-- Card Body -->
                <div class="card-body px-4 py-4">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                                @if ($incidents->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No incidents are available</td>
                                    </tr>
                                @else
                                @foreach ($incidents as $incident)
                                    <tr>
                                        <td>
                                            @if ($incident->monitor->paused == 1)
                                                <span class="badge" style="background-color:purple; color: white;">Paused</span>
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
                                        <td>{{ $incident->root_cause }}</td>
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
                                                {{ $incident->start_timestamp->diffForHumans($incident->end_timestamp, true) }}
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

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    $(document).ready(function () {
        $('#dataTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "order": [[3, "desc"]]
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
                            <td colspan="6" class="text-center text-muted">No incidents are available</td>
                        </tr>`;
                } else {
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
                            <tr>
                                <td>${statusBadge}</td>
                                <td>
                                    <a href="${incident.monitor.url}" target="_blank" class="text-primary">
                                        ${incident.monitor.url.substring(0, 30)}
                                    </a>
                                    <small class="d-block text-muted">${incident.monitor.name}</small>
                                </td>
                                <td>${incident.root_cause}</td>
                                <td>${new Date(incident.start_timestamp).toLocaleString()}</td>
                                <td>
                                    ${incident.end_timestamp ? new Date(incident.end_timestamp).toLocaleString() : '<span class="badge badge-warning">Ongoing</span>'}
                                </td>
                                <td>
                                    ${incident.end_timestamp ? moment(incident.start_timestamp).from(incident.end_timestamp, true) : moment(incident.start_timestamp).fromNow(true)}
                                </td>
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

    setInterval(fetchIncidents, 10000);
</script>
@endpush

@endsection