<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>CheckMySite - Website Monitoring Service</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    .gradient-bg {
      background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%);
    }
    .pulse-animation {
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0% {
        transform: scale(1);
        opacity: 1;
      }
      50% {
        transform: scale(1.05);
        opacity: 0.8;
      }
      100% {
        transform: scale(1);
        opacity: 1;
      }
    }
    .hero-section {
      padding-top: 120px;
      padding-bottom: 80px;
    }
    .feature-icon {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1rem;
    }
    .testimonial-avatar {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .popular-plan {
      position: relative;
      border: 2px solid #0d6efd;
      border-radius: 0.375rem;
    }
    .popular-badge {
      position: absolute;
      top: -12px;
      left: 50%;
      transform: translateX(-50%);
    }
    .step-circle {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
      font-size: 1.5rem;
      font-weight: bold;
    }
    .btn {
  border-radius: 0 !important;
}
    
  </style>
  <!-- PWA  -->
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <meta name="theme-color" content="#6777ef">
  <link rel="apple-touch-icon" href="{{ asset('mainlogo.png') }}">
</head>
<body>

  <script src="{{ asset('js/notification.js') }}"></script>
  <!-- Navigation -->
  <!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
  <div class="container">
    <a class="navbar-brand text-primary fw-bold" href="/">
      <i class="fas fa-heartbeat me-2"></i>CheckMySite
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-expanded="false" aria-controls="navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="#features">Features</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#how-it-works">How It Works</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#pricing">Pricing</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('documentation.page')}}">Documentation</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('latest.page')}}">Latest updates</a>
        </li>
        <li class="nav-item">
          <button class="nav-link"  data-feedbear-button>Give Feedback</button>
        </li>
        <button onclick="subscribeUser()">Subscribe</button> 
        <button onclick="sendNotification()">Send Push Notification</button>    
        
      </ul>
      @if (Route::has('login'))      
      <div class="d-flex">
      @auth 
        @hasrole('superadmin')
          <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Dashboard</a>
        @else
          <a href="{{ route('monitoring.dashboard') }}" class="btn btn-primary">Dashboard</a>
        @endhasrole
      
      @else  
      <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
      &nbsp;
      &nbsp;
      @if (Route::has('register'))  
      <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
      @endif
      @endauth
      </div>
      @endif
    </div>
  </div>
</nav>

  <!-- Hero Section -->
  <section class="gradient-bg hero-section text-white">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 text-center text-lg-start">
          <h1 class="display-4 fw-bold mb-4">Never Miss a Website Downtime Again</h1>
          <p class="lead mb-4">Get instant alerts when your websites go down. Monitor performance, uptime, and response times with CheckMySite's powerful monitoring tools.</p>
          <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3">
            {{-- <a href="{{ route('login') }}" class="btn btn-light btn-lg text-primary fw-bold">Start Monitoring</a>
             --}}
             @if(auth()->check())
                @hasrole('superadmin')
                  <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-lg text-primary fw-bold">
                      Start Monitoring
                  </a>
                @else
                  <a href="{{ route('monitoring.dashboard') }}" class="btn btn-light btn-lg text-primary fw-bold">
                      Start Monitoring
                  </a>
                @endhasrole
            @else
                <a href="{{ route('login') }}" class="btn btn-light btn-lg text-primary fw-bold">
                    Start Monitoring
                </a>
            @endif

          </div>
        </div>
        <div class="col-lg-6">
          <!-- Hero image would go here -->
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold mb-3">Powerful Monitoring Features</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Everything you need to keep your websites and services running smoothly, all in one place.</p>
      </div>
      
      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm">
            <div class="card-body">
              <div class="feature-icon bg-primary bg-opacity-10">
                <i class="fas fa-clock text-primary fs-4"></i>
              </div>
              <h3 class="h5 fw-bold">24/7 Monitoring</h3>
              <p class="text-muted">We check your websites every minute from multiple locations around the world.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm">
            <div class="card-body">
              <div class="feature-icon bg-primary bg-opacity-10">
                <i class="fas fa-bell text-primary fs-4"></i>
              </div>
              <h3 class="h5 fw-bold">Instant Alerts</h3>
              <p class="text-muted">Get notified via email, SMS, Slack, Discord, or webhook when your sites go down.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm">
            <div class="card-body">
              <div class="feature-icon bg-primary bg-opacity-10">
                <i class="fas fa-chart-line text-primary fs-4"></i>
              </div>
              <h3 class="h5 fw-bold">Detailed Analytics</h3>
              <p class="text-muted">Track response times, uptime percentage, and performance metrics over time.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm">
            <div class="card-body">
              <div class="feature-icon bg-primary bg-opacity-10">
                <i class="fas fa-globe text-primary fs-4"></i>
              </div>
              <h3 class="h5 fw-bold">Global Monitoring</h3>
              <p class="text-muted">Check from multiple regions to ensure your site is accessible everywhere.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm">
            <div class="card-body">
              <div class="feature-icon bg-primary bg-opacity-10">
                <i class="fas fa-shield-alt text-primary fs-4"></i>
              </div>
              <h3 class="h5 fw-bold">SSL expiry check</h3>
              <p class="text-muted">Get alerted before your SSL certificates expire to avoid security warnings.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-light shadow-sm">
            <div class="card-body">
              <div class="feature-icon bg-primary bg-opacity-10">
                <i class="fas fa-code text-primary fs-4"></i>
              </div>
              <h3 class="h5 fw-bold">API Access</h3>
              <p class="text-muted">Integrate our monitoring data directly into your applications and dashboards.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section id="how-it-works" class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold mb-3">How CheckMySite Works</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Simple setup, powerful results. Get started in less than 2 minutes.</p>
      </div>
      
      <div class="row">
        <div class="col-md-4 text-center mb-4 mb-md-0">
          <div class="step-circle bg-primary text-white">1</div>
          <h3 class="h5 fw-bold">Add Your Websites</h3>
          <p class="text-muted">Enter your website URLs and set your preferred check frequency.</p>
        </div>
        
        <div class="col-md-4 text-center mb-4 mb-md-0">
          <div class="step-circle bg-primary text-white">2</div>
          <h3 class="h5 fw-bold">Configure Alerts</h3>
          <p class="text-muted">Choose how you want to be notified when issues are detected.</p>
        </div>
        
        <div class="col-md-4 text-center">
          <div class="step-circle bg-primary text-white">3</div>
          <h3 class="h5 fw-bold">Relax & Stay Informed</h3>
          <p class="text-muted">We'll monitor your sites 24/7 and alert you if anything goes wrong.</p>
        </div>
      </div>
    </div>
  </section>



  <!-- Pricing Section -->
  <section id="pricing" class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold mb-3">Simple, Transparent Pricing</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Choose the plan that fits your needs. All plans include our core monitoring features.</p>
      </div>
      
      <div class="row justify-content-center g-4">
        <div class="col-lg-4">
          <div class="card h-100 border-light shadow-sm">
            <div class="card-body p-4">
              <h3 class="fw-bold mb-2">Basic</h3>
              <div class="text-primary mb-4">
                <span class="display-6 fw-bold">₹0</span>
              </div>
              <ul class="list-unstyled mb-4">
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 10 websites</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 5-minute checks</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Email alerts</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 1-day history</li>
              </ul>
              @if(auth()->check())
    <a href="{{ route('monitoring.dashboard') }}" class="btn btn-primary d-block">
      Get Started
    </a>
@else
    <a href="{{ route('login') }}" class="btn btn-primary d-block">
      Get Started
    </a>
@endif

            </div>
          </div>
        </div>
        
       
      
        <div class="col-lg-4">
          <div class="card h-100 border-light shadow-sm">
            <div class="card-body p-4">
              @foreach($plans as $plan) 
            <h3 class="fw-bold mb-2">{{ $plan->name }}</h3>
              <div class="text-primary mb-4">
                <span class="display-6 fw-bold">₹{{ $plan->amount }}</span>
                <span class="text-muted">/month</span>
              </div>
              @endforeach
              <ul class="list-unstyled mb-4">
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Unlimited websites</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 30-second checks</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> All alert channels</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> SSL expiry check</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Custom integrations</li>
              </ul>
              <a href="#" class="btn btn-primary d-block">Get Started</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>



  <!-- CTA Section -->
  <section class="py-5 gradient-bg text-white">
    <div class="container">
      <div class="row justify-content-center text-center">
        <div class="col-lg-8">
          <h2 class="display-5 fw-bold mb-4">Ready to Monitor Your Websites?</h2>
          <p class="lead mb-5">Join thousands of businesses that trust CheckMySite to keep their websites and services running smoothly.</p>
          <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            <a href="{{ route('login') }}" class="btn btn-light btn-lg text-primary fw-bold px-4">Start Your Free Trial</a>
            {{-- <a href="#" class="btn btn-outline-light btn-lg px-4">Schedule a Demo</a> --}}
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Footer -->
<footer class="bg-dark text-light py-5">
  <div class="container">
    <div class="row gy-4">
      
      <!-- Company Info -->
      <div class="col-lg-3 col-md-6">
        <h3 class="h5 fw-bold mb-3">CheckMySite</h3>
        <p class="mb-3">Enterprise-grade website monitoring for businesses of all sizes.</p>
        <div class="d-flex gap-3">
          <a href="#" class="text-light"><i class="fab fa-twitter"></i></a>
          <a href="#" class="text-light"><i class="fab fa-facebook"></i></a>
          <a href="#" class="text-light"><i class="fab fa-linkedin"></i></a>
          <a href="#" class="text-light"><i class="fab fa-github"></i></a>
        </div>
      </div>

      <!-- Product Section -->
      {{-- <div class="col-lg-3 col-md-6">
        <h3 class="h5 fw-bold mb-3">Product</h3>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#features" class="text-light text-decoration-none">Features</a></li>
          <li class="mb-2"><a href="#pricing" class="text-light text-decoration-none">Pricing</a></li>
          <li class="mb-2"><a href="#" class="text-light text-decoration-none">Integrations</a></li>
          <li class="mb-2"><a href="#" class="text-light text-decoration-none">API</a></li>
          <li class="mb-2"><a href="#" class="text-light text-decoration-none">Status</a></li>
        </ul>
      </div> --}}
      <div class="col-lg-4 col-md-4">
        <h3 class="h6 fw-bold mb-3">Product</h3>
        <div class="d-flex flex-wrap gap-3">
          <a href="#features" class="text-light text-decoration-none">Features</a>
          <a href="#pricing" class="text-light text-decoration-none">Pricing</a>
          <a href="#" class="text-light text-decoration-none">Integrations</a>
          <a href="#" class="text-light text-decoration-none">API</a>
          <a href="#" class="text-light text-decoration-none">Status</a>
        </div>
      </div>
     

    </div>

    <!-- Footer Divider -->
    <hr class="my-4 bg-secondary">

    <!-- Copyright & Legal Links -->
    <div class="row align-items-center">
      <div class="col-md-6 text-center text-md-start">
        <p class="mb-md-0">© 2025 CheckMySite. All rights reserved.</p>
      </div>
      <div class="col-md-6 text-center text-md-end">
        <a href="#" class="text-light text-decoration-none me-3">Privacy Policy</a>
        <a href="#" class="text-light text-decoration-none">Terms of Service</a>
      </div>
    </div>

  </div>
</footer>
  <!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('/sw.js') }}"></script>
<script>
   if ("serviceWorker" in navigator) {
      // Register a service worker hosted at the root of the
      // site using the default scope.
      navigator.serviceWorker.register("/sw.js",{ scope: "/" }).then(
      (registration) => {
         console.log("Service worker registration succeeded:", registration);
      },
      (error) => {
         console.error(Service worker registration failed: ${error});
      },
    );
  } else {
     console.error("Service workers are not supported.");
  }
</script>

<script>
  document.addEventListener("DOMContentLoaded", () => {
      async function subscribeUser() {
          if ('serviceWorker' in navigator && 'PushManager' in window) {
              try {
                  // ✅ Register service worker
                  const register = await navigator.serviceWorker.register('/sw.js');

                  // ✅ Subscribe for push notifications
                  const subscription = await register.pushManager.subscribe({
                      userVisibleOnly: true,
                      applicationServerKey: urlBase64ToUint8Array("{{ env('VAPID_PUBLIC_KEY') }}")
                  });

                  // ✅ Send subscription to backend
                  await fetch('/subscribe', {
                      method: 'POST',
                      body: JSON.stringify(subscription),
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': '{{ csrf_token() }}'
                      }
                  });

                  alert('Subscribed to Push Notifications!');
              } catch (error) {
                  console.error('Subscription failed:', error);
              }
          } else {
              alert("Your browser does not support push notifications.");
          }
      }

      // Utility function to convert base64 to Uint8Array
      function urlBase64ToUint8Array(base64String) {
          const padding = '='.repeat((4 - base64String.length % 4) % 4);
          const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
          const rawData = window.atob(base64);
          const outputArray = new Uint8Array(rawData.length);
          for (let i = 0; i < rawData.length; ++i) {
              outputArray[i] = rawData.charCodeAt(i);
          }
          return outputArray;
      }

      window.subscribeUser = subscribeUser; // Expose to global scope
  });
</script>

<script>
  function sendNotification() {
      fetch('/send-notification', {
          method: 'POST',
          headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
       }
      })
      .then(async res => {
          const isJson = res.headers.get("content-type")?.includes("application/json");
          const data = isJson ? await res.json() : null;

          if (!res.ok) {
              console.error('Server returned error:', data);
              alert("Notification failed. Server error.");
              return;
          }

          if (data?.success) {
              alert("Notification sent successfully!");
          } else {
              alert("Failed: " + (data?.error ?? "Unknown error"));
          }
      })
      .catch(err => {
          console.error('Fetch failed:', err);
          alert("Something went wrong.");
      });
  }
</script>


 {{-- for feedbear --}}
<script>
  (function (w, d, s, o, f, js, fjs) { w[o] = w[o] || function () { (w[o].q = w[o].q || []).push(arguments) }; js = d.createElement(s), fjs = d.getElementsByTagName(s)[0]; js.id = o; js.src = f; js.async = 1; fjs.parentNode.insertBefore(js, fjs); }(window, document, 'script', 'FeedBear', 'https://sdk.feedbear.com/widget.js'));
  FeedBear("button", {
  element: document.querySelector("[data-feedbear-button]"),
  project: "check-my-site",
  board: "feature-requests",
  jwt: null // see step 3,
  });
</script>

<script>
  (function (w, d, s, o, f, js, fjs) { w[o] = w[o] || function () { (w[o].q = w[o].q || []).push(arguments) }; js = d.createElement(s), fjs = d.getElementsByTagName(s)[0]; js.id = o; js.src = f; js.async = 1; fjs.parentNode.insertBefore(js, fjs); }(window, document, 'script', 'FeedBear', 'https://sdk.feedbear.com/widget.js'));
  FeedBear("button", {
  element: document.querySelector("[data-feedbear-button]"),
  project: "check-my-site",
  boards: "feature-requests",
  jwt: null // see step 3,
  });
 </script>


  </body>
</html>