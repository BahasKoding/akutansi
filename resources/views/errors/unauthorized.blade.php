<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access - WAWAWA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 text-center">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <i class="bi bi-shield-lock text-danger" style="font-size: 4rem;"></i>
                        <h2 class="mt-3 mb-3">Unauthorized Access</h2>
                        <p class="text-muted">Sorry, you don't have permission to access this page.</p>
                        <p class="text-muted">Please contact your administrator if you think this is a mistake.</p>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
                            <i class="bi bi-house-door"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 
