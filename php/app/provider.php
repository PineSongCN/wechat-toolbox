<?php

declare(strict_types=1);


use app\Request;
use app\ExceptionHandle;

// 容器Provider定义文件
return [
    'think\Request'          => Request::class,
    'think\exception\Handle' => ExceptionHandle::class,
];
