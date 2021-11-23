<?php

declare(strict_types=1);

namespace app\api;

use think\App;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;
    protected $plat = 'all';
    protected $version = 0;
    protected $codeMap = [
        0 => '成功',
        -10 => '连接失败',
        -20 => '写入日志失败',
        -40 => '不是有效的JSON格式',
        -90 => '系统故障，请与管理员联系。',
        -1000 => '失败',
        -1001 => '认证失败',
        -1002 => '授权过期',
        -1003 => '缺少参数',
        -1004 => '会员不存在',
        -1005 => '积分不足，扣减失败',
        -1006 => 'openId下积分不足，扣减失败',
        -1007 => 'unionId 不存在',
        -1008 => 'openID不存在',
        -1009 => '请求过期，客户端-服务端时间差异过大',
        -1010 => '请求错误',
        -1011 => '参数错误',
    ];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
    }
    /**
     * 检测授权
     *
     * @return void
     */
    protected function access()
    {
        return false;
    }


    /**
     * 返回JSON数据
     * @access protected
     * @param  string|array $data     数据
     * @param  int          $code     code
     * @param  string       $msg      提示信息
     * @return array|string
     * @throws ValidateException
     */
    public function jsonR($data = null, $code = -1000, $msg = '')
    {
        $http = 200;
        // $method = request()->method(true);
        if (isset($this->codeMap[$code]) && empty($msg)) {
            $msg = $this->codeMap[$code];
        }
        $response = [
            'code' => $code,
            'data' => $data,
            'message' => $msg
        ];

        return json($response)->code($http);
    }


    /**
     * 成功
     * @access protected
     * @param  string|array $data     数据
     * @param  int          $code     code
     * @param  string       $msg      提示信息
     * @return array|string
     */
    public function jsonS($data = null, $msg = '成功')
    {
        return $this->jsonR($data, 0, $msg);
    }


    /**
     * 出错
     * @access protected
     * @param  string|array $data     数据
     * @param  int          $code     code
     * @param  string       $msg      提示信息
     * @return array|string
     * @throws ValidateException
     */
    public function jsonE($code = -1000, $msg = '')
    {
        return $this->jsonR(null, $code, $msg);
    }
}
