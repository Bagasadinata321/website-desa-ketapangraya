<?php


use App\Core\Logger;

function noCache(string $path): string
{
    $fullPath = __DIR__ . '/../../public/' . ltrim($path, '/');
    $version  = file_exists($fullPath) ? filemtime($fullPath) : 0;
    return url('/' . ltrim($path, '/') . '?v=' . $version);
}
function formatDate(string $date): string
{
    $formatter = new IntlDateFormatter(
        'id_ID',
        IntlDateFormatter::LONG,
        IntlDateFormatter::NONE
    );

    return $formatter->format(new DateTime($date));
}
function url(string $path = ''): string
{
    if ($_SERVER['HTTP_HOST'] === 'localhost') {
        $base = '/ketapangraya/public';
    } else {
        $base = '';
    }

    return $base . '/' . ltrim($path, '/');
}
if (!function_exists('setting')) {
    function setting(string $key, string $default = ''): string
    {
        static $settings = null;

        // Load sekali saja dalam 1 request (Singleton Pattern)
        if ($settings === null) {
            $resourceModel = new \App\Models\Resource();
            // Asumsi method ini mengambil semua baris tabel settings menjadi array ['key' => 'value']
            $settings = $resourceModel->getAllSettings();
        }

        return $settings[$key] ?? $default;
    }
}
function partial($name)
{
    require __DIR__ . '/../../views/layouts/partials/' . $name . '.php';
}
if (!function_exists('getUploadPath')) {
    /**
     * Mengembalikan path sistem file (folder fisik) & membuat folder jika belum ada.
     */
    function getUploadPath(string $subfolder = ''): string
    {
        // Sesuaikan dirname() dengan kedalaman folder helpers.php Anda terhadap root proyek
        // Jika helpers.php berada di /app/helpers.php, dirname(__DIR__) menunjuk ke folder root proyek.
        $baseDir = dirname(__DIR__, 2) . '/public/uploads/';

        if (!empty($subfolder)) {
            $baseDir .= trim($subfolder, '/') . '/';
        }

        // Buat direktori otomatis jika belum dibuat
        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0755, true);
        }

        return $baseDir;
    }
}
function get_flash($key = null)
{
    if (!isset($_SESSION['_flash'])) {
        return null;
    }

    $flash = $_SESSION['_flash'];

    // hapus setelah dipakai (sekali tampil)
    unset($_SESSION['_flash']);

    if ($key) {
        return $flash[$key] ?? null;
    }

    return $flash;
}
if (!function_exists('set_flash')) {
    function set_flash(string $type, string $message, ?string $token = null): void
    {
        $flash = [$type => $message];

        if ($token !== null) {
            $flash['token'] = $token;
        }

        $_SESSION['_flash'] = $flash;
    }
}

function handle_result(
    $response,
    $result,
    string $redirect,
    string $message,
    array $extra = [] // 👈 tambahan untuk token dll
) {
    try {

        // ❌ jika gagal
        if (!$result) {

            Logger::error($message . ' gagal');

            return $response->redirect($redirect, array_merge([
                'error' => ucfirst($message)
            ], $extra));
        }

        // ✅ jika berhasil
        return $response->redirect($redirect, array_merge([
            'success' => ucfirst($message)
        ], $extra));
    } catch (\Throwable $e) {

        Logger::error($message . ' exception: ' . $e->getMessage());

        return $response->redirect($redirect, [
            'error' => ucfirst($message) . ' Gagal : '
        ]);
    }
}
function generateToken($length = 6)
{
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // tanpa karakter ambigu
    $max = strlen($chars) - 1;

    $token = '';
    for ($i = 0; $i < $length; $i++) {
        $token .= $chars[random_int(0, $max)];
    }

    return $token;
}
