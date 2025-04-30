@extends('dashboard')

@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
    * { border-radius: 0 !important; }
    body {
        background-color: #f8f9fa;
        color: #333;
    }
    .bg-dark { background-color: #2c3e50 !important; }
    .text-white { color: #fff !important; }
    .btn-primary, .btn-secondary {
        background-color: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
    }
    .btn-secondary {
        color: #fff;
        background-color: #81838f;
        border-color: #858796;
        margin-right: 8px;
     }
    .btn-secondary:hover {
        background-color: #666769;
    }
    .btn-warning {
        background-color: #f39c12;
        border-color: #f39c12;
        color: #fff;
    }
    .btn-warning:hover {
        background-color: #e68a00;
        border-color: #d97e00;
    }
    .card {
        border: 1px solid #ddd;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }
    .card-header {
        background-color: #e9ecef;
        border-bottom: 1px solid #ddd;
    }
    .card-body {
        padding: 1.5rem;
    }
    .text-success { color: #28a745 !important; }
    .text-muted { color: #6c757d !important; }
    .text-warning { color: #f39c12 !important; }
    .border-warning { border-color: #f39c12 !important; }
    .border-secondary { border-color: #3498db !important; }

    .ribbon {
        position: absolute;
        top: 10px;
        right: -20px;
        transform: rotate(45deg);
        width: 120px;
        font-size: 0.8rem;
        font-weight: bold;
        padding: 5px 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        text-align: center;
        z-index: 10;
    }
    .ribbon.bg-secondary {
        background-color: #3498db;
    }

    .premium-highlight {
        border: 3px solid #f39c12 !important;
        box-shadow: 0px 4px 20px rgba(243, 156, 18, 0.6);
        animation: glow 1.5s infinite alternate;
    }

    @keyframes glow {
        0% { box-shadow: 0px 4px 15px rgba(243, 156, 18, 0.3); }
        100% { box-shadow: 0px 4px 30px rgba(243, 156, 18, 0.7); }
    }

    footer.bg-dark {
        padding: 1rem 0;
        margin-top: 3rem;
    }
</style>
@endpush

<div class="container-fluid">
    <section id="upgrade" class="py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="text-center mb-5">
                    <h2 class="fw-bold mb-3 text-primary">Upgrade to Premium   <i class="fas fa-crown fa-lg" style="color: gold; animation: glow 1.5s infinite alternate;"></i></h2>
                    <p class="text-muted mx-auto" style="max-width: 600px;">
                        Unlock advanced monitoring features and take full control of your website’s uptime.
                    </p>
                </div>

                <div class="row justify-content-center g-4">
                    <div class="col-lg-4">
                        <div class="card h-100 border-secondary shadow-sm position-relative">
                            <div class="ribbon bg-secondary text-white">Your Current Plan</div>
                            <div class="card-body p-4">
                                <h5 class="fw-bold text-primary mb-2">Basic</h5>
                                <div class="text-primary mb-4">
                                    <span class="display-6 fw-bold">₹0</span>
                                </div>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Limited to 5 website monitors</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Email & Telegram Bot alerts</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 1-day history</li>
                                </ul>
                                <br>
                                <button class="btn btn-primary d-block w-100" disabled>Current Plan</button>
                            </div>
                        </div>
                    </div>

                    @foreach($plans as $plan)
                    <div class="col-lg-4">
                        <div class="card h-100 border-warning shadow-lg premium-highlight">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-2 text-warning">{{ $plan->name }}</h5>
                                <div class="text-warning mb-4">
                                    <span class="display-6 fw-bold">₹{{ $plan->amount }}</span>
                                    <span class="text-muted">/month</span>
                                </div>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> All Basic features</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Unlimited website monitoring</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> SSL expiry check</li>
                                </ul>
                                <br>
                                <form id="paymentForm_{{ $plan->id }}" action="{{ route('store') }}" method="POST" target="_blank">
                                    @csrf
                                    <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="mobile" value="{{ auth()->user()->phone }}">
                                    <input type="hidden" name="subscription_id" value="{{ $plan->id }}">
                                    <button type="submit" class="btn btn-warning d-block fw-bold w-100">Upgrade Now</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </section>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form[id^="paymentForm_"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const paymentWindow = window.open('', 'paymentWindow', 'width=600,height=800');
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(Object.fromEntries(new FormData(form)))
            })
            .then(response => response.json())
            .then(data => {
                if (data.payment_link) {
                    paymentWindow.location.href = data.payment_link;
                    
                    // Check payment status every 3 seconds
                    const checkPaymentStatus = setInterval(() => {
                        fetch('/cashfree/payments/status')
                        .then(response => response.json())
                        .then(status => {
                            console.log('status', status);
                            if (status.payment_success && status.payment_end_date!==null) {
                                clearInterval(checkPaymentStatus);
                                paymentWindow.close();
                                window.location.reload(); // Refresh parent page
                            }
                        });
                    }, 3000);
                } else {
                    paymentWindow.close();
                    alert('Error initiating payment. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                paymentWindow.close();
                alert('Error initiating payment. Please try again.');
            });
        });
    });
});
</script>
@endpush


@endsection