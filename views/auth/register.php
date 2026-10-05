<!-- ============ AUTH: REGISTER ============ -->
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
        <h1 class="auth__logo-text" style="width:100%; text-align:center; margin-bottom:10px">Desa Ketapang Raya</h1>

        <h2 class="auth__title">Pendaftaran Akun Admin</h2>

        <form class="auth__form" method="POST" action="<?= url('/register-submit') ?>">

            <!-- 1. FIELD NAMA LENGKAP -->
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
                <input
                    type="text"
                    name="nama"
                    class="form-field__input"
                    placeholder="Nama Lengkap"
                    required />
            </label>

            <!-- 2. FIELD EMAIL -->
            <label class="form-field">
                <svg
                    class="form-field__icon"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                    <polyline points="22,6 12,13 2,6" />
                </svg>
                <input
                    type="email"
                    name="email"
                    class="form-field__input"
                    placeholder="Alamat Email"
                    required />
            </label>

            <!-- 3. FIELD PASSWORD -->
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
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-field__input"
                    placeholder="Kata Sandi"
                    autocomplete="new-password"
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

            <!-- 4. FIELD KONFIRMASI PASSWORD -->
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
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-field__input"
                    placeholder="Konfirmasi Kata Sandi"
                    autocomplete="new-password"
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

            <div class="auth__row" style="justify-content: flex-end;">
                <a href="<?= url('/login') ?>" class="auth__forgot">Sudah punya akun? Login disini</a>
            </div>

            <!-- TOMBOL SUBMIT REGISTER -->
            <button type="submit" class="btn btn--primary btn--block">
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
                Daftar Sekarang
            </button>
        </form>
    </div>
</main>

<script>
    // 1. Toggle password visibility (mendukung multiple toggle untuk kata sandi & konfirmasi)
    document.querySelectorAll(".form-field__toggle").forEach((btn) => {
        btn.addEventListener("click", function() {
            const input = this.closest(".form-field").querySelector(".form-field__input");
            const isHidden = input.type === "password";
            input.type = isHidden ? "text" : "password";
            this.setAttribute("aria-label", isHidden ? "Sembunyikan kata sandi" : "Tampilkan kata sandi");
        });
    });

    // 2. Validasi kesesuaian Password & Konfirmasi Password sebelum Submit
    document.querySelector(".auth__form").addEventListener("submit", function(e) {
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("password_confirmation").value;

        if (password !== confirmPassword) {
            e.preventDefault(); // Batalkan pengiriman form
            alert("Konfirmasi kata sandi tidak cocok. Harap periksa kembali!");
            document.getElementById("password_confirmation").focus();
        }
    });

    // 3. Simple reveal on load
    document.querySelectorAll("[data-reveal]").forEach((el) => el.classList.add("is-visible"));
</script>