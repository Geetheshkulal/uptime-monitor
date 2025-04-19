@extends('dashboard')

@section('content')

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
            color: black;
        }

        .introjs-button:hover {
            background-color: #2e59d9;
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
    <div class="d-flex justify-content-end ">
        <a href="{{ route('ssl.history') }}" class="btn btn-outline-secondary rounded-pill mt-3 shadow-sm SslCheck">
            📜 View SSL Check History
        </a>
    </div>

    
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
                    <form id="sslCheckForm" action="{{ route('ssl.check.domain') }}" method="POST" class="mt-3">
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

                        <!-- View History Button -->
                      
                    </form>

                    <!-- SSL Details -->
                    @if(session('ssl_details'))
                        <div class="card mt-4 border-0 shadow-lg rounded-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold text-info mb-3">
                                    ℹ️ SSL Certificate Details
                                </h5>
                                <ul class="list-group list-group-flush text-start">
                                    <li class="list-group-item">
                                        <strong>🛡 Status:</strong> 
                                        <span 
                                            class="badge 
                                                {{ session('ssl_details')['days_remaining'] <= 0 ? 'bg-danger' : 
                                                   (session('ssl_details')['days_remaining'] <= 30 ? 'bg-warning text-dark' : 'bg-success') }}">
                                            {{ session('ssl_details')['status'] }}
                                        </span>
                                    </li>
                                    <li class="list-group-item bg-light">
                                        <strong>🌍 Domain:</strong> {{ session('ssl_details')['domain'] }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>🏅 Issuer:</strong> {{ session('ssl_details')['issuer'] }}
                                    </li>
                                    <li class="list-group-item bg-light">
                                        <strong>📆 Valid From:</strong> {{ session('ssl_details')['valid_from'] }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>⏳ Valid To:</strong> 
                                        <span class="badge 
                                            {{ session('ssl_details')['days_remaining'] < 10 ? 'bg-danger' : 'bg-success' }}">
                                            {{ session('ssl_details')['valid_to'] }} 
                                            ({{ session('ssl_details')['days_remaining'] }} days left)
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>

<script>
    document.getElementById('sslCheckForm').addEventListener('submit', function() {
        document.getElementById('submitButton').style.display = 'none';
        document.getElementById('loadingButton').style.display = 'block';
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

@endsection
