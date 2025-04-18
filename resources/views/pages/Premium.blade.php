<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upgrade to Premium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        * {
            border-radius: 0 !important;
        }
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
        }
        .premium-highlight {
            border: 3px solid gold !important;
            box-shadow: 0px 4px 20px rgba(255, 215, 0, 0.6);
            animation: glow 1.5s infinite alternate;
        }
        @keyframes glow {
            0% { box-shadow: 0px 4px 15px rgba(255, 215, 0, 0.3); }
            100% { box-shadow: 0px 4px 30px rgba(255, 215, 0, 0.7); }
        }
        .bg-dark {
            background-color: #212529 !important;
        }
        .text-warning {
            color: #ffc107 !important;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }
        .border-warning {
            border-color: #ffc107 !important;
        }
        .border-secondary {
            border-color: #6c757d !important;
        }
    </style>
     <!-- PWA  -->
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <meta name="theme-color" content="#6777ef">
  <link rel="apple-touch-icon" href="{{ asset('mainlogo.png') }}">
</head>
<body>
    <header class="bg-dark py-3">
        <div class="container text-white text-center">
            <h1>Upgrade Your Plan</h1>
        </div>
    </header>
    
    <section id="upgrade" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3 text-warning">Upgrade to Premium</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">
                    Unlock advanced monitoring features and take full control of your website’s uptime.
                </p>
            </div>
            
            <div class="row justify-content-center g-4">
                <!-- Basic Plan (Current Plan) -->
                <div class="col-lg-4">
                    <div class="card h-100 border-secondary shadow-sm position-relative">
                        <div class="ribbon bg-secondary text-white text-center">Your Current Plan</div>
                        <div class="card-body p-4">
                            <h3 class="fw-bold mb-2">Basic</h3>
                            <div class="text-secondary mb-4">
                                <span class="display-6 fw-bold">₹0</span>
                            </div>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 5 websites</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 1-minute checks</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Email & Telegram Bot alerts</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 1-day history</li>
                            </ul>
                            <button class="btn btn-secondary d-block" disabled>Current Plan</button>
                        </div>
                    </div>
                </div>
                
                <!-- Premium Plan (Highlighted) -->
                @foreach($plans as $plan)
                    <div class="col-lg-4">
                        <div class="card h-100 border-warning shadow-lg premium-highlight">
                            <div class="card-body p-4">
                                <h3 class="fw-bold mb-2 text-warning">{{$plan->name}}</h3>
                                <div class="text-warning mb-4">
                                    <span class="display-6 fw-bold">₹{{$plan->amount}}</span>
                                    <span class="text-muted">/month</span>
                                </div>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Unlimited websites</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 1-minute checks</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Email & Telegram Bot alerts</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> SSL expiry check</li>
                                    {{-- <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Custom integrations</li> --}}
                                </ul>
                                <form action="{{ route('store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="mobile" value="{{ auth()->user()->phone }}">
                                    <input type="hidden" name="subscription_id" value="{{$plan->id}}">
                                    <button type="submit" class="btn btn-warning d-block fw-bold">Upgrade Now</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; 2025 Uptime Monitor. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js', { scope: '/' })
                .then(function (registration) {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch(function (error) {
                    console.error('Service Worker registration failed:', error);
                });
        }
    </script>
</body>
</html>