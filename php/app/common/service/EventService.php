<?php

declare(strict_types=1);

namespace app\common\service;

use think\facade\Db;

use app\common\service\BaseService;
use app\common\model\Event;

class EventService extends BaseService
{
    /**
     * @param Event $model
     */
    public $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new Event();
    }

    /**
     * 列表
     */
    public function list($data)
    {
        $pageNo = isset($data['pageNo']) ? (int) $data['pageNo'] : 1;
        $pageSize = isset($data['pageSize']) ? (int) $data['pageSize'] : 10;
        
        $order = [
            'mb.create_time' => 'desc'
        ];
        $where = [];

        $total = $this->model
            ->where($where)
            ->count();
        $model = $this->model
            ->alias('mb')
            ->where($where)
            ->order($order)
            ->limit($pageSize)
            ->page($pageNo)
            ->field(['mb.*'])
            ->select()
            ->toArray();

        return [
            'list'       => $model,
            'pageSize'   => $pageSize,
            'pageNo'     => $pageNo,
            'totalPage'  => ceil($total / $pageSize),
            'totalCount' => $total,
        ];
    }

    /**
     * 创建
     *
     */
    public function create(array $data)
    {
        try {
            $model = Event::create($data);
            $model = $model === null ? false : $model->toArray();
            return $model;
        } catch (\Exception $e) {
            $code = $e->getCode() ? $e->getCode() : -1000;
            throw new \think\Exception($e->getMessage(), $code);
        }
    }
}
