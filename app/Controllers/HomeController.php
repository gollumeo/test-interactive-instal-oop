<?php

namespace app\Controllers;

use app\Views\View;

class HomeController extends Controller
{
    public View $view;

    public function index()
    {
        return $this->view('homeIndex');
    }
}