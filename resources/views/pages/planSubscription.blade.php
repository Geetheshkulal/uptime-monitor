@extends('dashboard')
@section('content')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

        /* Print Bill Styles */
        @media print {
            body * {
                visibility: hidden;
            }
            .print-bill-template, .print-bill-template * {
                visibility: visible;
            }
            .print-bill-template {
                position: absolute;
                left: -9999px;
                top: -9999px;
                display: none;
            }

            /* .print-bill-template {
            display: none;
            padding: 20px;
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            background: white;
            } */

            .no-print {
                display: none !important;
            }
        }
        
        .print-bill-template {
            position: absolute;
            left: -9999px;
            top: -9999px;
            padding: 20px;
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 0 auto;
            background: white;
        }

        .bill-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .bill-details {
            margin-bottom: 20px;
        }
        
        .bill-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .bill-table th, .bill-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .bill-table th {
            background-color: #f2f2f2;
        }
        
        .bill-footer {
            margin-top: 30px;
            text-align: right;
            border-top: 2px solid #000;
            padding-top: 10px;
        }

        .company-logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
    </style>
@endpush

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <div class="container-fluid">
            <!-- Payments Table -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4 ml-4">
                <h1 class="h3 mb-0 text-gray-800 font-600">My Payment History</h1>
            </div>
            <div class="card shadow mb-4" data-aos="fade-up">
                <div class="card-body skeleton">
                    <div class="table-responsive">
                        <table class="table" id="paymentsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Sl No.</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Payment Type</th>
                                    <th>Payment Status</th>
                                    <th>Transaction Id</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Print Bill</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscriptions as $subscription)
                                <tr>
                                    <td>{{ $loop->iteration}}</td>
                                    <td>₹{{ number_format($subscription->payment_amount, 2) }}</td>
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
                                    <td>{{ strtoupper($subscription->payment_status) }}</td>
                                    <td>{{ $subscription->transaction_id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($subscription->start_date)->format('d M Y, h:i A') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($subscription->end_date)->format('d M Y') }}</td>
                                    <td><button class="btn btn-sm btn-primary print-bill" data-id="{{ $subscription->id }}">Print Bill</button></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No payment history found.</td>
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

<!-- Hidden Bill Template -->
<div id="print-bill-template" class="print-bill-template">
    <div class="bill-header">
        <h3>PAYMENT RECEIPT</h3>
        <p><strong>ChechMySite</strong></p>
        <p>Email: checkmysite2025@gmail </p>
    </div>
    
    <div class="bill-details">
        <p><strong>Receipt Date:</strong> <span id="bill-date"></span></p>
        <p><strong>Transaction ID:</strong> <span id="bill-transaction-id"></span></p>
        <p><strong>Customer:</strong> {{ Auth::user()->name }}</p>
    </div>
    
    <table class="bill-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Subscription Payment</td>
                <td id="bill-amount"></td>
            </tr>
            <tr>
                <td><strong>Total Amount</strong></td>
                <td id="bill-total"></td>
            </tr>
        </tbody>
    </table>
    
    <div class="bill-details">
        <p><strong>Payment Method:</strong> <span id="bill-payment-type"></span></p>
        <p><strong>Payment Status:</strong> <span id="bill-payment-status"></span></p>
        <p><strong>Subscription Period:</strong> <span id="bill-start-date"></span> to <span id="bill-end-date"></span></p>
    </div>
    
    <div class="bill-footer">
        <p>Thank you for your business!</p>
        <p><strong>Authorized Signature</strong></p>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#paymentsTable').DataTable({
            "order": [[6, "desc"]],
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": [2, 3, 8] }
            ]
        });

        // Print Bill Functionality
        $(document).on('click', '.print-bill', function() {
            const row = $(this).closest('tr');
            const subscriptionId = $(this).data('id');
            const amount = row.find('td:eq(1)').text();
            const paymentType = row.find('td:eq(3)').text();
            const paymentStatus = row.find('td:eq(4)').text();
            const transactionId = row.find('td:eq(5)').text();
            const startDate = row.find('td:eq(6)').text();
            const endDate = row.find('td:eq(7)').text();
            
            // Format the current date for the bill
            const today = new Date();
            const formattedDate = today.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // Populate the bill template
            $('#bill-date').text(formattedDate);
            $('#bill-transaction-id').text(transactionId);
            $('#bill-amount').text(amount);
            $('#bill-total').text(amount);
            $('#bill-payment-type').text(paymentType);
            $('#bill-payment-status').text(paymentStatus);
            $('#bill-start-date').text(startDate);
            $('#bill-end-date').text(endDate);
            
            // Show the template temporarily
            const billTemplate = $('#print-bill-template');
            billTemplate.show();
            
            // Use html2canvas to capture the bill as an image
                html2canvas(billTemplate[0], {
                scale: 2,
                logging: false,
                useCORS: true
               }).then(canvas => {
                billTemplate.hide();
                
                // Create PDF
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('p', 'mm', 'a4');
                const imgData = canvas.toDataURL('image/png');
                const imgWidth = 190; // A4 width in mm (210 - 10mm margins on each side)
                const pageHeight = 277; // A4 height in mm (297 - 10mm top margin - 10mm bottom margin)
                const imgHeight = canvas.height * imgWidth / canvas.width;
                
                // Add first page
                pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);
                
                // Only add additional pages if content is taller than one page
                let heightLeft = imgHeight - pageHeight;
                let position = 10; // Start position for additional content
                
                while (heightLeft >= 0) {
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 10, position - pageHeight, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                    position += pageHeight;
                }
                
                // Save the PDF
                pdf.save(`Payment_Receipt_${transactionId}.pdf`);
                });
        });
    });
</script>
@endpush

@endsection