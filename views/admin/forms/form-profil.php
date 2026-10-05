<?php

/**
 * View: forms/form-profil (Kelompok C)
 * Reusable for: Perangkat Desa (officials), Mahasiswa KKN (students),
 * Mitra (partners), Administrator (admins).
 * Pure content fragment — no <html>, no layout.
 *
 * These 4 entities diverge more than Kelompok A/B, so this form is built
 * around a base set of fields (name + status + sort_order) plus a
 * controller-supplied $extraFields list for whatever's specific to that
 * entity. This keeps it 1 file instead of 4 near-duplicates.
 *
 * Expected variables (set by controller):
 *   $resourceKey     string  e.g. 'perangkat-desa' | 'mahasiswa-kkn' | 'mitra' | 'administrator'
 *   $pageLabel       string  e.g. 'Perangkat Desa'
 *   $isEdit          bool
 *   $primaryLabel    string  label for the "name" field — usually 'Nama',
 *                            but e.g. Administrator might just reuse 'Nama' too.
 *   $showPhoto       bool    true for officials/students/partners (photo/logo),
 *                            false for administrator (no photo needed)
 *   $photoLabel      string  'Foto Profil' | 'Logo'
 *   $showDescription bool    true only for officials (has `description` column);
 *                            false for students/partners/admins (no such column)
 *   $showSortOrder   bool    true for officials/students/partners;
 *                            false for administrator (table has no sort_order)
 *   $extraFields     array   ordered list of extra inputs specific to this
 *                            entity, each:
 *                            ['key','label','type'=>'text'|'email'|'password'|'select',
 *                             'placeholder' (optional), 'options' (for select, ['value'=>'label'])]
 *
 *   Examples of $extraFields per entity:
 *     officials:      [['key'=>'position','label'=>'Jabatan','type'=>'text']]
 *     students:       [['key'=>'position','label'=>'Peran di Tim','type'=>'text'],
 *                       ['key'=>'major','label'=>'Program Studi','type'=>'text'],
 *                       ['key'=>'campus','label'=>'Kampus Asal','type'=>'text'],
 *                       ['key'=>'nim','label'=>'NIM','type'=>'text']]
 *     partners:       [['key'=>'category','label'=>'Kategori','type'=>'text'],
 *                       ['key'=>'website_url','label'=>'Website','type'=>'text']]
 *     administrator:  [['key'=>'username','label'=>'Username','type'=>'text'],
 *                       ['key'=>'email','label'=>'Email','type'=>'email'],
 *                       ['key'=>'password','label'=>'Password','type'=>'password'],
 *                       ['key'=>'role','label'=>'Role','type'=>'select','options'=>[
 *                           'admin_desa'=>'Admin Desa','super_admin'=>'Super Admin']]]
 *
 *   $data            array   'name', plus each extraField's key, plus
 *                            'status' (0|1), 'sort_order', 'photo_url'
 */

if (!isset($resourceKey)) {
    $resourceKey = 'perangkat-desa';
}
if (!isset($pageLabel)) {
    $pageLabel = 'Perangkat Desa';
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
if (!isset($primaryLabel)) {
    $primaryLabel = 'Nama';
}
if (!isset($showPhoto)) {
    $showPhoto = true;
}
if (!isset($photoLabel)) {
    $photoLabel = 'Foto Profil';
}
if (!isset($showDescription)) {
    $showDescription = true;
}
if (!isset($showSortOrder)) {
    $showSortOrder = true;
}

if (!isset($extraFields)) {
    $extraFields = [
        ['key' => 'position', 'label' => 'Jabatan', 'type' => 'text', 'placeholder' => 'Sekretaris Desa'],
    ];
}

if (!isset($data)) {
    // Placeholder fallback — remove once the controller passes real data.
    $data = [
        'id' => $isEdit ? 1 : null,
        'name' => $isEdit ? 'S. Hasan Al Idrus' : '',
        'position' => $isEdit ? 'Sekretaris Desa' : '',
        'description' => '',
        'status' => 1,
        'sort_order' => $isEdit ? 1 : '',
        'photo_url' => null,
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
                        <label class="form-label" for="name"><?= htmlspecialchars($primaryLabel) ?></label>
                        <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($data['name']) ?>" />
                    </div>

                    <?php foreach ($extraFields as $field): ?>
                        <div class="form-group">
                            <label class="form-label" for="<?= htmlspecialchars($field['key']) ?>"><?= htmlspecialchars($field['label']) ?></label>
                            <?php if ($field['type'] === 'select'): ?>
                                <select id="<?= htmlspecialchars($field['key']) ?>" name="<?= htmlspecialchars($field['key']) ?>" class="form-control">
                                    <?php foreach ($field['options'] as $value => $label): ?>
                                        <option value="<?= htmlspecialchars($value) ?>" <?= ($data[$field['key']] ?? '') === $value ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <input type="<?= htmlspecialchars($field['type']) ?>" id="<?= htmlspecialchars($field['key']) ?>" name="<?= htmlspecialchars($field['key']) ?>" class="form-control"
                                    value="<?= $field['type'] === 'password' ? '' : htmlspecialchars((string) ($data[$field['key']] ?? '')) ?>"
                                    placeholder="<?= htmlspecialchars($field['placeholder'] ?? '') ?>" />
                                <?php if ($field['type'] === 'password' && $isEdit): ?>
                                    <p class="form-hint">Kosongkan kalau tidak ingin mengubah password.</p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <?php if ($showDescription): ?>
                        <div class="form-group">
                            <label class="form-label" for="description">Deskripsi <span class="optional">(Opsional)</span></label>
                            <textarea id="description" name="description" class="form-control"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div style="display:flex; flex-direction:column; gap:1.5rem;">
                <?php if ($showPhoto): ?>
                    <div class="card">
                        <div class="card__header">
                            <h2><?= htmlspecialchars($photoLabel) ?></h2>
                        </div>
                        <div class="card__body">
                            <div class="cover-picker">
                                <div class="cover-picker__preview" id="photoPreview" style="aspect-ratio: 3/4;">
                                    <?php if (!empty($data['photo_url'])): ?>
                                        <img src="<?= htmlspecialchars($data['photo_url']) ?>" alt="" style="width:100%;height:100%;object-fit:cover;" />
                                    <?php else: ?>
                                        <div class="ph"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <circle cx="12" cy="8" r="4" />
                                                <path d="M4 21c0-4 4-6 8-6s8 2 8 6" />
                                            </svg></div>
                                    <?php endif; ?>
                                </div>
                                <div class="cover-picker__actions">
                                    <button type="button" class="btn btn-secondary btn-sm btn-block" data-trigger-upload="#photoInput">Pilih Gambar</button>
                                    <input type="file" id="photoInput" accept="image/*" hidden data-preview-target="#photoPreview" name="cover_image" />
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card__body">
                        <div class="form-group">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="1" <?= $data['status'] == 1 ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= $data['status'] == 0 ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                        </div>

                        <?php if ($showSortOrder): ?>
                            <div class="form-group">
                                <label class="form-label" for="sort_order">Urutan</label>
                                <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?= htmlspecialchars((string) $data['sort_order']) ?>" min="1" />
                            </div>
                        <?php endif; ?>

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