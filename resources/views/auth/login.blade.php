<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In | Consumable Registry</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="login-page-body">

    <!-- Toast Notifications -->
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
            <div class="toast success" role="alert">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="#10b981" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <div class="toast-message">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast error" role="alert">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="#ef4444" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                <div class="toast-message">{{ session('error') }}</div>
            </div>
        @endif
    </div>

    <!-- Main Split Login Container -->
    <div class="login-split-wrapper">

        <!-- Left Illustration Panel with Interactive Characters -->
        <div class="login-illustration-panel" id="illustrationPanel">
            <!-- Scene Graphic Container -->
            <div class="characters-stage" id="charactersStage">
                
                <!-- Floor Shadows for Depth & Organic Grounding -->
                <div class="char-shadow shadow-orange"></div>
                <div class="char-shadow shadow-purple"></div>
                <div class="char-shadow shadow-black"></div>
                <div class="char-shadow shadow-yellow"></div>
                
                <!-- 1. Tall Purple Box Character -->
                <div class="char-entity char-purple" id="charPurple">
                    <svg viewBox="0 0 140 280" width="140" height="280">
                        <rect x="0" y="0" width="140" height="280" rx="16" fill="#5b13ec" />
                        <!-- Normal Left Eye -->
                        <g class="eye eye-left normal-eye">
                            <circle cx="36" cy="48" r="13" fill="#ffffff" />
                            <g class="pupil-group">
                                <circle class="pupil" cx="36" cy="48" r="5.5" fill="#18181b" />
                            </g>
                        </g>
                        <!-- Normal Right Eye -->
                        <g class="eye eye-right normal-eye">
                            <circle cx="88" cy="48" r="13" fill="#ffffff" />
                            <g class="pupil-group">
                                <circle class="pupil" cx="88" cy="48" r="5.5" fill="#18181b" />
                            </g>
                        </g>
                        <!-- Closed Eyes (Shown when password focused) -->
                        <g class="closed-eyes">
                            <path d="M 24 48 Q 36 38 48 48" fill="none" stroke="#18181b" stroke-width="4.5" stroke-linecap="round" />
                            <path d="M 76 48 Q 88 38 100 48" fill="none" stroke="#18181b" stroke-width="4.5" stroke-linecap="round" />
                        </g>
                        <!-- Mouth -->
                        <path class="mouth-open" d="M 54 72 Q 62 80 70 72" fill="none" stroke="#18181b" stroke-width="4" stroke-linecap="round" />
                        <path class="mouth-surprised" d="M 62 72 A 5 5 0 1 1 62 71.9 Z" fill="#18181b" />
                    </svg>
                </div>

                <!-- 2. Black Box Character (Behind) -->
                <div class="char-entity char-black" id="charBlack">
                    <svg viewBox="0 0 100 200" width="100" height="200">
                        <rect x="0" y="0" width="100" height="200" rx="8" fill="#18181b" />
                        <!-- Normal Eyes -->
                        <g class="eye eye-left normal-eye">
                            <circle cx="28" cy="34" r="12" fill="#ffffff" />
                            <g class="pupil-group">
                                <circle class="pupil" cx="28" cy="34" r="5" fill="#18181b" />
                            </g>
                        </g>
                        <g class="eye eye-right normal-eye">
                            <circle cx="64" cy="34" r="12" fill="#ffffff" />
                            <g class="pupil-group">
                                <circle class="pupil" cx="64" cy="34" r="5" fill="#18181b" />
                            </g>
                        </g>
                        <!-- Closed Eyes -->
                        <g class="closed-eyes">
                            <path d="M 18 34 Q 28 26 38 34" fill="none" stroke="#ffffff" stroke-width="3.5" stroke-linecap="round" />
                            <path d="M 54 34 Q 64 26 74 34" fill="none" stroke="#ffffff" stroke-width="3.5" stroke-linecap="round" />
                        </g>
                    </svg>
                </div>

                <!-- 3. Orange Dome Character (Foreground Left) -->
                <div class="char-entity char-orange" id="charOrange">
                    <svg viewBox="0 0 220 145" width="220" height="145">
                        <!-- Dome Shape -->
                        <path d="M 0 145 C 0 50 45 0 110 0 C 175 0 220 50 220 145 Z" fill="#f95726" />
                        <!-- Left Eye -->
                        <g class="eye eye-left">
                            <circle class="pupil" cx="72" cy="74" r="7" fill="#18181b" />
                        </g>
                        <!-- Right Eye -->
                        <g class="eye eye-right">
                            <circle class="pupil" cx="132" cy="74" r="7" fill="#18181b" />
                        </g>
                        <!-- Happy Smile Mouth -->
                        <path class="mouth-smile" d="M 94 92 Q 102 102 110 92" fill="none" stroke="#18181b" stroke-width="4.5" stroke-linecap="round" />
                        <path class="mouth-gasp" d="M 102 94 A 6 6 0 1 1 102 93.9 Z" fill="#18181b" />
                        
                        <!-- Paws (Slide up on password focus) -->
                        <g class="char-paws">
                            <ellipse class="paw paw-left" cx="62" cy="140" rx="20" ry="24" fill="#f95726" stroke="#18181b" stroke-width="3" />
                            <ellipse class="paw paw-right" cx="142" cy="140" rx="20" ry="24" fill="#f95726" stroke="#18181b" stroke-width="3" />
                        </g>
                    </svg>
                </div>

                <!-- 4. Yellow Arch Capsule Character (Right) -->
                <div class="char-entity char-yellow" id="charYellow">
                    <svg viewBox="0 0 110 180" width="110" height="180">
                        <!-- Arch Capsule Shape -->
                        <path d="M 0 180 L 0 55 C 0 22 25 0 55 0 C 85 0 110 22 110 55 L 110 180 Z" fill="#ffd600" />
                        <!-- Dot Eye -->
                        <circle class="pupil" cx="42" cy="52" r="5.5" fill="#18181b" />
                        <!-- Protruding Line Mouth -->
                        <line class="mouth-line" x1="8" y1="84" x2="48" y2="84" stroke="#18181b" stroke-width="6" stroke-linecap="round" />
                    </svg>
                </div>

            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="login-form-panel">
            <div class="login-form-content">
                
                <!-- Sparkle Accent Icon -->
                <div class="sparkle-logo">
                    <svg viewBox="0 0 40 40" width="36" height="36" fill="#18181b">
                        <path d="M 20 0 C 20 11 29 20 40 20 C 29 20 20 29 20 40 C 20 29 11 20 0 20 C 11 20 20 11 20 0 Z" />
                    </svg>
                </div>

                <h1 class="welcome-heading">Welcome back!</h1>
                <p class="welcome-subtitle">Please enter your details</p>

                <form action="{{ url('/login') }}" method="POST" id="loginForm">
                    @csrf

                    <!-- Email / Username Field -->
                    <div class="form-group-clean">
                        <label for="username">Email</label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="input-clean @error('username') is-invalid @enderror" 
                            value="{{ old('username') }}" 
                            required 
                            autofocus 
                            autocomplete="username"
                        >
                        @error('username')
                            <div class="field-error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group-clean">
                        <label for="password">Password</label>
                        <div class="password-field-wrapper">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="input-clean @error('password') is-invalid @enderror" 
                                required
                                autocomplete="current-password"
                            >
                            <button type="button" class="btn-toggle-eye" id="togglePasswordBtn" title="Toggle password visibility" tabindex="-1">
                                <!-- Eye Open Icon -->
                                <svg class="icon-eye-open" viewBox="0 0 24 24" width="20" height="20" stroke="#18181b" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3" fill="#18181b"></circle>
                                </svg>
                                <!-- Eye Closed Icon -->
                                <svg class="icon-eye-closed d-none" viewBox="0 0 24 24" width="20" height="20" stroke="#18181b" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <div class="field-error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Form Options Row -->
                    <div class="form-options-row">
                        <label class="remember-label">
                            <input type="checkbox" id="remember" name="remember" class="custom-checkbox" {{ old('remember') ? 'checked' : '' }}>
                            <span class="checkbox-text">Remember me for 30 days</span>
                        </label>
                        <a href="#" class="forgot-link" onclick="event.preventDefault(); alert('Silakan hubungi Administrator untuk reset password.');">Forgot password?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login-pill">
                        Log In
                    </button>
                </form>

            </div>
        </div>

    </div>

    <!-- JavaScript Interactive Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto dismiss toast notifications
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => toast.classList.add('show'), 100);
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 350);
                }, 5000);
            });

            // Interactive Character Reactions & Body Parallax
            const stage = document.getElementById('charactersStage');
            const charEntities = document.querySelectorAll('.char-entity');
            const pupils = document.querySelectorAll('.pupil');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const togglePasswordBtn = document.getElementById('togglePasswordBtn');

            let keyBounceTimer = null;

            // Cache static pupil centers to eliminate layout thrashing (reflows) on mousemove
            let pupilCenters = [];

            function updatePupilCenters() {
                const currentTransforms = Array.from(pupils).map(p => p.style.transform);
                pupils.forEach(p => p.style.transform = 'translate(0px, 0px)');

                pupilCenters = Array.from(pupils).map((pupil, index) => {
                    const rect = pupil.getBoundingClientRect();
                    return {
                        element: pupil,
                        x: rect.left + rect.width / 2,
                        y: rect.top + rect.height / 2
                    };
                });

                pupils.forEach((p, index) => {
                    if (currentTransforms[index]) p.style.transform = currentTransforms[index];
                });
            }

            updatePupilCenters();
            window.addEventListener('resize', updatePupilCenters);

            // Mouse Movement for Eyes & Body Parallax Leaning using requestAnimationFrame (Instant response)
            let mouseX = window.innerWidth / 2;
            let mouseY = window.innerHeight / 2;
            let ticking = false;

            window.addEventListener('mousemove', function (e) {
                mouseX = e.clientX;
                mouseY = e.clientY;

                if (!ticking) {
                    requestAnimationFrame(updateEyeAndBodyPositions);
                    ticking = true;
                }
            });

            function updateEyeAndBodyPositions() {
                ticking = false;

                const stageRect = stage.getBoundingClientRect();
                const stageCenterX = stageRect.left + stageRect.width / 2;
                const stageCenterY = stageRect.top + stageRect.height / 2;

                // Parallax shift for body entities
                const offsetX = (mouseX - stageCenterX) / 35;
                const offsetY = (mouseY - stageCenterY) / 35;

                charEntities.forEach(char => {
                    let factor = 1;
                    if (char.classList.contains('char-purple')) factor = 1.2;
                    else if (char.classList.contains('char-orange')) factor = 0.8;
                    else if (char.classList.contains('char-black')) factor = 1.4;
                    else if (char.classList.contains('char-yellow')) factor = 0.9;

                    const shiftX = offsetX * factor;
                    const shiftY = offsetY * factor;
                    const tilt = Math.max(-6, Math.min(6, shiftX * 0.8));

                    char.style.setProperty('--shift-x', `${shiftX}px`);
                    char.style.setProperty('--shift-y', `${shiftY}px`);
                    char.style.setProperty('--tilt-deg', `${tilt}deg`);
                });

                // Eye Pupil Tracking - Real-time zero-delay follow cursor
                if (stage.classList.contains('password-focused') && !stage.classList.contains('password-peeking')) {
                    pupils.forEach(pupil => pupil.style.transform = 'translate(0px, 0px)');
                    return;
                }

                pupilCenters.forEach(p => {
                    const deltaX = mouseX - p.x;
                    const deltaY = mouseY - p.y;
                    const angle = Math.atan2(deltaY, deltaX);
                    const distance = Math.hypot(deltaX, deltaY);

                    const maxMove = 5.5;
                    const moveDist = Math.min(distance / 25, maxMove);

                    const moveX = Math.cos(angle) * moveDist;
                    const moveY = Math.sin(angle) * moveDist;

                    p.element.style.transform = `translate(${moveX}px, ${moveY}px)`;
                });
            }

            // Focus on Username/Email Field -> Characters lean towards input
            if (usernameInput) {
                usernameInput.addEventListener('focus', () => {
                    stage.classList.add('username-focused');
                });
                usernameInput.addEventListener('blur', () => {
                    stage.classList.remove('username-focused');
                });
            }

            // Focus on Password Field -> Cover eyes & tilt bodies dramatically
            if (passwordInput) {
                passwordInput.addEventListener('focus', () => {
                    stage.classList.add('password-focused');
                });

                passwordInput.addEventListener('input', () => {
                    // Trigger dynamic bounce pop on keystrokes
                    stage.classList.add('keystroke-bounce');
                    clearTimeout(keyBounceTimer);
                    keyBounceTimer = setTimeout(() => {
                        stage.classList.remove('keystroke-bounce');
                    }, 140);
                });

                passwordInput.addEventListener('blur', () => {
                    stage.classList.remove('password-focused');
                    stage.classList.remove('password-peeking');
                    stage.classList.remove('keystroke-bounce');
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

            // Toggle Password Peek state
            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const iconOpen = togglePasswordBtn.querySelector('.icon-eye-open');
                    const iconClosed = togglePasswordBtn.querySelector('.icon-eye-closed');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        stage.classList.add('password-peeking');
                        if (iconOpen) iconOpen.classList.add('d-none');
                        if (iconClosed) iconClosed.classList.remove('d-none');
                    } else {
                        passwordInput.type = 'password';
                        stage.classList.remove('password-peeking');
                        if (iconOpen) iconOpen.classList.remove('d-none');
                        if (iconClosed) iconClosed.classList.add('d-none');
                    }
                });
            }
        });
    </script>
</body>
</html>
</body>
</html>


