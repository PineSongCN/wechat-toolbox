<?php

declare(strict_types=1);

namespace app\common\model;

use app\common\model\BaseModel;

class Event extends BaseModel
{
    protected $table = 'event';
    protected $autoWriteTimestamp = true;
    protected $pk = 'id';
}
