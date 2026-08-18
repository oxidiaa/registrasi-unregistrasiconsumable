<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In | MAI Smart Warehouse System</title>
    <meta name="description" content="Login ke MAI Smart Warehouse System - Consumable Registry. Platform manajemen gudang modern PT. Metalart Astra Indonesia.">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ============================================================
           MAI SMART WAREHOUSE LOGIN — 3D INDUSTRIAL THEME
           ============================================================ */

        :root {
            /* MAI Brand Colors from logo */
            --mai-blue-dark: #0d2d6e;
            --mai-blue:      #1a3fa8;
            --mai-blue-mid:  #1e50c8;
            --mai-red:       #d42b2b;
            --mai-red-light: #e83838;
            --mai-sky:       #00adef;
            --mai-sky-light: #29c5ff;

            /* Industrial Palette */
            --wh-bg:         #07101f;
            --wh-surface:    #0c1a30;
            --wh-card:       #101e36;
            --wh-border:     rgba(0, 173, 239, 0.15);
            --wh-border-glow: rgba(0, 173, 239, 0.4);
            --wh-text:       #e8f4ff;
            --wh-muted:      #7094b8;
            --wh-grid:       rgba(0, 120, 200, 0.08);

            --font-head:  'Outfit', sans-serif;
            --font-body:  'Plus Jakarta Sans', sans-serif;

            --ease-spring: cubic-bezier(0.34, 1.56, 0.64, 1);
            --ease-smooth: cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ── Reset & Base ── */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            min-height: 100vh;
        }

        body.login-page-body {
            display: flex;
            flex-direction: column;
            background: var(--wh-bg);
            font-family: var(--font-body);
            color: var(--wh-text);
            overflow: hidden;
        }

        /* ── Toast Notifications (preserved from original) ── */
        .toast-container {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            pointer-events: none;
        }

        .toast {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            min-width: 280px;
            max-width: 400px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
            opacity: 0;
            transform: translateX(30px);
            transition: all 0.35s var(--ease-smooth);
            pointer-events: auto;
        }

        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }

        .toast.success {
            background: rgba(16, 185, 129, 0.15);
            border-color: rgba(16, 185, 129, 0.4);
            color: #6ee7b7;
        }

        .toast.error {
            background: rgba(212, 43, 43, 0.2);
            border-color: rgba(212, 43, 43, 0.45);
            color: #fca5a5;
        }

        .toast-message {
            flex: 1;
            line-height: 1.4;
        }

        /* ============================================================
           MAIN SPLIT WRAPPER
           ============================================================ */
        .login-split-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
            position: relative;
        }

        /* ============================================================
           LEFT SIDE — 3D WAREHOUSE VISUAL
           ============================================================ */
        .login-illustration-panel {
            flex: 1.15;
            position: relative;
            overflow: hidden;
            background: var(--wh-bg);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Multi-layer deep background */
        .login-illustration-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 50% 30%, rgba(0, 100, 200, 0.18) 0%, transparent 70%),
                radial-gradient(ellipse 60% 40% at 20% 80%, rgba(212, 43, 43, 0.08) 0%, transparent 60%),
                radial-gradient(ellipse 50% 50% at 80% 15%, rgba(0, 173, 239, 0.1) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        /* Subtle grid overlay */
        .wh-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(0, 140, 220, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 140, 220, 0.06) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 0;
            pointer-events: none;
        }

        /* Perspective floor grid */
        .wh-floor-grid {
            position: absolute;
            bottom: 0;
            left: -50%;
            right: -50%;
            height: 55%;
            background-image:
                linear-gradient(rgba(0, 120, 200, 0.12) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 120, 200, 0.08) 1px, transparent 1px);
            background-size: 60px 60px;
            transform: perspective(400px) rotateX(60deg);
            transform-origin: bottom center;
            z-index: 1;
            pointer-events: none;
        }

        /* Scene container */
        .warehouse-scene {
            position: relative;
            z-index: 2;
            width: 90%;
            max-width: 680px;
            height: 420px;
            perspective: 1200px;
            perspective-origin: 50% 45%;
        }

        /* ── SHELF UNIT (Back Left) ── */
        .shelf-unit {
            position: absolute;
            bottom: 60px;
            left: 20px;
            width: 180px;
            height: 280px;
            transform: rotateY(15deg) rotateX(2deg);
            transform-style: preserve-3d;
            animation: floatSlow 8s ease-in-out infinite;
        }

        .shelf-frame {
            position: absolute;
            inset: 0;
            border: 2.5px solid rgba(0, 173, 239, 0.35);
            border-radius: 4px;
            background: linear-gradient(180deg,
                rgba(13, 30, 70, 0.85) 0%,
                rgba(8, 18, 45, 0.9) 100%);
            box-shadow:
                0 0 20px rgba(0, 173, 239, 0.1),
                inset 0 0 30px rgba(0, 80, 160, 0.15);
        }

        /* Shelf horizontal bars */
        .shelf-bar {
            position: absolute;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, rgba(0,173,239,0.6), rgba(0,100,200,0.4), rgba(0,173,239,0.6));
            box-shadow: 0 0 8px rgba(0, 173, 239, 0.4);
        }

        .shelf-bar:nth-child(1) { top: 25%; }
        .shelf-bar:nth-child(2) { top: 50%; }
        .shelf-bar:nth-child(3) { top: 75%; }

        /* Items on shelves */
        .shelf-item {
            position: absolute;
            border-radius: 3px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.4);
        }

        .shelf-item.box-a {
            width: 38px; height: 32px;
            left: 10px; top: 28%;
            background: linear-gradient(135deg, #c8860a, #a86b05);
            border-top: 2px solid #e8a020;
        }
        .shelf-item.box-b {
            width: 28px; height: 26px;
            left: 58px; top: 29%;
            background: linear-gradient(135deg, #1a5cb0, #0d3a7a);
            border-top: 2px solid #2a7ae8;
        }
        .shelf-item.box-c {
            width: 42px; height: 30px;
            left: 10px; top: 53%;
            background: linear-gradient(135deg, #8b2020, #6b1818);
            border-top: 2px solid #cc3030;
        }
        .shelf-item.box-d {
            width: 30px; height: 28px;
            left: 62px; top: 54%;
            background: linear-gradient(135deg, #2a6b2a, #1a4a1a);
            border-top: 2px solid #3a9a3a;
        }
        .shelf-item.cyl-a {
            width: 22px; height: 34px;
            left: 120px; top: 27%;
            background: linear-gradient(90deg, #888, #bbb, #888);
            border-radius: 50% 50% 3px 3px / 30% 30% 3px 3px;
        }

        /* ── SHELF UNIT RIGHT ── */
        .shelf-unit-right {
            position: absolute;
            bottom: 55px;
            right: 15px;
            width: 140px;
            height: 240px;
            transform: rotateY(-18deg) rotateX(2deg);
            transform-style: preserve-3d;
            animation: floatSlow 9s ease-in-out infinite 1.5s;
        }

        .shelf-unit-right .shelf-frame {
            border-color: rgba(26, 63, 168, 0.5);
            background: linear-gradient(180deg,
                rgba(10, 25, 60, 0.88) 0%,
                rgba(6, 15, 40, 0.92) 100%);
        }

        .shelf-unit-right .shelf-bar {
            background: linear-gradient(90deg, rgba(26,63,168,0.6), rgba(0,80,160,0.4), rgba(26,63,168,0.6));
            box-shadow: 0 0 8px rgba(26, 63, 168, 0.4);
        }

        .shelf-unit-right .shelf-item.box-a {
            width: 32px; height: 28px;
            left: 8px; top: 28%;
            background: linear-gradient(135deg, #d4812a, #b36020);
        }
        .shelf-unit-right .shelf-item.box-b {
            width: 24px; height: 24px;
            left: 50px; top: 29%;
            background: linear-gradient(135deg, #334eaa, #1e3080);
        }
        .shelf-unit-right .shelf-item.box-c {
            width: 36px; height: 26px;
            left: 8px; top: 55%;
            background: linear-gradient(135deg, #7a1c1c, #5a1212);
        }

        /* ── PALLET STACK (Center) ── */
        .pallet-stack {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 160px;
            animation: floatSlow 7s ease-in-out infinite 0.5s;
        }

        .pallet-base {
            width: 140px;
            height: 16px;
            background: linear-gradient(180deg, #8a6020, #5a3a10);
            border-radius: 3px;
            margin: 0 auto;
            position: relative;
            box-shadow: 0 4px 12px rgba(0,0,0,0.5);
        }

        .pallet-base::before {
            content: '';
            position: absolute;
            top: 3px;
            left: 5px;
            right: 5px;
            bottom: 3px;
            background: repeating-linear-gradient(
                90deg,
                transparent, transparent 12px,
                rgba(0,0,0,0.3) 12px, rgba(0,0,0,0.3) 14px
            );
        }

        .pallet-boxes {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-bottom: 2px;
        }

        .pallet-box {
            border-radius: 2px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.5);
        }

        .pallet-box.pb-1 {
            width: 42px; height: 36px;
            background: linear-gradient(135deg, #c8860a 0%, #8a5c05 100%);
            border-top: 2px solid #e8a520;
        }
        .pallet-box.pb-2 {
            width: 38px; height: 40px;
            background: linear-gradient(135deg, #1555b0 0%, #0a3070 100%);
            border-top: 2px solid #2a7ae8;
        }
        .pallet-box.pb-3 {
            width: 44px; height: 34px;
            background: linear-gradient(135deg, #c8860a 0%, #8a5c05 100%);
            border-top: 2px solid #e8a520;
        }

        /* ── FORKLIFT (SVG-based, simplified CSS) ── */
        .forklift-element {
            position: absolute;
            bottom: 30px;
            right: 85px;
            width: 120px;
            animation: forkliftMove 12s ease-in-out infinite;
        }

        @keyframes forkliftMove {
            0%, 100%  { transform: translateX(0); }
            40%       { transform: translateX(-18px); }
            60%       { transform: translateX(-12px); }
        }

        .forklift-body {
            position: relative;
            width: 90px;
            height: 55px;
            background: linear-gradient(135deg, #d4a020 0%, #b08010 100%);
            border-radius: 4px 4px 2px 2px;
            border: 2px solid rgba(255,200,50,0.4);
            box-shadow: 0 4px 16px rgba(0,0,0,0.5);
        }

        .forklift-cabin {
            position: absolute;
            top: -28px;
            left: 8px;
            width: 42px;
            height: 30px;
            background: linear-gradient(135deg, #f0c030 0%, #c09020 100%);
            border-radius: 4px 4px 0 0;
            border: 1px solid rgba(255,220,80,0.5);
        }

        .forklift-cabin::after {
            content: '';
            position: absolute;
            top: 4px; left: 6px; right: 6px; bottom: 4px;
            background: rgba(0, 173, 239, 0.3);
            border-radius: 2px;
            box-shadow: 0 0 8px rgba(0, 173, 239, 0.5);
        }

        .forklift-mast {
            position: absolute;
            top: -70px;
            right: 6px;
            width: 10px;
            height: 72px;
            background: linear-gradient(180deg, #888, #555);
            border-radius: 2px;
        }

        .forklift-forks {
            position: absolute;
            bottom: -6px;
            left: -18px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .fork {
            width: 22px;
            height: 4px;
            background: linear-gradient(90deg, #bbb, #888);
            border-radius: 0 0 1px 1px;
        }

        .forklift-wheel {
            position: absolute;
            bottom: -10px;
            width: 18px;
            height: 18px;
            background: #222;
            border-radius: 50%;
            border: 2px solid #555;
            box-shadow: 0 2px 6px rgba(0,0,0,0.5);
        }

        .forklift-wheel.w-left  { left: 8px; }
        .forklift-wheel.w-right { right: 8px; }

        /* ── CONVEYOR BELT (bottom center-right) ── */
        .conveyor-belt {
            position: absolute;
            bottom: 18px;
            left: 50%;
            transform: translateX(-20px);
            width: 140px;
        }

        .conveyor-track {
            height: 12px;
            background: linear-gradient(90deg,
                rgba(0,173,239,0.3) 0%,
                rgba(0,100,200,0.5) 50%,
                rgba(0,173,239,0.3) 100%);
            border-radius: 6px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0,173,239,0.3);
        }

        .conveyor-track::after {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                90deg,
                transparent 0, transparent 16px,
                rgba(0,173,239,0.4) 16px, rgba(0,173,239,0.4) 18px
            );
            animation: conveyorScroll 1.5s linear infinite;
        }

        @keyframes conveyorScroll {
            from { background-position-x: 0; }
            to   { background-position-x: 18px; }
        }

        .conveyor-roller {
            position: absolute;
            bottom: -5px;
            width: 14px;
            height: 14px;
            background: radial-gradient(circle, #555, #222);
            border-radius: 50%;
            border: 1px solid #666;
        }

        .conveyor-roller.r-l { left: -3px; }
        .conveyor-roller.r-r { right: -3px; }

        /* ── FLOATING PARTICLES ── */
        .wh-particles {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: var(--mai-sky);
            opacity: 0;
            animation: particleFloat var(--dur, 6s) ease-in-out infinite var(--delay, 0s);
        }

        @keyframes particleFloat {
            0%   { opacity: 0; transform: translateY(0) scale(0.5); }
            20%  { opacity: 0.6; }
            80%  { opacity: 0.4; }
            100% { opacity: 0; transform: translateY(-80px) scale(1.2); }
        }

        /* ── HUD DECORATIVE ELEMENTS ── */
        .wh-hud {
            position: absolute;
            z-index: 3;
            font-family: var(--font-head);
            pointer-events: none;
        }

        .hud-top-left {
            top: 2rem;
            left: 2rem;
        }

        .hud-bottom-left {
            bottom: 1.5rem;
            left: 2rem;
        }

        .hud-label {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--mai-sky);
            opacity: 0.75;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .hud-label::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--mai-sky);
            box-shadow: 0 0 8px var(--mai-sky);
            animation: hudPulse 2s ease-in-out infinite;
        }

        @keyframes hudPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.4; transform: scale(0.7); }
        }

        .hud-coords {
            font-size: 0.6rem;
            color: rgba(0, 173, 239, 0.5);
            letter-spacing: 0.08em;
            margin-top: 0.35rem;
        }

        .hud-status-bar {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .hud-status-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.62rem;
            color: rgba(0, 173, 239, 0.6);
            letter-spacing: 0.05em;
        }

        .hud-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 6px #10b981;
            animation: hudPulse 3s ease-in-out infinite;
        }

        .hud-dot.red  { background: var(--mai-red); box-shadow: 0 0 6px var(--mai-red); }
        .hud-dot.blue { background: var(--mai-sky);  box-shadow: 0 0 6px var(--mai-sky); animation-delay: 1s; }

        /* ── DECORATIVE TECH LINES ── */
        .tech-line {
            position: absolute;
            pointer-events: none;
            z-index: 2;
        }

        .tech-line-h {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0,173,239,0.4), transparent);
        }

        .tech-line-v {
            width: 1px;
            background: linear-gradient(180deg, transparent, rgba(0,173,239,0.3), transparent);
        }

        /* ── GLOW NODES ── */
        .glow-node {
            position: absolute;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            border: 1px solid rgba(0, 173, 239, 0.6);
            background: rgba(0, 173, 239, 0.2);
            box-shadow: 0 0 12px rgba(0, 173, 239, 0.5);
            z-index: 3;
            animation: nodeGlow 3s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes nodeGlow {
            0%, 100% { box-shadow: 0 0 8px rgba(0,173,239,0.4); opacity: 0.7; }
            50%       { box-shadow: 0 0 20px rgba(0,173,239,0.8); opacity: 1; }
        }

        @keyframes floatSlow {
            0%, 100% { transform: translateY(0) rotateY(15deg); }
            50%       { transform: translateY(-10px) rotateY(15deg); }
        }

        .shelf-unit-right {
            animation-name: floatSlowRight;
        }

        @keyframes floatSlowRight {
            0%, 100% { transform: translateY(0) rotateY(-18deg); }
            50%       { transform: translateY(-8px) rotateY(-18deg); }
        }

        /* ── AMBIENT LIGHT BEAMS ── */
        .light-beam {
            position: absolute;
            top: 0;
            width: 1px;
            height: 100%;
            background: linear-gradient(180deg,
                rgba(0, 173, 239, 0.15) 0%,
                rgba(0, 100, 200, 0.05) 60%,
                transparent 100%);
            pointer-events: none;
            z-index: 1;
            animation: beamFlicker 8s ease-in-out infinite;
        }

        @keyframes beamFlicker {
            0%, 100% { opacity: 0.5; }
            30%       { opacity: 0.8; }
            60%       { opacity: 0.3; }
        }

        /* ── WAREHOUSE SILHOUETTE OVERLAY ── */
        .wh-silhouette {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 180px;
            background: linear-gradient(0deg,
                rgba(7, 16, 31, 0.7) 0%,
                transparent 100%);
            z-index: 1;
            pointer-events: none;
        }

        /* ============================================================
           RIGHT SIDE — LOGIN CARD
           ============================================================ */
        .login-form-panel {
            flex: 0 0 460px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 2rem;
            background: linear-gradient(160deg,
                rgba(10, 20, 45, 0.97) 0%,
                rgba(7, 16, 31, 0.99) 100%);
            border-left: 1px solid rgba(0, 173, 239, 0.12);
            position: relative;
            overflow: hidden;
        }

        /* Right panel ambient glow */
        .login-form-panel::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -80px;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(0, 173, 239, 0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        .login-form-panel::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -60px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(26, 63, 168, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ── LOGIN CARD CONTENT ── */
        .login-form-content {
            width: 100%;
            max-width: 380px;
            position: relative;
            z-index: 2;
        }

        /* ── LOGO AREA ── */
        .login-logo-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
        }

        .login-logo-img {
            width: auto;
            max-width: 220px;
            height: 60px;
            object-fit: contain;
            filter: drop-shadow(0 4px 16px rgba(0, 120, 255, 0.25));
            transition: filter 0.3s ease;
        }

        .login-logo-img:hover {
            filter: drop-shadow(0 6px 24px rgba(0, 173, 239, 0.4));
        }

        .login-logo-divider {
            margin-top: 1rem;
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg,
                transparent,
                rgba(0, 173, 239, 0.4),
                rgba(26, 63, 168, 0.3),
                transparent);
        }

        /* ── WELCOME TEXT ── */
        .welcome-heading {
            font-family: var(--font-head);
            font-size: 1.8rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 0.4rem;
            text-align: center;
        }

        .welcome-subtitle {
            font-size: 0.875rem;
            color: var(--wh-muted);
            text-align: center;
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        /* ── SYSTEM BADGE ── */
        .system-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.3rem 0.85rem;
            background: rgba(0, 173, 239, 0.08);
            border: 1px solid rgba(0, 173, 239, 0.2);
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--mai-sky);
            margin-bottom: 1.25rem;
            margin-left: auto;
            margin-right: auto;
        }

        .system-badge-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 6px #10b981;
            animation: hudPulse 2s ease-in-out infinite;
        }

        /* ── GLASSMORPHISM LOGIN CARD ── */
        .login-glass-card {
            background: rgba(255, 255, 255, 0.035);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(0, 173, 239, 0.12);
            border-radius: 22px;
            padding: 2rem 2rem 1.75rem;
            box-shadow:
                0 24px 64px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.06),
                0 0 0 1px rgba(0, 120, 200, 0.05);
            transition: box-shadow 0.4s var(--ease-smooth);
        }

        .login-glass-card:hover {
            box-shadow:
                0 28px 72px rgba(0, 0, 0, 0.55),
                inset 0 1px 0 rgba(255, 255, 255, 0.08),
                0 0 0 1px rgba(0, 173, 239, 0.08);
        }

        /* ── FORM GROUPS ── */
        .form-group-clean {
            margin-bottom: 1.25rem;
        }

        .form-group-clean label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--wh-muted);
            margin-bottom: 0.5rem;
            transition: color 0.25s ease;
        }

        .form-group-clean:focus-within label {
            color: var(--mai-sky);
        }

        /* ── INPUT FIELD ── */
        .input-clean {
            width: 100%;
            padding: 0.875rem 1rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1.5px solid rgba(0, 120, 200, 0.2);
            border-radius: 12px;
            color: #fff;
            font-size: 0.925rem;
            font-family: var(--font-body);
            font-weight: 500;
            outline: none;
            transition: all 0.3s var(--ease-smooth);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .input-clean::placeholder {
            color: rgba(112, 148, 184, 0.5);
        }

        .input-clean:hover {
            border-color: rgba(0, 173, 239, 0.35);
            background: rgba(255, 255, 255, 0.055);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
        }

        .input-clean:focus {
            border-color: var(--mai-sky);
            background: rgba(0, 173, 239, 0.06);
            box-shadow:
                0 0 0 3px rgba(0, 173, 239, 0.15),
                0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .input-clean.is-invalid {
            border-color: rgba(212, 43, 43, 0.7);
            background: rgba(212, 43, 43, 0.05);
            box-shadow: 0 0 0 3px rgba(212, 43, 43, 0.1);
        }

        .field-error-msg {
            margin-top: 0.45rem;
            font-size: 0.775rem;
            color: #fca5a5;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .field-error-msg::before {
            content: '!';
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 15px;
            height: 15px;
            background: rgba(212, 43, 43, 0.25);
            border-radius: 50%;
            font-size: 0.65rem;
            font-weight: 900;
            flex-shrink: 0;
        }

        /* ── PASSWORD FIELD ── */
        .password-field-wrapper {
            position: relative;
        }

        .password-field-wrapper .input-clean {
            padding-right: 3rem;
        }

        .btn-toggle-eye {
            position: absolute;
            right: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0.25rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.5;
            transition: opacity 0.2s ease;
            border-radius: 6px;
        }

        .btn-toggle-eye:hover { opacity: 1; }

        .btn-toggle-eye svg {
            stroke: var(--mai-sky) !important;
        }

        .d-none { display: none !important; }

        /* ── FORM OPTIONS ROW ── */
        .form-options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            gap: 0.5rem;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .custom-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border: 1.5px solid rgba(0, 120, 200, 0.4);
            border-radius: 4px;
            background: rgba(255,255,255,0.04);
            cursor: pointer;
            position: relative;
            transition: all 0.25s ease;
            flex-shrink: 0;
        }

        .custom-checkbox:checked {
            background: var(--mai-blue);
            border-color: var(--mai-sky);
            box-shadow: 0 0 8px rgba(0, 173, 239, 0.3);
        }

        .custom-checkbox:checked::after {
            content: '';
            position: absolute;
            top: 2px; left: 4px;
            width: 5px; height: 8px;
            border: 2px solid #fff;
            border-top: none;
            border-left: none;
            transform: rotate(45deg);
        }

        .checkbox-text {
            font-size: 0.78rem;
            color: var(--wh-muted);
            font-weight: 500;
            user-select: none;
        }

        .forgot-link {
            font-size: 0.78rem;
            color: var(--mai-sky);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease, text-shadow 0.2s ease;
            white-space: nowrap;
        }

        .forgot-link:hover {
            color: var(--mai-sky-light);
            text-shadow: 0 0 12px rgba(0, 173, 239, 0.5);
        }

        /* ── LOGIN BUTTON ── */
        .btn-login-pill {
            width: 100%;
            padding: 0.95rem 1.5rem;
            background: linear-gradient(135deg,
                var(--mai-blue) 0%,
                var(--mai-blue-mid) 50%,
                #1060e0 100%);
            border: 1px solid rgba(0, 173, 239, 0.3);
            border-radius: 12px;
            color: #ffffff;
            font-family: var(--font-head);
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            cursor: pointer;
            transition: all 0.3s var(--ease-smooth);
            box-shadow:
                0 4px 20px rgba(26, 63, 168, 0.45),
                inset 0 1px 0 rgba(255,255,255,0.1);
            position: relative;
            overflow: hidden;
        }

        .btn-login-pill::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg,
                rgba(0, 173, 239, 0.15) 0%,
                transparent 50%,
                rgba(0, 173, 239, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-login-pill:hover {
            transform: translateY(-2px);
            box-shadow:
                0 8px 32px rgba(26, 63, 168, 0.6),
                0 0 20px rgba(0, 173, 239, 0.2),
                inset 0 1px 0 rgba(255,255,255,0.15);
            border-color: rgba(0, 173, 239, 0.5);
        }

        .btn-login-pill:hover::before { opacity: 1; }

        .btn-login-pill:active {
            transform: translateY(0) scale(0.99);
            box-shadow: 0 4px 16px rgba(26, 63, 168, 0.4);
        }

        /* ── FOOTER TEXT ── */
        .login-footer-text {
            margin-top: 1.5rem;
            text-align: center;
        }

        .login-footer-text p {
            font-size: 0.7rem;
            color: rgba(112, 148, 184, 0.5);
            letter-spacing: 0.04em;
            line-height: 1.6;
        }

        .login-footer-text strong {
            color: rgba(112, 148, 184, 0.75);
            font-weight: 700;
        }

        /* ============================================================
           RESPONSIVE
           ============================================================ */
        @media (max-width: 1024px) {
            .login-form-panel {
                flex: 0 0 400px;
            }

            .warehouse-scene {
                max-width: 480px;
                height: 360px;
            }

            .shelf-unit        { width: 140px; height: 220px; }
            .shelf-unit-right  { width: 110px; height: 190px; }
        }

        @media (max-width: 768px) {
            body.login-page-body { overflow: auto; }

            .login-split-wrapper {
                flex-direction: column;
                min-height: 100vh;
            }

            .login-illustration-panel {
                flex: 0 0 auto;
                height: 200px;
                overflow: hidden;
            }

            .warehouse-scene {
                max-width: 380px;
                height: 180px;
                transform: scale(0.7);
                transform-origin: center bottom;
            }

            .hud-top-left, .hud-bottom-left { display: none; }

            .login-form-panel {
                flex: 1;
                border-left: none;
                border-top: 1px solid rgba(0, 173, 239, 0.12);
                padding: 2rem 1.25rem 3rem;
            }
        }

        @media (max-width: 480px) {
            .login-illustration-panel { height: 150px; }

            .welcome-heading { font-size: 1.5rem; }

            .login-glass-card {
                padding: 1.5rem 1.25rem 1.25rem;
                border-radius: 18px;
            }

            .btn-login-pill { padding: 0.85rem; font-size: 0.95rem; }
        }
    </style>
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

        <!-- ======================================================
             LEFT SIDE — 3D WAREHOUSE VISUAL
             ====================================================== -->
        <div class="login-illustration-panel" id="illustrationPanel">

            <!-- Grid overlays -->
            <div class="wh-grid"></div>
            <div class="wh-floor-grid"></div>
            <div class="wh-silhouette"></div>

            <!-- Ambient light beams -->
            <div class="light-beam" style="left:20%; opacity:0.6; animation-delay:0s;"></div>
            <div class="light-beam" style="left:50%; opacity:0.4; animation-delay:2.5s;"></div>
            <div class="light-beam" style="left:78%; opacity:0.5; animation-delay:5s;"></div>

            <!-- Floating particles -->
            <div class="wh-particles" id="warehouseParticles">
                <div class="particle" style="left:12%; bottom:30%; --dur:6s; --delay:0s; background:var(--mai-sky); width:3px; height:3px;"></div>
                <div class="particle" style="left:25%; bottom:20%; --dur:8s; --delay:1.2s; background:var(--mai-blue); width:2px; height:2px;"></div>
                <div class="particle" style="left:38%; bottom:40%; --dur:7s; --delay:0.6s; background:var(--mai-sky); width:4px; height:4px;"></div>
                <div class="particle" style="left:55%; bottom:15%; --dur:9s; --delay:2s; background:white; width:2px; height:2px; opacity:0.4;"></div>
                <div class="particle" style="left:65%; bottom:35%; --dur:6.5s; --delay:3s; background:var(--mai-sky-light); width:3px; height:3px;"></div>
                <div class="particle" style="left:78%; bottom:25%; --dur:7.5s; --delay:1s; background:var(--mai-sky); width:2px; height:2px;"></div>
                <div class="particle" style="left:88%; bottom:45%; --dur:5.5s; --delay:4s; background:white; width:2px; height:2px;"></div>
                <div class="particle" style="left:45%; bottom:55%; --dur:10s; --delay:0.3s; background:var(--mai-blue); width:3px; height:3px;"></div>
            </div>

            <!-- HUD — Top Left -->
            <div class="wh-hud hud-top-left">
                <div class="hud-label">SMART WAREHOUSE SYSTEM</div>
                <div class="hud-coords">ZONE-A · BAY-12 · LEVEL-03</div>
            </div>

            <!-- HUD — Bottom Left Status -->
            <div class="wh-hud hud-bottom-left">
                <div class="hud-status-bar">
                    <div class="hud-status-item">
                        <div class="hud-dot"></div>
                        <span>SYSTEM ONLINE</span>
                    </div>
                    <div class="hud-status-item">
                        <div class="hud-dot blue"></div>
                        <span>WMS ACTIVE</span>
                    </div>
                    <div class="hud-status-item">
                        <div class="hud-dot red"></div>
                        <span>AUTO-SYNC</span>
                    </div>
                </div>
            </div>

            <!-- Decorative tech lines -->
            <div class="tech-line tech-line-h" style="top:18%; left:5%; width:25%; opacity:0.6;"></div>
            <div class="tech-line tech-line-h" style="bottom:20%; right:8%; width:20%; opacity:0.4;"></div>
            <div class="tech-line tech-line-v" style="left:30%; top:10%; height:20%; opacity:0.4;"></div>
            <div class="tech-line tech-line-v" style="right:20%; bottom:15%; height:18%; opacity:0.3;"></div>

            <!-- Glow nodes -->
            <div class="glow-node" style="top:18%; left:5%; animation-delay:0s;"></div>
            <div class="glow-node" style="top:18%; left:30%; animation-delay:0.5s; width:6px; height:6px; border-color:rgba(26,63,168,0.6); background:rgba(26,63,168,0.2); box-shadow:0 0 10px rgba(26,63,168,0.5);"></div>
            <div class="glow-node" style="bottom:20%; right:8%; animation-delay:1s;"></div>
            <div class="glow-node" style="bottom:38%; right:20%; animation-delay:1.5s; width:5px; height:5px;"></div>

            <!-- ──────────────────────────────────────────
                 3D WAREHOUSE SCENE
                 ────────────────────────────────────────── -->
            <div class="warehouse-scene" id="warehouseScene">

                <!-- LEFT SHELF UNIT -->
                <div class="shelf-unit">
                    <div class="shelf-frame"></div>
                    <div class="shelf-bar"></div>
                    <div class="shelf-bar"></div>
                    <div class="shelf-bar"></div>
                    <div class="shelf-item box-a"></div>
                    <div class="shelf-item box-b"></div>
                    <div class="shelf-item box-c"></div>
                    <div class="shelf-item box-d"></div>
                    <div class="shelf-item cyl-a"></div>
                </div>

                <!-- RIGHT SHELF UNIT -->
                <div class="shelf-unit-right">
                    <div class="shelf-frame"></div>
                    <div class="shelf-bar"></div>
                    <div class="shelf-bar"></div>
                    <div class="shelf-bar"></div>
                    <div class="shelf-item box-a"></div>
                    <div class="shelf-item box-b"></div>
                    <div class="shelf-item box-c"></div>
                </div>

                <!-- PALLET STACK (Center) -->
                <div class="pallet-stack">
                    <div class="pallet-boxes">
                        <div class="pallet-box pb-1"></div>
                        <div class="pallet-box pb-2"></div>
                        <div class="pallet-box pb-3"></div>
                    </div>
                    <div class="pallet-base"></div>
                </div>

                <!-- FORKLIFT (Right of center) -->
                <div class="forklift-element">
                    <div class="forklift-body">
                        <div class="forklift-cabin"></div>
                        <div class="forklift-mast"></div>
                        <div class="forklift-forks">
                            <div class="fork"></div>
                            <div class="fork"></div>
                        </div>
                        <div class="forklift-wheel w-left"></div>
                        <div class="forklift-wheel w-right"></div>
                    </div>
                </div>

                <!-- CONVEYOR BELT (Left of center) -->
                <div class="conveyor-belt" style="left: 40%; transform: translateX(-50%);">
                    <div class="conveyor-track">
                        <div class="conveyor-roller r-l"></div>
                        <div class="conveyor-roller r-r"></div>
                    </div>
                </div>

            </div>
            <!-- /warehouse-scene -->

        </div>
        <!-- /login-illustration-panel -->

        <!-- ======================================================
             RIGHT SIDE — LOGIN FORM
             ====================================================== -->
        <div class="login-form-panel">
            <div class="login-form-content">

                <!-- Logo -->
                <div class="login-logo-area">
                    <img
                        src="{{ asset('assets/images/MAI.png') }}"
                        alt="MAI Metalart Astra Indonesia"
                        class="login-logo-img"
                    >
                    <div class="login-logo-divider"></div>
                </div>

                <!-- System Badge -->
                <div style="text-align:center; margin-bottom:1.25rem;">
                    <span class="system-badge">
                        <span class="system-badge-dot"></span>
                        Smart Warehouse System
                    </span>
                </div>

                <!-- Welcome Heading -->
                <h1 class="welcome-heading">Welcome Back</h1>
                <p class="welcome-subtitle">Silakan masuk untuk melanjutkan ke sistem</p>

                <!-- Glassmorphism Card containing the form -->
                <div class="login-glass-card">

                    <form action="{{ url('/login') }}" method="POST" id="loginForm">
                        @csrf

                        <!-- Email / Username Field -->
                        <div class="form-group-clean">
                            <label for="username">Username / Email</label>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                class="input-clean @error('username') is-invalid @enderror"
                                value="{{ old('username') }}"
                                placeholder="Masukkan username atau email"
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
                                    placeholder="Masukkan password"
                                    required
                                    autocomplete="current-password"
                                >
                                <button type="button" class="btn-toggle-eye" id="togglePasswordBtn" title="Toggle password visibility" tabindex="-1">
                                    <!-- Eye Open Icon -->
                                    <svg class="icon-eye-open" viewBox="0 0 24 24" width="20" height="20" stroke="#00adef" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3" fill="#00adef"></circle>
                                    </svg>
                                    <!-- Eye Closed Icon -->
                                    <svg class="icon-eye-closed d-none" viewBox="0 0 24 24" width="20" height="20" stroke="#00adef" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                <!-- /login-glass-card -->

                <!-- Footer -->
                <div class="login-footer-text">
                    <p><strong>MAI Smart Warehouse System</strong></p>
                    <p>PT. Metalart Astra Indonesia &copy; {{ date('Y') }}</p>
                    <p>Consumable Registry v2.0</p>
                </div>

            </div>
        </div>
        <!-- /login-form-panel -->

    </div>
    <!-- /login-split-wrapper -->

    <!-- JavaScript Logic (preserved original + warehouse parallax) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ── Auto dismiss toast notifications (original logic preserved) ──
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => toast.classList.add('show'), 100);
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 350);
                }, 5000);
            });

            // ── Password toggle (original logic preserved) ──
            const passwordInput    = document.getElementById('password');
            const usernameInput    = document.getElementById('username');
            const togglePasswordBtn = document.getElementById('togglePasswordBtn');

            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const iconOpen   = togglePasswordBtn.querySelector('.icon-eye-open');
                    const iconClosed = togglePasswordBtn.querySelector('.icon-eye-closed');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        if (iconOpen)   iconOpen.classList.add('d-none');
                        if (iconClosed) iconClosed.classList.remove('d-none');
                    } else {
                        passwordInput.type = 'password';
                        if (iconOpen)   iconOpen.classList.remove('d-none');
                        if (iconClosed) iconClosed.classList.add('d-none');
                    }
                });

                passwordInput.addEventListener('blur', () => {
                    const iconOpen   = togglePasswordBtn.querySelector('.icon-eye-open');
                    const iconClosed = togglePasswordBtn.querySelector('.icon-eye-closed');
                    if (iconOpen && iconClosed) {
                        iconOpen.classList.remove('d-none');
                        iconClosed.classList.add('d-none');
                    }
                    passwordInput.type = 'password';
                });
            }

            // ── Subtle warehouse scene parallax on mouse move ──
            const scene = document.getElementById('warehouseScene');
            if (scene) {
                let animFrame = null;
                let mx = 0, my = 0;

                window.addEventListener('mousemove', function (e) {
                    mx = e.clientX;
                    my = e.clientY;
                    if (!animFrame) {
                        animFrame = requestAnimationFrame(applyParallax);
                    }
                });

                function applyParallax() {
                    animFrame = null;
                    const ww = window.innerWidth;
                    const wh = window.innerHeight;
                    const ox = (mx / ww - 0.5) * 16;
                    const oy = (my / wh - 0.5) * 8;
                    scene.style.transform = `rotateX(${-oy * 0.4}deg) rotateY(${ox * 0.3}deg) translateZ(0)`;
                }
            }

            // ── Input focus glow enhancement ──
            [usernameInput, passwordInput].forEach(input => {
                if (!input) return;
                input.addEventListener('focus', () => {
                    input.closest('.form-group-clean')
                         ?.querySelector('label')
                         ?.style.setProperty('color', 'var(--mai-sky)');
                });
                input.addEventListener('blur', () => {
                    input.closest('.form-group-clean')
                         ?.querySelector('label')
                         ?.style.removeProperty('color');
                });
            });

        });
    </script>
</body>
</html>
