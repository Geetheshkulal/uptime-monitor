<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>

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
                            <div class="text-center mb-4">
                                <h1 class="login-text">Reset Password</h1>
                            </div>

                            <form method="POST" action="{{ route('password.store') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-bold">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $request->email) }}" required autofocus>
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label fw-bold">New Password</label>
                                    <input type="password" id="password" name="password" class="form-control" required>
                                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label fw-bold">Confirm Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                                    @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Reset Password
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
