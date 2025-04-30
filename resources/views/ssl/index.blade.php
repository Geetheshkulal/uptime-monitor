@extends('dashboard')

@section('content')

<!-- Toastr CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css"/>

<!-- Styling -->
<style>
    body {
        background: linear-gradient(135deg, #c3eaff, #f6f8ff);
    }
    .card {
        background: #ffffff;
        border-radius: 15px;
    }
    .btn-primary {
        background: linear-gradient(135deg, #4a90e2, #007bff);
        border: none;
        transition: 0.3s;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #007bff, #4a90e2);
        box-shadow: 0px 4px 10px rgba(0, 123, 255, 0.3);
    }

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

     /* ========== INTROJS TOUR ========== */
     .introjs-tooltip {
            background-color: white;
            color: var(--dark-gray);
            font-family: 'Poppins', sans-serif;
            border-radius: 0.35rem;
            /* box-shadow: 0 0.5rem 1.5rem rgba(7, 18, 144, 0.2); */
            box-shadow: 0px 0px 6px 4px rgba(28, 61, 245, 0.2);   
        }

        .introjs-tooltip-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
        }

        .introjs-button {
            background-color: var(--primary);
            border-radius: 0.25rem;
            font-weight: 600;
            text-shadow: none;
            color: white;
        }

        .introjs-button:hover {
            background-color: #2e59d9;
            color: white;
            cursor: pointer;
        } 
        .introjs-overlay
        {
        pointer-events: none; 
        }

        .introjs-helperLayer {
        pointer-events: none;
        z-index: 1001;
        }

</style>

<div class="container my-5">
    <!-- Right aligned button -->

    
    <div class="row justify-content-center ">
        <div class="col-lg-6 col-md-8 ">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5 text-center SslBox ">

                    <!-- Title -->
                    <h2 class="fw-bold mb-4 text-primary">
                        🔒 SSL Certificate Expiry Check
                    </h2>

                    <!-- Success & Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center fade show shadow-sm">
                            ✅ <span class="ms-2">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center fade show shadow-sm">
                            ⚠️ <span class="ms-2">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Form -->
                    <form id="sslCheckForm" method="POST" class="mt-3">
                        @csrf
                        <div class="mb-4">
                            <label for="domain" class="form-label fw-semibold">
                                🌐 Enter Website URL:
                            </label>
                            <input type="url" id="domain" name="domain" class="form-control form-control-lg rounded-pill"
                                placeholder="https://example.com" required>
                        </div>

                        <!-- Submit Buttons -->
                        <button type="submit" id="submitButton" class="btn btn-primary btn-lg w-100 rounded-pill shadow">
                            🔍 Check SSL Expiry
                        </button>
                        <button id="loadingButton" class="btn btn-primary w-100 rounded-pill shadow mt-2" type="button" style="display:none;" disabled>
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Loading...
                        </button>                      
                    </form>

                    <!-- SSL Details Section -->

                    <div id="sslDetailsContainer" class="mt-4"></div>

                </div>
            </div>
        </div>
    </div>
<br>
<br>
    <!-- Content Row with container margins -->
    <div class="row mx-3">
        <div class="col-12">
            <div class="card shadow mb-4">
                <!-- Card Header with padding -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white px-4">
                    <h6 class="m-0 font-weight-bold text-primary SslCheck">SSL Certificate Records</h6>
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

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>

<script>
    document.getElementById('sslCheckForm').addEventListener('submit', function(e) {
        e.preventDefault();

        document.getElementById('submitButton').style.display = 'none';
        document.getElementById('loadingButton').style.display = 'block';

        const formData = new FormData(this);

        fetch("{{ route('ssl.check.domain')}}",{
            method:"POST",
            headers:{
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: formData
        })
        .then(response => response.json())
        .then(data=>{
            document.getElementById('submitButton').style.display = 'block';
            document.getElementById('loadingButton').style.display = 'none';

        if(data.success)
        {
            const sslDetailsContainer = document.getElementById('sslDetailsContainer');
            sslDetailsContainer.innerHTML = `
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-info mb-3">ℹ️ SSL Certificate Details</h5>
                        <ul class="list-group list-group-flush text-start">
                            <li class="list-group-item">
                                <strong>🛡 Status:</strong> 
                                <span class="badge ${data.ssl_details.days_remaining <= 0 ? 'custom-bg-danger' : 
                                    (data.ssl_details.days_remaining <= 30 ? 'custom-bg-warning' : 'custom-bg-success')}">
                                    ${data.ssl_details.status}
                                </span>
                            </li>
                            <li class="list-group-item bg-light">
                                <strong>🌍 Domain:</strong> ${data.ssl_details.domain}
                            </li>
                            <li class="list-group-item">
                                <strong>🏅 Issuer:</strong> ${data.ssl_details.issuer}
                            </li>
                            <li class="list-group-item bg-light">
                                <strong>📆 Valid From:</strong> ${data.ssl_details.valid_from}
                            </li>
                            <li class="list-group-item">
                                <strong>⏳ Valid To:</strong> 
                                <span class="badge ${data.ssl_details.days_remaining < 10 ? 'custom-bg-danger' : 'custom-bg-success'}">
                                    ${data.ssl_details.valid_to} (${data.ssl_details.days_remaining} days left)
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            `;
        }else{
            toastr.error(data.message, 'Error');
        }
        })
        .catch(error=>{
            document.getElementById('submitButton').style.display = 'block';
            document.getElementById('loadingButton').style.display = 'none';

        // Handle network or server errors
        console.error("Error:", error);
        toastr.error('An unexpected error occurred. Please try again.', 'Error');
        })
    });

</script>

<script>
      // Initialize tour(tool tip)
      document.addEventListener("DOMContentLoaded", function () {
        const intro = introJs();
        const savedStep=localStorage.getItem("introCurrentStep");

        intro.setOptions({
            disableInteraction: false,
            steps:[
        {
         element:document.querySelector('.SslCheck'),
         intro:'To view SSL check history',
         position:'left'
       },
       {
         element:document.querySelector('.SslBox'),
         intro:'To check SSL expire.'
       }
      ],
            dontShowAgain: true,
            nextLabel: 'Next',
            prevLabel: 'Back',
            doneLabel: 'Finish'
        });

        if (savedStep !== null) { 
        console.log("Resuming tour from step:", savedStep); 
        intro.goToStep(Number(savedStep));
        intro.start(); 
    } else {
        console.log("Starting tour from the beginning"); // Debugging
        intro.start(); 
    }
        
        // Save the current step to localStorage whenever the step changes
        intro.onchange(function () {
            const currentStep = intro._currentStep; // Get the current step
            console.log("Saving step:", currentStep);
            localStorage.setItem("introCurrentStep", currentStep); // Save it to localStorage
        });

        // Clear the saved step when the tour is completed
        intro.oncomplete(function () {
        localStorage.removeItem("introCurrentStep");
       });

        // Clear the saved step if the user exits the tour
        intro.onexit(function () {
        localStorage.removeItem("introCurrentStep");
        });
    });

</script>

<script>
    document.getElementById("startTourBtn").addEventListener("click", function () {
        const intro = introJs();
        intro.setOptions({
            steps: [
                {
                    element: document.querySelector('.SslCheck'),
                    intro: 'To view SSL check history',
                    position: 'left'
                },
                {
                    element: document.querySelector('.SslBox'),
                    intro: 'To check SSL expire.'
                }
            ],
            nextLabel: 'Next',
            prevLabel: 'Back',
            doneLabel: 'Finish'
        });
        intro.start();
    });
</script>


@endsection
