<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smart Fish Feeder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

    <div class="card border-0 shadow-sm rounded-4 p-4" style="width: 100%; max-width: 400px;">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-primary">Smart Fish Feeder</h4>
            <p class="text-muted small">Silakan login untuk mengelola sistem.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger small" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-semibold">Email</label>
                <input type="email" name="email" class="form-control rounded-3" required value="{{ old('email') }}">
            </div>
            <div class="mb-4">
                <label class="form-label small fw-semibold">Password</label>
                <input type="password" name="password" class="form-control rounded-3" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-semibold">Login</button>
        </form>
        
        <div class="text-center mt-3">
            <a href="{{ route('jadwal') }}" class="text-decoration-none small text-secondary">Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>