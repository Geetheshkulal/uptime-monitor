<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Login to your dashboard">
    <meta name="author" content="Your App">

    <title>Login</title>
    <script async src="https://www.google.com/recaptcha/api.js"></script>

    <!-- Custom fonts -->
    <link href="{{asset('frontend/assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{asset('frontend/assets/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        .bg-login-image {
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
        
        .login-text {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #4e73df;
            font-weight: 700;
        }
        
        .login-subtext {
            color: #858796;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        
        .login-footer {
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #858796;
        }
        
        .login-footer a {
            color: #4e73df;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .login-footer a:hover {
            color: #2e59d9;
            text-decoration: underline;
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

        .login-image {
        /* height: 70%;
        min-height: 400px;
        object-fit: cover;
        padding-left: 23px;
        margin-top: 53px;
        font-size: large; */
        width: 381px;       
        height: 376px;       
        object-fit: cover;   
        padding-left: 23px;
        margin-top: 53px;
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
* {
    border-radius: 0 !important;
}
        @media (max-width: 768px) {
            .bg-login-image {
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
                        {{-- back button --}}
                        <div class="custom-back-button position-absolute">
                            <a href="/" class="btn btn-circle btn-light custom-btn-lg">
                                <i class="fa-solid fa-arrow-left text-primary"></i>
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-lg-5 d-none d-lg-block p-0">
                                <img src="{{ asset('frontend/assets/img/login.jpg') }}" alt="Login Image" class="img-fluid login-image" />
                            </div>
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="login-text">Welcome Back!</h1>
                                        <p class="login-subtext">Please login to access your account</p>
                                    </div>

                                    @if(session('status'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('status') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif

                                    <form class="user" method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" 
                                                id="email" name="email" placeholder="Email Address" 
                                                value="{{ old('email', request()->cookie('remember_email')) }}" required autofocus>
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <div class="password-input-container">
                                                <input type="password" class="form-control form-control-user"
                                                    id="password" name="password" placeholder="Password" value="{{ request()->cookie('remember_password') }}" required>
                                                <span class="password-toggle" onclick="togglePassword('password')">
                                                    <i class="far fa-eye"></i>
                                                </span>
                                            </div>
                                            @error('password')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="g-recaptcha mt-4" data-sitekey={{config('services.recaptcha.key')}}></div>
                                        @error('g-recaptcha-response')
                                            <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                        @enderror


                                        <div class="form-group d-flex justify-content-between align-items-center">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="remember_me" name="remember" {{ request()->cookie('remember_email') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="remember_me">Remember Me</label>
                                            </div>
                                            @if (Route::has('password.request'))
                                                <a class="small text-primary" href="{{ route('password.request') }}">Forgot Password?</a>
                                            @endif
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block mt-4" id="login-btn">
                                            <span class="spinner-border spinner-border-sm d-none" id="login-spinner"></span>
                                            <span id="login-text">Login</span>
                                        </button>

                                        <div class="divider">
                                            <span>OR</span>
                                        </div>

                                        @if (Route::has('register'))
                                            <div class="text-center login-footer">
                                                Don't have an account? <a href="{{ route('register') }}">Sign up here</a>
                                            </div>
                                        @endif
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
    <script src="{{asset('frontend/assets/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('frontend/assets/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/sb-admin-2.min.js')}}"></script>
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

        document.addEventListener('DOMContentLoaded', () => {
            const loginForm = document.querySelector('form');
            const loginBtn = document.getElementById('login-btn');
            const loginText = document.getElementById('login-text');
            const loginSpinner = document.getElementById('login-spinner');

            loginForm.addEventListener('submit', () => {
                loginBtn.disabled = true;
                loginSpinner.classList.remove('d-none');
                loginText.textContent = 'Authenticating...';
            });
        });
    </script>

</body>
</html>