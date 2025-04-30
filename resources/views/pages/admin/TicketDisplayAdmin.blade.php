
@extends('dashboard')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


<style>
    * {
        border-radius: 0 !important;
    }
    </style>    
    
@endpush
</style>

<style>
    .tippy-box[data-theme~='custom'] {
        background-color: #0e55e1 !important; 
        color: #fff; 
        font-size: 14px; 
        border-radius: 5px !important; 
        padding: 7px !important; 
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1) !important; 
    }

    .tippy-box[data-theme~='custom'] .tippy-arrow {
        color: #0e55e1; 
    }

</style>


<div class="page-content">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mx-3 mt-3">
        <h1 class="h3 mb-0 text-gray-800">Tickets</h1>
    </div>

  <!-- Ticket Card -->
  {{-- <div class="row ml-4 d-flex justify-content-center">
  <div class="col-xl-2 col-md-6 mb-4 ">
    <div class="card card-counter border-left-primary shadow h-100 py-2 rounded">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Total Tickets</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">10</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-ticket-alt text-primary fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-2 col-md-6 mb-4">
    <div class="card card-counter border-left-success shadow h-100 py-2 rounded">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Open Tickets</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">10</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-folder-open text-success fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-2 col-md-6 mb-4">
    <div class="card card-counter border-left-danger shadow h-100 py-2 rounded">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Closed Tickets</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">10</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-check-circle text-danger fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-2 col-md-6 mb-4">
    <div class="card card-counter border-left-warning shadow h-100 py-2 rounded">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        On HoldTickets</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">10</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-pause-circle text-warning fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>
  </div> --}}


  <!-- Ticket Card -->
<div class="row mx-3 mb-4 d-flex justify-content-around">
    <div class="col-md-3 col-xl-2 mb-4">
        <div class="card card-counter border-left-primary shadow h-100 py-2 rounded">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Tickets</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$TotalTickets}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-ticket-alt text-primary fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-xl-2 mb-4">
        <div class="card card-counter border-left-success shadow h-100 py-2 rounded">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Open Tickets</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$OpenTickets}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-folder-open text-success fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-xl-2 mb-4">
        <div class="card card-counter border-left-danger shadow h-100 py-2 rounded">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Closed Tickets</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$ClosedTickets}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle text-danger fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-xl-2 mb-4">
        <div class="card card-counter border-left-warning shadow h-100 py-2 rounded">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            On Hold Tickets</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$OnHoldTickets}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-pause-circle text-warning fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



    <!-- Content Row -->
    <div class="row mx-3">
        <div class="col-12">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white px-4">
                    <h6 class="m-0 font-weight-bold text-primary">All Tickets</h6>
                    <!-- <a href="#" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New
                    </a> -->
                </div>
                
                <!-- Card Body -->
                <div class="card-body px-4 py-4">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>SL No</th>
                                    <th>Ticket Id</th>
                                    <th>User Id</th>
                                    <th>Title</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Assigned User</th>
                                    <th>Created at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tickets as $ticket)
                                    <tr>
                                        <td>{{$ticket->id}}</td>
                                        <td>{{ $ticket->ticket_id }}</td>
                                        <td>{{ $ticket->user_id }}</td>
                                        <td>{{$ticket->title}}</td>
                                        <td>{!! $ticket->message !!}</td>
                                        <td>
                                            @if($ticket->status === 'open')
                                                <span class="badge badge-success rounded">Open</span>
                                            @elseif($ticket->status === 'closed')
                                                <span class="badge badge-danger rounded">Closed</span>
                                            @elseif($ticket->status === 'on hold')
                                                <span class="badge badge-warning rounded">On Hold</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($ticket->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{$ticket->priority}}</td>
                                        <td>{{$ticket->assigned_user_id ? $ticket->assigned_user_id :'N/A'}}</td>
                                        <td>{{$ticket->created_at}}</td>
                                        <td>
                                            <div class="d-flex">
                                            <a href="{{ route('display.tickets.show', $ticket->id) }}" class="text-decoration-none ToolTip">
                                            <i id="myComment"  class="far fa-comment-alt" style="color: #0e55e1; font-size:1.5rem; cursor: pointer; padding: 8px;"></i>
                                        </a>
                                        @if($ticket->status === 'closed')
                                        <a class="text-decoration-none" >
                                            <i id="myClosed" class="fas fa-check-circle text-danger" style="color: #e10e18; font-size:1.5rem; padding: 8px;"></i>
                                        </a>
                                         @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No subscription plans available</td>
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

<!-- Edit Subscription Modal -->
<div class="modal fade" id="editSubscriptionModal" tabindex="-1" role="dialog" aria-labelledby="editSubscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubscriptionModalLabel">Edit Subscription Plan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editSubscriptionForm" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <input type="hidden" name="id" id="subscription_id">
                    <div class="form-group">
                        <label for="name">Plan Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" step="0.01" class="form-control" id="edit_amount" name="amount" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Load Tippy.js after Bootstrap -->
<script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
<script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>



<script>
    tippy('#myComment', {
    content: 'View Comment',
    theme: 'custom', 
    animation: 'scale', 
    arrow: true, 
    placement: 'top', 
});

tippy('#myClosed', {
    content: 'Closed',
    theme: 'custom', 
    animation: 'scale', 
    arrow: true, 
    placement: 'top', 
});

document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
            toastr.success("{{ session('success') }}", "Success", {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 4000
            });
        @endif
});

    $(document).ready(function () {

        $('#dataTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "order": [[0, "asc"]]
        });

        // Handle edit button click
        $('.edit-subscription').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var amount = $(this).data('amount');
            
            $('#subscription_id').val(id);
            $('#edit_name').val(name);
            $('#edit_amount').val(amount);
            
            // Update form action
            $('#editSubscriptionForm').attr('action', 'edit/billing/' + id);
        });
    });
</script>
@endpush

@endsection