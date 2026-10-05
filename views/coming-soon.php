<style>
    *,
    *::before,
    *::after {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --gold: #c3a84a;
        --gold-light: #e8d08a;
        --gold-dim: #7a6528;
        --black: #080807;
        --black-2: #0f0e0c;
        --off-white: #f5f0e8;
    }

    html,
    body {
        height: 100%;
        background: var(--black);
        color: var(--off-white);
        font-family: 'Poppins', sans-serif;
        overflow: hidden;
    }

    /* grain overlay */
    body::before {
        content: '';
        position: fixed;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
        opacity: 0.04;
        pointer-events: none;
        z-index: 10;
    }

    /* radial glow */
    body::after {
        content: '';
        position: fixed;
        inset: 0;
        background: radial-gradient(ellipse 60% 50% at 50% 50%, rgba(195, 168, 74, 0.07) 0%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }

    .scene {
        position: relative;
        z-index: 1;
        height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0;
    }

    /* ornamen garis atas */
    .ornament {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 2.5rem;
        opacity: 0;
        animation: fadeUp 1.2s ease forwards 0.3s;
    }

    .ornament-line {
        width: 80px;
        height: 0.5px;
        background: linear-gradient(90deg, transparent, var(--gold-dim), var(--gold), var(--gold-dim), transparent);
    }

    .ornament-diamond {
        width: 6px;
        height: 6px;
        background: var(--gold);
        transform: rotate(45deg);
        box-shadow: 0 0 8px rgba(195, 168, 74, 0.5);
    }

    .ornament-dot {
        width: 3px;
        height: 3px;
        background: var(--gold-dim);
        transform: rotate(45deg);
    }

    /* brand */
    .brand {
        font-family: 'Cinzel Decorative', serif;
        font-size: clamp(0.6rem, 2vw, 0.75rem);
        letter-spacing: 0.45em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 1.5rem;
        opacity: 0;
        animation: fadeUp 1.2s ease forwards 0.5s;
    }

    /* heading utama */
    .heading {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(3.5rem, 12vw, 8rem);
        font-weight: 300;
        line-height: 0.9;
        letter-spacing: -0.01em;
        text-align: center;
        color: var(--off-white);
        opacity: 0;
        animation: fadeUp 1.4s ease forwards 0.7s;
    }

    .heading em {
        font-style: italic;
        color: var(--gold-light);
        display: block;
    }

    /* ornamen tengah */
    .divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 2.5rem 0;
        opacity: 0;
        animation: fadeUp 1.2s ease forwards 1s;
    }

    .divider-line {
        width: 50px;
        height: 0.5px;
        background: linear-gradient(90deg, transparent, var(--gold-dim));
    }

    .divider-line.right {
        background: linear-gradient(90deg, var(--gold-dim), transparent);
    }

    .divider svg {
        width: 18px;
        height: 18px;
        fill: none;
        stroke: var(--gold);
        stroke-width: 1;
        opacity: 0.7;
    }

    /* subtitle */
    .subtitle {
        font-family: 'Poppins', sans-serif;
        font-weight: 200;
        font-size: clamp(0.65rem, 2vw, 0.8rem);
        letter-spacing: 0.3em;
        text-transform: uppercase;
        color: rgba(245, 240, 232, 0.4);
        text-align: center;
        line-height: 2;
        opacity: 0;
        animation: fadeUp 1.2s ease forwards 1.2s;
    }

    /* corner decorations */
    .corner {
        position: fixed;
        width: 60px;
        height: 60px;
        opacity: 0;
        animation: fadeIn 1.5s ease forwards 1.5s;
    }

    .corner svg {
        width: 100%;
        height: 100%;
        stroke: var(--gold-dim);
        stroke-width: 0.5;
        fill: none;
    }

    .corner.tl {
        top: 2rem;
        left: 2rem;
    }

    .corner.tr {
        top: 2rem;
        right: 2rem;
        transform: scaleX(-1);
    }

    .corner.bl {
        bottom: 2rem;
        left: 2rem;
        transform: scaleY(-1);
    }

    .corner.br {
        bottom: 2rem;
        right: 2rem;
        transform: scale(-1);
    }

    /* animasi */
    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(24px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* subtle gold shimmer pada heading */
    .heading {
        background: linear-gradient(135deg, var(--off-white) 0%, var(--off-white) 40%, var(--gold-light) 50%, var(--off-white) 60%, var(--off-white) 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: fadeUp 1.4s ease forwards 0.7s, shimmer 6s linear infinite 2s;
    }

    .heading em {
        background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 50%, var(--gold) 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: fadeUp 1.4s ease forwards 0.7s, shimmer 4s linear infinite 2s;
    }

    @keyframes shimmer {
        from {
            background-position: 200% center;
        }

        to {
            background-position: -200% center;
        }
    }

    @media (max-width: 480px) {
        .ornament-line {
            width: 40px;
        }

        .corner {
            width: 40px;
            height: 40px;
        }

        .corner.tl {
            top: 1rem;
            left: 1rem;
        }

        .corner.tr {
            top: 1rem;
            right: 1rem;
        }

        .corner.bl {
            bottom: 1rem;
            left: 1rem;
        }

        .corner.br {
            bottom: 1rem;
            right: 1rem;
        }
    }
</style>

<!-- corner decorations -->
<div class="corner tl">
    <svg viewBox="0 0 60 60">
        <path d="M2 58 L2 2 L58 2" />
    </svg>
</div>
<div class="corner tr">
    <svg viewBox="0 0 60 60">
        <path d="M2 58 L2 2 L58 2" />
    </svg>
</div>
<div class="corner bl">
    <svg viewBox="0 0 60 60">
        <path d="M2 58 L2 2 L58 2" />
    </svg>
</div>
<div class="corner br">
    <svg viewBox="0 0 60 60">
        <path d="M2 58 L2 2 L58 2" />
    </svg>
</div>

<div class="scene">

    <div class="ornament">
        <div class="ornament-dot"></div>
        <div class="ornament-line"></div>
        <div class="ornament-diamond"></div>
        <div class="ornament-line" style="background: linear-gradient(90deg, var(--gold-dim), var(--gold), var(--gold-dim), transparent)"></div>
        <div class="ornament-dot"></div>
    </div>

    <p class="brand">DigiInvite</p>

    <h1 class="heading">
        Coming<br>
        <em>Soon</em>
    </h1>

    <div class="divider">
        <div class="divider-line"></div>
        <svg viewBox="0 0 24 24">
            <path d="M12 2 L14.5 9.5 L22 12 L14.5 14.5 L12 22 L9.5 14.5 L2 12 L9.5 9.5 Z" />
        </svg>
        <div class="divider-line right"></div>
    </div>

    <p class="subtitle">
        Undangan digital yang elegan<br>
        sedang disiapkan untuk anda
    </p>

</div>