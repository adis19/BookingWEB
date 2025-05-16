<!DOCTYPE html>
<html>
<head>
    <title>Debug Information</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h1>Debug Information</h1>
            </div>
            <div class="card-body">
                <h2>Request Data:</h2>
                <pre>{{ json_encode($data, JSON_PRETTY_PRINT) }}</pre>
                
                <h2>Session Data:</h2>
                <pre>{{ json_encode(session()->all(), JSON_PRETTY_PRINT) }}</pre>
                
                <h2>Authentication Status:</h2>
                <p>User is {{ Auth::check() ? 'logged in as '.Auth::user()->name : 'not logged in' }}</p>
                
                <h2>Validation Errors:</h2>
                <pre>{{ json_encode($errors->all(), JSON_PRETTY_PRINT) }}</pre>
                
                <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</body>
</html>
