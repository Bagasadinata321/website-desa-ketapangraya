<!-- ============ AUTH: LOGIN ============ -->
<main class="auth">
    <div class="auth__bg" data-parallax="0.12">
        <div class="ph">
            <svg
                class="ph__icon"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.5">
                <path d="M3 16l5-5 4 4 5-6 4 5" />
                <circle cx="12" cy="12" r="10" />
            </svg>
            <span class="ph__label">Foto udara: muara &amp; pemukiman Desa Ketapang Raya saat senja</span>
        </div>
    </div>
    <div class="auth__overlay"></div>

    <div class="auth__card" data-reveal="fade">
        <h1 class="auth__logo-text" style="width:100%; text-align:center;margin-bottom:10px">Desa Ketapang Raya</h1>

        <h2 class="auth__title">Masuk ke Akun Anda</h2>

        <!-- PERBAIKAN: Tambahkan method="POST" dan action ke route/URL loginProcess Anda -->
        <form class="auth__form" method="POST" action="<?= url('/login-process') ?>">

            <label class="form-field">
                <svg
                    class="form-field__icon"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5">
                    <circle cx="12" cy="8" r="4" />
                    <path d="M4 21c0-4 4-6 8-6s8 2 8 6" />
                </svg>
                <!-- PERBAIKAN: Tambahkan name="email" (atau name="username" tergantung di database Anda) -->
                <input
                    type="text"
                    name="email"
                    class="form-field__input"
                    placeholder="Username atau Email"
                    autocomplete="username"
                    required />
            </label>

            <label class="form-field">
                <svg
                    class="form-field__icon"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5">
                    <rect x="4" y="10" width="16" height="10" rx="2" />
                    <path d="M8 10V7a4 4 0 0 1 8 0v3" />
                </svg>
                <!-- PERBAIKAN: Tambahkan name="password" -->
                <input
                    type="password"
                    name="password"
                    class="form-field__input"
                    placeholder="Kata Sandi"
                    autocomplete="current-password"
                    required />
                <button
                    type="button"
                    class="form-field__toggle"
                    aria-label="Tampilkan kata sandi">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </button>
            </label>

            <div class="auth__row">
                <label class="checkbox">
                    <!-- Anda bisa tambahkan checkbox Remember Me disini jika perlu -->
                </label>
                <a href="<?= url('/lupa-sandi') ?>" class="auth__forgot">Lupa kata sandi?</a>
            </div>

            <button type="submit" class="btn btn--primary btn--block">
                <svg
                    class="btn__icon"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                    <path d="M10 17l5-5-5-5" />
                    <path d="M15 12H3" />
                </svg>
                Login
            </button>
            <div style="width: 100%; height:1px; background: var(--clr-border-dark)"></div>
            <a href="<?= url('/register') ?>" class="btn btn--primary btn--block" style="color: var(--clr-primary);
  background-color: var(--clr-text-white); border: 1px solid var(--clr-primary)">
                <svg
                    class="btn__icon"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <line x1="20" y1="8" x2="20" y2="14"></line>
                    <line x1="23" y1="11" x2="17" y2="11"></line>
                </svg>
                Daftar
            </a>
        </form>
    </div>
</main>

<script>
    // Toggle password visibility
    document.querySelector(".form-field__toggle").addEventListener("click", function() {
        const input = this.closest(".form-field").querySelector(".form-field__input");
        const isHidden = input.type === "password";
        input.type = isHidden ? "text" : "password";
        this.setAttribute("aria-label", isHidden ? "Sembunyikan kata sandi" : "Tampilkan kata sandi");
    });

    // Simple reveal on load
    document.querySelectorAll("[data-reveal]").forEach((el) => el.classList.add("is-visible"));
</script>