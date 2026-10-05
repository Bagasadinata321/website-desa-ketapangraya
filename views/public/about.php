<?php

/**
 * View: Public About (Tentang Kami)
 * Expects: $sections, $staffList, $teamList, $kknList
 */

$hero       = $sections['hero'] ?? [];
$profil     = $sections['profil_desa'] ?? [];
$kolaborasi = $sections['kolaborasi'] ?? [];
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
                    <circle cx="12" cy="8" r="4" />
                    <path d="M4 21c0-4 4-6 8-6s8 2 8 6" />
                </svg>
                <span class="ph__label">Foto: perangkat desa &amp; tim KKN bersama warga</span>
            </div>
        <?php endif; ?>
    </div>
    <div class="hero__overlay"></div>
    <div class="container hero__content">
        <span class="eyebrow"><?= htmlspecialchars($hero['subtitle'] ?? 'Tentang Kami') ?></span>
        <h1 data-reveal="fade"><?= nl2br(htmlspecialchars($hero['title'] ?? "Bersama Membangun\nDesa Ketapang Raya")) ?></h1>
        <p data-reveal="fade">
            <?= htmlspecialchars($hero['description'] ?? 'Kolaborasi masyarakat, pemerintah desa, dan mahasiswa KKN untuk desa yang lebih maju dan berkelanjutan.') ?>
        </p>
    </div>
</header>

<!-- ============ PROFIL DESA ============ -->
<section class="section section--surface">
    <div class="container grid-2">
        <div class="intro__body" data-reveal="left">
            <h2><?= htmlspecialchars($profil['title'] ?? 'Profil Desa') ?></h2>
            <p>
                <?= htmlspecialchars($profil['description'] ?? 'Desa Ketapang Raya merupakan desa pesisir yang memiliki potensi alam, budaya, dan sumber daya manusia yang terus berkembang. Dengan semangat gotong royong, desa ini berkomitmen menjadi desa mandiri, sejahtera, dan lestari.') ?>
            </p>
        </div>
        <div class="intro__media" data-reveal="right">
            <?php
            $profilImg = !empty($profil['image']) ? ltrim($profil['image'], '/') : null;
            ?>
            <?php if ($profilImg): ?>
                <img src="<?= url(htmlspecialchars($profilImg)) ?>" alt="Profil Desa" style="width:100%;height:100%;object-fit:cover;border-radius:var(--radius-lg);" />
            <?php else: ?>
                <div class="ph">
                    <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 16l5-5 4 4 5-6 4 5" />
                        <circle cx="12" cy="12" r="10" />
                    </svg>
                    <span class="ph__label">Foto: permukiman terapung tepi sungai desa</span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ============ PEMERINTAH DESA (DINAMIS DB/JSON) ============ -->
<!-- ============ PEMERINTAH DESA (DINAMIS DB/JSON) ============ -->
<!-- ============ PEMERINTAH DESA (DINAMIS DB/JSON) ============ -->
<section class="section section--alt">
    <div class="container">
        <div class="section__head" data-reveal="fade">
            <h2>Pemerintah Desa</h2>
            <p>Perangkat desa yang bekerja untuk kemajuan Desa Ketapang Raya.</p>
        </div>

        <div class="people-row">
            <?php
            $allStaff = $staffList ?? [];
            $kades    = null;

            // 1. Cari spesifik official yang menjabat sebagai "Kepala Desa"
            foreach ($allStaff as $key => $staff) {
                $pos = strtolower($staff['position'] ?? '');
                if (str_contains($pos, 'kepala desa') || str_contains($pos, 'kades')) {
                    $kades = $staff;
                    unset($allStaff[$key]); // Hapus Kades dari daftar staf biasa agar tidak terduplikasi di grid
                    break;
                }
            }

            // Re-index array sisa perangkat desa
            $allStaff = array_values($allStaff);

            // 2. Ambil path gambar Kepala Desa jika ditemukan
            $kadesImg = !empty($kades['thumbnail_url'] ?? $kades['image'] ?? $kades['file_path'] ?? null)
                ? ltrim($kades['thumbnail_url'] ?? $kades['image'] ?? $kades['file_path'], '/')
                : null;
            ?>

            <!-- KARTU KEPALA DESA (HIGHLIGHT) -->
            <div class="chief-card" data-reveal="left">
                <div class="chief-card__photo">
                    <?php if ($kadesImg): ?>
                        <img src="<?= url(htmlspecialchars($kadesImg)) ?>" alt="<?= htmlspecialchars($kades['name'] ?? '') ?>" style="width:100%;height:100%;object-fit:cover;" />
                    <?php else: ?>
                        <div class="ph">
                            <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 21c0-4 4-6 8-6s8 2 8 6" />
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Jika Kades ditemukan di DB, tampilkan datanya. Jika belum ada, gunakan default fallback -->
                <div class="chief-card__name"><?= htmlspecialchars($kades['name'] ?? 'Bapak Suparman') ?></div>
                <div class="chief-card__role"><?= htmlspecialchars($kades['position'] ?? 'Kepala Desa Ketapang Raya') ?></div>
                <p style="font-size: var(--fs-small); color: var(--clr-text-light)">
                    <?= htmlspecialchars($kades['description'] ?? 'Memimpin dengan komitmen untuk mewujudkan desa maju, mandiri, dan sejahtera.') ?>
                </p>
            </div>

            <!-- GRID PERANGKAT DESA LAINNYA -->
            <div data-reveal="right">
                <h3 style="font-size: var(--fs-h4); margin-bottom: 1.4rem; text-align: center;">Perangkat Desa</h3>
                <div class="staff-grid" data-stagger-group="staff">
                    <?php if (!empty($allStaff)): ?>
                        <?php foreach ($allStaff as $staf): ?>
                            <?php
                            $stafImg = !empty($staf['thumbnail_url'] ?? $staf['image'] ?? $staf['file_path'] ?? null)
                                ? ltrim($staf['thumbnail_url'] ?? $staf['image'] ?? $staf['file_path'], '/')
                                : null;
                            ?>
                            <div class="staff-item" data-reveal="fade" data-stagger-group="staff">
                                <div class="staff-item__avatar">
                                    <?php if ($stafImg): ?>
                                        <img src="<?= url(htmlspecialchars($stafImg)) ?>" alt="<?= htmlspecialchars($staf['name'] ?? '') ?>" style="width:100%;height:100%;object-fit:cover;border-radius:50%;" />
                                    <?php else: ?>
                                        <div class="ph"></div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="staff-item__name"><?= htmlspecialchars($staf['name'] ?? '') ?></div>
                                    <div class="staff-item__role"><?= htmlspecialchars($staf['position'] ?? '') ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Fallback jika belum ada perangkat desa lain di DB -->
                        <div class="staff-item" data-reveal="fade" data-stagger-group="staff">
                            <div class="staff-item__avatar">
                                <div class="ph"></div>
                            </div>
                            <div>
                                <div class="staff-item__name">S. Hasan Al Idrus</div>
                                <div class="staff-item__role">Sekretaris Desa</div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ============ TIM KKN (DINAMIS DB/JSON) ============ -->
<section class="section section--surface">
    <div class="container">
        <div class="section__head" data-reveal="fade">
            <h2>Tim KKN</h2>
            <p>Mahasiswa KKN yang menjadi bagian dari perjalanan membangun desa.</p>
        </div>

        <div class="team-grid">
            <?php if (!empty($teamList)): ?>
                <?php foreach ($teamList as $member): ?>
                    <?php
                    $teamImg = !empty($member['thumbnail_url'] ?? $member['image'] ?? null)
                        ? ltrim($member['thumbnail_url'] ?? $member['image'], '/')
                        : null;
                    ?>
                    <div class="team-card" data-reveal="scale" data-stagger-group="tim">
                        <div class="team-card__photo">
                            <?php if ($teamImg): ?>
                                <img src="<?= url(htmlspecialchars($teamImg)) ?>" alt="<?= htmlspecialchars($member['name'] ?? '') ?>" style="width:100%;height:100%;object-fit:cover;" />
                            <?php else: ?>
                                <div class="ph"></div>
                            <?php endif; ?>
                        </div>
                        <div class="team-card__name"><?= htmlspecialchars($member['name'] ?? '') ?></div>
                        <div class="team-card__role"><?= htmlspecialchars($member['position'] ?? '') ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback Tim Dummy -->
                <div class="team-card" data-reveal="scale" data-stagger-group="tim">
                    <div class="team-card__photo">
                        <div class="ph"></div>
                    </div>
                    <div class="team-card__name">Andi Pratama</div>
                    <div class="team-card__role">Ketua Tim</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ============ PROGRAM & KEGIATAN KKN ============ -->
<!-- ============ PROGRAM & KEGIATAN KKN ============ -->
<section class="section section--surface kkn" style="border-top: 1px solid var(--clr-border, #eee);">
    <div class="container">
        <div class="section__head" data-reveal="fade">
            <span class="eyebrow">Program &amp; Kegiatan KKN</span>
            <h2>Program &amp; Kegiatan KKN</h2>
            <p>Kolaborasi mahasiswa KKN dalam mendukung pengembangan Desa Ketapang Raya</p>
        </div>
    </div>

    <div class="kkn__slider-wrapper" data-reveal="fade">
        <div class="kkn__slider-container">
            <div class="kkn__slider-track">
                <?php if (!empty($kknList)): ?>
                    <?php
                    // Duplikasi array agar efek infinite slider / marquee tetap berjalan seamless
                    $loopItems = array_merge($kknList, $kknList);
                    $totalRealItems = count($kknList);
                    ?>
                    <?php foreach ($loopItems as $idx => $kkn): ?>
                        <?php
                        // Ambil daftar gambar yang sudah digroup oleh Controller
                        $images = $kkn['images'] ?? [];

                        // Fallback jika 'images' belum di-group (single image string)
                        if (empty($images)) {
                            $singleImg = $kkn['image'] ?? $kkn['thumbnail_url'] ?? $kkn['file_path'] ?? null;
                            if (!empty($singleImg)) {
                                $images = [ltrim($singleImg, '/')];
                            }
                        }

                        // Batasi maksimal 3 foto per card agar layout rapi
                        $images = array_slice($images, 0, 3);
                        ?>
                        <div class="kkn__card" <?= $idx >= $totalRealItems ? 'aria-hidden="true"' : '' ?>>

                            <!-- GALERI MULTI-FOTO (MAKSIMAL 3 FOTO DARI PROGRAM YANG SAMA) -->
                            <div class="kkn__gallery" style="display: flex; gap: 8px; margin-bottom: 1rem;">
                                <?php if (!empty($images)): ?>
                                    <?php foreach ($images as $img): ?>
                                        <div class="kkn__media" style="flex: 1; height: 180px; overflow: hidden; border-radius: var(--radius-md);">
                                            <img src="<?= url(htmlspecialchars(ltrim($img, '/'))) ?>" alt="<?= htmlspecialchars($kkn['title'] ?? '') ?>" style="width:100%; height:100%; object-fit:cover;" />
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="kkn__media" style="width: 100%; height: 180px;">
                                        <div class="ph ph--landscape">
                                            <span class="ph__label"><?= htmlspecialchars($kkn['title'] ?? 'Foto Program') ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- INFORMASI PROGRAM -->
                            <div class="kkn__content-centered" style="text-align: center;">
                                <div class="kkn__meta" style="justify-content: center;">
                                    <span class="kkn__num"><?= sprintf('%02d', ($idx % $totalRealItems) + 1) ?></span>
                                    <h3><?= htmlspecialchars($kkn['title'] ?? '') ?></h3>
                                </div>
                                <p><?= htmlspecialchars($kkn['excerpt'] ?? $kkn['description'] ?? '') ?></p>
                            </div>

                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Fallback jika belum ada data di database -->
                    <div class="kkn__card">
                        <div class="kkn__gallery">
                            <div class="kkn__media" style="width: 100%; height: 180px;">
                                <div class="ph ph--landscape"><span class="ph__label">Foto Program</span></div>
                            </div>
                        </div>
                        <div class="kkn__content-centered" style="text-align: center;">
                            <div class="kkn__meta" style="justify-content: center;"><span class="kkn__num">01</span>
                                <h3>Digitalisasi Desa</h3>
                            </div>
                            <p>Membantu pengelolaan informasi desa dan pemanfaatan teknologi untuk pelayanan serta promosi potensi desa.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ============ KOLABORASI UNTUK DESA ============ -->
<section class="section section--alt">
    <div class="container grid-2">
        <div class="intro__body" data-reveal="left">
            <h2><?= htmlspecialchars($kolaborasi['title'] ?? 'Kolaborasi Untuk Desa') ?></h2>
            <p>
                <?= htmlspecialchars($kolaborasi['description'] ?? 'Kerja sama antara pemerintah desa, masyarakat, dan mahasiswa KKN menjadi kunci dalam mendorong pembangunan Desa Ketapang Raya yang lebih baik dan berkelanjutan. Terima kasih kepada seluruh pihak yang telah berkontribusi.') ?>
            </p>
        </div>
        <div class="intro__media" data-reveal="right">
            <?php
            $kolaborasiImg = !empty($kolaborasi['image']) ? ltrim($kolaborasi['image'], '/') : null;
            ?>
            <?php if ($kolaborasiImg): ?>
                <img src="<?= url(htmlspecialchars($kolaborasiImg)) ?>" alt="Kolaborasi" style="width:100%;height:100%;object-fit:cover;border-radius:var(--radius-lg);" />
            <?php else: ?>
                <div class="ph">
                    <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="8" cy="9" r="3" />
                        <circle cx="16" cy="9" r="3" />
                        <path d="M2 20c0-3 3-5 6-5s6 2 6 5M12 20c0-3 3-5 6-5s6 2 6 5" />
                    </svg>
                    <span class="ph__label">Foto bersama: perangkat desa &amp; tim KKN</span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>