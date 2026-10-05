<div class="topbar" id="topbar">
  <div class="container topbar__inner">
    <span class="topbar__location">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z" />
        <circle cx="12" cy="10" r="2.5" />
      </svg>
      <?= htmlspecialchars(setting('topbar_location', 'Desa Ketapang Raya, Indonesia')) ?>
    </span>
    <div class="topbar__socials">
      <?php if ($ig = setting('social_instagram')): ?>
        <a href="<?= htmlspecialchars($ig) ?>" target="_blank" rel="noopener" aria-label="Instagram">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="18" height="18" rx="5" />
            <circle cx="12" cy="12" r="4" />
            <circle cx="17.5" cy="6.5" r="1" />
          </svg>
        </a>
      <?php endif; ?>

      <?php if ($fb = setting('social_facebook')): ?>
        <a href="<?= htmlspecialchars($fb) ?>" target="_blank" rel="noopener" aria-label="Facebook">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
          </svg>
        </a>
      <?php endif; ?>

      <?php if ($yt = setting('social_youtube')): ?>
        <a href="<?= htmlspecialchars($yt) ?>" target="_blank" rel="noopener" aria-label="YouTube">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="2" y="5" width="20" height="14" rx="4" />
            <path d="M10 9.5v5l4.5-2.5z" fill="currentColor" stroke="none" />
          </svg>
        </a>
      <?php endif; ?>
    </div>
  </div>
</div>
<nav class="navbar" id="navbar">
  <div class="container navbar__inner">
    <a href="<?= url('/') ?>" class="navbar__logo">
      <span class="navbar__logo-icon">
        <?php if ($logo = setting('village_logo')): ?>
          <!-- TAMPILKAN GAMBAR LOGO JIKA TERSEDIA -->
          <img src="<?= url(htmlspecialchars($logo)) ?>" alt="Logo <?= htmlspecialchars(setting('village_name', 'Desa Ketapang Raya')) ?>" style="width: 100%; height: 100%; object-fit: contain;" />
        <?php else: ?>
          <!-- FALLBACK: PAKAI SVG PLACEHOLDER JIKA BELUM ADA GAMBAR -->
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 22V10" />
            <path d="M12 10C12 6 9 3 5 3c0 4.5 2.5 7.5 7 7z" />
            <path d="M12 14C12 10 15 7 19 7c0 4.5-2.5 7.5-7 7z" />
          </svg>
        <?php endif; ?>
      </span>
      <span class="navbar__logo-text">
        <?= str_replace(' ', ' ', htmlspecialchars(setting('village_name', 'Desa Ketapang Raya'))) ?>
      </span>
    </a>

    <ul class="navbar__menu" id="navMenu">
      <li><a href="<?= url('/') ?>" data-nav="home">Home</a></li>
      <li><a href="<?= url('/test-live/produk') ?>" data-nav="potensi">Potensi Desa</a></li>
      <li><a href="<?= url('/test-live/about') ?>" data-nav="tentang">Tentang Kami</a></li>
      <li><a href="<?= url('/login') ?>" data-nav="login">Login</a></li>
    </ul>

    <button class="navbar__toggle" id="navToggle" aria-label="Buka menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>