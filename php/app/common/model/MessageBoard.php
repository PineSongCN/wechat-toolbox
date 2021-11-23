<?php

declare(strict_types=1);

namespace app\common\model;

use app\common\model\BaseModel;

class MessageBoard extends BaseModel
{
    protected $table = 'message_board';
    protected $autoWriteTimestamp = true;
    protected $pk = 'message_board_id';
}
