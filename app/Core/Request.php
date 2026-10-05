<?php

namespace App\Core;

use App\Core\UploadFile;

class Request
{
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function uri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // hapus base folder project
        $base = '/ketapangraya/public';

        if (str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }

        return $uri ?: '/';
    }

    public function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($_GET, $_POST);
    }
    public function files(string $key): array
    {
        if (!isset($_FILES[$key])) {
            return [];
        }

        $files = $_FILES[$key];
        $result = [];

        // handle multiple upload
        if (is_array($files['name'])) {
            foreach ($files['name'] as $i => $name) {
                $result[] = new UploadFile([
                    'name'     => $files['name'][$i],
                    'type'     => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error'    => $files['error'][$i],
                    'size'     => $files['size'][$i],
                ]);
            }
        } else {
            // single file fallback
            $result[] = new UploadFile($files);
        }

        return $result;
    }
    public function inputFormatted(string $key, string $format = null, $default = null)
    {
        $value = $this->input($key, $default);

        if ($value === null) return null;

        switch ($format) {
            case 'title':
                return ucwords(strtolower($value), " ., &-");
            case 'lower':
                return strtolower($value);
            case 'upper':
                return strtoupper($value);
            case 'trim': {
                    $data = strtolower($value);

                    // ganti semua selain huruf & angka jadi "-"
                    $value = preg_replace('/[^a-z0-9]+/', '-', $value);

                    // hapus "-" di awal & akhir
                    $value = trim($value, '-');
                    return $data;
                }
            default:
                return $value;
        }
    }
}
