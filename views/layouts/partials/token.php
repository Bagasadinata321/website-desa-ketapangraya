<div class="hero-overlay">
    <div class="login-card">
        <div class="">

            <h2>Client</h2>
            <?php if (!empty($error)) : ?>
                2 <p style="color:red;"><?= $error ?></p>
            <?php endif; ?>

            <form method="POST" action="<?= url('/token/validate') ?>">
                <div class="login-form">
                    <label>Masukkan Token</label>
                    <input type="text" name="token" required>
                </div>

                <button type="submit">Masuk</button>
            </form>
        </div>
    </div>
</div>