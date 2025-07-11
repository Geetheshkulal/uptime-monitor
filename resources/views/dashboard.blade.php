<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>@yield('title', 'CheckMySite')</title>

    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <meta name="theme-color" content="#6777ef">
    <link rel="apple-touch-icon" href="{{ asset('mainlogo.png') }}">
    

    <!-- Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js', { scope: '/' })
                .then(reg => console.log('Service Worker registered:', reg.scope))
                .catch(err => console.error('Service Worker registration failed:', err));
        }
    </script>

    <!-- Fonts and styles -->
    <link href="{{ asset('frontend/assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">


    <script>
        
        (function () {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark-mode');
            }
        })();
    </script>
    
    <!-- Skeleton Loader Styles -->
    <style>
        *:not(.status-dot):not(.status-badge,.status-indicator,.bar-segment,.badge,.whatsapp-bubble, .telegram-bubble):not(#login-spinner):not(#profile-photo):not(.icon-circle){
            border-radius: 0 !important;
        }
        * {
            font-family: "Nunito", sans-serif;
        }
        /* Hide Scrollbar but Allow Scrolling */
        #accordionSidebar {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 100;
            overflow-y: scroll; /* Enable vertical scrolling */
            overflow-x: hidden; /* Prevent horizontal scrolling */
            background-color: #4e73df;
            /* Sidebar background color */
            
        }

        /* Hide scrollbar for WebKit Browsers (Chrome, Edge, Safari) */
        #accordionSidebar::-webkit-scrollbar {
            display: none; /* Hides the scrollbar */
        }



        .scroll-to-top {
            width: 40px;
            height: 40px;
            bottom: 20px;
            right: 20px;
        }

        .skeleton {
            position: relative;
            background-color: whitesmoke;
            border-radius: 0.4rem;
           }

        .skeleton.sm { height: 16px; width: 30%; margin-bottom: 8px; }
        .skeleton.md { height: 20px; width: 60%; margin-bottom: 10px; }
        .skeleton.lg { height: 24px; width: 100%; margin-bottom: 12px; }

        .skeleton * {
            visibility: hidden;
        }

        .skeleton::after {
            content: "";
            position: absolute;
            top: 0;
            left: -150px;
            width: 150px;
            height: 100%;
            background: linear-gradient(
                90deg,
                rgba(255,255,255,0) 0%,
                rgba(255,255,255,0.5) 50%,
                rgba(255,255,255,0) 100%
            );
            animation: shimmer 1.2s infinite ease-in-out;
        }

        @keyframes shimmer {
            0% { left: -150px; }
            100% { left: 100%; }
        }
       
 

        #content {
            overflow-y: hidden; 
        }
      
    </style>

<style>
  

.dark-mode body,
body.dark-mode,
.dark-mode #content-wrapper {
    background-color: #181818 !important;
    color: #e0e0e0 !important;
}

.dark-mode .card,
.dark-mode .modal-content,
.dark-mode .dropdown-menu,
.dark-mode .bg-white {
    background-color: #2b2b2b !important;
    color: #ffffff !important;
}

.dark-mode .btn-primary {
    background-color: #3a3f51;
    border-color: #3a3f51;
}

.dark-mode .form-control {
    background-color: #2e2e2e;
    color: white;
    border-color: #444;
}

/* Default (light mode) */
#accordionSidebar {
    background-color: #4e73df;
    /* transition: background-color 0.3s ease; */
}

/* Dark mode version */
body.dark-mode #accordionSidebar {
    background-color: #1e1e2f; /* Or any dark tone */
}

body.dark-mode #accordionSidebar .nav-link,
body.dark-mode #accordionSidebar .nav-link i,
body.dark-mode #accordionSidebar .sidebar-heading,
body.dark-mode .sidebar-brand-text {
    color: #ffffff;
}

body.dark-mode #accordionSidebar {
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
    border-right: 1px solid #333;
}
</style>

@stack('styles')
</head>

<body id="page-top" class="loading">
    <div id="wrapper">
        @include('body.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('body.header')

                <!-- Page Content -->
                @yield('content')
            </div>
            @if(!request()->routeIs('premium.page'))
                @include('body.footer')
            @endif
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ready to Leave?</h5>
                    <button class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <div class="modal-body">Click "Logout" to end your session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Scripts -->
    <script src="{{ asset('frontend/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/demo/chart-area-demo.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    @stack('scripts')

    <!-- Remove skeletons after load -->
    <script>
        window.addEventListener('load', () => {
    setTimeout(() => {
        document.body.classList.remove('loading');
     // Ensure scrolling is enabled

        const skeletons = document.querySelectorAll('.skeleton');
        skeletons.forEach(el => el.classList.remove('skeleton'));
    }, 500); // Delay for loader effect
});
    </script>

    <!-- Push Notification Subscription -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            async function subscribeUser() {
                if ('serviceWorker' in navigator && 'PushManager' in window) {
                    try {
                        const reg = await navigator.serviceWorker.ready;
                        const sub = await reg.pushManager.subscribe({
                            userVisibleOnly: true,
                            applicationServerKey: urlBase64ToUint8Array("{{ env('VAPID_PUBLIC_KEY') }}")
                        });

                        const data = {
                            endpoint: sub.endpoint,
                            keys: {
                                p256dh: btoa(String.fromCharCode.apply(null, new Uint8Array(sub.getKey('p256dh')))),
                                auth: btoa(String.fromCharCode.apply(null, new Uint8Array(sub.getKey('auth'))))
                            }
                        };

                        await fetch('/subscribe', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(data)
                        });

                        console.log("Push subscription successful");
                    } catch (err) {
                        console.error("Push subscription failed:", err);
                    }
                } else {
                    console.warn("Push not supported");
                }
            }

            function urlBase64ToUint8Array(base64String) {
                const padding = '='.repeat((4 - base64String.length % 4) % 4);
                const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
                const raw = atob(base64);
                return Uint8Array.from([...raw].map(c => c.charCodeAt(0)));
            }

            window.subscribeUser = subscribeUser;
            subscribeUser();
        });
    </script>
</body>
</html>