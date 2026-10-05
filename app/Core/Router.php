<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, callable|array $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post(string $uri, callable|array $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    private function addRoute(string $method, string $uri, callable|array $action): void
    {
        $this->routes[$method][$uri] = $action;
    }

    public function dispatch(Request $request, Response $response): void
    {
        $httpMethod = $request->method();
        $uri = $request->uri();

        $routes = $this->routes[$httpMethod] ?? [];

        foreach ($routes as $route => $action) {

            // ubah /weddings/{slug} menjadi regex
            $pattern = preg_replace('#\{[^}]+\}#', '([^/]+)', $route);
            $pattern = "#^$pattern$#";

            if (preg_match($pattern, $uri, $matches)) {

                array_shift($matches); // hapus full match

                // Controller route
                if (is_array($action)) {

                    $controllerClass = $action[0];
                    $controllerMethod = $action[1];
                    $middleware = $action[2] ?? null;

                    // middleware
                    if ($middleware) {
                        $middlewareInstance = new $middleware();
                        $middlewareInstance->handle();
                    }

                    $controller = new $controllerClass();

                    if (method_exists($controller, 'setResponse')) {
                        $controller->setResponse($response);
                    }
                    $controller->$controllerMethod($request, $response, ...$matches);

                    return;
                }

                // Closure route
                if ($action instanceof \Closure) {

                    $action($request, $response, ...$matches);

                    return;
                }
            }
        }
        $response->setStatusCode(404);
        $role = '';
        $layout = 'main';
        if (!empty($_SESSION['admin'])) {
            $role = '.admin';
            $layout = 'admin';
        }
        $response->view('errors' . $role . '.under-development', [], $layout);
    }
}
