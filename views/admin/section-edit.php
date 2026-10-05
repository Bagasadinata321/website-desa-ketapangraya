<?php

/**
 * View: section-edit
 * Pure content fragment. Expected variables:
 *   $sectionKey   string, e.g. 'hero' | 'mengenal_desa' | 'informasi_singkat'
 *   $data         array — shape depends on $data['type']:
 *                   'text'  → judul, subjudul, deskripsi, tombol_teks, tombol_link, has_desc, has_button
 *                   'stats' → jumlah_kk, jumlah_penduduk, jumlah_dusun, tahun_berdiri, luas_wilayah, deskripsi
 * Image (background/cover) is NOT a column here — it belongs in `media_relations`
 * (entity_type='section', entity_id=<id>, usage='background'). The controller
 * should resolve the current image URL separately and pass it as $currentImageUrl.
 */
if (!isset($sectionKey)) {
    $sectionKey = $slug ?? 'hero';
}

if ($sectionKey) {
    // Placeholder fallback — remove once the controller passes real data.
    $sectionDataMap = [
        'hero' => [
            'type'     => 'text',
            'title'    => 'Hero',
            'judul'    => 'Selamat Datang di Desa Ketapang Raya',
            'subjudul' => 'Desa Wisata Bahari yang Memikat',
            'deskripsi' => 'Desa Ketapang Raya menawarkan keindahan alam, budaya, dan potensi ekonomi yang luar biasa.',
            'tombol_teks' => 'Jelajahi Desa',
            'tombol_link' => '#mengenal-desa',
            'has_desc' => true,
            'has_button' => true,
        ],
        'mengenal_desa' => [
            'type'     => 'text',
            'title'    => 'Mengenal Desa',
            'judul'    => 'Mengenal Lebih Dekat Desa Ketapang Raya',
            'subjudul' => '',
            'deskripsi' => 'Desa Ketapang Raya merupakan desa pesisir dengan kekayaan alam, masyarakat yang harmonis, serta berbagai potensi lokal yang terus berkembang.',
            'tombol_teks' => 'Selengkapnya',
            'tombol_link' => 'tentang-kami.html',
            'has_desc' => true,
            'has_button' => true,
        ],
        'informasi_singkat' => [
            'type'            => 'stats',
            'title'           => 'Informasi Singkat',
            'deskripsi'       => 'Desa pesisir yang kaya akan alam, budaya, dan semangat gotong royong.',
            'jumlah_kk'       => 1499,
            'jumlah_penduduk' => 4763,
            'jumlah_dusun'    => 6,
            'tahun_berdiri'   => 2010,
            'luas_wilayah'    => 209.6,
        ],
    ];
    $data = $sectionDataMap[$sectionKey] ?? $sectionDataMap['hero'];
}

$type = $data['type'] ?? 'text';
?>
<main class="admin-content">
    <div class="admin-page-header">
        <h1>Edit Section - <?= htmlspecialchars($data['title']) ?></h1>
        <p class="admin-breadcrumb"><a href="homepage-sections.php">Homepage</a> / <span><?= htmlspecialchars($data['title']) ?></span></p>
    </div>

    <form method="post" action="section-update.php">
        <input type="hidden" name="section_key" value="<?= htmlspecialchars($sectionKey) ?>" />

        <div class="admin-grid-2">
            <div class="card">
                <div class="card__body">

                    <?php if ($type === 'text'): ?>
                        <div class="form-group">
                            <label class="form-label" for="judul">Judul</label>
                            <input type="text" id="judul" name="judul" class="form-control" value="<?= htmlspecialchars($data['judul']) ?>" />
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="subjudul">Subjudul</label>
                            <input type="text" id="subjudul" name="subjudul" class="form-control" value="<?= htmlspecialchars($data['subjudul']) ?>" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Gambar Background</label>
                            <div class="cover-picker">
                                <div class="cover-picker__preview" id="sectionImagePreview">
                                    <?php if (!empty($currentImageUrl)): ?>
                                        <img src="<?= htmlspecialchars($currentImageUrl) ?>" alt="" style="width:100%;height:100%;object-fit:cover;" />
                                    <?php else: ?>
                                        <div class="ph"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M3 16l5-5 4 4 5-6 4 5" />
                                                <circle cx="12" cy="12" r="10" />
                                            </svg></div>
                                    <?php endif; ?>
                                </div>
                                <div class="cover-picker__actions">
                                    <button type="button" class="btn btn-secondary btn-sm" data-trigger-upload="#sectionImageInput">Ganti Gambar</button>
                                    <button type="button" class="btn btn-danger btn-sm">Hapus</button>
                                    <input type="file" id="sectionImageInput" accept="image/*" hidden data-preview-target="#sectionImagePreview" name="background_image" />
                                </div>
                            </div>
                            <p class="form-hint">Disimpan lewat media_relations (usage: background), bukan kolom di tabel sections.</p>
                        </div>

                        <?php if (!empty($data['has_desc'])): ?>
                            <div class="form-group">
                                <label class="form-label" for="deskripsi">Deskripsi <span class="optional">(Opsional)</span></label>
                                <textarea id="deskripsi" name="deskripsi" class="form-control"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($data['has_button'])): ?>
                            <div class="form-group">
                                <label class="form-label">Tombol <span class="optional">(Opsional)</span></label>
                                <div class="form-row">
                                    <div>
                                        <label class="form-label" for="tombol_teks" style="font-weight:500; font-size:0.76rem;">Teks Tombol</label>
                                        <input type="text" id="tombol_teks" name="tombol_teks" class="form-control" value="<?= htmlspecialchars($data['tombol_teks']) ?>" />
                                    </div>
                                    <div>
                                        <label class="form-label" for="tombol_link" style="font-weight:500; font-size:0.76rem;">Link</label>
                                        <input type="text" id="tombol_link" name="tombol_link" class="form-control" value="<?= htmlspecialchars($data['tombol_link']) ?>" />
                                    </div>
                                </div>
                                <p class="form-hint">tombol_teks + tombol_link disimpan sebagai satu object di kolom sections.meta (JSON).</p>
                            </div>
                        <?php endif; ?>

                    <?php elseif ($type === 'stats'): ?>
                        <div class="form-group">
                            <label class="form-label" for="deskripsi">Deskripsi Band</label>
                            <textarea id="deskripsi" name="deskripsi" class="form-control"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="jumlah_kk">Jumlah KK</label>
                                <input type="number" id="jumlah_kk" name="jumlah_kk" class="form-control" value="<?= htmlspecialchars((string) $data['jumlah_kk']) ?>" />
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="jumlah_penduduk">Jumlah Penduduk</label>
                                <input type="number" id="jumlah_penduduk" name="jumlah_penduduk" class="form-control" value="<?= htmlspecialchars((string) $data['jumlah_penduduk']) ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="jumlah_dusun">Jumlah Dusun</label>
                                <input type="number" id="jumlah_dusun" name="jumlah_dusun" class="form-control" value="<?= htmlspecialchars((string) $data['jumlah_dusun']) ?>" />
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="tahun_berdiri">Tahun Berdiri</label>
                                <input type="number" id="tahun_berdiri" name="tahun_berdiri" class="form-control" value="<?= htmlspecialchars((string) $data['tahun_berdiri']) ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="luas_wilayah">Luas Wilayah (Ha)</label>
                            <input type="number" step="0.1" id="luas_wilayah" name="luas_wilayah" class="form-control" value="<?= htmlspecialchars((string) $data['luas_wilayah']) ?>" />
                        </div>
                        <p class="form-hint">Kelima field angka ini disimpan sebagai satu object di kolom sections.meta (JSON) — bukan kolom terpisah.</p>

                        <div class="form-group">
                            <label class="form-label">Gambar Latar Band</label>
                            <div class="cover-picker">
                                <div class="cover-picker__preview" id="statsImagePreview">
                                    <?php if (!empty($currentImageUrl)): ?>
                                        <img src="<?= htmlspecialchars($currentImageUrl) ?>" alt="" style="width:100%;height:100%;object-fit:cover;" />
                                    <?php else: ?>
                                        <div class="ph"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M3 16l5-5 4 4 5-6 4 5" />
                                                <circle cx="12" cy="12" r="10" />
                                            </svg></div>
                                    <?php endif; ?>
                                </div>
                                <div class="cover-picker__actions">
                                    <button type="button" class="btn btn-secondary btn-sm" data-trigger-upload="#statsImageInput">Ganti Gambar</button>
                                    <input type="file" id="statsImageInput" accept="image/*" hidden data-preview-target="#statsImagePreview" name="band_image" />
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <div class="card">
                <div class="card__body">
                    <div class="form-group">
                        <label class="form-label" for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="1" selected>Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                        <p class="form-hint">Dikirim sebagai 1/0 — cocok langsung dengan sections.is_active (TINYINT).</p>
                    </div>

                    <div class="form-actions" style="border-top:none; margin-top:0; padding-top:0; flex-direction:column; align-items:stretch;">
                        <button type="submit" class="btn btn-primary btn-block">Simpan Perubahan</button>
                        <a href="homepage-sections.php" class="btn btn-secondary btn-block">Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>