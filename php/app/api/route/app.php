<?php

declare(strict_types=1);


use think\facade\Route;

Route::group('/', function () {
    Route::get('/', function () {
        return 'Hello,ThinkPHP6!';
    });
    Route::any('/passcode', 'User/passcode');
    Route::any('/token', 'User/login');
    Route::get('/token-check', 'User/check');
    Route::any('/test', 'Test/index');
})->allowCrossDomain();

Route::group('/message-board', function () {
    Route::get('/', 'MessageBoard/list');
    Route::post('/', 'MessageBoard/create');
})->allowCrossDomain();

Route::miss(function () {
    return json([
        'code' => 404,
        'data' => null,
        'message' => '请求路径错误'
    ])->code(404);
});
