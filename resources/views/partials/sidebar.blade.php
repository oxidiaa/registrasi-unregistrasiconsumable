<aside class="sidebar" id="sidebar">
    <!-- Sidebar Header & Logo -->
    <div class="sidebar-brand-wrapper">
        <button type="button" class="sidebar-toggle-btn no-print" onclick="toggleSidebar()" title="Sembunyikan Sidebar">
            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>

        <div class="sidebar-logo-container">
            <img src="{{ asset('assets/images/MAI TERANG.png') }}?v={{ file_exists(public_path('assets/images/MAI TERANG.png')) ? filemtime(public_path('assets/images/MAI TERANG.png')) : time() }}" alt="MAI Metalart Astra Indonesia" class="sidebar-mai-logo">
        </div>
        <div class="sidebar-brand-text">
            <div class="brand-title">SATURNUS</div>
            <div class="brand-subtitle">Consumable Registry</div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
        <div class="sidebar-nav-heading">MAIN MENU</div>
        <ul class="menu-list">
            <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <div class="menu-icon-box">
                        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="9"></rect>
                            <rect x="14" y="3" width="7" height="5"></rect>
                            <rect x="14" y="12" width="7" height="9"></rect>
                            <rect x="3" y="16" width="7" height="5"></rect>
                        </svg>
                    </div>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="menu-item {{ request()->routeIs('form-registrasi') ? 'active' : '' }}">
                <a href="{{ route('form-registrasi') }}">
                    <div class="menu-icon-box">
                        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </div>
                    <span>Form Registrasi</span>
                </a>
            </li>

            @if(in_array(strtoupper(Auth::user()->role ?? ''), ['MASTER', 'ADMIN']))
            <li class="menu-item">
                <a href="{{ route('form-registrasi') }}#account-master" onclick="if(window.switchSheet) switchSheet('account-master');">
                    <div class="menu-icon-box">
                        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <span>Account Master</span>
                </a>
            </li>
            @endif

            <li class="menu-item menu-item-logout">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <div class="menu-icon-box">
                        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </div>
                    <span>Keluar (Logout)</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>

    <!-- Sidebar Footer & Profile Section -->
    <div class="sidebar-footer">
        @auth
            <div class="sidebar-user-card">
                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="sidebar-user-details">
                    <span class="user-display-name">{{ Auth::user()->name }}</span>
                    <span class="user-display-role">
                        <span class="user-status-dot"></span>
                        {{ Auth::user()->role ?? Auth::user()->department ?? 'Administrator' }}
                    </span>
                </div>
            </div>
        @endauth
        <div class="sidebar-system-info">
            <span>PT. Metalart Astra Indonesia</span>
            <small>Smart Warehouse System &copy; {{ date('Y') }}</small>
        </div>
    </div>
</aside>