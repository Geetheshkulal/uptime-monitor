@extends('dashboard')
@section('content')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
<style>
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
    </style>


<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <div class="container-fluid">
            <!-- Payments Table -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4 ml-4">
                <h1 class="h3 mb-0 text-gray-800 font-600">My Payment History</h1>
            </div>
            <div class="card shadow mb-4">
                <div class="card-body skeleton">

                    <div class="table-responsive">
                        <table class="table" id="paymentsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Sl No.</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Payment Type</th>
                                    <th>Transaction Id</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscriptions as $subscription)
                                <tr>
                                    <td>{{ $loop->iteration}}</td>
                                    <td>₹{{ number_format($subscription->subscription->amount, 2) }}</td>
                                    <td>
                                        @if($subscription->status === 'active')
                                            <span class="badge badge-success">Active</span>
                                        @elseif($subscription->status === 'expired')
                                            <span class="badge badge-danger">Expired</span>
                                        @else
                                            <span class="badge badge-warning">{{ ucfirst($subscription->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ strtoupper($subscription->payment_type) }}</td>
                                    <td>{{ $subscription->transaction_id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($subscription->start_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($subscription->end_date)->format('d M Y') }}</td>
                                    <td>{{ $subscription->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No payment history found.</td>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#paymentsTable').DataTable({
            "order": [[6, "desc"]], // Order by created_at (7th column, index 6)
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": [2, 3] } // Make status and payment type columns non-orderable
            ]
        });
    });

</script>
@endpush

@endsection
