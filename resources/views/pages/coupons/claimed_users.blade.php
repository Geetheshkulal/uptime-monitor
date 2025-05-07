

@extends('dashboard')
@section('content')

@push('styles')


    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
           * {
            border-radius: 0 !important;
        }

        .filter-container {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        .filter-container label {
            margin-bottom: 0;
            font-weight: 600;
            color: #6e707e;
        }

</style>
@endpush

<div id="content-wrapper" class="d-flex flex-column">
    <h2 class="m-3">Users who claimed coupon: {{ $coupon->code }}</h2>
    <div id="content">
        <div class="container-fluid">
            <!-- Activity Log Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Coupon Claimed</h6>
        
                </div>
                <div class="card-body">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="couponTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>SL no</th>
                                    <th>User Id</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Claimed At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($claimedUsers as $index => $user)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->pivot->created_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <p class="m-0">No users have claimed this coupon.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>


<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#couponTable').DataTable({ 
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "order": [[7, "desc"]],
            "columnDefs": [
                { "searchable": false, "targets": [8] } // Disable sorting for action column
            ]
            
        });

        // Filter table when user is selected
        $('#userFilter').change(function() {
            var userId = $(this).val();
            table.column(5).search(userId).draw();
        });
    });

   
</script>

@endpush

@endsection