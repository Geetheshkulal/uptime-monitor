@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@endpush

<style>
    .custom-bg-danger{
        background-color:  #ff4d4d;
        color: white;
    }
    .custom-bg-warning {
    background-color: #ffcc00;
    color: black; 
  }

.custom-bg-success {
    background-color: #4caf50; 
    color: white; 
}
.custom-bg-danger,
.custom-bg-warning,
.custom-bg-success {
    padding: 0.3rem 0.4rem; 
    font-size: 0.8rem; 
    border-radius: 0.25rem; 
    display: inline-block; 
}

 </style>

<div class="page-content">
    <!-- Page Heading with proper margins -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mx-3 mt-3">
        <h1 class="h3 mb-0 text-gray-800">📜 SSL Check History</h1>
        <a href="{{ route('ssl.check.domain') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Back to SSL Checker
        </a>
    </div>

    <!-- Content Row with container margins -->
    <div class="row mx-3">
        <div class="col-12">
            <div class="card shadow mb-4">
                <!-- Card Header with padding -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white px-4">
                    <h6 class="m-0 font-weight-bold text-primary">SSL Certificate Records</h6>
                </div>
                
                <!-- Card Body with consistent padding -->
                <div class="card-body px-4 py-4">
                    @if($sslChecks->count())
                    <div class="table-responsive">
                        <table class="table table-bordered" id="sslChecksTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Domain</th>
                                    <th>Issuer</th>
                                    <th>Valid From</th>
                                    <th>Valid To</th>
                                    <th>Status</th>
                                    <th>Checked At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sslChecks as $key => $ssl)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $ssl->url }}</td>
                                    <td>{{ $ssl->issuer }}</td>
                                    <td>{{ $ssl->valid_from }}</td>
                                    <td>{{ $ssl->valid_to }}</td>
                                    <td>
                                        @if(str_contains($ssl->status, 'Expired'))
                                            <span class="badge custom-bg-danger">{{ $ssl->status }}</span>
                                        @elseif(str_contains($ssl->status, 'Soon'))
                                            <span class="badge custom-bg-warning text-dark">{{ $ssl->status }}</span>
                                        @else
                                            <span class="badge custom-bg-success">{{ $ssl->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $ssl->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <p class="text-center">No SSL history available yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#sslChecksTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "order": [[0, "asc"]],
            "columnDefs": [
                { "orderable": false, "targets": [6] } // Disable sorting for status column if needed
            ]
        });
    });

    @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif
    
    @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif
</script>
@endpush
@endsection