<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Optional: Add Bootstrap or custom CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        .error {
            font-size: 100px;
            font-weight: bold;
            color: #e3342f;
        }
    </style>
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="text-center">
        <div class="error mx-auto" data-text="404">500</div>
        <p class="lead text-gray-800 mb-2">Internal Server Error </p>
        <p class="text-gray-500 mb-4">Some error has occurred on the server... Please try again later.</p>
        @hasrole('superadmin')
            <a href="{{ url('/admin/dashboard') }}">&larr; Back to Dashboard</a>
        @else
            <a href="{{ url('/dashboard') }}">&larr; Back to Dashboard</a>
        @endhasrole
    </div>
</body>
</html>
