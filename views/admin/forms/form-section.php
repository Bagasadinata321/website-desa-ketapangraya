<?php

/**
 * View: forms/form-section
 * Reusable untuk SEMUA section yang tidak locked, di semua halaman
 * (home, tentang-kami, destinasi-produk). Section locked TIDAK PERNAH
 * sampai ke form ini — sudah ditolak duluan di AdminController::sectionEdit().
 *
 * Bentuk form TIDAK lagi hardcode (title/subtitle/description/meta) —
 * dirender dinamis dari $fields, skema per section yang didefinisikan di
 * AdminController::$resourceMap (lihat konten-home/konten-tentang-kami/
 * konten-destinasi-produk). Field type yang didukung: 'text', 'textarea',
 * 'image', 'repeater'.
 *
 * Expected variables (set by controller):
 *   $resourceKey    string  e.g. 'konten-home'
 *   $sectionKey     string  e.g. 'hero'
 *   $sectionLabel   string  e.g. 'Hero'
 *   $pageLabel      string  e.g. 'Konten Homepage'
 *   $fields         array   skema field section ini (lihat AdminController)
 *   $data           array   nilai saat ini, key sesuai $fields, plus 'image_url'
 */

if (!isset($fields)) {
    $fields = [];
}
if (!isset($data)) {
    $data = ['title' => '', 'subtitle' => '', 'description' => '', 'image_url' => null];
}
?>
<main class="admin-content">
    <div class="admin-page-header">
        <h1>Edit Konten: <?= htmlspecialchars($sectionLabel) ?></h1>
        <p class="admin-breadcrumb">
            <a href="<?= url('admin/' . $resourceKey . '/list') ?>">Data Konten</a> /
            <a href="<?= url('admin/' . $resourceKey . '/list') ?>"><?= htmlspecialchars($pageLabel) ?></a> /
            <span><?= htmlspecialchars($sectionLabel) ?></span>
        </p>
    </div>

    <form action="<?= url('admin/' . $resourceKey . '/section-save') ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="section_key" value="<?= htmlspecialchars($sectionKey ?? '') ?>">

        <div class="card">
            <div class="card__body">

                <?php foreach ($fields as $field): ?>
                    <?php
                    $key   = $field['key'];
                    $label = $field['label'];
                    $type  = $field['type'];
                    $value = $data[$key] ?? '';
                    ?>

                    <?php if ($type === 'text'): ?>
                        <div class="form-group">
                            <label class="form-label" for="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></label>
                            <input
                                type="text"
                                id="<?= htmlspecialchars($key) ?>"
                                name="<?= htmlspecialchars($key) ?>"
                                class="form-control"
                                value="<?= htmlspecialchars((string) $value) ?>"
                                placeholder="<?= htmlspecialchars($field['placeholder'] ?? '') ?>" />
                        </div>

                    <?php elseif ($type === 'textarea'): ?>
                        <div class="form-group">
                            <label class="form-label" for="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></label>
                            <textarea
                                id="<?= htmlspecialchars($key) ?>"
                                name="<?= htmlspecialchars($key) ?>"
                                class="form-control"
                                rows="5"><?= htmlspecialchars((string) $value) ?></textarea>
                        </div>

                    <?php elseif ($type === 'image'): ?>
                        <div class="form-group">
                            <label class="form-label" for="image"><?= htmlspecialchars($label) ?></label>
                            <?php if (!empty($data['image_url'])): ?>
                                <div class="form-current-image" style="margin-bottom:.5rem;">
                                    <img
                                        src="<?= htmlspecialchars($data['image_url']) ?>"
                                        alt=""
                                        style="max-width:240px;display:block;border-radius:8px;" />
                                </div>
                            <?php endif; ?>
                            <input
                                type="file"
                                id="image"
                                name="image"
                                class="form-control"
                                accept="image/jpeg,image/png,image/gif,image/webp" />
                            <p class="form-hint">
                                Kosongkan kalau tidak ingin mengganti gambar saat ini. Format JPG/PNG/GIF/WebP,
                                maks. 5MB — otomatis dikonversi ke WebP.
                            </p>
                        </div>

                    <?php elseif ($type === 'repeater'): ?>
                        <div class="form-group">
                            <label class="form-label"><?= htmlspecialchars($label) ?></label>

                            <div class="repeater" data-repeater="<?= htmlspecialchars($key) ?>">
                                <?php
                                $rows = is_array($value) && !empty($value)
                                    ? $value
                                    : [array_fill_keys(array_column($field['item_fields'], 'key'), '')];
                                ?>
                                <?php foreach ($rows as $i => $row): ?>
                                    <div class="repeater__row" style="display:flex;gap:.75rem;align-items:flex-end;margin-bottom:.75rem;">
                                        <?php foreach ($field['item_fields'] as $itemField): ?>
                                            <div class="form-group" style="flex:1;margin-bottom:0;">
                                                <label class="form-label"><?= htmlspecialchars($itemField['label']) ?></label>
                                                <input
                                                    type="text"
                                                    name="<?= htmlspecialchars($key) ?>[<?= (int) $i ?>][<?= htmlspecialchars($itemField['key']) ?>]"
                                                    class="form-control"
                                                    value="<?= htmlspecialchars((string) ($row[$itemField['key']] ?? '')) ?>"
                                                    placeholder="<?= htmlspecialchars($itemField['placeholder'] ?? '') ?>" />
                                            </div>
                                        <?php endforeach; ?>
                                        <button type="button" class="btn btn-secondary btn-sm repeater__remove">Hapus</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <button type="button" class="btn btn-secondary btn-sm" data-repeater-add="<?= htmlspecialchars($key) ?>">
                                + Tambah Item
                            </button>

                            <template data-repeater-template="<?= htmlspecialchars($key) ?>">
                                <div class="repeater__row" style="display:flex;gap:.75rem;align-items:flex-end;margin-bottom:.75rem;">
                                    <?php foreach ($field['item_fields'] as $itemField): ?>
                                        <div class="form-group" style="flex:1;margin-bottom:0;">
                                            <label class="form-label"><?= htmlspecialchars($itemField['label']) ?></label>
                                            <input
                                                type="text"
                                                name="<?= htmlspecialchars($key) ?>[__INDEX__][<?= htmlspecialchars($itemField['key']) ?>]"
                                                class="form-control"
                                                placeholder="<?= htmlspecialchars($itemField['placeholder'] ?? '') ?>" />
                                        </div>
                                    <?php endforeach; ?>
                                    <button type="button" class="btn btn-secondary btn-sm repeater__remove">Hapus</button>
                                </div>
                            </template>
                        </div>
                    <?php endif; ?>

                <?php endforeach; ?>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= url('admin/' . $resourceKey . '/list') ?>" class="btn btn-secondary">Batal</a>
                </div>
            </div>
        </div>
    </form>
</main>

<script src="<?= noCache('/js/admin/multiparaghraf.js') ?>"></script>