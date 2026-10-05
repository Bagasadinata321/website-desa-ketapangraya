<?php

namespace App\Core;

use App\Core\Logger;

class Response
{
    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    public function html(string $content): void
    {
        echo $content;
    }

    public function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function view(string $view, array $data = [], string $layout = 'main'): void
    {
        extract($data);

        $viewPath = __DIR__ . '/../../views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {

            Logger::error("View not found: {$viewPath}");

            $this->setStatusCode(404);

            // 🔥 deteksi admin atau bukan
            if (str_contains($view, 'admin.')) {
                $viewPath = __DIR__ . '/../../views/errors/admin/under-development.php';
            } else {
                $viewPath = __DIR__ . '/../../views/errors/public/under-development.php';
            }
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        if ($layout) {

            $layoutPath = __DIR__ . '/../../views/layouts/' . $layout . '.php';

            if (!file_exists($layoutPath)) {
                throw new \Exception("Layout not found: {$layoutPath}");
            }

            require $layoutPath;
            return;
        }

        echo $content;
    }
    public function redirect(string $url, array $flash = []): void
    {
        if (!empty($flash)) {

            // pastikan session flash selalu array
            if (!isset($_SESSION['_flash']) || !is_array($_SESSION['_flash'])) {
                $_SESSION['_flash'] = [];
            }

            // merge supaya tidak ketimpa kalau ada multi flash
            $_SESSION['_flash'] = array_merge($_SESSION['_flash'], $flash);
        }

        header('Location: ' . url($url));
        exit;
    }
}
