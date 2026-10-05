<?php

/**
 * View: homepage-sections
 * Pure content fragment. Expected variable: $sections
 * (array from `sections` table where page_id = home, ordered by sort_order)
 */
if (!isset($sections)) {
    $sections = [
        ['id' => 1, 'key' => 'hero',              'title' => 'Hero',              'desc' => 'Section hero dengan gambar background', 'active' => true],
        ['id' => 2, 'key' => 'mengenal_desa',     'title' => 'Mengenal Desa',     'desc' => 'Tentang Desa Ketapang Raya',              'active' => true],
        ['id' => 3, 'key' => 'potensi_desa',      'title' => 'Potensi Desa',      'desc' => 'Daftar potensi yang dimiliki desa',       'active' => true],
        ['id' => 4, 'key' => 'program_kkn',       'title' => 'Program KKN',       'desc' => 'Kegiatan KKN yang telah dilaksanakan',    'active' => true],
        ['id' => 5, 'key' => 'informasi_singkat', 'title' => 'Informasi Singkat', 'desc' => 'Statistik dan informasi singkat desa',    'active' => true],
    ];
}
?>
<main class="admin-content">
    <div class="admin-page-header">
        <h1>Homepage</h1>
        <p class="admin-breadcrumb"><a href="dashboard.php">Kelola Halaman</a> / <span>Homepage</span></p>
    </div>

    <div class="card">
        <div class="card__header">
            <h2>Urutan Section di Homepage</h2>
            <form method="post" action="homepage-sections-reorder.php">
                <input type="hidden" name="order" id="sectionOrderInput" value="" />
                <button type="submit" class="btn btn-primary btn-sm">Simpan Urutan</button>
            </form>
        </div>
        <div class="card__body">
            <div class="section-list">
                <?php foreach ($sections as $i => $s): ?>
                    <div class="section-row" data-section-id="<?= $s['id'] ?>">
                        <span class="section-row__handle">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M8 6h.01M8 12h.01M8 18h.01M16 6h.01M16 12h.01M16 18h.01" />
                            </svg>
                        </span>
                        <span class="section-row__num"><?= $i + 1 ?></span>
                        <div class="section-row__body">
                            <div class="section-row__title"><?= htmlspecialchars($s['title']) ?></div>
                            <div class="section-row__desc"><?= htmlspecialchars($s['desc']) ?></div>
                        </div>
                        <div class="section-row__actions">
                            <span class="badge badge--<?= $s['active'] ? 'success' : 'neutral' ?>"><?= $s['active'] ? 'Aktif' : 'Nonaktif' ?></span>
                            <a href="<?= url('/admin/konten-home/section-edit/' . urlencode($s['key'])) ?>" class="btn btn-secondary btn-sm">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                </svg>
                                Edit
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="tip-banner">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M12 16v-5M12 8h.01" />
                </svg>
                <span>Tips: Drag and drop untuk mengubah urutan section. Section yang tidak aktif tidak akan ditampilkan di website.</span>
            </div>
        </div>
    </div>

</main>