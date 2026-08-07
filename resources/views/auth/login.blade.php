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
        <!-- Interactive Animated Characters Header -->
        <div class="login-characters-container" id="charactersContainer">
            <!-- Character 1: Purple Bear -->
            <div class="char-wrapper char-purple" id="charPurple">
                <svg class="character-svg" viewBox="0 0 100 100" width="86" height="86">
                    <defs>
                        <linearGradient id="purpleGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#6366f1" />
                            <stop offset="100%" stop-color="#4338ca" />
                        </linearGradient>
                    </defs>
                    <!-- Ears -->
                    <circle cx="22" cy="22" r="13" fill="url(#purpleGrad)" />
                    <circle cx="22" cy="22" r="7" fill="#a5b4fc" />
                    <circle cx="78" cy="22" r="13" fill="url(#purpleGrad)" />
                    <circle cx="78" cy="22" r="7" fill="#a5b4fc" />
                    <!-- Body / Head -->
                    <rect x="12" y="18" width="76" height="76" rx="38" fill="url(#purpleGrad)" />
                    <!-- Snout -->
                    <ellipse cx="50" cy="56" rx="22" ry="16" fill="#e0e7ff" />
                    <ellipse cx="50" cy="48" rx="5.5" ry="3.5" fill="#1e1b4b" />
                    <path d="M 44 58 Q 50 64 56 58" fill="none" stroke="#1e1b4b" stroke-width="2.5" stroke-linecap="round" />
                    <!-- Left Eye -->
                    <g class="eye eye-left">
                        <circle cx="34" cy="38" r="11" fill="#ffffff" />
                        <g class="pupil-group">
                            <circle class="pupil" cx="34" cy="38" r="4.8" fill="#1e1b4b" />
                            <circle cx="35.8" cy="36.2" r="1.6" fill="#ffffff" />
                        </g>
                    </g>
                    <!-- Right Eye -->
                    <g class="eye eye-right">
                        <circle cx="66" cy="38" r="11" fill="#ffffff" />
                        <g class="pupil-group">
                            <circle class="pupil" cx="66" cy="38" r="4.8" fill="#1e1b4b" />
                            <circle cx="67.8" cy="36.2" r="1.6" fill="#ffffff" />
                        </g>
                    </g>
                    <!-- Paws -->
                    <g class="hand hand-left">
                        <ellipse cx="22" cy="84" rx="12" ry="16" fill="url(#purpleGrad)" stroke="#4338ca" stroke-width="1.5" />
                        <ellipse cx="22" cy="81" rx="6" ry="8" fill="#a5b4fc" opacity="0.7" />
                    </g>
                    <g class="hand hand-right">
                        <ellipse cx="78" cy="84" rx="12" ry="16" fill="url(#purpleGrad)" stroke="#4338ca" stroke-width="1.5" />
                        <ellipse cx="78" cy="81" rx="6" ry="8" fill="#a5b4fc" opacity="0.7" />
                    </g>
                </svg>
            </div>

            <!-- Character 2: Orange Fox -->
            <div class="char-wrapper char-orange" id="charOrange">
                <svg class="character-svg" viewBox="0 0 100 100" width="86" height="86">
                    <defs>
                        <linearGradient id="orangeGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#f59e0b" />
                            <stop offset="100%" stop-color="#ea580c" />
                        </linearGradient>
                    </defs>
                    <!-- Pointed Ears -->
                    <polygon points="12,38 30,8 42,38" fill="url(#orangeGrad)" />
                    <polygon points="18,36 30,16 38,36" fill="#fde68a" />
                    <polygon points="58,38 70,8 88,38" fill="url(#orangeGrad)" />
                    <polygon points="62,36 70,16 82,36" fill="#fde68a" />
                    <!-- Head -->
                    <rect x="14" y="24" width="72" height="72" rx="36" fill="url(#orangeGrad)" />
                    <!-- White Face Patch -->
                    <path d="M 14 62 Q 50 82 86 62 Q 76 92 50 94 Q 24 92 14 62 Z" fill="#ffffff" />
                    <ellipse cx="50" cy="56" rx="5" ry="3.5" fill="#451a03" />
                    <!-- Blush -->
                    <circle cx="26" cy="52" r="5" fill="#f87171" opacity="0.4" />
                    <circle cx="74" cy="52" r="5" fill="#f87171" opacity="0.4" />
                    <!-- Eyes -->
                    <g class="eye eye-left">
                        <circle cx="33" cy="42" r="10" fill="#ffffff" />
                        <g class="pupil-group">
                            <circle class="pupil" cx="33" cy="42" r="4.5" fill="#451a03" />
                            <circle cx="34.5" cy="40.5" r="1.5" fill="#ffffff" />
                        </g>
                    </g>
                    <g class="eye eye-right">
                        <circle cx="67" cy="42" r="10" fill="#ffffff" />
                        <g class="pupil-group">
                            <circle class="pupil" cx="67" cy="42" r="4.5" fill="#451a03" />
                            <circle cx="68.5" cy="40.5" r="1.5" fill="#ffffff" />
                        </g>
                    </g>
                    <!-- Paws -->
                    <g class="hand hand-left">
                        <ellipse cx="22" cy="84" rx="12" ry="16" fill="url(#orangeGrad)" stroke="#c2410c" stroke-width="1.5" />
                        <ellipse cx="22" cy="81" rx="6" ry="8" fill="#fde68a" opacity="0.7" />
                    </g>
                    <g class="hand hand-right">
                        <ellipse cx="78" cy="84" rx="12" ry="16" fill="url(#orangeGrad)" stroke="#c2410c" stroke-width="1.5" />
                        <ellipse cx="78" cy="81" rx="6" ry="8" fill="#fde68a" opacity="0.7" />
                    </g>
                </svg>
            </div>

            <!-- Character 3: Cyan Robot -->
            <div class="char-wrapper char-cyan" id="charCyan">
                <svg class="character-svg" viewBox="0 0 100 100" width="86" height="86">
                    <defs>
                        <linearGradient id="cyanGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#06b6d4" />
                            <stop offset="100%" stop-color="#0284c7" />
                        </linearGradient>
                    </defs>
                    <!-- Antenna -->
                    <line x1="50" y1="20" x2="50" y2="8" stroke="#06b6d4" stroke-width="4" stroke-linecap="round" />
                    <circle cx="50" cy="6" r="6" fill="#38bdf8" />
                    <!-- Head -->
                    <rect x="14" y="20" width="72" height="72" rx="24" fill="url(#cyanGrad)" />
                    <!-- Screen Visor -->
                    <rect x="22" y="28" width="56" height="42" rx="14" fill="#0f172a" opacity="0.85" />
                    <!-- Eyes -->
                    <g class="eye eye-left">
                        <circle cx="35" cy="48" r="9" fill="#ffffff" />
                        <g class="pupil-group">
                            <circle class="pupil" cx="35" cy="48" r="4.2" fill="#0284c7" />
                            <circle cx="36.5" cy="46.5" r="1.4" fill="#ffffff" />
                        </g>
                    </g>
                    <g class="eye eye-right">
                        <circle cx="65" cy="48" r="9" fill="#ffffff" />
                        <g class="pupil-group">
                            <circle class="pupil" cx="65" cy="48" r="4.2" fill="#0284c7" />
                            <circle cx="66.5" cy="46.5" r="1.4" fill="#ffffff" />
                        </g>
                    </g>
                    <!-- Mouth Visor Line -->
                    <line x1="42" y1="62" x2="58" y2="62" stroke="#38bdf8" stroke-width="2.5" stroke-linecap="round" opacity="0.8" />
                    <!-- Paws/Hands -->
                    <g class="hand hand-left">
                        <ellipse cx="22" cy="84" rx="12" ry="16" fill="url(#cyanGrad)" stroke="#0369a1" stroke-width="1.5" />
                        <ellipse cx="22" cy="81" rx="6" ry="8" fill="#7dd3fc" opacity="0.7" />
                    </g>
                    <g class="hand hand-right">
                        <ellipse cx="78" cy="84" rx="12" ry="16" fill="url(#cyanGrad)" stroke="#0369a1" stroke-width="1.5" />
                        <ellipse cx="78" cy="81" rx="6" ry="8" fill="#7dd3fc" opacity="0.7" />
                    </g>
                </svg>
            </div>
        </div>

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
                    <div class="password-input-wrapper">
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                        <button type="button" class="btn-toggle-password" id="togglePasswordBtn" title="Tampilkan/Sembunyikan Password" tabindex="-1">
                            <svg class="icon-eye-open" viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="icon-eye-closed d-none" viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
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
            // Toast Notification Timer
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

            // Character Eye Tracking & Form Interactive Behavior
            const container = document.getElementById('charactersContainer');
            const charWrappers = document.querySelectorAll('.char-wrapper');
            const eyes = document.querySelectorAll('.eye');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const togglePasswordBtn = document.getElementById('togglePasswordBtn');

            if (container && eyes.length > 0) {
                // Mouse Movement Tracking for Eyes & 3D Head Parallax
                window.addEventListener('mousemove', function (e) {
                    const isPasswordCovered = container.classList.contains('password-active') && !container.classList.contains('password-peek');

                    eyes.forEach(eye => {
                        const pupilGroup = eye.querySelector('.pupil-group');
                        if (!pupilGroup) return;

                        if (isPasswordCovered) {
                            pupilGroup.style.transform = 'translate(0px, 0px)';
                            return;
                        }

                        const rect = eye.getBoundingClientRect();
                        const eyeCenterX = rect.left + rect.width / 2;
                        const eyeCenterY = rect.top + rect.height / 2;

                        const deltaX = e.clientX - eyeCenterX;
                        const deltaY = e.clientY - eyeCenterY;
                        const angle = Math.atan2(deltaY, deltaX);

                        const distance = Math.hypot(deltaX, deltaY);
                        const maxMove = 4.5;
                        const moveDist = Math.min(distance / 25, maxMove);

                        const pupilX = Math.cos(angle) * moveDist;
                        const pupilY = Math.sin(angle) * moveDist;

                        pupilGroup.style.transform = `translate(${pupilX}px, ${pupilY}px)`;
                    });

                    charWrappers.forEach(wrapper => {
                        const rect = wrapper.getBoundingClientRect();
                        const charX = rect.left + rect.width / 2;
                        const charY = rect.top + rect.height / 2;
                        const tiltX = Math.max(-8, Math.min(8, (e.clientX - charX) / 45));
                        const tiltY = Math.max(-5, Math.min(5, (e.clientY - charY) / 45));
                        
                        const svg = wrapper.querySelector('.character-svg');
                        if (svg) {
                            svg.style.transform = `rotate(${tiltX * 0.4}deg) translate(${tiltX}px, ${tiltY}px)`;
                        }
                    });
                });

                // Username Focus State
                if (usernameInput) {
                    usernameInput.addEventListener('focus', () => {
                        container.classList.add('username-active');
                    });
                    usernameInput.addEventListener('blur', () => {
                        container.classList.remove('username-active');
                    });
                }

                // Password Focus State (Characters Cover Eyes)
                if (passwordInput) {
                    passwordInput.addEventListener('focus', () => {
                        container.classList.add('password-active');
                    });
                    passwordInput.addEventListener('blur', () => {
                        container.classList.remove('password-active');
                        container.classList.remove('password-peek');
                        if (togglePasswordBtn) {
                            const iconOpen = togglePasswordBtn.querySelector('.icon-eye-open');
                            const iconClosed = togglePasswordBtn.querySelector('.icon-eye-closed');
                            if (iconOpen && iconClosed) {
                                iconOpen.classList.remove('d-none');
                                iconClosed.classList.add('d-none');
                            }
                            passwordInput.type = 'password';
                        }
                    });
                }

                // Toggle Password Visibility (Peek State)
                if (togglePasswordBtn && passwordInput) {
                    togglePasswordBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const iconOpen = togglePasswordBtn.querySelector('.icon-eye-open');
                        const iconClosed = togglePasswordBtn.querySelector('.icon-eye-closed');

                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            container.classList.add('password-peek');
                            if (iconOpen) iconOpen.classList.add('d-none');
                            if (iconClosed) iconClosed.classList.remove('d-none');
                        } else {
                            passwordInput.type = 'password';
                            container.classList.remove('password-peek');
                            if (iconOpen) iconOpen.classList.remove('d-none');
                            if (iconClosed) iconClosed.classList.add('d-none');
                        }
                    });
                }
            }
        });
    </script>
</body>
</html>

