@extends('dashboard')
@section('content')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Table styles */
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
                left: 0;
                top: 0;
                width: 100%;
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }
        }
        
        .print-bill-template {
            position: absolute;
            left: -9999px;
            top: -9999px;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .bill-container {
            padding: 40px;
        }

        .bill-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .company-info {
            text-align: left;
        }

        .company-name {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .company-tagline {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 15px;
        }

        .company-address {
            font-size: 13px;
            line-height: 1.6;
            color: #555;
        }

        .bill-title {
            font-size: 28px;
            font-weight: 300;
            color: #3498db;
            margin-bottom: 10px;
            text-align: right;
        }

        .bill-meta {
            text-align: right;
            font-size: 13px;
        }

        .bill-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .bill-from, .bill-to {
            flex: 1;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
        }

        .bill-to {
            margin-left: 20px;
            background: #f1f8fe;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }

        .bill-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }

        .bill-table thead th {
            background: #3498db;
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 500;
        }

        .bill-table tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        .bill-table tbody tr:last-child td {
            border-bottom: none;
        }

        .bill-table tfoot td {
            padding: 12px 15px;
            font-weight: 600;
            background: #f9f9f9;
        }

        .bill-summary {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .total-box {
            width: 300px;
            padding: 15px;
            background: #f1f8fe;
            border-radius: 5px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .total-label {
            font-weight: 600;
        }

        .total-amount {
            font-weight: 700;
            color: #2c3e50;
        }

        .grand-total {
            font-size: 18px;
            color: #3498db;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            margin-top: 10px;
        }

        .bill-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #7f8c8d;
            text-align: center;
        }

        .thank-you {
            font-size: 16px;
            color: #3498db;
            margin-bottom: 10px;
        }

        .signature-area {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature-line {
            border-top: 1px solid #ccc;
            width: 200px;
            margin-top: 40px;
            text-align: center;
            padding-top: 5px;
            font-size: 12px;
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
                                    <th>Coupon Code</th>
                                    <th>Flat Discount</th>
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
                                    @if ($subscription->coupon_code)
                                        <td><span class="badge badge-success">{{ $subscription->coupon_code }}</span></td>
                                    @else
                                        <td><span class="">Not Applied</span></td>
                                    @endif
                                    <td>{{ $subscription->coupon_value ? $subscription->coupon_value: 'Not Applied' }}</td>
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
                                    <td><button class="btn btn-sm btn-primary print-bill" 
                                        data-id="{{ $subscription->id }}"
                                        data-address="{{ $subscription->address }}"
                                        data-city="{{ $subscription->city }}"
                                        data-state="{{ $subscription->state }}"
                                        data-country="{{ $subscription->country }}"
                                        data-pincode="{{ $subscription->pincode }}">Print Bill</button></td>
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
<div id="print-bill-template" class="print-bill-template" style="display: none;">
    <div class="bill-container">
        <div class="bill-header">
            <div class="company-info">
                <div class="company-name">ChechMySite</div>
                <div class="company-tagline">Website Monitoring Solutions</div>
                <div class="company-address">
                    123 Tech Park, Innovation Road<br>
                    Bangalore, Karnataka 560001<br>
                    India<br>
                    GSTIN: 22ABCDE1234F1Z5<br>
                    Phone: +91 9876543210<br>
                    Email: billing@checkmysite.com
                </div>
            </div>
            <div class="invoice-info">
                <div class="bill-title">INVOICE</div>
                <div class="bill-meta">
                    <div><strong>Invoice #:</strong> <span id="bill-transaction-id"></span></div>
                    <div><strong>Date:</strong> <span id="bill-date"></span></div>
                </div>
            </div>
        </div>

        <div class="bill-details">
            <div class="bill-from">
                <div class="section-title">From:</div>
                <div>
                    <strong>ChechMySite</strong><br>
                    123 Tech Park, Innovation Road<br>
                    Bangalore, Karnataka 560001<br>
                    India<br>
                    GSTIN: 22ABCDE1234F1Z5
                </div>
            </div>
            <div class="bill-to">
                <div class="section-title">Bill To:</div>
                <div>
                    <strong>{{ Auth::user()->name }}</strong><br>
                    <span id="bill-address">
                        <!-- Address will be populated here -->
                    </span>
                </div>
            </div>
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
                    <td>Premium Subscription Plan</td>
                    <td id="bill-amount"></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>Total</strong></td>
                    <td id="bill-total"></td>
                </tr>
            </tfoot>
        </table>

        <div class="bill-summary">
            <div class="total-box">
                <div class="total-row">
                    <span class="total-label">Subtotal:</span>
                    <span class="total-amount" id="bill-subtotal"></span>
                </div>
                <div class="total-row">
                    <span class="total-label">Tax (0%):</span>
                    <span class="total-amount">₹0.00</span>
                </div>
                <div class="total-row grand-total">
                    <span class="total-label">Total:</span>
                    <span class="total-amount" id="bill-grand-total"></span>
                </div>
            </div>
        </div>

        <div class="additional-info">
            <div><strong>Payment Method:</strong> <span id="bill-payment-type"></span></div>
            <div><strong>Payment Status:</strong> <span id="bill-payment-status"></span></div>
            <div><strong>Subscription Period:</strong> <span id="bill-start-date"></span> to <span id="bill-end-date"></span></div>
        </div>

        <div class="signature-area">
            <div class="signature-line">Customer Signature</div>
            <div class="signature-line">For ChechMySite</div>
        </div>

        <div class="bill-footer">
            <div class="thank-you">Thank you for your business!</div>
            <div>This is computer generated receipt and does not require physical signature.</div>
            <div>If you have any questions about this invoice, please contact our billing department at billing@checkmysite.com</div>
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
            
            // Get address details from data attributes
            const address = $(this).data('address');
            const city = $(this).data('city');
            const state = $(this).data('state');
            const country = $(this).data('country');
            const pincode = $(this).data('pincode');
            
            // Format the current date for the bill
            const today = new Date();
            const formattedDate = today.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
            
            // Populate the bill template
            $('#bill-date').text(formattedDate);
            $('#bill-transaction-id').text('INV-' + transactionId);
            $('#bill-amount').text(amount);
            $('#bill-subtotal').text(amount);
            $('#bill-total').text(amount);
            $('#bill-grand-total').text(amount);
            $('#bill-payment-type').text(paymentType);
            $('#bill-payment-status').text(paymentStatus);
            $('#bill-start-date').text(startDate);
            $('#bill-end-date').text(endDate);
            
            // Populate address
            let addressHtml = '';
            if (address) addressHtml += address + '<br>';
            if (city) addressHtml += city;
            if (state) addressHtml += ', ' + state;
            if (pincode) addressHtml += ' - ' + pincode;
            if (country) addressHtml += '<br>' + country;
            
            $('#bill-address').html(addressHtml || 'No address provided');
            
            // Show the template temporarily
            const billTemplate = $('#print-bill-template');
            const printClone = billTemplate.clone().attr('id', 'print-clone');
            
            printClone.css({
                'position': 'fixed',
                'left': '-9999px',
                'top': '0',
                'display': 'block',
                'z-index': '99999'
            }).appendTo('body');
        
        // Use html2canvas on the clone
        html2canvas(printClone[0], {
            scale: 2,
            logging: false,
            useCORS: true,
            scrollX: 0,
            scrollY: 0,
            windowWidth: printClone[0].scrollWidth,
            windowHeight: printClone[0].scrollHeight
        }).then(canvas => {
            // Remove the clone immediately after capturing
            printClone.remove();
            
            // Create PDF
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF('p', 'pt', 'a4');
            const imgData = canvas.toDataURL('image/png');
            const imgWidth = pdf.internal.pageSize.getWidth() - 40;
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            
            pdf.addImage(imgData, 'PNG', 20, 20, imgWidth, imgHeight);
            pdf.save(`Invoice_${transactionId}.pdf`);
        });
    });
});
</script>
@endpush

@endsection