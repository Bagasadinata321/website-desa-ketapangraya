<?php

/**
 * View: forms/form-lengkap (Kelompok A)
 * Reusable for: Destinasi, Produk, Program KKN.
 * Pure content fragment — no <html>, no layout.
 *
 * Expected variables (set by controller):
 *   $resourceKey     string  e.g. 'destinasi' | 'produk' | 'program-kkn'
 *   $pageLabel       string  e.g. 'Destinasi'
 *   $isEdit          bool
 *   $data            array   existing record (edit) or empty defaults (create):
 *                            title, slug, secondary, excerpt, content (ARRAY of
 *                            blocks — lihat block-editor.js), status, sort_order,
 *                            is_featured, cover_url, gallery (array of image URLs)
 *
 *   $secondaryField  array|null  the ONE field that differs per resource, e.g.:
 *                            Destinasi:   ['key'=>'location',     'label'=>'Lokasi',      'placeholder'=>'Ketapang Raya, Lombok']
 *                            Produk:      ['key'=>'price_info',   'label'=>'Info Harga',  'placeholder'=>'Hubungi kami']
 *                            Program KKN: ['key'=>'period_label', 'label'=>'Periode',     'placeholder'=>'2024']
 *                            Pass null to hide this field entirely.
 *
 *   $statusType      string  'published_draft' (Destinasi, Produk) | 'active_inactive' (Program KKN)
 *   $showFeatured    bool    show "Jadikan Unggulan" toggle — true only for Destinasi
 *                            (products/programs tables have no is_featured column)
 */

if (!isset($resourceKey)) {
    $resourceKey = 'destinasi';
}
if (!isset($pageLabel)) {
    $pageLabel = 'Destinasi';
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
if (!isset($secondaryField)) {
    $secondaryField = ['key' => 'location', 'label' => 'Lokasi', 'placeholder' => 'Ketapang Raya, Lombok'];
}
if (!isset($statusType)) {
    $statusType = 'published_draft';
}
if (!isset($showFeatured)) {
    $showFeatured = true;
}

if (!isset($data)) {
    // Placeholder fallback — remove once the controller passes real data.
    $data = [
        'id' => $isEdit ? 1 : null,
        'title' => $isEdit ? 'Pantai Cemare' : '',
        'slug' => $isEdit ? 'pantai-cemare' : '',
        'secondary' => $isEdit ? 'Ketapang Raya, Lombok' : '',
        'excerpt' => $isEdit ? 'Pantai indah dengan pasir putih dan air jernih yang cocok untuk wisata keluarga.' : '',
        'content' => $isEdit ? [
            ['type' => 'paragraph', 'text' => 'Pantai Cemare merupakan salah satu destinasi unggulan di Desa Ketapang Raya, menawarkan pasir putih dan air laut yang jernih.'],
        ] : [],
        'status' => $statusType === 'active_inactive' ? 1 : 'published',
        'sort_order' => $isEdit ? 1 : '',
        'is_featured' => $isEdit,
        'cover_url' => null,
        'gallery' => [],
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
            <!-- Left column -->
            <div style="display:flex; flex-direction:column; gap:1.5rem;">
                <div class="card">
                    <div class="card__body">
                        <div class="form-group">
                            <label class="form-label" for="title">Judul</label>
                            <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($data['title']) ?>" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="slug">Slug</label>
                            <input type="text" id="slug" name="slug" class="form-control" value="<?= htmlspecialchars($data['slug']) ?>" />
                            <p class="form-hint">URL: /<?= htmlspecialchars($resourceKey) ?>/<?= htmlspecialchars($data['slug'] ?: 'slug-otomatis') ?></p>
                        </div>

                        <?php if ($secondaryField): ?>
                            <div class="form-group">
                                <label class="form-label" for="secondary"><?= htmlspecialchars($secondaryField['label']) ?></label>
                                <input type="text" id="secondary" name="<?= htmlspecialchars($secondaryField['key']) ?>" class="form-control" value="<?= htmlspecialchars($data['secondary']) ?>" placeholder="<?= htmlspecialchars($secondaryField['placeholder'] ?? '') ?>" />
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label class="form-label" for="excerpt">Excerpt</label>
                            <textarea id="excerpt" name="excerpt" class="form-control" placeholder="Ringkasan singkat untuk tampilan card/listing..."><?= htmlspecialchars($data['excerpt']) ?></textarea>
                            <p class="form-hint">Ditampilkan di halaman listing, bukan halaman detail.</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Konten</label>

                            <div class="block-editor" data-block-editor>
                                <div class="block-editor__list" data-block-list></div>

                                <p class="block-editor__empty" data-block-empty>
                                    Belum ada konten. Tambah block pertama lewat tombol di bawah.
                                </p>

                                <div class="block-editor__add">
                                    <button type="button" class="btn btn-secondary btn-sm" data-add-type="heading">+ Heading</button>
                                    <button type="button" class="btn btn-secondary btn-sm" data-add-type="paragraph">+ Paragraf</button>
                                    <button type="button" class="btn btn-secondary btn-sm" data-add-type="quote">+ Kutipan</button>
                                    <button type="button" class="btn btn-secondary btn-sm" data-add-type="list">+ List</button>
                                </div>
                            </div>

                            <!-- Data awal editor. type="application/json" supaya browser tidak
                                 coba render isinya sebagai HTML, dan aman dari escaping HTML biasa. -->
                            <script type="application/json" data-block-editor-initial>
                                <?= json_encode(
                                    is_array($data['content'] ?? null) ? $data['content'] : [],
                                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                                ) ?>
                            </script>

                            <!-- Ini yang benar-benar terkirim ke server saat submit — diisi
                                 otomatis oleh block-editor.js dari block-block di atas. -->
                            <input type="hidden" id="content" name="content" data-block-editor-output />

                            <p class="form-hint">Ditampilkan di halaman detail, tersimpan sebagai JSON block.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right column -->
            <div style="display:flex; flex-direction:column; gap:1.5rem;">
                <div class="card">
                    <div class="card__header">
                        <h2>Gambar Cover</h2>
                    </div>
                    <div class="card__body">
                        <div class="cover-picker">
                            <div class="cover-picker__preview" id="coverPreview">
                                <?php if (!empty($data['cover_url'])): ?>
                                    <img src="<?= url(htmlspecialchars($data['cover_url'])) ?>" alt="" style="width:100%;height:100%;object-fit:cover;" />
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
                        <p class="form-hint">Disimpan lewat media_relations (usage: cover), bukan kolom langsung.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card__header">
                        <h2>Gallery</h2>
                    </div>
                    <div class="card__body">
                        <div class="gallery-picker">
                            <?php foreach ($data['gallery'] as $g): ?>
                                <div class="gallery-picker__item"><img src="<?= url(htmlspecialchars($g)); ?>" alt="" style="width:100%;height:100%;object-fit:cover;" /></div>
                            <?php endforeach; ?>
                            <button type="button" class="gallery-picker__add" data-trigger-upload="#galleryInput">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 5v14M5 12h14" />
                                </svg>
                                Tambah Gambar
                            </button>
                            <input type="file" id="galleryInput" accept="image/*" multiple hidden name="gallery_images[]" />
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card__body">
                        <div class="form-group">
                            <label class="form-label" for="status">Status</label>
                            <?php if ($statusType === 'active_inactive'): ?>
                                <select id="status" name="status" class="form-control">
                                    <option value="1" <?= $data['status'] == 1 ? 'selected' : '' ?>>Aktif</option>
                                    <option value="0" <?= $data['status'] == 0 ? 'selected' : '' ?>>Nonaktif</option>
                                </select>
                            <?php else: ?>
                                <select id="status" name="status" class="form-control">
                                    <option value="published" <?= $data['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                                    <option value="draft" <?= $data['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                                </select>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="sort_order">Urutan</label>
                            <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?= htmlspecialchars((string) $data['sort_order']) ?>" min="1" />
                        </div>

                        <?php if ($showFeatured): ?>
                            <div class="form-group">
                                <label style="display:flex; align-items:center; gap:0.6rem; cursor:pointer;">
                                    <label class="switch">
                                        <input type="checkbox" name="is_featured" value="1" <?= !empty($data['is_featured']) ? 'checked' : '' ?> />
                                        <span class="switch__track"></span>
                                    </label>
                                    <span class="form-label" style="margin-bottom:0;">Jadikan Unggulan (tampil di Homepage)</span>
                                </label>
                            </div>
                        <?php endif; ?>

                        <div class="form-actions" style="flex-direction:column; align-items:stretch;">
                            <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                            <?php if ($statusType === 'published_draft'): ?>
                                <button type="submit" name="save_as" value="draft" class="btn btn-secondary btn-block">Simpan Draft</button>
                            <?php endif; ?>
                            <a href="<?= url('admin/' . $resourceKey . '/list') ?>" class="btn btn-secondary btn-block">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<script src="<?= noCache('/js/admin/block-editor.js') ?>"></script>