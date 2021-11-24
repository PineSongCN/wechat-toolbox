<?php

declare(strict_types=1);

namespace app\common\service;

use think\facade\Db;

use app\common\service\BaseService;
use app\common\model\MessageBoard;

class MessageBoardService extends BaseService
{
    /**
     * @param Message $model
     */
    public $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new MessageBoard();
    }

    /**
     * 列表
     */
    public function list($data)
    {
        $pageNo = isset($data['pageNo']) ? (int) $data['pageNo'] : 1;
        $pageSize = isset($data['pageSize']) ? (int) $data['pageSize'] : 10;
        $to = $data['to'] ?? false;
        if(!$to) {
            $message = '请填写名称或暗语';
            $code = -1000;
            throw new \think\Exception($message, $code);
        }

        $order = [
            'mb.create_time' => 'desc'
        ];
        $where = [
            ['mb.to', '=', trim($data['to'])]
        ];

        $total = $this->model
            ->alias('mb')
            ->where($where)
            ->count();
        $model = $this->model
            ->alias('mb')
            ->where($where)
            ->order($order)
            ->limit($pageSize)
            ->page($pageNo)
            ->field(['mb.message_board_id','mb.from','mb.to','mb.content','mb.create_time'])
            ->select()
            ->toArray();
            foreach ($model as &$v) {
                $v['avatar'] = 'https://assets.yqt.life/web/wechat-toolbox/images/avatar/01.jpg';
            }
            unset($v);
        return [
            'list'       => $model,
            'pageSize'   => $pageSize,
            'pageNo'     => $pageNo,
            'totalPage'  => ceil($total / $pageSize),
            'totalCount' => $total,
        ];
    }


    /**
     * 详情
     *
     */
    public function find($data)
    {
        $id = $data['message_board_id'];
        $model = $this->model->find($id);
        $model = $model === null ? false : $model->toArray();
        if (!$model) {
            $message = '查询失败';
            $code = -1000;
            throw new \think\Exception($message, $code);
        }

        return $model;
    }

    /**
     * 创建
     *
     */
    public function create(array $data)
    {
        Db::startTrans();

        try {

            $model = MessageBoard::create($data);
            $model = $model === null ? false : $model->toArray();
            Db::commit();
            return $model;
        } catch (\Exception $e) {
            Db::rollback();
            $code = $e->getCode() ? $e->getCode() : -1000;
            throw new \think\Exception($e->getMessage(), $code);
        }
    }

    /**
     * 删除
     *
     * @return bool
     */
    public function del($data)
    {
        $id = $data['message_board_id'];
        // Db::table('message_board')->where([['message_board_id', '=', $id]])->delete();

        return true;
    }

}
