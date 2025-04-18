<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Verification</title>

    <!-- Fonts & Styles -->
    <link href="{{ asset('frontend/assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,700" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#6777ef">
    <link rel="apple-touch-icon" href="{{ asset('mainlogo.png') }}">

</head>
<style>
* {
    border-radius: 0 !important;
 }
</style>

<body class="bg-gradient-primary d-flex align-items-center justify-content-center min-vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8 col-md-10">
                <div class="card o-hidden border-0 shadow-lg">
                    <div class="card-body p-0">
                        <div class="p-4">
                            <div class="text-center">
                                <h1 class="login-text">Verify Your Email</h1>
                                <p class="login-subtext">
                                    Thanks for signing up! Please verify your email by clicking the link we just sent. <br>
                                    If you didn’t receive it, we’ll gladly send another.
                                </p>
                            </div>

                            @if (session('status') == 'verification-link-sent')
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    A new verification link has been sent to your email address.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Resend Verification Email
                                </button>
                            </form>

                            <hr>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-link btn-block text-sm text-gray-600 dark:text-gray-400">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('frontend/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/sb-admin-2.min.js') }}"></script>

    <!--PWA-->
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
