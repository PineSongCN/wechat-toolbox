<?php


namespace app\api\controller;

use Exception;
use Firebase\JWT\JWT;
use think\App;
use think\exception\ValidateException;
use think\facade\Request;

class Controller
{

    protected $user = ['user_id' => 1];
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
        -1001 => '请进行登录', // 认证失败
        -1002 => '请进行登录', //授权过期
        -1003 => '缺少参数',
        -1004 => '会员不存在',
        -1007 => 'unionId 不存在',
        -1008 => 'openID不存在',
        -1009 => '请求过期，客户端-服务端时间差异过大',
        -1010 => '请求错误',
        -1011 => '参数错误',
        -1012 => '手机号已存在',
    ];

    public function __construct()
    {
        // $this->checkToken();
    }

    public function checkToken()
    {
        $whiteList = ['/api/token', '/api/passcode', '/api/test'];
        $baseUrl = Request::baseUrl();
        if (!in_array($baseUrl, $whiteList)) {
            $accessToken = request()->header('Authorization');
            $response = $this->verifyAccessToken($accessToken);
            if ($response !== true) {
                // header($response['header']);
                header('Content-Type: application/json; charset=utf8');
                if ($baseUrl === '/api/user/token-check') {
                    echo json_encode([
                        'code' => 0,
                        'data' => false,
                        'message' => $this->codeMap[$response['code']]
                    ], JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode([
                        'code' => $response['code'],
                        'data' => null,
                        'message' => $this->codeMap[$response['code']]
                    ], JSON_UNESCAPED_UNICODE);
                }
                exit(200);
            }
        }
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

    protected function verifyAccessToken($jwt = '')
    {
        // $key = md5('wine20200407'); //jwt的签发密钥，验证token的时候需要用到
        // JWT::$leeway = 3;
        try {
            if ($jwt) {
                $key = env('JWT.KEY');
                $jwtAuth = json_encode(JWT::decode($jwt, $key, array('HS256')));
                $authInfo = json_decode($jwtAuth, true);

                if (!empty($authInfo['user_id'])) {
                    $this->user['user_id'] = $authInfo['user_id'];
                    return true;
                } else {
                    return [
                        'header' => 'HTTP/1.1 401 Unauthorized',
                        'http' => 200,
                        'data' => '用户验证不通过，用户不存在。',
                        'code' => -1001
                    ];
                }
            } else {
                return [
                    'header' => 'HTTP/1.1 401 Unauthorized',
                    'http' => 401,
                    'data' => '用户验证不通过，Token无效。',
                    'code' => -1001
                ];
            }
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return [
                'header' => 'HTTP/1.1 401 Unauthorized',
                'http' => 401,
                'data' => '用户验证不通过，Token无效。',
                'code' => -1001
            ];
        } catch (\Firebase\JWT\ExpiredException $e) {
            return [
                'header' => 'HTTP/1.1 401 Unauthorized',
                'http' => 401,
                'data' => '用户验证不通过，Token过期。',
                'code' => -1002
            ];
        } catch (Exception $e) {
            return [
                'header' => 'HTTP/1.1 401 Unauthorized',
                'http' => 401,
                'data' => '用户验证不通过，Token无效。',
                'code' => -1001
            ];
        }
    }

    public function nonempty($var)
    {
        return isset($var) && $var !== null && $var !== false;
    }
}
