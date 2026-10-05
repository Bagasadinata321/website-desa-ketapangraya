<div class="admin-page-header" style="padding: 20px 20px 10px">
    <h1>Edit Pengaturan Website</h1>
    <div class="admin-breadcrumb">
        <a href="<?= url('admin/dashboard') ?>">Dashboard</a> / <span>Pengaturan Website</span>
    </div>
</div>

<form style="padding: 0 20px 20px" action="<?= url('/admin/settings/save') ?>" method="POST" enctype="multipart/form-data">
    <div class="settings-cards">

        <!-- KARTU 1: INFORMASI UMUM & LOGO -->
        <div class="card">
            <div class="card__header">
                <h2>Informasi Umum Desa</h2>
            </div>
            <div class="card__body">
                <div class="form-group">
                    <label class="form-label">Nama Desa</label>
                    <input type="text" name="village_name" class="form-control" value="<?= htmlspecialchars($settings['village_name'] ?? '') ?>" placeholder="Desa Ketapang Raya">
                </div>

                <div class="form-group">
                    <label class="form-label">Logo Desa</label>
                    <?php if (!empty($settings['village_logo'])): ?>
                        <div class="settings-brand-preview">
                            <div class="settings-brand-preview__box">
                                <img src="<?= url(htmlspecialchars($settings['village_logo'])) ?>" alt="Logo Desa" style="width:100%; height:100%; object-fit:contain;" />
                            </div>
                            <div class="form-hint">Logo saat ini terpasang</div>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="village_logo" class="form-control" accept="image/*">
                    <div class="form-hint">Kosongkan jika tidak ingin mengganti logo. Format JPG, PNG, WebP, SVG.</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kecamatan</label>
                        <input type="text" name="district" class="form-control" value="<?= htmlspecialchars($settings['district'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kabupaten</label>
                        <input type="text" name="regency" class="form-control" value="<?= htmlspecialchars($settings['regency'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Provinsi</label>
                        <input type="text" name="province" class="form-control" value="<?= htmlspecialchars($settings['province'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kode Pos</label>
                        <input type="text" name="postal_code" class="form-control" value="<?= htmlspecialchars($settings['postal_code'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- KARTU 2: KONTAK & ALAMAT -->
        <div class="card">
            <div class="card__header">
                <h2>Kontak & Alamat</h2>
            </div>
            <div class="card__body">
                <div class="form-group">
                    <label class="form-label">Alamat Kantor Desa</label>
                    <textarea name="office_address" class="form-control" rows="3"><?= htmlspecialchars($settings['office_address'] ?? '') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email Resmi Desa</label>
                        <input type="email" name="village_email" class="form-control" value="<?= htmlspecialchars($settings['village_email'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telepon / WhatsApp</label>
                        <input type="text" name="village_phone" class="form-control" value="<?= htmlspecialchars($settings['village_phone'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Embed Iframe Google Maps</label>
                    <textarea name="footer_map_iframe" class="form-control" rows="3" placeholder="<iframe src=... ></iframe>"><?= htmlspecialchars($settings['footer_map_iframe'] ?? '') ?></textarea>
                    <div class="form-hint">Paste kode embed HTML map dari Google Maps untuk ditampilkan di footer.</div>
                </div>
            </div>
        </div>

        <!-- KARTU 3: MEDIA SOSIAL & FOOTER CREDIT -->
        <div class="card" style="grid-column: 1 / -1;">
            <div class="card__header">
                <h2>Media Sosial & Footer</h2>
            </div>
            <div class="card__body">
                <div class="admin-grid-3col">
                    <div class="form-group">
                        <label class="form-label">URL Instagram</label>
                        <input type="text" name="social_instagram" class="form-control" value="<?= htmlspecialchars($settings['social_instagram'] ?? '') ?>" placeholder="https://instagram.com/...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">URL Facebook</label>
                        <input type="text" name="social_facebook" class="form-control" value="<?= htmlspecialchars($settings['social_facebook'] ?? '') ?>" placeholder="https://facebook.com/...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">URL YouTube</label>
                        <input type="text" name="social_youtube" class="form-control" value="<?= htmlspecialchars($settings['social_youtube'] ?? '') ?>" placeholder="https://youtube.com/...">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label class="form-label">Teks Credit Footer</label>
                    <input type="text" name="footer_credit" class="form-control" value="<?= htmlspecialchars($settings['footer_credit'] ?? '') ?>" placeholder="Website dikembangkan oleh KKN">
                </div>

                <!-- FOOTER ACTIONS -->

            </div>
        </div>
        <div class="card" style="grid-column: 1 / -1;">
            <div class="card__header">
                <h2>Notifikasi Email Pendaftaran</h2>
            </div>
            <div class="card__body">
                <div class="form-group">
                    <label class="form-label">Email Penerima Notifikasi (Super Admin)</label>
                    <input type="email" name="superadmin_email" class="form-control" value="<?= htmlspecialchars($settings['superadmin_email'] ?? '') ?>" placeholder="contoh: admin@gmail.com">
                    <div class="form-hint">Setiap kali ada pendaftaran administrator baru, notifikasi persetujuan akan dikirimkan ke alamat email ini.</div>
                </div>
                <div class="form-actions">
                    <a href="<?= url('admin/dashboard') ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        Simpan Pengaturan
                    </button>
                </div>
            </div>
        </div>
        <!-- KARTU 4: PENGATURAN NOTIFIKASI EMAIL -->

    </div>
</form>