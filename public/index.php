<?php

declare(strict_types=1);

date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../storage/logs/' . date('Y-m-d') . '.log');

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

/* load global helpers */
require_once __DIR__ . '/../app/Core/Helpers.php';

use App\Core\Router;
use App\Core\Request;
use App\Core\Response;

$router = new Router();

/* load routes */
require_once __DIR__ . '/../routes/web.php';

$request = new Request();
$response = new Response();

$router->dispatch($request, $response);
