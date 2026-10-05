<?php

namespace App\Controllers;

use App\Core\Response;

class BaseController
{
    protected Response $response;

    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    protected function view(string $view, array $data = []): void
    {
        $this->response->view($view, $data);
    }
}