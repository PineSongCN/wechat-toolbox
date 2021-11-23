<?php

declare(strict_types=1);

use think\facade\Route;

Route::get('/think', 'index/hello');
Route::get('/index', 'index/index');
Route::get('/', 'index/index');
