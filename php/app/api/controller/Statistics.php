<?php


namespace app\api\controller;

use think\exception\ValidateException;

use app\common\service\StatisticsService;

class Statistics extends Controller
{
    protected $service;
    public function __construct()
    {
        parent::__construct();
        $this->service = new StatisticsService();
    }
    public function create()
    {
        $data = request()->param();
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
