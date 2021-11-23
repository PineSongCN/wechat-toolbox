<?php

declare(strict_types=1);

namespace app;

use think\App;
use think\exception\ValidateException;
use think\Validate;

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

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 用户ID
     * @var string
     */
    protected $userId = '';

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
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }


    /**
     * 验证数据
     * @access protected
     * @param  string|array $data     数据
     * @param  int          $http     提示信息
     * @return array|string
     * @throws ValidateException
     */
    public function resJson($data = null, $http = null)
    {
        // $data['method'] = Request::method(true);
        // 401 未登录
        // 403 无权访问
        // 404 找不到
        // 405 找不到
        // 401 未登录
        if (!$http) {
            $method = request()->method(true);
            $data = $data ?? [];
            if(!is_string($data)) {
                switch ($method) {
                    case 'GET':
                    case 'PUT':
                    case 'PATCH':
                        $http = 200;
                        break;
    
                    case 'POST':
                        $http = 201;
                        // 接受未处理，处于队列中
                        // $http = 202;
                        break;
    
                    case 'DELETE':
                        $http = 204;
                        break;
    
                    default:
                        $http = 200;
                        break;
                }

            } else {
                $data = [
                    'message' => $data
                ];
                $http = 400;
            }
        } else if ($http === 422) {
            $data = [
                'message' => '参数校验错误',
                'fields' => $data
            ];
        } else {
            $data = [
                'message' => $data
            ];
        }
        return json($data)->code($http);
    }
}
