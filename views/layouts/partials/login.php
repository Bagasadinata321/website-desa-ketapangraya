<div class="hero-overlay">
    <div class="login-card">
        <div class="">

            <h2>Login Admin</h2>
            <?php if (!empty($error)) : ?>
                2 <p style="color:red;"><?= $error ?></p>
            <?php endif; ?>

            <form method="POST" action="<?= url('/admin/login') ?>">

                <div class="login-form">
                    <label>Username</label>
                    <input
                        type="text"
                        name="username"
                        value="<?= $old['username'] ?? '' ?>"
                        required>
                </div>

                <div class="login-form">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit">Login</button>
            </form>
        </div>
        <span>Ingin Mengecek Undangan? </span><a href="<?= url('/token') ?>">Masukkan Token</a>
    </div>
</div>