<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Consumable Registry</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="login-body">

    <!-- Decorative Background Glows -->
    <div class="bg-glow-1" style="top: -50px; right: -50px;"></div>
    <div class="bg-glow-2" style="bottom: -50px; left: -50px;"></div>

    <!-- Toast Notification Container -->
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
            <div class="toast success" role="alert">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="#10b981" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <div class="toast-message">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast error" role="alert">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="#ef4444" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                <div class="toast-message">{{ session('error') }}</div>
            </div>
        @endif
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header-logo">
                <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </div>
            
            <h2>Masuk Sistem</h2>
            <p>Silakan masuk menggunakan akun administrator Anda.</p>

            <form action="{{ url('/login') }}" method="POST">
                @csrf
                <div class="form-group" style="text-align: left;">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" placeholder="admin" value="{{ old('username') }}" required autofocus>
                    @error('username')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="text-align: left;">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                    @error('password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Ingat Saya</label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Masuk
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.classList.add('show');
                }, 100);

                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        toast.remove();
                    }, 350);
                }, 5000);
            });
        });
    </script>
</body>
</html>
