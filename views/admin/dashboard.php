<?php

/**
 * View: dashboard (Sederhana & Tanpa Grafik Pusing)
 */

// Fallback jika controller belum mengirim data aktual
if (!isset($stats)) {
    $stats = [
        ['label' => 'Destinasi',    'value' => 0, 'icon' => 'bi-map',          'link' => url('admin/destinasi/list')],
        ['label' => 'Produk',       'value' => 0, 'icon' => 'bi-box-seam',     'link' => url('admin/produk/list')],
        ['label' => 'Potensi Desa', 'value' => 0, 'icon' => 'bi-star',         'link' => url('admin/potensi-desa/list')],
        ['label' => 'Jumlah Admin', 'value' => 0, 'icon' => 'bi-person-badge', 'link' => url('admin/administrator/list')],
    ];
}
?>

<main class="admin-content">

    <!-- Header Sapaan Sederhana -->
    <div class="admin-page-header" style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem; font-weight: 700; color: #1e293b; margin-bottom: 0.25rem;">
            Selamat Datang di Panel Admin 👋
        </h1>
        <p style="color: #64748b; font-size: 0.95rem;">
            Kelola seluruh konten, destinasi, dan informasi Desa Ketapang Raya di sini.
        </p>
    </div>

    <!-- Ringkasan Statistik Utama -->
    <div class="stat-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
        <?php foreach ($stats as $s): ?>
            <div class="stat-card" style="background: #ffffff; padding: 1.25rem; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <span style="font-size: 0.875rem; font-weight: 600; color: #64748b;"><?= htmlspecialchars($s['label']) ?></span>
                    <i class="bi <?= $s['icon'] ?? 'bi-folder' ?>" style="font-size: 1.25rem; color: #2563eb;"></i>
                </div>
                <div style="font-size: 1.875rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">
                    <?= $s['value'] ?>
                </div>
                <a href="<?= $s['link'] ?>" style="font-size: 0.85rem; color: #2563eb; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;">
                    Kelola Data &rarr;
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Jalan Pintas / Quick Links -->
    <div class="card" style="background: #ffffff; padding: 1.5rem; border-radius: 12px; border: 1px solid #e2e8f0;">
        <h2 style="font-size: 1.1rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem;">
            Aksi Cepat
        </h2>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="<?= url('admin/konten-home/list') ?>" class="btn btn-primary" style="padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; background: #2563eb; color: white; font-size: 0.875rem;">
                Edit Konten Beranda
            </a>
            <a href="<?= url('admin/destinasi/create') ?>" class="btn btn-outline" style="padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; border: 1px solid #cbd5e1; color: #334155; font-size: 0.875rem;">
                + Tambah Destinasi
            </a>
            <a href="<?= url('admin/produk/create') ?>" class="btn btn-outline" style="padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; border: 1px solid #cbd5e1; color: #334155; font-size: 0.875rem;">
                + Tambah Produk
            </a>
            <a href="<?= url('/') ?>" target="_blank" class="btn btn-link" style="padding: 0.6rem 1.2rem; text-decoration: none; color: #64748b; font-size: 0.875rem;">
                Lihat Website Utama ↗
            </a>
        </div>
    </div>

</main>