<?php

namespace App\Core;

/**
 * ImageUploader
 *
 * Validasi file upload gambar dan KONVERSI WAJIB ke WebP — tidak ada opsi
 * untuk menyimpan format asli (JPG/PNG/GIF). Kalau konversi gagal (mis. GD
 * di server tidak dibangun dengan dukungan WebP), upload ditolak dengan
 * exception, bukan diam-diam fallback menyimpan file asli.
 *
 * Tidak menyentuh database sama sekali — cuma urus file fisik + kembalikan
 * metadata-nya. Yang insert ke tabel `media` adalah Resource::createMedia().
 */
class Imageuploader
{
    private const ALLOWED_MIME = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private const MAX_SIZE_BYTES = 5 * 1024 * 1024; // 5MB

    private string $uploadDir;
    private string $urlPrefix;
    private int $quality;

    /**
     * @param string $uploadDir  path absolut folder tujuan di server,
     *                           mis. dirname(__DIR__, 2) . '/public/uploads'
     * @param string $urlPrefix  path publik yang dipakai di <img src="...">,
     *                           mis. '/uploads'
     * @param int    $quality    kualitas WebP 0-100 (default 82 — titik
     *                           seimbang antara ukuran file & kualitas visual)
     */
    public function __construct(string $uploadDir, string $urlPrefix = '/uploads', int $quality = 82)
    {
        $this->uploadDir = rtrim($uploadDir, '/');
        $this->urlPrefix = '/' . trim($urlPrefix, '/');
        $this->quality   = max(0, min(100, $quality));

        if (!is_dir($this->uploadDir) && !mkdir($this->uploadDir, 0755, true) && !is_dir($this->uploadDir)) {
            throw new \RuntimeException("Gagal membuat folder upload: {$this->uploadDir}");
        }
    }

    /**
     * Proses satu file dari $_FILES (satu elemen, bukan array multi-file).
     * Selalu mengembalikan file .webp di disk kalau berhasil.
     *
     * @param array $file salah satu elemen $_FILES, mis. $_FILES['cover_image']
     * @return array{filename:string,original_filename:string,path:string,mime_type:string,size_bytes:int,width:?int,height:?int}|null
     *         null kalau memang tidak ada file yang diupload (input kosong) —
     *         beda dari upload yang GAGAL, yang melempar exception.
     *
     * @throws \RuntimeException kalau file ada tapi tidak valid/gagal dikonversi
     */
    public function upload(array $file): ?array
    {
        // Input file kosong (user tidak pilih apa-apa) — bukan error, cuma "tidak ada".
        if (empty($file['name']) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload gagal (kode error: ' . $file['error'] . ').');
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            throw new \RuntimeException('File tidak valid.');
        }

        if ($file['size'] > self::MAX_SIZE_BYTES) {
            throw new \RuntimeException('Ukuran file melebihi batas maksimum 5MB.');
        }

        // Cek MIME type dari ISI file (bukan dari nama/ekstensi atau header
        // Content-Type yang dikirim browser — keduanya bisa dipalsukan).
        $finfo    = new \finfo(FILEINFO_MIME_TYPE);
        $realMime = $finfo->file($file['tmp_name']);

        if (!in_array($realMime, self::ALLOWED_MIME, true)) {
            throw new \RuntimeException('Tipe file tidak didukung. Hanya JPG, PNG, GIF, atau WebP.');
        }

        if (!function_exists('imagewebp')) {
            throw new \RuntimeException(
                'Server tidak mendukung konversi WebP — ekstensi GD PHP belum aktif ' .
                    'atau di-build tanpa dukungan WebP. Cek php.ini (extension=gd) dan ' .
                    'jalankan phpinfo() untuk pastikan baris "WebP Support" bernilai enabled.'
            );
        }

        $filename = uniqid('img_', true) . '.webp';
        $destPath = $this->uploadDir . '/' . $filename;

        $this->convertToWebp($file['tmp_name'], $realMime, $destPath);

        $dimensions = @getimagesize($destPath);

        return [
            'filename'          => $filename,
            'original_filename' => $file['name'],
            'path'              => $this->urlPrefix . '/' . $filename,
            'mime_type'         => 'image/webp',
            'size_bytes'        => filesize($destPath) ?: 0,
            'width'             => $dimensions[0] ?? null,
            'height'            => $dimensions[1] ?? null,
        ];
    }

    /**
     * Proses banyak file sekaligus, mis. dari input `name="gallery_images[]"`
     * ($_FILES['gallery_images'] datang dalam bentuk array-of-arrays dari PHP,
     * method ini yang urus normalisasinya).
     *
     * @return array<int, array> daftar metadata file yang berhasil dikonversi
     *         (file yang gagal/kosong otomatis dilewati, tidak menghentikan yang lain)
     */
    public function uploadMultiple(array $filesField): array
    {
        if (empty($filesField['name']) || !is_array($filesField['name'])) {
            return [];
        }

        $results = [];
        $count   = count($filesField['name']);

        for ($i = 0; $i < $count; $i++) {
            if (($filesField['error'][$i] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                continue; // slot kosong, lewati
            }

            $singleFile = [
                'name'     => $filesField['name'][$i],
                'type'     => $filesField['type'][$i],
                'tmp_name' => $filesField['tmp_name'][$i],
                'error'    => $filesField['error'][$i],
                'size'     => $filesField['size'][$i],
            ];

            $meta = $this->upload($singleFile);
            if ($meta !== null) {
                $results[] = $meta;
            }
        }

        return $results;
    }

    /** Sisi terpanjang gambar dibatasi ke ukuran ini (px) sebelum di-encode
     *  ke WebP — cukup untuk tampilan web, tapi jauh lebih hemat ukuran file
     *  dibanding menyimpan foto HP mentah (12MP+) apa adanya. */
    private const MAX_DIMENSION = 2000;

    private function convertToWebp(string $srcPath, string $srcMime, string $destPath): void
    {
        switch ($srcMime) {
            case 'image/webp':
                // Sudah WebP — copy langsung, tidak perlu decode+encode ulang
                // (menghindari kompresi berlapis yang menurunkan kualitas).
                // Catatan: berarti file WebP yang diupload TIDAK ikut kena
                // pembatasan MAX_DIMENSION di bawah. Kalau itu masalah buat
                // kamu (mis. orang upload WebP beresolusi sangat besar),
                // kasih tahu saya — tinggal hapus shortcut ini supaya semua
                // format, termasuk WebP, selalu didekode+resize+encode ulang.
                if (!copy($srcPath, $destPath)) {
                    throw new \RuntimeException('Gagal menyalin file WebP.');
                }
                return;

            case 'image/jpeg':
                $image = @imagecreatefromjpeg($srcPath);
                if ($image !== false) {
                    $image = $this->fixJpegOrientation($image, $srcPath);
                }
                break;

            case 'image/png':
                $image = @imagecreatefrompng($srcPath);
                if ($image !== false) {
                    // Pertahankan transparansi PNG saat dikonversi.
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                break;

            case 'image/gif':
                $image = @imagecreatefromgif($srcPath);
                break;

            default:
                throw new \RuntimeException("Tipe gambar tidak didukung untuk konversi: {$srcMime}");
        }

        if ($image === false) {
            throw new \RuntimeException('Gagal membaca file gambar — file mungkin rusak atau bukan gambar valid.');
        }

        $image = $this->resizeIfTooLarge($image);

        $success = imagewebp($image, $destPath, $this->quality);
        imagedestroy($image);

        if (!$success) {
            throw new \RuntimeException('Gagal mengkonversi gambar ke WebP.');
        }
    }

    /**
     * Baca flag rotasi EXIF (dari kamera HP) dan putar gambarnya betulan,
     * supaya hasil WebP tidak kesamping/terbalik. Cuma relevan untuk JPEG —
     * PNG/GIF/WebP tidak punya flag orientasi EXIF seperti ini.
     */
    private function fixJpegOrientation($image, string $srcPath)
    {
        if (!function_exists('exif_read_data')) {
            // Ekstensi exif tidak aktif di server — lewati koreksi (bukan
            // fatal, tapi cek php.ini: extension=exif kalau ini terjadi).
            return $image;
        }

        $exif = @exif_read_data($srcPath);
        if (!$exif || empty($exif['Orientation'])) {
            return $image;
        }

        switch ((int) $exif['Orientation']) {
            case 3:
                $rotated = imagerotate($image, 180, 0);
                break;
            case 6:
                $rotated = imagerotate($image, -90, 0);
                break;
            case 8:
                $rotated = imagerotate($image, 90, 0);
                break;
            default:
                return $image;
        }

        if ($rotated !== false) {
            imagedestroy($image);
            return $rotated;
        }

        return $image;
    }

    /**
     * Resize proporsional kalau sisi terpanjang gambar melebihi
     * self::MAX_DIMENSION. Gambar yang sudah kecil dibiarkan apa adanya
     * (tidak pernah diperbesar).
     */
    private function resizeIfTooLarge($image)
    {
        $width  = imagesx($image);
        $height = imagesy($image);
        $longest = max($width, $height);

        if ($longest <= self::MAX_DIMENSION) {
            return $image;
        }

        $ratio     = self::MAX_DIMENSION / $longest;
        $newWidth  = (int) round($width * $ratio);
        $newHeight = (int) round($height * $ratio);

        $resized = imagecreatetruecolor($newWidth, $newHeight);

        // Pertahankan transparansi (relevan untuk PNG) saat resize.
        imagealphablending($resized, false);
        imagesavealpha($resized, true);

        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);

        return $resized;
    }
}
