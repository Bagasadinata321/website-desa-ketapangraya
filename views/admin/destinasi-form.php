<?php

/**
 * View: destinasi-form
 * Pure content fragment. Expected variables:
 *   $isEdit   bool
 *   $data     array — keys map directly to `destinations` columns:
 *             title, slug, location, excerpt, content, status,
 *             sort_order, is_featured
 * Note: form field is named "sort_order" (not "order") to match the
 * column name directly — no renaming needed in the controller.
 */
if (!isset($isEdit)) {
    $editId = $destinasi_id ?? null;
    $isEdit = $editId !== null;
}

if ($isEdit) {
    // Placeholder fallback — remove once the controller passes real data.
    $data = $isEdit ? [
        'title'    => 'Pantai Cemare',
        'slug'     => 'pantai-cemare',
        'location' => 'Ketapang Raya, Lombok',
        'excerpt'  => 'Pantai indah dengan pasir putih dan air jernih yang cocok untuk wisata keluarga.',
        'content'  => "Pantai Cemare merupakan salah satu destinasi unggulan di Desa Ketapang Raya. Pantai ini memiliki hamparan pasir yang masih terjaga dan berbatasan langsung dengan laut lepas. Cocok untuk berenang, memancing, dan bersantai bersama keluarga.",
        'status'   => 'published',
        'sort_order' => 1,
        'is_featured' => true,
    ] : [
        'title' => '',
        'slug' => '',
        'location' => '',
        'excerpt' => '',
        'content' => '',
        'status' => 'published',
        'sort_order' => '',
        'is_featured' => false,
    ];
}
?>
<main class="admin-content">
    <div class="admin-page-header">
        <h1><?= $isEdit ? 'Edit Destinasi' : 'Tambah Destinasi' ?></h1>
        <p class="admin-breadcrumb"><a href="dashboard.php">Data Konten</a> / <a href="destinasi-list.php">Destinasi</a> / <span><?= $isEdit ? 'Edit' : 'Tambah' ?></span></p>
    </div>

    <form method="post" action="destinasi-save.php">
        <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= htmlspecialchars($editId) ?>" /><?php endif; ?>

        <div class="admin-grid-2">
            <!-- Left column -->
            <div style="display:flex; flex-direction:column; gap:1.5rem;">
                <div class="card">
                    <div class="card__body">
                        <div class="form-group">
                            <label class="form-label" for="title">Judul</label>
                            <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($data['title']) ?>" placeholder="Pantai Cemare" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="slug">Slug</label>
                            <input type="text" id="slug" name="slug" class="form-control" value="<?= htmlspecialchars($data['slug']) ?>" placeholder="pantai-cemare" />
                            <p class="form-hint">URL: /destinasi/<?= htmlspecialchars($data['slug'] ?: 'slug-otomatis') ?></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="location">Lokasi</label>
                            <input type="text" id="location" name="location" class="form-control" value="<?= htmlspecialchars($data['location']) ?>" placeholder="Ketapang Raya, Lombok" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="excerpt">Excerpt</label>
                            <textarea id="excerpt" name="excerpt" class="form-control" placeholder="Ringkasan singkat untuk tampilan card/listing..."><?= htmlspecialchars($data['excerpt']) ?></textarea>
                            <p class="form-hint">Ditampilkan di halaman listing, bukan halaman detail.</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="content">Konten</label>
                            <div class="rte-toolbar">
                                <button type="button"><strong>B</strong></button>
                                <button type="button"><em>I</em></button>
                                <button type="button" style="text-decoration:underline;">U</button>
                                <span class="rte-sep"></span>
                                <button type="button">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="17" y1="10" x2="3" y2="10" />
                                        <line x1="21" y1="6" x2="3" y2="6" />
                                        <line x1="21" y1="14" x2="3" y2="14" />
                                        <line x1="17" y1="18" x2="3" y2="18" />
                                    </svg>
                                </button>
                                <button type="button">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="8" y1="6" x2="21" y2="6" />
                                        <line x1="8" y1="12" x2="21" y2="12" />
                                        <line x1="8" y1="18" x2="21" y2="18" />
                                        <line x1="3" y1="6" x2="3.01" y2="6" />
                                        <line x1="3" y1="12" x2="3.01" y2="12" />
                                        <line x1="3" y1="18" x2="3.01" y2="18" />
                                    </svg>
                                </button>
                                <span class="rte-sep"></span>
                                <button type="button">🔗</button>
                            </div>
                            <textarea id="content" name="content" class="form-control rte-body"><?= htmlspecialchars($data['content']) ?></textarea>
                            <p class="form-hint">Ditampilkan di halaman detail destinasi.</p>
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
                                <div class="ph">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M3 16l5-5 4 4 5-6 4 5" />
                                        <circle cx="12" cy="12" r="10" />
                                    </svg>
                                </div>
                            </div>
                            <div class="cover-picker__actions">
                                <button type="button" class="btn btn-secondary btn-sm btn-block" data-trigger-upload="#coverInput">Pilih Gambar</button>
                                <input type="file" id="coverInput" accept="image/*" hidden data-preview-target="#coverPreview" name="cover_image" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card__header">
                        <h2>Gallery</h2>
                    </div>
                    <div class="card__body">
                        <div class="gallery-picker">
                            <div class="gallery-picker__item">
                                <div class="ph"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <circle cx="12" cy="8" r="4" />
                                        <path d="M4 21c0-4 4-6 8-6s8 2 8 6" />
                                    </svg></div>
                            </div>
                            <div class="gallery-picker__item">
                                <div class="ph"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M4 20V9M4 9l8-6 8 6M4 9l16 0M20 20V9" />
                                    </svg></div>
                            </div>
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
                            <select id="status" name="status" class="form-control">
                                <option value="published" <?= $data['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                                <option value="draft" <?= $data['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="sort_order">Urutan</label>
                            <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?= htmlspecialchars((string) $data['sort_order']) ?>" min="1" />
                        </div>
                        <div class="form-group">
                            <label style="display:flex; align-items:center; gap:0.6rem; cursor:pointer;">
                                <label class="switch">
                                    <input type="checkbox" name="is_featured" value="1" <?= !empty($data['is_featured']) ? 'checked' : '' ?> />
                                    <span class="switch__track"></span>
                                </label>
                                <span class="form-label" style="margin-bottom:0;">Jadikan Unggulan (tampil di Homepage)</span>
                            </label>
                        </div>

                        <div class="form-actions" style="flex-direction:column; align-items:stretch;">
                            <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                            <button type="submit" name="save_as" value="draft" class="btn btn-secondary btn-block">Simpan Draft</button>
                            <a href="destinasi-list.php" class="btn btn-secondary btn-block">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</main>