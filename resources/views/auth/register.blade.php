<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Register for an account">
    <meta name="author" content="Your App">

    <title>Register </title>

    <!-- Custom fonts -->
    <link href="{{asset('frontend/assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{asset('frontend/assets/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
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
        
        .register-text {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #4e73df;
            font-weight: 700;
        }
        
        .register-subtext {
            color: #858796;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        
        .register-footer {
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #858796;
        }
        
        .register-footer a {
            color: #4e73df;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .register-footer a:hover {
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
        
        .password-strength {
            height: 4px;
            width: 100%;
            background: #e9ecef;
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease, background 0.3s ease;
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
        
        .password-row {
            display: flex;
            gap: 15px;
        }
        
        .password-col {
            flex: 1;
        }
        .regsiter-image{
            margin-top: 80px;
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
        
        @media (max-width: 768px) {
            .card {
                margin: 1rem;
            }
            
            .form-control-user {
                padding: 0.8rem 1.1rem;
            }
            
            .password-row {
                flex-direction: column;
                gap: 0;
            }
        }
        * {
    border-radius: 0 !important;
}
    </style>

<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#6777ef">
<link rel="apple-touch-icon" href="{{ asset('mainlogo.png') }}">

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
                            <div class="col-lg-5 d-none d-lg-block bg-register-image">
                                <img src="{{ asset('frontend/assets/img/register.jpg') }}" alt="Login Image" class="img-fluid regsiter-image" />
                            </div>
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="register-text">Create an Account!</h1>
                                        <p class="register-subtext">Fill in your details to get started</p>
                                    </div>

                                    <form class="user" method="POST" action="{{ route('register') }}">
                                        @csrf
                                        
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" 
                                                id="name" name="name" placeholder="Full Name" 
                                                value="{{ old('name') }}" required autofocus>
                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" 
                                                id="email" name="email" placeholder="Email Address" 
                                                value="{{ old('email') }}" required>
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        
                                    
                                        <div class="form-group">
                                        <div class="input-group">
                                            @if(isset($dialCode))
                                                <div class="input-group-prepend">
                                                     <input type="hidden" name="country_code" value="{{ $dialCode }}">
                                                    <span class="input-group-text">{{ $dialCode }}</span>
                                                </div>
                                            @endif
                                            <input type="text" class="form-control form-control-user" 
                                                id="phone" name="phone" placeholder="Phone Number" 
                                                value="{{ old('phone') }}" required>
                                        </div>
                                            @error('phone')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="password-row">
                                                <div class="password-col">
                                                    <div class="password-input-container">
                                                        <input type="password" class="form-control form-control-user"
                                                            id="password" name="password" placeholder="Password" required
                                                            onkeyup="checkPasswordStrength(this.value)">
                                                        <span class="password-toggle" onclick="togglePassword('password')">
                                                            <i class="far fa-eye"></i>
                                                        </span>
                                                    </div>
                                                    <div class="password-strength">
                                                        <div class="password-strength-bar" id="password-strength-bar"></div>
                                                    </div>
                                                    <small id="password-strength-text" class="text-muted"></small>
                                                    @error('password')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="password-col">
                                                    <div class="password-input-container">
                                                        <input type="password" class="form-control form-control-user"
                                                            id="password_confirmation" name="password_confirmation" 
                                                            placeholder="Confirm Password" required>
                                                        <span class="password-toggle" onclick="togglePassword('password_confirmation')">
                                                            <i class="far fa-eye"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-user btn-block" id="register-btn">
                                            <span class="spinner-border spinner-border-sm d-none" id="register-spinner"></span>
                                            <span id="register-text">Register Account</span>
                                        </button>

                                        <div class="divider">
                                            <span>OR</span>
                                        </div>

                                        <div class="text-center register-footer">
                                            <a href="{{ route('login') }}">Already have an account? Login!</a>
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
    
        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');
            let strength = 0;
            let missingRequirements = [];
            
            // Check requirements
            const hasMinLength = password.length >= 8;
            const hasLowercase = /[a-z]/.test(password);
            const hasUppercase = /[A-Z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecialChar = /[\W_]/.test(password);
            
            if (!hasMinLength) missingRequirements.push("8+ characters");
            if (!hasLowercase) missingRequirements.push("lowercase letter");
            if (!hasUppercase) missingRequirements.push("uppercase letter");
            if (!hasNumber) missingRequirements.push("number");
            if (!hasSpecialChar) missingRequirements.push("special character");
    
            // Only count strength if minimum length is met
            if (hasMinLength) {
                if (hasLowercase) strength++;
                if (hasUppercase) strength++;
                if (hasNumber) strength++;
                if (hasSpecialChar) strength++;
            } else {
                strengthBar.style.width = '0%';
                strengthBar.style.background = 'transparent';
                strengthText.textContent = 'Missing: ' + missingRequirements.join(', ');
                strengthText.className = 'text-danger';
                return;
            }
            
            const width = (strength / 4) * 100;
            strengthBar.style.width = width + '%';
            
            if (password.length === 0) {
                strengthBar.style.background = 'transparent';
                strengthText.textContent = '';
            } else if (missingRequirements.length > 0) {
                strengthBar.style.background = '#dc3545';
                strengthText.textContent = 'Missing: ' + missingRequirements.join(', ');
                strengthText.className = 'text-danger';
            } else {
                strengthBar.style.background = '#28a745';
                strengthText.textContent = 'Strong password';
                strengthText.className = 'text-success';
            }
        }
    
        document.addEventListener('DOMContentLoaded', () => {
            const registerForm = document.querySelector('form');
            const registerBtn = document.getElementById('register-btn');
            const registerText = document.getElementById('register-text');
            const registerSpinner = document.getElementById('register-spinner');
    
            registerForm.addEventListener('submit', function(e) {
                const password = document.getElementById('password').value;
                const hasMinLength = password.length >= 8;
                const hasLowercase = /[a-z]/.test(password);
                const hasUppercase = /[A-Z]/.test(password);
                const hasNumber = /\d/.test(password);
                const hasSpecialChar = /[\W_]/.test(password);
                
                if (!hasMinLength || !hasLowercase || !hasUppercase || !hasNumber || !hasSpecialChar) {
                    e.preventDefault();
                    return false;
                }
                
                registerBtn.disabled = true;
                registerSpinner.classList.remove('d-none');
                registerText.textContent = 'Creating Account...';
            });
        });
    </script>

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