<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Your App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 400px;
            margin: auto;
            margin-top: 5%;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="register-container">
    <h3 class="text-center fw-bold mb-4">Create an Account</h3>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label fw-bold">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required autofocus>
            @error('name') 
                <small class="text-danger">{{ $message }}</small> 
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-bold">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
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

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label fw-bold">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm your password" required>
            @error('password_confirmation') 
                <small class="text-danger">{{ $message }}</small> 
            @enderror
        </div>

        <!-- Register Button -->
        <button type="submit" class="btn btn-primary w-100">Register</button>

        <!-- Already have an account? -->
        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none">Already registered? Log in</a>
        </div>
    </form>
</div>

</body>
</html>
