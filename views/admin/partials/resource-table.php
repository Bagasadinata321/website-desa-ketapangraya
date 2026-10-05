<?php

/**
 * Partial: admin/partials/resource-table
 * Satu blok "Tambah [X] button + tabel + pagination" untuk SATU resource.
 */

if (!function_exists('list_view_thumb')) {
    function list_view_thumb($url)
    {
        if (!empty($url)) {
            echo '<div class="table-thumb"><img src="' . url(htmlspecialchars($url)) . '" alt="" style="width:100%;height:100%;object-fit:cover;" /></div>';
        } else {
            echo '<div class="table-thumb"><div class="ph"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 16l5-5 4 4 5-6 4 5"/><circle cx="12" cy="12" r="10"/></svg></div></div>';
        }
    }
}

function render_resource_table_block(array $block): void
{
    $resourceKey     = $block['resourceKey'];
    $pageLabel       = $block['pageLabel'];
    $blockTitle      = $block['blockTitle'] ?? null;
    $columns         = $block['columns'];
    $items           = $block['items'];
    $pagination      = $block['pagination'];
    $paginationParam = $block['paginationParam'] ?? 'page';

    // Daftar resource yang diizinkan memiliki fitur Naik/Turun
    $reorderableResources = [
        'destinasi',
        'potensi-desa',
        'produk',
        'program-kkn',
        'mahasiswa-kkn',
        'perangkat-desa',
        'mitra'
    ];

    $canReorder = in_array($resourceKey, $reorderableResources, true);
?>
    <?php if ($blockTitle !== null): ?>
        <div class="admin-block-header" style="display:flex;justify-content:space-between;align-items:center;margin:2rem 0 1rem;">
            <h2 style="margin:0;"><?= htmlspecialchars($blockTitle) ?></h2>
            <a href="<?= url('admin/' . $resourceKey . '/create') ?>" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Tambah <?= htmlspecialchars($pageLabel) ?>
            </a>
        </div>
    <?php else: ?>
        <div class="admin-page-header admin-page-header--row" style="margin-bottom:1rem;">
            <div></div>
            <a href="<?= url('admin/' . $resourceKey . '/create') ?>" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Tambah <?= htmlspecialchars($pageLabel) ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <?php foreach ($columns as $col): ?>
                            <th><?= htmlspecialchars($col['label']) ?></th>
                        <?php endforeach; ?>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $i => $row): ?>
                        <?php
                        $rowId     = $row['id'] ?? $row['section_key'] ?? null;
                        $isSection = isset($row['section_key']);

                        if ($isSection) {
                            $editUrl = url('admin/' . $resourceKey . '/' . $rowId . '/edit');
                        } else {
                            $editUrl = url('admin/' . $resourceKey . '/edit/' . $rowId);
                        }
                        ?>
                        <tr>
                            <td><?= ($pagination['from'] ?? 1) + $i ?></td>

                            <?php foreach ($columns as $col): ?>
                                <td>
                                    <?php
                                    $key = $col['key'];
                                    $val = $row[$key] ?? null;

                                    switch ($col['type']) {
                                        case 'thumbnail':
                                            list_view_thumb($val);
                                            break;

                                        case 'text-bold':
                                            echo '<span style="font-weight:600;">' . htmlspecialchars((string) $val) . '</span>';
                                            break;

                                        case 'status':
                                            $map = $col['status_map'][$val] ?? ['label' => (string) $val, 'class' => 'neutral'];
                                            echo '<span class="badge badge--' . htmlspecialchars($map['class']) . '">' . htmlspecialchars($map['label']) . '</span>';
                                            break;

                                        case 'toggle':
                                            $checked = !empty($val) ? 'checked' : '';
                                            echo '<label class="switch">'
                                                . '<input type="checkbox" ' . $checked . ' data-toggle-resource="' . htmlspecialchars($resourceKey) . '" data-toggle-field="' . htmlspecialchars($key) . '" data-toggle-id="' . htmlspecialchars((string) $row['id']) . '" />'
                                                . '<span class="switch__track"></span></label>';
                                            break;

                                        case 'count-badge':
                                            $suffix = $col['suffix'] ?? '';
                                            echo '<span class="badge badge--neutral">' . (int) $val . ' ' . htmlspecialchars($suffix) . '</span>';
                                            break;

                                        case 'text':
                                        default:
                                            echo htmlspecialchars((string) $val);
                                            break;
                                    }
                                    ?>
                                </td>
                            <?php endforeach; ?>

                            <td>
                                <div class="table-cell-actions">

                                    <!-- 1. AKSI TERIMA / TOLAK (KHUSUS ADMINISTRATOR DENGAN STATUS PENDING) -->
                                    <?php if ($resourceKey === 'administrator' && isset($row['status']) && $row['status'] === 'pending'): ?>
                                        <form action="<?= url('admin/administrator/approve/' . $row['id']) ?>" method="POST" style="display:inline;">
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn--icon btn--sm btn-success" title="Setujui Pendaftaran" onclick="return confirm('Setujui akun ini sebagai administrator?')">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            </button>
                                        </form>

                                        <form action="<?= url('admin/administrator/approve/' . $row['id']) ?>" method="POST" style="display:inline;">
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn--icon btn--sm btn-danger" title="Tolak Pendaftaran" onclick="return confirm('Tolak pendaftaran akun ini?')">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                                </svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <!-- 2. AKSI NAIK / TURUN (KHUSUS DESTINASI, POTENSI DESA, PRODUK, PROGRAM & TIM KKN, PERANGKAT DESA, MITRA) -->
                                    <?php if ($canReorder): ?>
                                        <button type="button"
                                            class="btn btn--icon btn--sm js-reorder-btn btn-secondary"
                                            data-id="<?= $row['id'] ?>"
                                            data-resource="<?= htmlspecialchars($resourceKey) ?>"
                                            data-direction="up"
                                            title="Pindah ke Atas">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 19V5M5 12l7-7 7 7" />
                                            </svg>
                                        </button>

                                        <button type="button"
                                            class="btn btn--icon btn--sm js-reorder-btn btn-secondary"
                                            data-id="<?= $row['id'] ?>"
                                            data-resource="<?= htmlspecialchars($resourceKey) ?>"
                                            data-direction="down"
                                            title="Pindah ke Bawah">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 5v14M5 12l7 7 7-7" />
                                            </svg>
                                        </button>
                                    <?php endif; ?>

                                    <!-- 3. AKSI EDIT (WAJIB ADA DI SEMUA RESOURCE & SECTION) -->
                                    <a href="<?= $editUrl ?>" class="btn btn-secondary btn-icon" aria-label="Edit" title="Edit">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                        </svg>
                                    </a>

                                    <!-- 4. AKSI HAPUS (WAJIB ADA DI SEMUA RESOURCE & SECTION) -->
                                    <a href="<?= url('/admin/' . $resourceKey . '/delete/' . $rowId) ?>" class="btn btn-danger btn-icon" aria-label="Hapus" title="Hapus"
                                        data-confirm="Hapus &quot;<?= htmlspecialchars($row['delete_label'] ?? $row['username'] ?? $row['title'] ?? $row['name'] ?? 'item ini') ?>&quot;? Tindakan ini tidak bisa dibatalkan.">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6" />
                                        </svg>
                                    </a>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="<?= count($columns) + 2 ?>" style="text-align:center; color:var(--admin-text-muted); padding: 2.5rem;">
                                Belum ada data <?= htmlspecialchars(strtolower($pageLabel)) ?>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($pagination) && isset($pagination['total_pages']) && $pagination['total_pages'] > 1): ?>
            <div class="pagination">
                <span>Menampilkan <?= $pagination['from'] ?> - <?= $pagination['to'] ?> dari <?= $pagination['total'] ?> data</span>
                <div class="pagination__pages">
                    <a href="?<?= htmlspecialchars($paginationParam) ?>=<?= max(1, $pagination['current_page'] - 1) ?>">&lsaquo;</a>
                    <?php for ($p = 1; $p <= $pagination['total_pages']; $p++): ?>
                        <?php if ($p === $pagination['current_page']): ?>
                            <span class="is-active"><?= $p ?></span>
                        <?php else: ?>
                            <a href="?<?= htmlspecialchars($paginationParam) ?>=<?= $p ?>"><?= $p ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <a href="?<?= htmlspecialchars($paginationParam) ?>=<?= min($pagination['total_pages'], $pagination['current_page'] + 1) ?>">&rsaquo;</a>
                </div>
            </div>
        <?php elseif (!empty($pagination)): ?>
            <div class="pagination">
                <span>Menampilkan <?= $pagination['from'] ?> - <?= $pagination['to'] ?> dari <?= $pagination['total'] ?> data</span>
            </div>
        <?php endif; ?>
    </div>
<?php
}
