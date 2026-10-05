<?php

/**
 * Sidebar partial.
 * Include this after setting $currentPage in the parent page, e.g.:
 *   $currentPage = 'dashboard';
 *   include __DIR__ . '/partials/sidebar.php';
 *
 * $currentPage values used across the panel:
 * dashboard, homepage, destinasi-produk, tentang-kami,
 * potensi-desa, destinasi, produk, program-kkn,
 * perangkat-desa, mahasiswa-kkn, mitra, media,
 * website, seo, administrator
 */
if (!isset($currentPage)) {
    $currentPage = '';
}
function nav_active($key, $currentPage)
{
    return $key === $currentPage ? ' is-active' : '';
}
?>
<aside class="admin-sidebar">

    <div class="admin-sidebar__brand">
        <span class="admin-sidebar__brand-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22V10" />
                <path d="M12 10C12 6 9 3 5 3c0 4.5 2.5 7.5 7 7z" />
                <path d="M12 14C12 10 15 7 19 7c0 4.5-2.5 7.5-7 7z" />
            </svg>
        </span>
        <span class="admin-sidebar__brand-text">Desa Ketapang<br />Raya</span>
    </div>

    <nav class="admin-nav">
        <a href="<?= url('/admin/dashboard') ?>" class="admin-nav__item <?= nav_active('dashboard', $currentPage) ?>" data-nav-key="dashboard">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="9" rx="1" />
                <rect x="14" y="3" width="7" height="5" rx="1" />
                <rect x="14" y="12" width="7" height="9" rx="1" />
                <rect x="3" y="16" width="7" height="5" rx="1" />
            </svg>
            Dashboard
            <?= $currentPage ?>
        </a>

        <div class="admin-nav__group-label">Kelola Halaman</div>
        <a href="<?= url('/admin/konten-home/list') ?>" class="admin-nav__item<?= nav_active('homepage', $currentPage) ?>" data-nav-key="homepage">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 10l9-7 9 7" />
                <path d="M5 9v11h14V9" />
            </svg>
            Homepage
        </a>
        <a href="<?= url('/admin/konten-destinasi-produk/list') ?>" class="admin-nav__item<?= nav_active('destinasi-produk', $currentPage) ?>" data-nav-key="destinasi-produk">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 20V9M4 9l8-6 8 6M4 9l16 0M20 20V9" />
            </svg>
            Destinasi &amp; Produk
        </a>
        <a href="<?= url('/admin/konten-tentang-kami/list') ?>" class="admin-nav__item<?= nav_active('tentang-kami', $currentPage) ?>" data-nav-key="tentang-kami">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="8" r="4" />
                <path d="M4 21c0-4 4-6 8-6s8 2 8 6" />
            </svg>
            Tentang Kami
        </a>

        <div class="admin-nav__group-label">Data Konten</div>
        <a href="<?= url('/admin/potensi-desa/list') ?>" class="admin-nav__item<?= nav_active('potensi-desa', $currentPage) ?>" data-nav-key="potensi-desa">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M2 12c3-4 7-6 10-6s7 2 10 6c-3 4-7 6-10 6s-7-2-10-6Z" />
                <circle cx="12" cy="12" r="2" />
            </svg>
            Potensi Desa
        </a>
        <a href="<?= url('/admin/destinasi/list') ?>" class="admin-nav__item<?= nav_active('destinasi', $currentPage) ?>" data-nav-key="destinasi">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="5" />
                <path d="M2 12h3m14 0h3M12 2v3m0 14v3" />
            </svg>
            Destinasi
        </a>
        <a href="<?= url('/admin/produk/list') ?>" class="admin-nav__item<?= nav_active('produk', $currentPage) ?>" data-nav-key="produk">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="4" y="4" width="16" height="16" rx="2" />
                <path d="M4 10h16M10 4v16" />
            </svg>
            Produk
        </a>
        <a href="<?= url('/admin/kkn/list') ?>" class="admin-nav__item<?= nav_active('program-kkn', $currentPage) ?>" data-nav-key="program-kkn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 21c0-4 4-6 8-6s8 2 8 6" />
                <circle cx="12" cy="8" r="4" />
                <path d="M2 3l2 2M22 3l-2 2" />
            </svg>
            KKN
        </a>
        <a href="<?= url('/admin/perangkat-desa/list') ?>" class="admin-nav__item<?= nav_active('perangkat-desa', $currentPage) ?>" data-nav-key="perangkat-desa">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" />
                <circle cx="10" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
            Perangkat Desa
        </a>

        <a href="<?= url('/admin/mitra/list') ?>" class="admin-nav__item<?= nav_active('mitra', $currentPage) ?>" data-nav-key="mitra">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78L12 21l8.84-8.61a5.5 5.5 0 0 0 0-7.78Z" />
            </svg>
            Mitra
        </a>

        <div class="admin-nav__group-label">Pengaturan</div>
        <a href="<?= url('/admin/settings/edit') ?>" class="admin-nav__item<?= nav_active('website', $currentPage) ?>" data-nav-key="website">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="9" />
                <path d="M3 12h18M12 3c2.5 2.7 4 6 4 9s-1.5 6.3-4 9c-2.5-2.7-4-6-4-9s1.5-6.3 4-9Z" />
            </svg>
            Website
        </a>
        <a href="<?= url('/admin/administrator/list') ?>" class="admin-nav__item<?= nav_active('administrator', $currentPage) ?>" data-nav-key="administrator" id="manageAdmin">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
            Administrator
        </a>
    </nav>

    <div class="admin-sidebar__logout">
        <a href="<?= url('/logout') ?>" data-confirm="Yakin ingin keluar dari admin panel?">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                <path d="M16 17l5-5-5-5" />
                <path d="M21 12H9" />
            </svg>
            Logout
        </a>
    </div>
</aside>