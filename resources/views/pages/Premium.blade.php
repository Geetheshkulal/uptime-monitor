@extends('dashboard')

@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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

<!-- Apply Coupon Button -->
<div class="text-end mb-3 me-3">
{{-- <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#applyCouponModal">
        Apply Coupon
    </button> --}}

    <button id="couponActionBtn" class="btn btn-outline-primary" 
            data-bs-toggle="modal" data-bs-target="#applyCouponModal"
            data-action="apply">
        @if(session('applied_coupon'))
            Remove Coupon
        @else
            Apply Coupon
        @endif
    </button>

</div>

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
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Monitor 5 websites</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 5-minute check</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Email alerts</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 1-Month history</li>
                                    <li class="mb-3"><i class="fas fa-times text-danger me-2"></i> Telegram bot alert unavailable</li>
                                    <li class="mb-3"><i class="fas fa-times text-danger me-2"></i> SSL expiry check unavailable</li>
                                </ul>
                                <br>
                                <button class="btn btn-primary d-block w-100" disabled>Current Plan</button>
                            </div>
                        </div>
                    </div>

                    @php
                         $appliedCoupon = session('applied_coupon');
                    @endphp


                    @foreach($plans as $plan)
                    <div class="col-lg-4">
                        <div class="card h-100 border-warning shadow-lg premium-highlight">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-2 text-warning">{{ $plan->name }}</h5>
                                <div class="text-warning mb-4">

                                @php
                                    $originalPrice = $plan->amount;
                                    $discount = $appliedCoupon['discount'] ?? 0;
                                    $finalPrice = max(0, $originalPrice - $discount);
                                @endphp
                
                                @if($discount > 0)
                                    <span class="display-6 fw-bold">
                                        <del>₹{{ number_format($originalPrice, 2) }}</del>
                                        ₹{{ number_format($finalPrice, 2) }}
                                    </span>

                                    <span class="text-muted">/month</span>
                            
                                @else
                                    {{-- <span class="display-6 fw-bold">₹{{ number_format($originalPrice, 2) }}</span> --}}
                                    <span class="display-6 fw-bold" data-original="{{ $plan->amount }}">₹{{ session('applied_coupon') ? ($plan->amount - session('applied_coupon.discount')) : $plan->amount }}</span>
                                    <span class="text-muted">/month</span>
                                @endif
                               
                                    {{-- <span class="display-6 fw-bold">₹{{ $plan->amount }}</span>
                                    <span class="text-muted">/month</span> --}}
                                </div>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> All Basic features</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Unlimited website monitoring</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 1-minute check</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Telegram bot alerts</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 4-Month history</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> SSL expiry check</li>
                                </ul>
                                <br>

                                @if(session('applied_coupon'))
                                    <div class="applied-coupon-msg text-success fw-bold mb-2">
                                    You applied coupon code "{{ session('applied_coupon.code') }}"
                                    </div>
                                @else
                                    <div class="applied-coupon-msg text-success fw-bold mb-2" style="display: none;"></div>
                                @endif

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


<!-- Coupon Modal -->
<div class="modal fade" id="applyCouponModal" tabindex="-1" aria-labelledby="couponModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="applyCouponForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="couponModalLabel">Enter Coupon Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="coupon_code" class="form-control" placeholder="Enter code" required>
                    <div id="couponMessage" class="text-danger mt-2"></div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <div id="removeCouponWrapper" class="{{ session('applied_coupon') ? '' : 'd-none' }}">
                        <button type="button" id="removeCouponBtn" class="btn btn-danger">Remove Coupon</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

  

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>


<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right", // or any other position
        "timeOut": "3000"
    };

var count = 200;
var defaults = {
  origin: { y: 0.7 }
};

function fire(particleRatio, opts) {
  confetti({
    ...defaults,
    ...opts,
    particleCount: Math.floor(count * particleRatio)
  });
}

function runConfettiPopper() {
  fire(0.25, {
    spread: 26,
    startVelocity: 55,
  });
  fire(0.2, {
    spread: 60,
  });
  fire(0.35, {
    spread: 100,
    decay: 0.91,
    scalar: 0.8
  });
  fire(0.1, {
    spread: 120,
    startVelocity: 25,
    decay: 0.92,
    scalar: 1.2
  });
  fire(0.1, {
    spread: 120,
    startVelocity: 45,
  });
}
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const couponActionBtn = document.getElementById('couponActionBtn');
        const couponForm = document.getElementById('applyCouponForm');
        const message = document.getElementById('couponMessage');
        const removeWrapper = document.getElementById('removeCouponWrapper');
        const removeBtn = document.getElementById('removeCouponBtn');
    
        // Initialize button state
        @if(session('applied_coupon'))
            couponActionBtn.textContent = 'Remove Coupon';
            couponActionBtn.setAttribute('data-action', 'remove');
            couponActionBtn.removeAttribute('data-bs-toggle');
            couponActionBtn.removeAttribute('data-bs-target');
        @endif
    
        couponForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const code = this.coupon_code.value;
    
            fetch('/apply-coupon', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                   
                    document.querySelectorAll('.premium-highlight .display-6').forEach(el => {
                        const original = parseFloat(el.getAttribute('data-original'));
                        el.innerHTML = `<del>₹${original.toFixed(2)}</del> ₹${(original - data.discount).toFixed(2)}`;
                    });
    
                    document.querySelectorAll('.premium-highlight .applied-coupon-msg').forEach(el => {
                        el.style.display = 'block';
                        el.textContent = `You applied coupon code "${code}"`;
                    });
    
                   
                    couponActionBtn.textContent = 'Remove Coupon';
                    couponActionBtn.setAttribute('data-action', 'remove');
                    couponActionBtn.removeAttribute('data-bs-toggle');
                    couponActionBtn.removeAttribute('data-bs-target');
                    
                    const modal = bootstrap.Modal.getInstance(document.getElementById('applyCouponModal'));
                    modal.hide();
                    toastr.success(data.message);
                    runConfettiPopper();
                } else {
                    message.classList.remove('text-success');
                    message.classList.add('text-danger');
                    message.textContent = data.message;
                }
            });
        });
    
        
        couponActionBtn.addEventListener('click', function(e) {
            if (this.getAttribute('data-action') === 'remove') {
                e.preventDefault();
                removeCoupon();
            }
            // If action is 'apply', default modal behavior will occur
        });
    
        // Remove coupon function
        function removeCoupon() {
            fetch('/remove-coupon', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                   
                    document.querySelectorAll('.premium-highlight .display-6').forEach(el => {
                        const original = parseFloat(el.getAttribute('data-original'));
                        el.innerHTML = `₹${original.toFixed(2)}`;
                    });
    
                    document.querySelectorAll('.premium-highlight .applied-coupon-msg').forEach(el => {
                        el.style.display = 'none';
                        el.textContent = '';
                    });
    
                    // Reset button
                    couponActionBtn.textContent = 'Apply Coupon';
                    couponActionBtn.setAttribute('data-action', 'apply');
                    couponActionBtn.setAttribute('data-bs-toggle', 'modal');
                    couponActionBtn.setAttribute('data-bs-target', '#applyCouponModal');
                    
                    toastr.success(data.message);
                    window.location.reload(); 
                }
            });
        }
    
        removeBtn.addEventListener('click', removeCoupon);
    });
</script>

{{-- // don't remove this code, want for backup --}}

{{-- <script>
    document.addEventListener('DOMContentLoaded', () => {
        const couponForm = document.getElementById('applyCouponForm');
        const message = document.getElementById('couponMessage');
        const removeWrapper = document.getElementById('removeCouponWrapper');
        const removeBtn = document.getElementById('removeCouponBtn');

        // If coupon is already applied (from server-side), show remove button
        @if(session('applied_coupon'))
            removeWrapper.classList.remove('d-none');
            message.classList.remove('text-danger');
            message.classList.add('text-success');
            message.textContent = 'Coupon already applied.';
        @endif


        couponForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const code = this.coupon_code.value;

            fetch(`/apply-coupon`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        
                        document.querySelectorAll('.premium-highlight .display-6').forEach(el => {
                            const original = parseFloat(el.getAttribute('data-original'));
                            el.innerHTML = `<del>₹${original.toFixed(2)}</del> ₹${(original - data.discount).toFixed(2)}`;
                        });

                        document.querySelectorAll('.premium-highlight .applied-coupon-msg').forEach(el => {
                            el.style.display = 'block';
                            el.textContent = `You applied coupon code "${code}"`;
                        });


                        message.classList.remove('text-danger');
                        message.classList.add('text-success');
                        message.textContent = 'Coupon applied successfully!';
                        removeWrapper.classList.remove('d-none');

                        toastr.success(data.message);
                
                        setTimeout(()=>{

                        const modal = bootstrap.Modal.getInstance(document.getElementById('applyCouponModal'));
                        modal.hide();
                        }, 2000);
                        
                        runConfettiPopper();

                    } else {
                        message.classList.remove('text-success');
                        message.classList.add('text-danger');
                        message.textContent = data.message;
                    }
                });
        });

        removeBtn.addEventListener('click', function () {
            fetch(`/remove-coupon`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                        
                        setTimeout(()=>{
                    const modal = bootstrap.Modal.getInstance(document.getElementById('applyCouponModal'));
                        modal.hide();
                    }, 1000);

                        document.querySelectorAll('.premium-highlight .display-6').forEach(el => {
                            const original = parseFloat(el.getAttribute('data-original'));
                            el.innerHTML = `₹${original.toFixed(2)}`;
                        });

                        document.querySelectorAll('.premium-highlight .applied-coupon-msg').forEach(el => {
                             el.style.display = 'none';
                             el.textContent = '';
                        });
                    
                        window.location.reload(); 
                        
                    }
                });
        });
    });
</script> --}}
    


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
                            if (status.payment_success && status.payment_end_date!==null && status.status==='paid') {
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