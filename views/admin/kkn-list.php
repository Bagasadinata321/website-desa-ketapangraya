<?php

/**
 * View: kkn-list
 * Satu halaman admin menampilkan DUA resource sekaligus:
 * Anggota Tim KKN (di atas) dan Program KKN (di bawah). Masing-masing
 * punya tombol "Tambah", tabel, dan pagination sendiri-sendiri — tombol
 * edit/delete/create tetap mengarah ke handler resource masing-masing
 * (admin/mahasiswa-kkn/... dan admin/program-kkn/...) yang sudah ada,
 * TIDAK ada endpoint create/edit/delete baru untuk halaman ini.
 *
 * Beda dari list.php: pagination dua blok ini WAJIB pakai query-string
 * param yang berbeda (lihat 'paginationParam' di tiap blok) — kalau sama-
 * sama 'page', pindah halaman di satu blok akan ikut memindah blok yang
 * lain karena keduanya baca query string yang sama.
 *
 * Expected variables (set by controller, mis. AdminController::kknList()):
 *
 *   $pageLabel        string  e.g. 'Tim & Program KKN'
 *   $breadcrumbParent string  e.g. 'Data Konten'
 *   $blocks           array   list of block definitions, tiap elemen sesuai
 *                             dokumentasi di partials/resource-table.php
 *                             (resourceKey, pageLabel, blockTitle, columns,
 *                             items, pagination, paginationParam)
 */

if (!isset($pageLabel)) {
    $pageLabel = 'Tim & Program KKN';
}
if (!isset($breadcrumbParent)) {
    $breadcrumbParent = 'Data Konten';
}
if (!isset($blocks)) {
    $blocks = [];
}

require_once __DIR__ . '/partials/resource-table.php';
?>
<main class="admin-content">
    <div class="admin-page-header">
        <h1><?= htmlspecialchars($pageLabel) ?></h1>
        <p class="admin-breadcrumb"><?= htmlspecialchars($breadcrumbParent) ?> / <span><?= htmlspecialchars($pageLabel) ?></span></p>
    </div>

    <?php foreach ($blocks as $block): ?>
        <?php render_resource_table_block($block); ?>
    <?php endforeach; ?>
</main>