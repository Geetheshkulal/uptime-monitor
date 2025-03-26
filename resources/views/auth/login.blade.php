<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Your App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            margin-top: 10%;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="login-container">
    <h3 class="text-center fw-bold mb-4">Login</h3>

    @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-bold">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required autofocus>
            @error('email') 
                <small class="text-danger">{{ $message }}</small> 
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-bold">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
            @error('password') 
                <small class="text-danger">{{ $message }}</small> 
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
            <label class="form-check-label" for="remember_me">Remember Me</label>
        </div>

        <!-- Login Button -->
        <button type="submit" class="btn btn-primary w-100" id="login-btn">
            <span class="spinner-border spinner-border-sm d-none" id="login-spinner"></span>
            Log In
        </button>

        <!-- Forgot Password & Register -->
        <div class="text-center mt-3">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot your password?</a>
            @endif
            <br>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="text-decoration-none">Don't have an account? Register</a>
            @endif
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const loginForm = document.querySelector('form');
        const loginBtn = document.getElementById('login-btn');
        const loginSpinner = document.getElementById('login-spinner');

        loginForm.addEventListener('submit', () => {
            loginBtn.disabled = true;
            loginSpinner.classList.remove('d-none');
            loginBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Loading...`;
        });
    });
</script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</body>
</html>
