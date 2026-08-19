<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Warehouse') | MAI Consumable Registry</title>
    
    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="mai-dashboard-body">

    <!-- SVG Gradients Definition -->
    <svg style="position: absolute; width: 0; height: 0;" width="0" height="0" version="1.1" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#00adef" />
                <stop offset="100%" stop-color="#1a3fa8" />
            </linearGradient>
            <linearGradient id="maiRedGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#f43f5e" />
                <stop offset="100%" stop-color="#d42b2b" />
            </linearGradient>
        </defs>
    </svg>

    <!-- Sidebar Navigation -->
    @include('partials.sidebar')

    <!-- Toast Notification Container -->
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
            <div class="toast success" role="alert">
                <svg viewBox="0 0 24 24" width="22" height="22" stroke="#10b981" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <div class="toast-message">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast error" role="alert">
                <svg viewBox="0 0 24 24" width="22" height="22" stroke="#ef4444" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                <div class="toast-message">{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="toast error" role="alert">
                <svg viewBox="0 0 24 24" width="22" height="22" stroke="#ef4444" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
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

    <!-- Floating Sidebar Open Toggle (when sidebar is hidden) -->
    <button type="button" class="floating-sidebar-toggle no-print" id="floatingSidebarToggle" onclick="toggleSidebar()" title="Buka Sidebar">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>

    <!-- Main Content Wrapper -->
    <main class="main-content" id="mainContent">
        <!-- 3D Warehouse Subtle Atmospheric Background -->
        <div class="app-bg-grid no-print"></div>
        <div class="app-bg-glow-1 no-print"></div>
        <div class="app-bg-glow-2 no-print"></div>
        <div class="app-bg-glow-3 no-print"></div>
        
        @yield('content')
    </main>

    <!-- Custom Notification, Sidebar & Toast JavaScript -->
    <script>
        // Sidebar Toggle with LocalStorage Persistence
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const floatBtn = document.getElementById('floatingSidebarToggle');
            if (!sidebar || !mainContent) return;

            const isCollapsed = sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded', isCollapsed);
            if (floatBtn) {
                floatBtn.classList.toggle('show', isCollapsed);
            }
            localStorage.setItem('mai_sidebar_collapsed', isCollapsed ? 'true' : 'false');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Restore saved sidebar state
            const savedState = localStorage.getItem('mai_sidebar_collapsed');
            if (savedState === 'true') {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('mainContent');
                const floatBtn = document.getElementById('floatingSidebarToggle');
                if (sidebar && mainContent) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('expanded');
                    if (floatBtn) floatBtn.classList.add('show');
                }
            }

            // Find and show all toasts in container
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

        // Helper function to show dynamic success/error notifications manually
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            if (!container) return;
            
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            let iconHtml = '';
            if (type === 'success') {
                iconHtml = `
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="#10b981" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                `;
            } else {
                iconHtml = `
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="#ef4444" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
