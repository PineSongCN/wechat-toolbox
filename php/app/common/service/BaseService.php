<?php

declare(strict_types=1);

namespace app\common\service;

use think\facade\Cache;
use app\common\utils\ArrayV2;
use app\common\utils\Tool;
use app\common\utils\Webapi;

class BaseService
{
    public $redis = null;
    public $array;
    public $tool;
    public $webapi;

    public function __construct()
    {
        // $this->redis = Cache::store('redis');
        $this->array = new ArrayV2();
        $this->tool = new Tool();
        $this->webapi = new Webapi();
    }
}
