<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Forgot your password">
    <meta name="author" content="Your App">

    <title>Forgot Password</title>

    <!-- Custom fonts -->
    <link href="{{ asset('frontend/assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('frontend/assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        .bg-password-image {
            background: url('{{ asset('frontend/assets/img/login-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            min-height: 300px;
        }
        
        .card {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: 2rem auto;
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
        
        .password-text {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #4e73df;
            font-weight: 700;
        }
        
        .password-subtext {
            color: #858796;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        
        .password-footer {
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #858796;
        }
        
        .password-footer a {
            color: #4e73df;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .password-footer a:hover {
            color: #2e59d9;
            text-decoration: underline;
        }
        
        .divider {
            position: relative;
            text-align: center;
            margin: 1.5rem 0;
            font-size: 0.8rem;
            color: #b7b9cc;
        }
        
        .divider:before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e3e6f0;
            z-index: 0;
        }
        
        .divider span {
            position: relative;
            display: inline-block;
            padding: 0 12px;
            background: white;
            z-index: 1;
        }
        
        @media (max-width: 768px) {
            .bg-password-image {
                min-height: 200px;
                border-radius: 1rem 1rem 0 0;
            }
            
            .card {
                margin: 1rem;
            }
            
            .form-control-user {
                padding: 0.8rem 1.1rem;
            }
        }
    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-5 d-none d-lg-block bg-password-image"></div>
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="password-text">Forgot Password?</h1>
                                        <p class="password-subtext">Enter your email and we'll send you a reset link</p>
                                    </div>

                                    @if(session('status'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('status') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif

                                    <form class="user" method="POST" action="{{ route('password.email') }}">
                                        @csrf
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" 
                                                id="email" name="email" placeholder="Email Address" 
                                                value="{{ old('email') }}" required autofocus>
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block mt-4" id="reset-btn">
                                            <span class="spinner-border spinner-border-sm d-none" id="reset-spinner"></span>
                                            <span id="reset-text">Send Reset Link</span>
                                        </button>

                                        <div class="divider">
                                            <span>OR</span>
                                        </div>

                                        <div class="text-center password-footer">
                                            <div class="mb-2">
                                                <a href="{{ route('login') }}">Already have an account? Login!</a>
                                            </div>
                                            <div>
                                                <a href="{{ route('register') }}">Create an Account!</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const resetForm = document.querySelector('form');
            const resetBtn = document.getElementById('reset-btn');
            const resetText = document.getElementById('reset-text');
            const resetSpinner = document.getElementById('reset-spinner');

            resetForm.addEventListener('submit', () => {
                resetBtn.disabled = true;
                resetSpinner.classList.remove('d-none');
                resetText.textContent = 'Sending...';
            });
        });
    </script>

</body>
</html>