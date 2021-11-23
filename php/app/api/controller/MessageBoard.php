<?php


namespace app\api\controller;

use think\exception\ValidateException;

use app\common\service\MessageBoardService;

class MessageBoard extends Controller
{
    protected $service;
    public function __construct()
    {
        parent::__construct();
        $this->service = new MessageBoardService();
    }
    public function create()
    {
        $data = request()->param();
        // $data['user_id'] = $this->user['user_id'];
        // $data[''] = ;
        $response = $this->service->create($data);
        if ($response) {
            return $this->jsonS($response);
        } else {
            return $this->jsonE(-1001, '信息获取失败');
        }
    }

    public function list()
    {
        try {
            $updateData = request()->param();
            // $updateData['user_id'] = $this->user['user_id'];

            $response = $this->service->list($updateData);
            if ($response) {
                return $this->jsonS($response);
            } else {
                return $this->jsonE(-1000, '失败');
            }
        } catch (ValidateException $e) {
            return $this->jsonE(-1000, $e->getError());
        } catch (\Exception $e) {
            return $this->jsonE($e->getCode() ? $e->getCode() : -1000, $e->getMessage());
        }
    }
}
