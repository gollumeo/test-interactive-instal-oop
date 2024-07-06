<?php

use app\Controllers\HomeController;
use app\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/test', [HomeController::class, 'test']);