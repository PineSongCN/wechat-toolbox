<?php

declare(strict_types=1);

namespace app\common\model;

use app\common\model\BaseModel;

class Statistics extends BaseModel
{
    protected $table = 'statistics';
    protected $autoWriteTimestamp = true;
    protected $pk = 'id';
}
