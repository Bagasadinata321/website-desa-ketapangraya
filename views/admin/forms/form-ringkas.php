<?php

/**
 * View: forms/form-ringkas (Kelompok B)
 * Reusable for: Potensi Desa (table: highlights).
 * Pure content fragment — no <html>, no layout.
 *
 * Expected variables (set by controller):
 *   $resourceKey  string  'potensi-desa'
 *   $pageLabel    string  'Potensi Desa'
 *   $isEdit       bool
 *   $data         array   title, excerpt, status (0|1), sort_order, cover_url
 */

if (!isset($resourceKey)) {
    $resourceKey = 'potensi-desa';
}
if (!isset($pageLabel)) {
    $pageLabel = 'Potensi Desa';
}
if (isset($item) && !isset($data)) {
    $data = $item;
}
if (isset($mode) && !isset($isEdit)) {
    $isEdit = ($mode === 'edit');
}
if (!isset($isEdit)) {
    $isEdit = isset($_GET['id']);
}

if (!isset($data)) {
    // Placeholder fallback — remove once the controller passes real data.
    $data = [
        'id' => $isEdit ? 1 : null,
        'title' => $isEdit ? 'Ekowisata' : '',
        'excerpt' => $isEdit ? 'Keindahan alam mangrove sebagai daya tarik wisata dan edukasi lingkungan.' : '',
        'status' => 1,
        'sort_order' => $isEdit ? 1 : '',
        'cover_url' => null,
    ];
}
?>
<main class="admin-content">
    <div class="admin-page-header">
        <h1><?= $isEdit ? 'Edit ' . htmlspecialchars($pageLabel) : 'Tambah ' . htmlspecialchars($pageLabel) ?></h1>
        <p class="admin-breadcrumb">
            <a href="<?= url('admin/' . $resourceKey . '/list') ?>">Data Konten</a> /
            <a href="<?= url('admin/' . $resourceKey . '/list') ?>"><?= htmlspecialchars($pageLabel) ?></a> /
            <span><?= $isEdit ? 'Edit' : 'Tambah' ?></span>
        </p>
    </div>

    <form method="post" action="<?= url('admin/' . $resourceKey . '/save') ?>" enctype="multipart/form-data">
        <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= htmlspecialchars((string) $data['id']) ?>" /><?php endif; ?>

        <div class="admin-grid-2">
            <div class="card">
                <div class="card__body">
                    <div class="form-group">
                        <label class="form-label" for="title">Judul</label>
                        <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($data['title']) ?>" placeholder="Ekowisata" />
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="excerpt">Deskripsi Singkat</label>
                        <textarea id="excerpt" name="excerpt" class="form-control" placeholder="1-2 kalimat ringkas, tanpa konten panjang."><?= htmlspecialchars($data['excerpt']) ?></textarea>
                        <p class="form-hint">Section ini tampil sebagai card ringkas di homepage — tidak ada halaman detail terpisah.</p>
                    </div>
                </div>
            </div>

            <div style="display:flex; flex-direction:column; gap:1.5rem;">
                <div class="card">
                    <div class="card__header">
                        <h2>Gambar</h2>
                    </div>
                    <div class="card__body">
                        <div class="cover-picker">
                            <div class="cover-picker__preview" id="coverPreview">
                                <?php if (!empty($data['cover_url'])): ?>
                                    <img src="<?= htmlspecialchars($data['cover_url']) ?>" alt="" style="width:100%;height:100%;object-fit:cover;" />
                                <?php else: ?>
                                    <div class="ph"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path d="M3 16l5-5 4 4 5-6 4 5" />
                                            <circle cx="12" cy="12" r="10" />
                                        </svg></div>
                                <?php endif; ?>
                            </div>
                            <div class="cover-picker__actions">
                                <button type="button" class="btn btn-secondary btn-sm btn-block" data-trigger-upload="#coverInput">Pilih Gambar</button>
                                <input type="file" id="coverInput" accept="image/*" hidden data-preview-target="#coverPreview" name="cover_image" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card__body">
                        <div class="form-group">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="1" <?= $data['status'] == 1 ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= $data['status'] == 0 ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="sort_order">Urutan</label>
                            <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?= htmlspecialchars((string) $data['sort_order']) ?>" min="1" />
                        </div>

                        <div class="form-actions" style="flex-direction:column; align-items:stretch;">
                            <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                            <a href="<?= url('admin/' . $resourceKey . '/list') ?>" class="btn btn-secondary btn-block">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>