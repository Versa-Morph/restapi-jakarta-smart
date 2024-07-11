<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized - Smart Jakarta</title>
    <link rel="stylesheet" href="{{ asset('assets/auth/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/auth/css/style.css') }}">
</head>
<body>
    <div class="container text-center mt-5">
        <h1>401</h1>
        <h2>Unauthorized</h2>
        <p>You are not authorized to access this page.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Go to Home</a>
    </div>
</body>
</html>
