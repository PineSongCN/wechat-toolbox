<?php

declare(strict_types=1);
use think\facade\Route;

Route::miss(function () {
    return json([
        'code' => 404,
        'data' => null,
        'message' => '请求路径错误'
    ])->code(404);
});