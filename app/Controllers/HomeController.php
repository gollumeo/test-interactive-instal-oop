<?php

namespace app\Controllers;

use app\Views\View;

class HomeController extends Controller
{

    public function index(): View
    {
        return $this->view('homeIndex');
    }

    public function test(): View
    {
        return $this->view('test/homeTest', data: [], title: 'Test');
    }
}