<?php

/**
 * View: Public Home (Beranda)
 * Expects: $sections, $destinasiList, $highlights, $produkList
 */

// Helper fallback untuk section statis
$hero         = $sections['hero'] ?? [];
$mengenal     = $sections['mengenal_desa'] ?? [];
$potensi      = $sections['potensi_desa'] ?? [];
$statsSection = $sections['informasi_singkat'] ?? [];

// Ambil data statistik dari JSON meta atau fallback variabel 'stats'
$statsData    = $statsSection['meta']['stats'] ?? $statsSection['stats'] ?? [];
?>

<!-- ============ HERO SECTION ============ -->
<header class="hero">
    <div class="hero__bg" data-parallax="0.22">
        <?php
        $heroImg = !empty($hero['image']) ? ltrim($hero['image'], '/') : null;
        ?>
        <?php if ($heroImg): ?>
            <img src="<?= url(htmlspecialchars($heroImg)) ?>" alt="Hero BG" style="width:100%;height:100%;object-fit:cover;" />
        <?php else: ?>
            <div class="ph">
                <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 16l5-5 4 4 5-6 4 5" />
                    <circle cx="12" cy="12" r="10" />
                </svg>
                <span class="ph__label">Foto udara: pemukiman &amp; muara Desa Ketapang Raya</span>
            </div>
        <?php endif; ?>
    </div>
    <div class="hero__overlay"></div>
    <div class="container hero__content">
        <span class="eyebrow"><?= htmlspecialchars($hero['subtitle'] ?? 'Desa Ketapang Raya') ?></span>
        <h1 data-reveal="fade"><?= nl2br(htmlspecialchars($hero['title'] ?? "Mengenal Lebih Dekat\nDesa Ketapang Raya")) ?></h1>
        <p data-reveal="fade">
            <?= htmlspecialchars($hero['description'] ?? 'Menjelajahi potensi alam, budaya, dan kehidupan masyarakat pesisir.') ?>
        </p>
        <a href="<?= url('destinasi-produk') ?>" class="btn btn--primary">
            Jelajahi Desa
            <svg class="btn__icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 12h14M13 6l6 6-6 6" />
            </svg>
        </a>
    </div>
    <div class="hero__scroll">
        <span class="hero__scroll-icon"></span>
        Scroll untuk mengenal desa
    </div>
</header>

<!-- ============ MENGENAL LEBIH DEKAT ============ -->
<section class="section section--surface">
    <div class="container grid-2">
        <div class="intro__media" data-reveal="left">
            <?php
            $mengenalImg = !empty($mengenal['image']) ? ltrim($mengenal['image'], '/') : null;
            ?>
            <?php if ($mengenalImg): ?>
                <img src="<?= url(htmlspecialchars($mengenalImg)) ?>" alt="Mengenal Desa" style="width:100%;height:100%;object-fit:cover;border-radius:var(--radius-lg);" />
            <?php else: ?>
                <div class="ph">
                    <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="9" cy="9" r="3" />
                        <path d="M3 20l6-6 4 4 8-9" />
                    </svg>
                    <span class="ph__label">Foto: musyawarah warga di balai desa</span>
                </div>
            <?php endif; ?>
        </div>
        <div class="intro__body" data-reveal="right">
            <h2><?= nl2br(htmlspecialchars($mengenal['title'] ?? "Mengenal Lebih Dekat\nDesa Ketapang Raya")) ?></h2>
            <p>
                <?= htmlspecialchars($mengenal['description'] ?? 'Desa Ketapang Raya merupakan desa pesisir dengan kekayaan alam, masyarakat yang harmonis, serta berbagai potensi lokal yang terus berkembang.') ?>
            </p>
            <a href="<?= url('tentang-kami') ?>" class="link-arrow">
                Selengkapnya
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M13 6l6 6-6 6" />
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- ============ POTENSI DESA ============ -->
<section class="section section--alt">
    <div class="container">
        <div class="section__head" data-reveal="fade">
            <h2>Potensi Desa</h2>
            <p>Menjelajahi berbagai potensi yang menjadi keunggulan dan ciri khas Desa Ketapang Raya</p>
        </div>

        <div class="grid-3">
            <?php
            // Mendukung array dari 'items' (JSON) atau $highlights dari database
            $potensiItems = $potensi['items'] ?? $highlights ?? [];
            ?>
            <?php if (!empty($potensiItems) && is_array($potensiItems)): ?>
                <?php foreach ($potensiItems as $item): ?>
                    <?php
                    $potensiImgRaw = $item['thumbnail_url'] ?? $item['image'] ?? null;
                    $potensiImg    = !empty($potensiImgRaw) ? ltrim($potensiImgRaw, '/') : null;
                    $itemTitle     = $item['title'] ?? '';
                    $itemDesc      = $item['description'] ?? $item['excerpt'] ?? '';
                    ?>
                    <div class="potensi-card" data-reveal="scale" data-stagger-group="potensi">
                        <div class="potensi-card__media">
                            <?php if ($potensiImg): ?>
                                <img src="<?= url(htmlspecialchars($potensiImg)) ?>" alt="<?= htmlspecialchars($itemTitle) ?>" style="width:100%;height:100%;object-fit:cover;" />
                            <?php else: ?>
                                <div class="ph">
                                    <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M2 12c3-4 7-6 10-6s7 2 10 6c-3 4-7 6-10 6s-7-2-10-6Z" />
                                        <circle cx="12" cy="12" r="2" />
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h3><?= htmlspecialchars($itemTitle) ?></h3>
                        <p><?= htmlspecialchars($itemDesc) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Static Fallback Items -->
                <div class="potensi-card" data-reveal="scale" data-stagger-group="potensi">
                    <div class="potensi-card__media">
                        <div class="ph"><svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M2 12c3-4 7-6 10-6s7 2 10 6c-3 4-7 6-10 6s-7-2-10-6Z" />
                                <circle cx="12" cy="12" r="2" />
                            </svg></div>
                    </div>
                    <h3>Perikanan</h3>
                    <p>Cumi-cumi dan sebagian lobster hasil tangkapan laut, serta udang dari budidaya tambak warga.</p>
                </div>
                <div class="potensi-card" data-reveal="scale" data-stagger-group="potensi">
                    <div class="potensi-card__media">
                        <div class="ph"><svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M4 18c2-6 4-10 8-14 4 4 6 8 8 14" />
                                <path d="M4 18h16" />
                            </svg></div>
                    </div>
                    <h3>Garam Telaga Bagek</h3>
                    <p>Garam halus tradisional yang diproduksi dengan bahan bakar kayu bakar, sudah tembus pasar luar daerah.</p>
                </div>
                <div class="potensi-card" data-reveal="scale" data-stagger-group="potensi">
                    <div class="potensi-card__media">
                        <div class="ph"><svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="4" y="4" width="16" height="16" rx="2" />
                                <path d="M4 10h16M10 4v16" />
                            </svg></div>
                    </div>
                    <h3>Garam Kedome</h3>
                    <p>Garam yodium hasil produksi skala pabrik, menjadi salah satu andalan ekonomi Dusun Kedome.</p>
                </div>
                <div class="potensi-card" data-reveal="scale" data-stagger-group="potensi">
                    <div class="potensi-card__media">
                        <div class="ph"><svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 21c0-4 4-6 8-6s8 2 8 6" />
                            </svg></div>
                    </div>
                    <h3>Budaya Desa</h3>
                    <p>Tradisi Madak — aktivitas masyarakat pesisir mencari kerang saat air laut surut, masih lestari hingga kini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ============ DESTINASI UNGGULAN (LOCKED - DINAMIS DARI DB) ============ -->
<section class="section section--surface">
    <div class="container">
        <div class="section__head" data-reveal="fade">
            <h2>Destinasi Unggulan</h2>
            <p>Temukan tempat menarik yang menjadi daya tarik desa Ketapang Raya</p>
        </div>

        <div class="grid-3">
            <?php
            $destList = $destinasiList ?? $destinations ?? [];
            ?>
            <?php if (!empty($destList)): ?>
                <?php foreach ($destList as $dest): ?>
                    <?php
                    // Ambil path gambar dari kemungkinan properti yang ada
                    $destImgRaw = $dest['thumbnail_url'] ?? $dest['cover_url'] ?? $dest['image'] ?? null;
                    $destImg    = !empty($destImgRaw) ? ltrim($destImgRaw, '/') : null;
                    $destSlug   = $dest['slug'] ?? '';
                    ?>
                    <div class="feature-card" data-reveal="scale" data-stagger-group="destinasi">
                        <?php if ($destImg): ?>
                            <img src="<?= url(htmlspecialchars($destImg)) ?>" alt="<?= htmlspecialchars($dest['title']) ?>" style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;z-index:0;" />
                        <?php else: ?>
                            <div class="ph">
                                <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M4 20V9M4 9l8-6 8 6M4 9l16 0M20 20V9" />
                                </svg>
                            </div>
                        <?php endif; ?>

                        <div class="feature-card__overlay"></div>
                        <div class="feature-card__body">
                            <h3><?= htmlspecialchars($dest['title']) ?></h3>
                            <p><?= htmlspecialchars($dest['excerpt'] ?? '') ?></p>
                            <a href="<?= url('destinasi/' . $destSlug) ?>" class="link-arrow link-arrow--light">
                                Lihat Selengkapnya
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M13 6l6 6-6 6" />
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Default Placeholder kalau DB belum ada data -->
                <div class="feature-card" data-reveal="scale" data-stagger-group="destinasi">
                    <div class="ph">
                        <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M4 20V9M4 9l8-6 8 6M4 9l16 0M20 20V9" />
                        </svg>
                    </div>
                    <div class="feature-card__overlay"></div>
                    <div class="feature-card__body">
                        <h3>Ekowisata Mangrove</h3>
                        <p>Nikmati keindahan hutan mangrove yang penting bagi ekosistem lingkungan.</p>
                        <a href="<?= url('destinasi/ekowisata-mangrove') ?>" class="link-arrow link-arrow--light">
                            Lihat Selengkapnya
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>



<!-- ============ PRODUK UNGGULAN DESA ============ -->
<section class="section section--surface">
    <div class="container">
        <div class="section__head" data-reveal="fade">
            <h2>Produk Unggulan Desa</h2>
            <p>Hasil karya dan olahan lokal khas masyarakat Desa Ketapang Raya</p>
        </div>

        <div class="grid-3">
            <?php
            $pList = $produkList ?? $products ?? [];
            ?>
            <?php if (!empty($pList) && is_array($pList)): ?>
                <?php foreach ($pList as $prod): ?>
                    <?php
                    $prodImgRaw = $prod['thumbnail_url'] ?? $prod['cover_url'] ?? $prod['image'] ?? null;
                    $prodImg    = !empty($prodImgRaw) ? ltrim($prodImgRaw, '/') : null;
                    $prodTitle  = $prod['title'] ?? '';
                    $prodPrice  = $prod['price_info'] ?? $prod['price'] ?? '';
                    $prodExcerpt = $prod['excerpt'] ?? $prod['description'] ?? '';
                    $prodSlug   = $prod['slug'] ?? '';
                    ?>
                    <div class="potensi-card" data-reveal="scale" data-stagger-group="produk">
                        <div class="potensi-card__media">
                            <?php if ($prodImg): ?>
                                <img src="<?= url(htmlspecialchars($prodImg)) ?>" alt="<?= htmlspecialchars($prodTitle) ?>" style="width:100%;height:100%;object-fit:cover;" />
                            <?php else: ?>
                                <div class="ph">
                                    <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h3><?= htmlspecialchars($prodTitle) ?></h3>
                        <?php if (!empty($prodPrice)): ?>
                            <span class="badge" style="display:inline-block; margin-bottom: 0.5rem; font-size: 0.85rem; font-weight: 600; color: var(--clr-primary, #007bb6);">
                                <?= htmlspecialchars($prodPrice) ?>
                            </span>
                        <?php endif; ?>
                        <p><?= htmlspecialchars($prodExcerpt) ?></p>
                        <?php if (!empty($prodSlug)): ?>
                            <a href="<?= url('produk/' . $prodSlug) ?>" class="link-arrow" style="margin-top: auto; padding-top: 0.5rem;">
                                Detail Produk
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M13 6l6 6-6 6" />
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Static Fallback Items jika belum ada data di DB -->
                <div class="potensi-card" data-reveal="scale" data-stagger-group="produk">
                    <div class="potensi-card__media">
                        <div class="ph">
                            <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                    </div>
                    <h3>Garam Halus Telaga Bagek</h3>
                    <span class="badge" style="display:inline-block; margin-bottom: 0.5rem; font-size: 0.85rem; font-weight: 600; color: var(--clr-primary, #007bb6);">Hubungi Kami</span>
                    <p>Garam tradisional berkualitas tinggi hasil olahan warga pesisir Desa Ketapang Raya.</p>
                </div>
                <div class="potensi-card" data-reveal="scale" data-stagger-group="produk">
                    <div class="potensi-card__media">
                        <div class="ph">
                            <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                    </div>
                    <h3>Olahan Hasil Laut & Cumi</h3>
                    <span class="badge" style="display:inline-block; margin-bottom: 0.5rem; font-size: 0.85rem; font-weight: 600; color: var(--clr-primary, #007bb6);">Mulai Rp 25.000</span>
                    <p>Cumi-cumi segar dan produk olahan hasil tangkapan nelayan lokal.</p>
                </div>
                <div class="potensi-card" data-reveal="scale" data-stagger-group="produk">
                    <div class="potensi-card__media">
                        <div class="ph">
                            <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                    </div>
                    <h3>Kerajinan Warga Pesisir</h3>
                    <span class="badge" style="display:inline-block; margin-bottom: 0.5rem; font-size: 0.85rem; font-weight: 600; color: var(--clr-primary, #007bb6);">Variatif</span>
                    <p>Produk kreatif kerajinan tangan khas masyarakat pesisir Ketapang Raya.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- ============ INFORMASI SINGKAT DESA (STATISTIK) ============ -->
<section class="section--tight">
    <div>
        <div class="stats-band" data-reveal="fade">
            <div class="stats-band__bg" data-parallax="0.12">
                <?php
                $statsImg = !empty($statsSection['image']) ? ltrim($statsSection['image'], '/') : null;
                ?>
                <?php if ($statsImg): ?>
                    <img src="<?= url(htmlspecialchars($statsImg)) ?>" alt="Stats BG" style="width:100%;height:100%;object-fit:cover;" />
                <?php else: ?>
                    <div class="ph">
                        <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M3 16l5-5 4 4 5-6 4 5" />
                            <circle cx="12" cy="12" r="10" />
                        </svg>
                        <span class="ph__label">Foto: suasana senja pesisir desa</span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="stats-band__overlay"></div>
            <div class="stats-band__inner">
                <p class="stats-band__text">
                    <?= htmlspecialchars($statsSection['description'] ?? 'Desa pesisir yang kaya akan alam, budaya, dan semangat gotong royong.') ?>
                </p>
                <div class="stats-band__stats">
                    <?php if (!empty($statsData) && is_array($statsData)): ?>
                        <?php foreach ($statsData as $st): ?>
                            <div>
                                <div class="stat__number" data-count="<?= htmlspecialchars($st['number'] ?? '0') ?>">0</div>
                                <div class="stat__label"><?= htmlspecialchars($st['label'] ?? '') ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div>
                            <div class="stat__number" data-count="1499">0</div>
                            <div class="stat__label">Jumlah KK</div>
                        </div>
                        <div>
                            <div class="stat__number" data-count="2856">0</div>
                            <div class="stat__label">Penduduk</div>
                        </div>
                        <div>
                            <div class="stat__number" data-count="5">0</div>
                            <div class="stat__label">Dusun</div>
                        </div>
                        <div>
                            <div class="stat__number" data-count="1998">0</div>
                            <div class="stat__label">Tahun Berdiri</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>