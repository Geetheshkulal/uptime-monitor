@extends('dashboard')
@section('content')
<head>
    @push('styles')
    <!-- External CSS Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <style>
        /* ========== GLOBAL STYLES ========== */
        :root {
            --primary: #4e73df;
            --primary-light: #e3f2fd;
            --success: #1cc88a;
            --danger: #e74a3b;
            --warning: #f6c23e;
            --info: purple;
            --gray: #858796;
            --light-gray: #f8f9fc;
            --dark-gray: #5a5c69;
        }

        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--dark-gray);
        }

        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transition: all 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1.5rem rgba(58, 59, 69, 0.2);
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 600;
            padding: 1rem 1.35rem;
        }

        /* ========== STATUS INDICATORS ========== */
        .status-badge {
            display: inline-flex;
            width: 100px; 
            align-items: center;       
            justify-content: center;  
            align-items: center;
            padding: 0.35rem 0.65rem;
            border-radius: 0.35rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge-open {
            background-color: rgba(28, 200, 138, 0.1);
            color: var(--success);
        }

        .badge-closed {
            background-color: rgba(231, 74, 59, 0.1);
            color: var(--danger);
        }

        .badge-pending {
            background-color: rgba(246, 194, 62, 0.1);
            color: var(--warning);
        }

        /* ========== BUTTONS ========== */
        .btn {
            border-radius: 0.35rem;
            font-weight: 600;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }

        .btn-view {
            background-color: rgba(28, 200, 138, 0.1);
            color: var(--success);
            border: none;
        }

        .btn-view:hover {
            background-color: rgba(28, 200, 138, 0.2);
        }

        /* ========== TABLE STYLES ========== */
        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            height: 100%;
        }

        .table thead th {
            border: none;
            font-weight: 700;
            color: var(--gray);
            padding: 1rem;
            background: #f8f9fc;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid #e3e6f0;
        }

        /* ========== UTILITY CLASSES ========== */
        .text-primary {
            color: var(--primary) !important;
        }

        .font-600 {
            font-weight: 600;
        }

        /* ========== ANIMATIONS ========== */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease forwards;
        }
    </style>
</head>

<!-- Main Content -->
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div data-aos="fade-up" class="d-flex align-items-center justify-content-between mb-4 fade-in">
                <h1 class="h3 mb-0 text-gray-800 font-600">Support Tickets</h1>
                <a class="btn btn-primary" href="{{route('raise.tickets')}}">
                    <i class="fas fa-plus fa-sm"></i> Raise Ticket
                </a>
            </div>

            <!-- Tickets Table -->
            <div class="fade-in">
                <div data-aos="fade-up" class="card mb-4 px-4">
                    <br>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="min-width: 100%;">
                            <table class="table mb-0" id="ticketsTable">
                                <thead>
                                    <tr>
                                        <th>Ticket ID</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Created</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Sample Data - Replace with dynamic data from your backend -->
                                    @foreach ($tickets as $ticket )
                                        <tr>
                                            <td class="font-600">{{ $ticket->ticket_id }}</td>
                                            <td>{{ $ticket->title }}</td>
                                            <td>
                                                @switch($ticket->status)
                                                    @case('open')
                                                        <span class="status-badge badge-open">Open</span>
                                                        @break

                                                    @case('closed')
                                                        <span class="status-badge badge-closed">Closed</span>
                                                        @break

                                                    @case('on hold')
                                                        <span class="status-badge badge-pending">Pending</span>
                                                        @break
                                                @endswitch
                                            </td>
                                                <td>{{$ticket->priority}}</td>
                                                <td>{{$ticket->created_at->diffForHumans()}}</td>
                                                <td>{{$ticket->updated_at->diffForHumans()}}</td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-view">
                                                        <i class="fas fa-eye mr-1"></i>View
                                                    </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Required Scripts -->
<script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/sb-admin-2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

<script>
    // Initialize animations
    AOS.init({
        duration: 400,
        easing: 'ease-out',
        once: true
    });

    // Initialize DataTable
    $(document).ready(function() {
        $('#ticketsTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "order": [[4, "desc"]], // Default sort by Created date
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search tickets...",
                "lengthMenu": "Show _MENU_",
                "info": "Showing _START_ to _END_ of _TOTAL_ tickets"
            }
        });
    });

    // Show success message if exists
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
            toastr.success("{{ session('success') }}", "Success", {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 5000
            });
        @endif
    });
</script>
@endpush

@endsection