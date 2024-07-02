<?php

namespace app\Controllers;

use app\Views\View;

abstract class Controller
{
    protected function view(string $view, array $data = []): View
    {
        $viewInstance = new View();
        $viewInstance->render($view, $data);
        return $viewInstance;
    }
}