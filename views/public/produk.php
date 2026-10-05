<?php

/**
 * View: Public Destinasi & Produk
 * Expects: $sections, $destinasiList, $produkList
 */

// Helper fallback untuk section statis dari database
$hero             = $sections['hero'] ?? [];
$pengantar        = $sections['pengantar'] ?? [];
$langkahEkowisata = $sections['langkah_ekowisata'] ?? [];
$quoteSection     = $sections['quote'] ?? [];
?>

<!-- ============ HERO SECTION ============ -->
<header class="hero">
    <div class="hero__bg" data-parallax="0.22">
        <?php
        $heroImgRaw = $hero['image'] ?? $hero['thumbnail_url'] ?? null;
        $heroImg    = !empty($heroImgRaw) ? ltrim($heroImgRaw, '/') : null;
        ?>
        <?php if ($heroImg): ?>
            <img src="<?= url(htmlspecialchars($heroImg)) ?>" alt="Hero BG" style="width:100%;height:100%;object-fit:cover;" />
        <?php else: ?>
            <div class="ph">
                <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M4 20V9M4 9l8-6 8 6M4 9l16 0M20 20V9" />
                </svg>
                <span class="ph__label">Foto udara: kawasan hutan mangrove &amp; sungai</span>
            </div>
        <?php endif; ?>
    </div>
    <div class="hero__overlay"></div>
    <div class="container hero__content">
        <span class="eyebrow"><?= htmlspecialchars($hero['subtitle'] ?? 'Potensi Desa') ?></span>
        <h1 data-reveal="fade">
            <?= nl2br(htmlspecialchars($hero['title'] ?? "Destinasi Wisata &\nProduk Unggulan\nDesa Ketapang Raya")) ?>
        </h1>
        <p data-reveal="fade">
            <?= htmlspecialchars($hero['description'] ?? 'Menjelajahi keindahan alam dan hasil karya masyarakat desa yang penuh potensi.') ?>
        </p>
    </div>
</header>

<!-- ============ PENGANTAR POTENSI DESA ============ -->
<section class="section section--surface">
    <div class="container">
        <div class="editorial-intro" data-reveal="fade">
            <span class="editorial-intro__rule"></span>
            <span class="eyebrow"><?= htmlspecialchars($pengantar['subtitle'] ?? 'Potensi Desa') ?></span>
            <h2><?= htmlspecialchars($pengantar['title'] ?? 'Kekayaan yang Tumbuh dari Pesisir Ketapang Raya') ?></h2>
            <p>
                <?= htmlspecialchars($pengantar['description'] ?? 'Di sepanjang pesisirnya, Desa Ketapang Raya menyimpan potensi yang tumbuh dari laut, tambak, dan tangan-tangan masyarakat yang merawatnya secara turun-temurun — mulai dari hasil tangkapan laut, industri garam rakyat, hingga keindahan alam yang masih terjaga.') ?>
            </p>
            <?php
            $desc2 = $pengantar['meta']['description_2'] ?? $pengantar['description_2'] ?? null;
            ?>
            <?php if (!empty($desc2)): ?>
                <p><?= htmlspecialchars($desc2) ?></p>
            <?php else: ?>
                <p>Semua itu bukan sekadar sumber penghidupan. Ia adalah identitas yang terus dijaga, dan cerita yang ingin kami bagikan kepada setiap orang yang datang mengenal Desa Ketapang Raya lebih dekat.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ============ DESTINASI WISATA (LOCKED - DINAMIS DB) ============ -->
<section class="section section--alt">
    <div class="container">
        <div class="section__head" data-reveal="fade">
            <h2>Destinasi Wisata</h2>
            <p>Menyusuri pesisir Ketapang Raya, dari rimbunnya hutan mangrove hingga debur ombak yang menyambut di setiap pantainya.</p>
        </div>

        <?php if (!empty($destinasiList)): ?>
            <?php foreach ($destinasiList as $idx => $dest): ?>
                <?php
                $destImgRaw = $dest['thumbnail_url'] ?? $dest['cover_url'] ?? $dest['image'] ?? null;
                $destImg    = !empty($destImgRaw) ? ltrim($destImgRaw, '/') : null;
                ?>
                <div class="row <?= $idx % 2 !== 0 ? 'row--reverse' : '' ?>">
                    <div class="row__media" data-reveal="<?= $idx % 2 === 0 ? 'left' : 'right' ?>">
                        <?php if ($destImg): ?>
                            <img src="<?= url(htmlspecialchars($destImg)) ?>" alt="<?= htmlspecialchars($dest['title']) ?>" style="width:100%;height:100%;object-fit:cover;border-radius:var(--radius-lg);" />
                        <?php else: ?>
                            <div class="ph">
                                <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M4 20V9M4 9l8-6 8 6M4 9l16 0M20 20V9" />
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row__body" data-reveal="<?= $idx % 2 === 0 ? 'right' : 'left' ?>">
                        <h3><?= htmlspecialchars($dest['title']) ?></h3>
                        <p><?= htmlspecialchars($dest['excerpt'] ?? '') ?></p>
                        <a href="<?= url('destinasi/' . ($dest['slug'] ?? '')) ?>" class="link-arrow">
                            Lihat Destinasi
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Fallback Static Items -->
            <div class="row">
                <div class="row__media" data-reveal="left">
                    <div class="ph">
                        <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M4 20V9M4 9l8-6 8 6M4 9l16 0M20 20V9" />
                        </svg>
                    </div>
                </div>
                <div class="row__body" data-reveal="right">
                    <h3>Ekowisata Mangrove</h3>
                    <p>Kawasan mangrove Desa Ketapang Raya menawarkan pengalaman menikmati keindahan alam pesisir sekaligus mengenal pentingnya menjaga ekosistem lingkungan.</p>
                    <a href="<?= url('destinasi/ekowisata-mangrove') ?>" class="link-arrow">
                        Lihat Destinasi
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ============ PRODUK UNGGULAN DESA (LOCKED - DINAMIS DB) ============ -->
<section class="section section--surface">
    <div class="container">
        <div class="section__head" data-reveal="fade">
            <h2>Produk Unggulan Desa</h2>
            <p>Dari kekayaan alam pesisir, lahirlah berbagai produk lokal yang menjadi kebanggaan masyarakat.</p>
        </div>

        <?php if (!empty($produkList)): ?>
            <?php foreach ($produkList as $prod): ?>
                <?php
                $prodImgRaw = $prod['thumbnail_url'] ?? $prod['cover_url'] ?? $prod['image'] ?? null;
                $prodImg    = !empty($prodImgRaw) ? ltrim($prodImgRaw, '/') : null;
                ?>
                <div class="product-row" data-reveal="fade" data-stagger-group="produk">
                    <div class="product-row__media">
                        <?php if ($prodImg): ?>
                            <img src="<?= url(htmlspecialchars($prodImg)) ?>" alt="<?= htmlspecialchars($prod['title']) ?>" style="width:100%;height:100%;object-fit:cover;border-radius:var(--radius-lg);" />
                        <?php else: ?>
                            <div class="ph">
                                <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M4 18c2-6 4-10 8-14 4 4 6 8 8 14" />
                                    <path d="M4 18h16" />
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="product-row__body">
                        <h3><?= htmlspecialchars($prod['title']) ?></h3>
                        <p><?= htmlspecialchars($prod['excerpt'] ?? '') ?></p>
                        <a href="<?= url('produk/' . ($prod['slug'] ?? '')) ?>" class="link-arrow">
                            Lihat Produk
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Fallback Static Items -->
            <div class="product-row" data-reveal="fade" data-stagger-group="produk">
                <div class="product-row__media">
                    <div class="ph">
                        <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M4 18c2-6 4-10 8-14 4 4 6 8 8 14" />
                            <path d="M4 18h16" />
                        </svg>
                    </div>
                </div>
                <div class="product-row__body">
                    <h3>Garam Halus Telaga Bagek</h3>
                    <p>Garam halus tradisional yang diproduksi dengan bahan bakar kayu bakar, mampu memproduksi hingga 5–7 ton per hari.</p>
                    <a href="<?= url('produk/garam-halus-telaga-bagek') ?>" class="link-arrow">
                        Lihat Produk
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ============ LANGKAH MENUJU EKOWISATA ============ -->
<section class="section section--tight section--alt">
    <div class="container">
        <div class="section__head" data-reveal="fade" style="margin-bottom: 0">
            <span class="eyebrow eyebrow--dark"><?= htmlspecialchars($langkahEkowisata['subtitle'] ?? 'Masa Depan Desa') ?></span>
            <h2><?= htmlspecialchars($langkahEkowisata['title'] ?? 'Langkah Menuju Ekowisata') ?></h2>
            <p>
                <?= htmlspecialchars($langkahEkowisata['description'] ?? 'Di balik keindahan yang sudah bisa dinikmati hari ini, Desa Ketapang Raya masih terus melangkah. Pembangunan kawasan ekowisata ditargetkan mulai berjalan tahun ini...') ?>
            </p>
        </div>
    </div>
</section>

<!-- ============ QUOTE ============ -->
<section class="quote" data-reveal="fade">
    <div class="container">
        <blockquote>
            <span class="quote__mark">&ldquo;</span>
            <?= htmlspecialchars($quoteSection['description'] ?? 'Laut dan tanah di Ketapang Raya bukan hanya memberi kehidupan — keduanya warisan yang kami jaga, untuk masa depan yang ingin kami bangun bersama.') ?>
        </blockquote>
    </div>
</section>