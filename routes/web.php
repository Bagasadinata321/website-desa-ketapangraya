<?php

use App\Controllers\AboutController;
use App\Core\Router;

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\DestinasiProdukController;
use App\Controllers\HomeController;
use App\Controllers\SitemapController;
use App\Middleware\AuthMiddleware;


$router->get('/sitemap.xml', [SitemapController::class, 'index']);

$router->get('/robots.txt', function ($response) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /login\nDisallow: /logout\nDisallow: /register\nSitemap: " . url('/sitemap.xml');
    exit;
});

$router->get('/', [HomeController::class, 'index']);

$router->get('/produk', [DestinasiProdukController::class, 'index']);

$router->get('/about', [AboutController::class, 'index']);
$router->get('/destinasi/{slug}', [HomeController::class, 'showDestinasiDetail']);
// -> render detail.php dengan pageType = 'destinasi'
//    {slug} nanti dipakai controller utk cari row di tabel `destinations`
//    (belum dipakai sekarang — masih placeholder sesuai permintaan kamu)

$router->get('/produk/{slug}', [HomeController::class, 'showProdukDetail']);
// -> render detail.php dengan pageType = 'produk'
//    {slug} nanti dipakai controller utk cari row di tabel `products`


$router->get('/login', [AuthController::class, 'showLogin']);

$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/register', [AuthController::class, 'showRegister']);

$router->post('/login-process', [AuthController::class, 'loginProcess']);
$router->post('/register-submit', [AuthController::class, 'registerProcess']);

$router->get('/admin/dashboard', [
    AdminController::class,
    'index',
    AuthMiddleware::class
]);


$router->get('/admin/settings/edit', [
    AdminController::class,
    'showSettings',
    AuthMiddleware::class
]);

$router->post('/admin/settings/save', [
    AdminController::class,
    'saveSettings',
    AuthMiddleware::class
]);

$router->get('/admin/section/homepage', [
    AdminController::class,
    'showHomepage',
    AuthMiddleware::class
]);

$router->get('/admin/content/destinasi-list', [
    AdminController::class,
    'showDestinasi',
    AuthMiddleware::class
]);

$router->get('/admin/kkn/list', [
    AdminController::class,
    'kknList',
    AuthMiddleware::class
]);
$router->post('/admin/administrator/approve/{id}', [
    AdminController::class,
    'approveAdmin',
    AuthMiddleware::class
]);
$router->post('/admin/{halaman}/section-save', [
    AdminController::class,
    'sectionSave',
    AuthMiddleware::class
]);

$router->get('/admin/{halaman}/list', [
    AdminController::class,
    'resourceList',
    AuthMiddleware::class
]);

$router->get('/admin/{halaman}/{sectionKey}/edit', [
    AdminController::class,
    'sectionEdit',
    AuthMiddleware::class
]);

$router->get('/admin/{halaman}/edit/{id}', [
    AdminController::class,
    'resourceEdit',
    AuthMiddleware::class
]);

$router->post('/admin/{halaman}/save', [
    AdminController::class,
    'resourceSave',
    AuthMiddleware::class
]);

$router->get('/admin/{halaman}/delete/{id}', [
    AdminController::class,
    'resourceDelete',
    AuthMiddleware::class
]);


$router->get('/admin/{halaman}/create', [
    AdminController::class,
    'resourceCreate',
    AuthMiddleware::class
]);
// Endpoint AJAX Toggle Status/Featured
$router->post('/admin/{halaman}/toggle/{id}', [
    AdminController::class,
    'toggleStatus',
    AuthMiddleware::class
]);
$router->post('/admin/{halaman}/reorder/{id}', [
    AdminController::class,
    'reorderItem',
    AuthMiddleware::class
]);
