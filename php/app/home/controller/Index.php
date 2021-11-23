<?php

declare(strict_types=1);

namespace app\home\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return 'Home';
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
