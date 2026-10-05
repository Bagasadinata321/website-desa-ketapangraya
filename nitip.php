/**

 * WPCode Snippet: GSC Advanced Bulk Cleanup + Auto 301 Redirect & Direct Post Access

 * Author: Custom SEO Assistant

 */



// 1. Tambahkan menu di WordPress Admin (Tools > GSC Bulk Cleanup)

add_action('admin_menu', function () {

    add_management_page(

        'GSC Bulk Cleanup',

        'GSC Bulk Cleanup',

        'manage_options',

        'gsc-bulk-cleanup',

        'gsc_bulk_cleanup_render_page'

    );

});



// 2. Render Halaman Utama

function gsc_bulk_cleanup_render_page() {

    if (!current_user_can('manage_options')) {

        wp_die('Anda tidak punya izin untuk mengakses halaman ini.');

    }



    echo '<div class="wrap"><h1>GSC Advanced Bulk Cleanup</h1>';



    // FITUR CLEAR LOG: Hapus semua catatan redirect

    if (isset($_POST['gsc_clear_redirect_logs']) && check_admin_referer('gsc_clear_log_action', 'gsc_clear_log_nonce')) {

        delete_option('gsc_bulk_301_redirects');

        echo '<div class="notice notice-success"><p>Semua catatan log 301 redirect berhasil dihapus.</p></div>';

    }



    // STEP 2: Eksekusi tindakan

    if (isset($_POST['gsc_bulk_execute']) && check_admin_referer('gsc_bulk_execute_action', 'gsc_bulk_execute_nonce')) {

        gsc_bulk_cleanup_execute();

        gsc_bulk_cleanup_render_log_table();

        echo '</div>';

        return;

    }



    // STEP 1: Upload CSV & Tampilkan Preview

    if (isset($_POST['gsc_bulk_preview']) && check_admin_referer('gsc_bulk_preview_action', 'gsc_bulk_preview_nonce')) {

        gsc_bulk_cleanup_preview();

        echo '</div>';

        return;

    }



    // Default: Tampilkan Form Upload & Log Riwayat

    gsc_bulk_cleanup_upload_form();

    gsc_bulk_cleanup_render_log_table();

    echo '</div>';

}



// 3. Form Upload & Parameter Konfigurasi

function gsc_bulk_cleanup_upload_form() {

    ?>

    <p>Upload file CSV hasil ekspor dari Google Search Console (sheet <strong>Pages</strong> dalam format <code>.csv</code>).</p>

    <form method="post" enctype="multipart/form-data">

        <?php wp_nonce_field('gsc_bulk_preview_action', 'gsc_bulk_preview_nonce'); ?>

        <table class="form-table">

            <tr>

                <th><label for="gsc_csv_file">File CSV GSC (Pages)</label></th>

                <td><input type="file" name="gsc_csv_file" id="gsc_csv_file" accept=".csv" required></td>

            </tr>

            <tr>

                <th><label for="gsc_action_type">Tindakan Eksekusi (Bulk Action)</label></th>

                <td>

                    <select name="gsc_action_type" id="gsc_action_type">

                        <option value="draft" selected>Ubah Status Jadi Draft (Sangat Direkomendasikan)</option>

                        <option value="trash">Pindahkan ke Tong Sampah (Trash)</option>

                        <option value="delete">Hapus Permanen dari Database</option>

                    </select>

                </td>

            </tr>

            <tr>

                <th>Opsi 301 Redirect</th>

                <td>

                    <label>

                        <input type="checkbox" name="gsc_enable_redirect" value="1" checked>

                        <strong>Buat 301 Redirect ke Homepage Otomatis</strong>

                    </label>

                    <p class="description">URL yang diubah statusnya akan langsung di-redirect ke Homepage (<code><?php echo esc_url(home_url('/')); ?></code>) agar tidak menghasilkan error 404.</p>

                </td>

            </tr>

            <tr>

                <th>Mode Syarat Filter</th>

                <td>

                    <fieldset>

                        <label>

                            <input type="radio" name="gsc_filter_mode" value="both" checked>

                            <strong>Keduanya (Clicks DAN Impressions)</strong> — <em>Paling Aman</em>

                        </label><br>

                        <label>

                            <input type="radio" name="gsc_filter_mode" value="clicks_only">

                            <strong>Berdasarkan Clicks Sahaja</strong>

                        </label><br>

                        <label>

                            <input type="radio" name="gsc_filter_mode" value="impressions_only">

                            <strong>Berdasarkan Impressions Sahaja</strong>

                        </label>

                    </fieldset>

                </td>

            </tr>

            <tr>

                <th><label for="gsc_threshold">Maksimal Clicks</label></th>

                <td>

                    <input type="number" name="gsc_threshold" id="gsc_threshold" value="3" min="0" step="1">

                    <p class="description">Pos diproses jika Clicks kurang dari angka ini.</p>

                </td>

            </tr>

            <tr>

                <th><label for="gsc_max_impressions">Maksimal Impressions</label></th>

                <td>

                    <input type="number" name="gsc_max_impressions" id="gsc_max_impressions" value="100" min="0" step="1">

                    <p class="description">Pos diproses jika Impressions kurang dari angka ini.</p>

                </td>

            </tr>

            <tr>

                <th><label for="gsc_min_age">Usia Minimal Artikel</label></th>

                <td>

                    <input type="number" name="gsc_min_age" id="gsc_min_age" value="3" min="0" step="1"> Bulan

                </td>

            </tr>

            <tr>

                <th><label for="gsc_post_type">Post Type Target</label></th>

                <td>

                    <select name="gsc_post_type" id="gsc_post_type">

                        <option value="post" selected>Post (Artikel Blog)</option>

                        <option value="page">Page (Halaman Statis)</option>

                        <option value="any">Semua Post Type Publik</option>

                    </select>

                </td>

            </tr>

            <tr>

                <th>Proteksi Laman (Page)</th>

                <td>

                    <label>

                        <input type="checkbox" name="gsc_allow_pages" value="1">

                        Izinkan laman (page) ikut diproses jika cocok kriteria

                    </label>

                </td>

            </tr>

        </table>

        <?php submit_button('Proses & Tampilkan Preview', 'primary', 'gsc_bulk_preview'); ?>

    </form>

    <?php

}



// 4. Parser File CSV

function gsc_bulk_cleanup_parse_csv($tmp_path, $threshold, $max_impressions, $filter_mode) {

    if (($handle = fopen($tmp_path, 'r')) === false) {

        return ['error' => 'Gagal membuka file CSV.'];

    }



    $header = fgetcsv($handle);

    if (!$header) {

        fclose($handle);

        return ['error' => 'File CSV kosong atau tidak valid.'];

    }



    if (isset($header[0])) {

        $header[0] = preg_replace('/[\x{EF}\x{BB}\x{BF}\x{FEFF}]/u', '', $header[0]);

    }



    $header_norm = array_map(function ($h) {

        $clean = preg_replace('/[[:cntrl:]]/', '', $h);

        return strtolower(trim($clean));

    }, $header);



    $page_idx = null;

    $clicks_idx = null;

    $impressions_idx = null;



    foreach ($header_norm as $i => $h) {

        if (in_array($h, ['page', 'pages', 'landing page', 'top pages', 'url', 'urls', 'link'], true)) { 

            $page_idx = $i; 

        }

        if (in_array($h, ['clicks', 'click'], true)) { 

            $clicks_idx = $i; 

        }

        if (in_array($h, ['impressions', 'impression'], true)) { 

            $impressions_idx = $i; 

        }

    }



    if ($page_idx === null || $clicks_idx === null) {

        fclose($handle);

        return ['error' => 'Kolom "Page" dan/atau "Clicks" tidak ditemukan di header CSV. Header yang terbaca: ' . esc_html(implode(', ', $header))];

    }



    $rows = [];

    while (($row = fgetcsv($handle)) !== false) {

        if (!isset($row[$page_idx]) || !isset($row[$clicks_idx])) { continue; }

        

        $url = trim($row[$page_idx]);

        $clicks = (int) str_replace(',', '', $row[$clicks_idx]);

        $impressions = isset($row[$impressions_idx]) ? (int) str_replace(',', '', $row[$impressions_idx]) : 0;



        if ($url === '') { continue; }



        $clicks_condition = ($clicks < $threshold);

        $impressions_condition = ($impressions < $max_impressions);



        $is_match = false;

        if ($filter_mode === 'clicks_only') {

            $is_match = $clicks_condition;

        } elseif ($filter_mode === 'impressions_only') {

            $is_match = $impressions_condition;

        } else {

            $is_match = ($clicks_condition && $impressions_condition);

        }



        if ($is_match) {

            $rows[] = ['url' => $url, 'clicks' => $clicks, 'impressions' => $impressions];

        }

    }

    fclose($handle);



    return ['rows' => $rows];

}



// 5. Validasi URL ke Post ID

function gsc_bulk_cleanup_url_to_post_id($url, $post_type, $allow_pages, $min_age_months) {

    $site_host = wp_parse_url(home_url(), PHP_URL_HOST);

    $url_host = wp_parse_url($url, PHP_URL_HOST);



    if ($url_host && $site_host && strtolower($url_host) !== strtolower($site_host)) {

        return 0;

    }



    $post_id = url_to_postid($url);

    if (!$post_id) { return 0; }



    $actual_type = get_post_type($post_id);



    if ($actual_type === 'page' && !$allow_pages) {

        return 'skip_page';

    }



    if ($post_type !== 'any' && $actual_type !== $post_type) {

        return 'skip_type';

    }



    if ($min_age_months > 0) {

        $published = get_post_time('U', true, $post_id);

        if ($published) {

            $age_seconds = time() - $published;

            $min_seconds = $min_age_months * 30 * DAY_IN_SECONDS;

            if ($age_seconds < $min_seconds) {

                return 'skip_too_new';

            }

        }

    }



    return $post_id;

}



// 6. Tampilkan Preview (Ditambahkan Kolom Aksi Akses Konten)

function gsc_bulk_cleanup_preview() {

    if (empty($_FILES['gsc_csv_file']['tmp_name']) || !is_uploaded_file($_FILES['gsc_csv_file']['tmp_name'])) {

        echo '<div class="notice notice-error"><p>File CSV tidak ditemukan atau gagal di-upload.</p></div>';

        gsc_bulk_cleanup_upload_form();

        return;

    }



    $threshold = isset($_POST['gsc_threshold']) ? (int) $_POST['gsc_threshold'] : 3;

    $max_impressions = isset($_POST['gsc_max_impressions']) ? (int) $_POST['gsc_max_impressions'] : 100;

    $filter_mode = isset($_POST['gsc_filter_mode']) ? sanitize_text_field($_POST['gsc_filter_mode']) : 'both';

    $post_type = isset($_POST['gsc_post_type']) ? sanitize_text_field($_POST['gsc_post_type']) : 'post';

    $action_type = isset($_POST['gsc_action_type']) ? sanitize_text_field($_POST['gsc_action_type']) : 'draft';

    $enable_redirect = !empty($_POST['gsc_enable_redirect']);

    $min_age_months = isset($_POST['gsc_min_age']) ? (int) $_POST['gsc_min_age'] : 3;

    $allow_pages = !empty($_POST['gsc_allow_pages']);



    $parsed = gsc_bulk_cleanup_parse_csv($_FILES['gsc_csv_file']['tmp_name'], $threshold, $max_impressions, $filter_mode);



    if (isset($parsed['error'])) {

        echo '<div class="notice notice-error"><p>' . esc_html($parsed['error']) . '</p></div>';

        gsc_bulk_cleanup_upload_form();

        return;

    }



    $matched = [];

    $not_found = 0;

    $skipped_page = 0;

    $skipped_type = 0;

    $skipped_too_new = 0;



    foreach ($parsed['rows'] as $row) {

        $result = gsc_bulk_cleanup_url_to_post_id($row['url'], $post_type, $allow_pages, $min_age_months);



        if (is_int($result) && $result > 0) {

            $matched[$result] = [

                'url' => $row['url'],

                'clicks' => $row['clicks'],

                'impressions' => $row['impressions'],

                'title' => get_the_title($result),

                'date' => get_the_date('Y-m-d', $result),

            ];

        } elseif ($result === 'skip_page') {

            $skipped_page++;

        } elseif ($result === 'skip_type') {

            $skipped_type++;

        } elseif ($result === 'skip_too_new') {

            $skipped_too_new++;

        } else {

            $not_found++;

        }

    }



    echo '<div class="notice notice-info"><p><strong>Ringkasan Filter Proteksi:</strong><br>';

    echo 'Mode Filter: <strong>' . strtoupper(str_replace('_', ' ', $filter_mode)) . '</strong><br>';

    echo 'Opsi 301 Redirect ke Homepage: <strong>' . ($enable_redirect ? 'AKTIF' : 'NONAKTIF') . '</strong><br>';

    echo intval($not_found) . ' URL dihiraukan (karena tidak ditemukan di database WordPress).<br>';

    echo intval($skipped_page) . ' laman (page) dilewati.<br>';

    echo intval($skipped_type) . ' pos dilewati karena post type tidak sesuai.<br>';

    echo intval($skipped_too_new) . ' pos dilewati karena umurnya belum mencapai ' . intval($min_age_months) . ' bulan.</p></div>';



    if (empty($matched)) {

        echo '<div class="notice notice-warning"><p>Tidak ada pos yang memenuhi kriteria filter saat ini.</p></div>';

        gsc_bulk_cleanup_upload_form();

        return;

    }



    $token = wp_generate_password(12, false);

    set_transient('gsc_bulk_' . $token, [

        'matched' => $matched,

        'action_type' => $action_type,

        'enable_redirect' => $enable_redirect

    ], 15 * MINUTE_IN_SECONDS);



    echo '<p>Ditemukan <strong>' . count($matched) . ' pos valid</strong> yang akan diproses menjadi <strong>' . strtoupper($action_type) . '</strong>' . ($enable_redirect ? ' dan otomatis di-redirect 301 ke Homepage' : '') . ':</p>';



    echo '<table class="widefat striped"><thead><tr><th>ID</th><th>Judul Artikel</th><th>Tanggal Publish</th><th>URL</th><th>Clicks</th><th>Impressions</th><th>Aksi Konten</th></tr></thead><tbody>';

    foreach ($matched as $post_id => $data) {

        $edit_url = get_edit_post_link($post_id);

        $preview_url = get_preview_post_link($post_id);



        echo '<tr>';

        echo '<td>' . intval($post_id) . '</td>';

        echo '<td><strong>' . esc_html($data['title']) . '</strong></td>';

        echo '<td>' . esc_html($data['date']) . '</td>';

        echo '<td><a href="' . esc_url($data['url']) . '" target="_blank">' . esc_html($data['url']) . '</a></td>';

        echo '<td>' . intval($data['clicks']) . '</td>';

        echo '<td>' . intval($data['impressions']) . '</td>';

        echo '<td>';

        if ($edit_url) {

            echo '<a href="' . esc_url($edit_url) . '" class="button button-small" target="_blank" style="margin-right:4px;">✏️ Edit Pos</a>';

        }

        if ($preview_url) {

            echo '<a href="' . esc_url($preview_url) . '" class="button button-small" target="_blank">👁️ Pratinjau</a>';

        }

        echo '</td>';

        echo '</tr>';

    }

    echo '</tbody></table>';



    ?>

    <form method="post" style="margin-top:20px;">

        <?php wp_nonce_field('gsc_bulk_execute_action', 'gsc_bulk_execute_nonce'); ?>

        <input type="hidden" name="gsc_token" value="<?php echo esc_attr($token); ?>">

        <p>

            <?php submit_button('Eksekusi ' . strtoupper($action_type) . ' Sekarang (' . count($matched) . ' Pos)', 'delete', 'gsc_bulk_execute', false); ?>

            <a href="<?php echo esc_url(admin_url('tools.php?page=gsc-bulk-cleanup')); ?>" class="button button-secondary" style="margin-left:10px;">Batal</a>

        </p>

    </form>

    <?php

}



// 7. Simpan Aturan 301 Redirect (Simpan ID Pos untuk Link Langsung)

function gsc_bulk_add_redirect_rule($url, $post_id = 0) {

    $path = wp_parse_url($url, PHP_URL_PATH);

    if (!$path || $path === '/') { return; }



    $redirects = get_option('gsc_bulk_301_redirects', []);

    $redirects[rtrim($path, '/')] = [

        'target' => home_url('/'),

        'post_id' => $post_id

    ];

    update_option('gsc_bulk_301_redirects', $redirects);

}



// 8. Tampilkan Tabel Log Riwayat Redirect dengan Tombol Edit & Pratinjau

function gsc_bulk_cleanup_render_log_table() {

    $redirects = get_option('gsc_bulk_301_redirects', []);

    echo '<hr style="margin-top:40px; margin-bottom:20px;">';

    echo '<h2>Catatan / Log Riwayat 301 Redirect (' . count($redirects) . ' URL)</h2>';



    if (empty($redirects)) {

        echo '<p><em>Belum ada log URL yang diproses dan di-redirect.</em></p>';

        return;

    }



    echo '<p>Berikut adalah daftar path URL lama yang telah sukses di-redirect 301 ke Homepage secara otomatis:</p>';

    echo '<table class="widefat striped"><thead><tr><th>#</th><th>Path URL Asli (Di-redirect)</th><th>Tujuan Redirect</th><th>Status HTTP</th><th>Aksi Konten</th></tr></thead><tbody>';

    

    $i = 1;

    foreach ($redirects as $path => $info) {

        // Penanganan kompatibilitas versi lama (string vs array)

        $target = is_array($info) ? $info['target'] : $info;

        $post_id = is_array($info) && isset($info['post_id']) ? $info['post_id'] : url_to_postid(home_url($path));



        $edit_url = $post_id ? get_edit_post_link($post_id) : '';

        $preview_url = $post_id ? get_preview_post_link($post_id) : '';



        echo '<tr>';

        echo '<td>' . $i++ . '</td>';

        echo '<td><code>' . esc_html($path) . '</code></td>';

        echo '<td><code>' . esc_html($target) . '</code></td>';

        echo '<td><span class="button button-small" style="background:#d4edda; color:#155724; border-color:#c3e6cb;">301 Permanent</span></td>';

        echo '<td>';

        if ($edit_url) {

            echo '<a href="' . esc_url($edit_url) . '" class="button button-small" target="_blank" style="margin-right:4px;">✏️ Edit Pos</a>';

        }

        if ($preview_url) {

            echo '<a href="' . esc_url($preview_url) . '" class="button button-small" target="_blank">👁️ Pratinjau Konten</a>';

        }

        if (!$edit_url && !$preview_url) {

            echo '<span style="color:#888;">-</span>';

        }

        echo '</td>';

        echo '</tr>';

    }

    echo '</tbody></table>';



    ?>

    <form method="post" style="margin-top:15px;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua catatan log redirect ini?');">

        <?php wp_nonce_field('gsc_clear_log_action', 'gsc_clear_log_nonce'); ?>

        <input type="submit" name="gsc_clear_redirect_logs" class="button button-secondary" value="Bersihkan Semua Log Redirect">

    </form>

    <?php

}



// 9. Tangkap Pengunjung yang Mengakses URL Mati & Lempar 301 ke Homepage

add_action('template_redirect', function () {

    if (is_404() || is_single() === false || get_post_status() === 'draft') {

        $requested_path = rtrim(wp_parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        $redirects = get_option('gsc_bulk_301_redirects', []);



        if (isset($redirects[$requested_path])) {

            $info = $redirects[$requested_path];

            $target = is_array($info) ? $info['target'] : $info;

            wp_redirect($target, 301);

            exit;

        }

    }

});



// 10. Eksekutor Utama

function gsc_bulk_cleanup_execute() {

    $token = isset($_POST['gsc_token']) ? sanitize_text_field($_POST['gsc_token']) : '';

    $data_transient = get_transient('gsc_bulk_' . $token);



    if (!$data_transient || !is_array($data_transient['matched'])) {

        echo '<div class="notice notice-error"><p>Sesi preview telah kedaluwarsa. Silakan upload ulang file CSV.</p></div>';

        gsc_bulk_cleanup_upload_form();

        return;

    }



    $matched = $data_transient['matched'];

    $action_type = $data_transient['action_type'];

    $enable_redirect = $data_transient['enable_redirect'];



    $success_count = 0;

    $failed_count = 0;



    foreach ($matched as $post_id => $data) {

        $result = false;



        switch ($action_type) {

            case 'draft':

                $result = wp_update_post([

                    'ID' => $post_id,

                    'post_status' => 'draft'

                ]);

                break;



            case 'trash':

                $result = wp_trash_post($post_id);

                break;



            case 'delete':

                $result = wp_delete_post($post_id, true);

                break;

        }



        if ($result && !is_wp_error($result)) {

            $success_count++;

            if ($enable_redirect) {
                gsc_bulk_add_redirect_rule($data['url'], $post_id);
            }
        } else {
            $failed_count++;
        }
    }
    delete_transient('gsc_bulk_' . $token);
    echo '<div class="notice notice-success"><p><strong>Selesai!</strong> Sebanyak <strong>' . intval($success_count) . ' pos</strong> berhasil diproses menjadi <strong>' . strtoupper($action_type) . '</strong>' . ($enable_redirect ? ' dan telah di-redirect 301 ke Homepage.' : '.') . '</p></div>';
    echo '<p><a href="' . esc_url(admin_url('tools.php?page=gsc-bulk-cleanup')) . '" class="button button-primary">&larr; Kembali ke Form Cleanup</a></p>';
}