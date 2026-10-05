<footer class="site-footer">
  <div class="container">
    <div class="footer__grid">
      <div class="footer__col">
        <div class="footer__logo">
          <span class="navbar__logo-icon">
            <?php if ($logo = setting('village_logo')): ?>
              <!-- TAMPILKAN GAMBAR LOGO -->
              <img src="<?= url(htmlspecialchars($logo)) ?>" alt="Logo <?= htmlspecialchars(setting('village_name', 'Desa Ketapang Raya')) ?>" style="width: 100%; height: 100%; object-fit: contain;" />
            <?php else: ?>
              <!-- FALLBACK SVG PLACEHOLDER -->
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22V10" />
                <path d="M12 10C12 6 9 3 5 3c0 4.5 2.5 7.5 7 7z" />
                <path d="M12 14C12 10 15 7 19 7c0 4.5-2.5 7.5-7 7z" />
              </svg>
            <?php endif; ?>
          </span>
          <span class="footer__logo-text"><?= htmlspecialchars(setting('village_name', 'Desa Ketapang Raya')) ?></span>
        </div>
      </div>

      <div class="footer__col">
        <h4>Informasi Desa</h4>
        <address>
          <span><?= htmlspecialchars(setting('footer_address', 'Jalan Desa Ketapang Raya, Tj. Luar, Keruak, Kabupaten Lombok Timur, Nusa Tenggara Bar. 83672')) ?></span>
          <?php if ($email = setting('footer_email')): ?>
            <a href="mailto:<?= htmlspecialchars($email) ?>"><?= htmlspecialchars($email) ?></a>
          <?php endif; ?>
        </address>

        <div class="footer__map" style="margin-top: 1rem;">
          <?php if ($mapIframe = setting('footer_map_iframe')): ?>
            <?= $mapIframe ?>
          <?php else: ?>
            <div class="ph">
              <span class="ph__label">Peta lokasi desa</span>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="footer__col">
        <h4>Pihak Pendukung</h4>
        <ul>
          <li>Pemerintah <?= htmlspecialchars(setting('village_name', 'Desa Ketapang Raya')) ?></li>
          <li>KKN Universitas Mataram</li>
        </ul>
      </div>

      <div class="footer__col">
        <h4>Navigasi</h4>
        <ul>
          <li><a href="<?= url('/') ?>">Home</a></li>
          <li><a href="<?= url('/test-live/produk') ?>">Potensi Desa</a></li>
          <li><a href="<?= url('/test-live/about') ?>">Tentang Kami</a></li>
        </ul>
      </div>
    </div>

    <div class="footer__bottom">
      <span>&copy; <?= date('Y') ?> <?= htmlspecialchars(setting('village_name', 'Desa Ketapang Raya')) ?></span>
      <div class="footer__socials">
        <?php if ($ig = setting('social_instagram')): ?>
          <a href="<?= htmlspecialchars($ig) ?>" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="18" height="18" rx="5" />
              <circle cx="12" cy="12" r="4" />
              <circle cx="17.5" cy="6.5" r="1" />
            </svg></a>
        <?php endif; ?>
        <?php if ($fb = setting('social_facebook')): ?>
          <a href="<?= htmlspecialchars($fb) ?>" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
            </svg></a>
        <?php endif; ?>
        <?php if ($yt = setting('social_youtube')): ?>
          <a href="<?= htmlspecialchars($yt) ?>" target="_blank" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="2" y="5" width="20" height="14" rx="4" />
              <path d="M10 9.5v5l4.5-2.5z" fill="currentColor" stroke="none" />
            </svg></a>
        <?php endif; ?>
      </div>
      <span><?= htmlspecialchars(setting('footer_credit', 'Website dikembangkan oleh KKN')) ?></span>
    </div>
  </div>
</footer>