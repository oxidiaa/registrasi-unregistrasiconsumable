<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path
                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
            </path>
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
                <a href="{{ route('form-registrasi') }}">
                    <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span>Form Registrasi</span>
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

    <div class="sidebar-footer"
        style="text-align: left; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 1.5rem;">
        @auth
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                <div
                    style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #a78bfa 0%, #60a5fa 100%); display: flex; align-items: center; justify-content: center; font-weight: 800; font-family: var(--font-heading); color: var(--bg-sidebar);">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div style="display: flex; flex-direction: column;">
                    <span
                        style="font-weight: 600; font-size: 0.9rem; color: var(--text-on-dark);">{{ Auth::user()->name }}</span>
                    <span style="font-size: 0.75rem; color: #64748b;">Administrator</span>
                </div>
            </div>
        @endauth
        <p style="text-align: center; font-size: 0.8rem; color: #475569;">&copy; 2026 Registry App</p>
    </div>
</aside>