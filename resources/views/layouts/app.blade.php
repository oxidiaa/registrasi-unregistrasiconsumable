<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Registrasi Barang') | Consumable Registry</title>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <!-- SVG Gradients Definition -->
    <svg style="position: absolute; width: 0; height: 0;" width="0" height="0" version="1.1" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#a78bfa" />
                <stop offset="100%" stop-color="#60a5fa" />
            </linearGradient>
        </defs>
    </svg>

    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
            <span>Consumable Registry</span>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="menu-list">
                <li class="menu-item active">
                    <a href="{{ route('dashboard') }}">
                        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="9"></rect>
                            <rect x="14" y="3" width="7" height="5"></rect>
                            <rect x="14" y="12" width="7" height="9"></rect>
                            <rect x="3" y="16" width="7" height="5"></rect>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Keluar (Logout)</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        
        <div class="sidebar-footer" style="text-align: left; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 1.5rem;">
            @auth
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                    <div style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #a78bfa 0%, #60a5fa 100%); display: flex; align-items: center; justify-content: center; font-weight: 800; font-family: var(--font-heading); color: var(--bg-sidebar);">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-weight: 600; font-size: 0.9rem; color: var(--text-on-dark);">{{ Auth::user()->name }}</span>
                        <span style="font-size: 0.75rem; color: #64748b;">Administrator</span>
                    </div>
                </div>
            @endauth
            <p style="text-align: center; font-size: 0.8rem; color: #475569;">&copy; 2026 Registry App</p>
        </div>
    </aside>

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

        @if($errors->any())
            <div class="toast error" role="alert">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="#ef4444" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div class="toast-message">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content Wrapper -->
    <main class="main-content">
        <div class="bg-glow-1"></div>
        <div class="bg-glow-2"></div>
        
        @yield('content')
    </main>

    <!-- Custom Notification and Toast JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Find and show all toasts in container
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                // Trigger show class after a small timeout for animation
                setTimeout(() => {
                    toast.classList.add('show');
                }, 100);

                // Auto hide toast after 5 seconds
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        toast.remove();
                    }, 350); // wait for CSS transition to complete
                }, 5000);
            });
        });

        // Helper function to show dynamic success/error notifications manually
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            let iconHtml = '';
            if (type === 'success') {
                iconHtml = `
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="#10b981" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                `;
            } else {
                iconHtml = `
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="#ef4444" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                `;
            }

            toast.innerHTML = `
                ${iconHtml}
                <div class="toast-message">${message}</div>
            `;
            
            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('show');
            }, 50);

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 350);
            }, 5000);
        }
    </script>
    @yield('scripts')
</body>
</html>
