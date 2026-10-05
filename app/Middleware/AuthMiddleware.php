<?php

namespace App\Middleware;

class AuthMiddleware
{
    public function handle()
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (str_starts_with($uri, '/admin')) {
            if (empty($_SESSION['admin'])) {
                header("Location: " . url('/token'));
                exit;
            }
        }

        if (str_starts_with($uri, '/client')) {
            if (empty($_SESSION['client'])) {
                header("Location: " . url('/login'));
                exit;
            }
        }
    }
}
