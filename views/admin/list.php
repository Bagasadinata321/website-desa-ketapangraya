<?php

/**
 * View: list
 * Generic, reusable list page for ANY single "Kategori 1" resource
 * (Destinasi, Produk, Program KKN, Potensi Desa, Perangkat Desa,
 * Mitra, Administrator, Mahasiswa KKN). Pure content fragment — no
 * <html>, no layout.
 *
 * Untuk halaman yang menampilkan LEBIH DARI SATU resource sekaligus
 * (mis. Anggota KKN + Program KKN dalam satu halaman), lihat kkn-list.php
 * — bukan file ini. list.php sengaja tetap khusus 1 resource per halaman,
 * rendering tabelnya sendiri sudah dipindah ke partials/resource-table.php
 * supaya bisa dipakai ulang oleh kkn-list.php tanpa duplikasi.
 *
 * Expected variables (set by controller before including this view):
 *
 *   $resourceKey    string   e.g. 'destinasi' — used to build edit/delete URLs:
 *                            url('admin/{resourceKey}/edit/{id}')
 *                            url('admin/{resourceKey}/delete/{id}')
 *                            url('admin/{resourceKey}/create')
 *
 *   $pageLabel      string   e.g. 'Destinasi' — shown as <h1>, tombol tambah,
 *                            dan teks empty-state
 *   $breadcrumbParent string e.g. 'Data Konten'
 *
 *   $columns        array    lihat dokumentasi tipe kolom di
 *                            partials/resource-table.php
 *
 *   $items          array    rows of data. Each row must have at least:
 *                            'id', plus whatever keys $columns references.
 *                            A 'delete_label' key (fallback: row['title'] or
 *                            row['name']) is used in the delete confirmation text.
 *
 *   $pagination     array    ['from' => 1, 'to' => 5, 'total' => 12,
 *                             'current_page' => 1, 'total_pages' => 3]
 */

if (!isset($resourceKey)) {
    $resourceKey = 'destinasi';
}
if (!isset($pageLabel)) {
    $pageLabel = 'Destinasi';
}
if (!isset($breadcrumbParent)) {
    $breadcrumbParent = 'Data Konten';
}

if (!isset($columns)) {
    // Placeholder fallback — remove once the controller passes real config.
    $columns = [
        ['key' => 'thumbnail_url', 'label' => 'Gambar',   'type' => 'thumbnail'],
        ['key' => 'title',         'label' => 'Judul',    'type' => 'text-bold'],
        ['key' => 'location',      'label' => 'Lokasi',   'type' => 'text'],
        ['key' => 'status',        'label' => 'Status',   'type' => 'status', 'status_map' => [
            'published' => ['label' => 'Published', 'class' => 'success'],
            'draft'     => ['label' => 'Draft',      'class' => 'neutral'],
        ]],
        ['key' => 'is_featured',   'label' => 'Unggulan', 'type' => 'toggle'],
        ['key' => 'sort_order',    'label' => 'Urutan',   'type' => 'text'],
    ];
}

if (!isset($items)) {
    $items = [
        ['id' => 1, 'title' => 'Pantai Cemare',  'location' => 'Ketapang Raya', 'status' => 'published', 'is_featured' => true,  'sort_order' => 1, 'thumbnail_url' => null],
        ['id' => 2, 'title' => 'Telaga Bagek',    'location' => 'Ketapang Raya', 'status' => 'published', 'is_featured' => true,  'sort_order' => 2, 'thumbnail_url' => null],
        ['id' => 3, 'title' => 'Bukit Panorama',  'location' => 'Ketapang Raya', 'status' => 'published', 'is_featured' => false, 'sort_order' => 3, 'thumbnail_url' => null],
        ['id' => 4, 'title' => 'Hutan Mangrove',  'location' => 'Ketapang Raya', 'status' => 'draft',     'is_featured' => false, 'sort_order' => 4, 'thumbnail_url' => null],
        ['id' => 5, 'title' => 'Pulau Kecil',     'location' => 'Ketapang Raya', 'status' => 'published', 'is_featured' => false, 'sort_order' => 5, 'thumbnail_url' => null],
    ];
}

if (!isset($pagination)) {
    $pagination = ['from' => 1, 'to' => count($items), 'total' => count($items), 'current_page' => 1, 'total_pages' => 1];
}

require_once __DIR__ . '/partials/resource-table.php';
?>
<main class="admin-content">
    <div class="admin-page-header">
        <h1><?= htmlspecialchars($pageLabel) ?></h1>
        <p class="admin-breadcrumb"><?= htmlspecialchars($breadcrumbParent) ?> / <span><?= htmlspecialchars($pageLabel) ?></span></p>
    </div>

    <?php
    render_resource_table_block([
        'resourceKey'     => $resourceKey,
        'pageLabel'       => $pageLabel,
        'blockTitle'      => null, // 1 resource per halaman, judul cukup <h1> di atas
        'columns'         => $columns,
        'items'           => $items,
        'pagination'      => $pagination,
        'paginationParam' => 'page',
    ]);
    ?>
</main>
<script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.js-reorder-btn');
        if (!btn) return;

        e.preventDefault();

        const id = btn.dataset.id;
        const resource = btn.dataset.resource; // Mengambil resourceKey (misal: 'destinasi', 'produk')
        const direction = btn.dataset.direction;
        const currentRow = btn.closest('tr');
        if (!currentRow) return;

        // Cari baris tetangga (Atas/Bawah)
        const targetRow = direction === 'up' ?
            currentRow.previousElementSibling :
            currentRow.nextElementSibling;

        if (!targetRow || targetRow.tagName !== 'TR') return;

        btn.disabled = true;

        // Catat posisi awal untuk FLIP Animation
        const currentRectStart = currentRow.getBoundingClientRect();
        const targetRectStart = targetRow.getBoundingClientRect();

        // Buat URL endpoint presisi tanpa terpengaruh URL halaman saat ini
        const endpoint = '<?= url("admin") ?>/' + resource + '/reorder/' + id;

        fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    direction: direction
                })
            })
            .then(async res => {
                const isJson = res.headers.get('content-type')?.includes('application/json');
                const data = isJson ? await res.json() : null;

                if (!res.ok) {
                    const errorMsg = (data && data.message) || `HTTP Error ${res.status}`;
                    throw new Error(errorMsg);
                }
                return data;
            })
            .then(data => {
                btn.disabled = false;

                if (data.success) {
                    // Swap DOM Position
                    if (direction === 'up') {
                        currentRow.parentNode.insertBefore(currentRow, targetRow);
                    } else {
                        currentRow.parentNode.insertBefore(targetRow, currentRow);
                    }

                    // FLIP Animation Execution
                    const currentRectEnd = currentRow.getBoundingClientRect();
                    const targetRectEnd = targetRow.getBoundingClientRect();

                    const currentInvertY = currentRectStart.top - currentRectEnd.top;
                    const targetInvertY = targetRectStart.top - targetRectEnd.top;

                    currentRow.style.transform = `translateY(${currentInvertY}px)`;
                    targetRow.style.transform = `translateY(${targetInvertY}px)`;
                    currentRow.style.transition = 'none';
                    targetRow.style.transition = 'none';

                    requestAnimationFrame(() => {
                        currentRow.style.transition = 'transform 0.35s cubic-bezier(0.2, 0, 0, 1)';
                        targetRow.style.transition = 'transform 0.35s cubic-bezier(0.2, 0, 0, 1)';

                        currentRow.style.transform = '';
                        targetRow.style.transform = '';
                    });

                    setTimeout(() => {
                        currentRow.style.transition = '';
                        targetRow.style.transition = '';
                    }, 350);

                } else {
                    alert(data.message || 'Gagal mengubah urutan');
                }
            })
            .catch(err => {
                btn.disabled = false;
                console.error('Reorder Error:', err);
                alert('Gagal: ' + err.message);
            });
    });
</script>