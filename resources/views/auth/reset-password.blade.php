<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Reset your password">
    <meta name="author" content="Your App">

    <title>Reset Password</title>

    <!-- Fonts and Icons -->
    <link href="{{ asset('frontend/assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- SB Admin 2 CSS -->
    <link href="{{ asset('frontend/assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            background: #4e73df;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
        }

        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
            border-radius: 10rem;
            padding: 0.75rem 1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
            transform: translateY(-1px);
        }

        .btn-primary:focus {
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.5);
        }

        .form-control-user {
            border-radius: 10rem;
            padding: 1rem 1.25rem;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .form-control-user:focus {
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .login-text {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #4e73df;
            font-weight: 700;
        }

        .custom-back-button {
            top: 15px;
            left: 20px;
            z-index: 1;
            position: absolute;
        }

        .custom-btn-lg {
            width: 50px;
            height: 50px;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-input-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6e707e;
        }

        * {
            border-radius: 0 !important;
        }

        @media (max-width: 768px) {
            .card {
                margin: 1rem;
            }

            .form-control-user {
                padding: 0.8rem 1.1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="col-xl-8 col-lg-10 col-md-10">
            <div class="card o-hidden border-0 shadow-lg">
                <div class="card-body p-5 position-relative">
                    {{-- back button --}}
                    <div class="custom-back-button position-absolute">
                        <a href="/" class="btn btn-circle btn-light custom-btn-lg">
                            <i class="fa-solid fa-arrow-left text-primary"></i>
                        </a>
                    </div>

                    <div class="text-center mb-4">
                        <h1 class="login-text">Reset Password</h1>
                    </div>

                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email -->
                        <div class="form-group mb-4">
                            <input type="email" id="email" name="email" class="form-control form-control-user"
                                placeholder="Email Address" value="{{ old('email', $request->email) }}" required autofocus>
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- New Password -->
                        <div class="form-group mb-4">
                            <div class="password-input-container">
                                <input type="password" id="password" name="password"
                                    class="form-control form-control-user" placeholder="New Password" required>
                                <span class="password-toggle" onclick="togglePassword('password')">
                                    <i class="far fa-eye"></i>
                                </span>
                            </div>
                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group mb-4">
                            <div class="password-input-container">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control form-control-user" placeholder="Confirm Password" required>
                                <span class="password-toggle" onclick="togglePassword('password_confirmation')">
                                    <i class="far fa-eye"></i>
                                </span>
                            </div>
                            @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-user btn-block mt-4">
                            Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('frontend/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>