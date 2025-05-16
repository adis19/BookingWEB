<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html, body {
            font-family: 'Nunito', sans-serif;
            height: 100%;
        }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .content {
            max-width: 700px;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
            text-align: center;
        }
        .error-code {
            font-size: 80px;
            font-weight: 700;
            color: #6c4a31;
            margin-bottom: 20px;
        }
        .error-message {
            font-size: 22px;
            margin-bottom: 30px;
        }
        .description {
            margin-bottom: 30px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="error-code">
            @yield('code')
        </div>

        <div class="error-message">
            @yield('message')
        </div>

        <div class="description">
            @yield('description')
        </div>

        <a href="{{ url('/') }}" class="btn btn-primary">Back to Home</a>
    </div>
</body>
</html>
