<?php

declare(strict_types=1);

namespace app\common\service;

use think\facade\Db;

use app\common\service\BaseService;
use Firebase\JWT\JWT;
use app\common\model\User;
use app\common\model\UserWechat;


class AdminService extends BaseService
{
    /**
     * @param User $model
     */
    public $model;
    public $redis_version = '0904';
    public $salt = '@987654321!';

    public function __construct()
    {
        parent::__construct();
        $this->model = new User();
    }
    public function resetPassword($data)
    {
        $userid = $data['userid'];
        $password = md5($this->salt . '123456');
        $updateData = [
            ['userid', '=', $userid],
            ['password', '=', $password],
        ];
        $model = $this->update($updateData);

        return $model;
    }
    public function makeToken($user)
    {
        $userid = $user['userid'];
        $key = env('JWT.KEY');
        $time = time();
        $expireTime = $time + (86400 * 30);
        $accessToken = [
            "userid" => $userid,
            "iat" => $time,
            "nbf" => $time,
            "exp" => $expireTime
        ];
        return ['access_token' => JWT::encode($accessToken, $key), 'expire_time' => $expireTime * 1000];
    }
    public function login($data)
    {
        $username = $data['username'];
        $password = md5($this->salt . $data['password']);
        $where = [
            ['email', '=', $username],
            ['password', '=', $password],
            ['status', '=', 1],
        ];
        $model = $this->model->where($where)->field(['*', 'userid' => 'userid', 'realname' => 'name'])->find();
        if ($model && ($model['roleid'] === 1)) {
            $model = $model->toArray();
            $model['userid'] = $model['userid'];
            $model['access_token'] = $this->makeToken($model)['access_token'];
        }

        return $model;
    }

    /**
     * 创建
     *
     */
    public function create(array $data)
    {
        $data['createtime'] = Date('Y-m-d H:i:s', time());
        $model = User::create($data);
        $model = $model === null ? false : $model->toArray();
        $userid = $model['userid'];
        $time = time();
        $year = date('Y', $time);
        $target = $data['target'] ?? 0;
        $where = [['user_id', '=', $userid], ['year', '=', $year]];
        $updateData = [
            'type' => 'user',
            'label' => 'target',
            'value' => $target,
            'update_time' => $time,
            'update_time' => $time,
        ];
        $updateData['year'] = $year;
        $updateData['user_id'] = $userid;
        $updateData['create_time'] = $time;

        $targetMoldel = Db::table('t_wx_user_target')->save($updateData);

        $extraData = [];
        foreach ($data['deptAndRole'] as $v) {
            $extraData[] = [
                'user_id' => $userid,
                'department_id' => $v[0],
                'role_id' => $v[1],
                'create_time' => time(),
            ];
        }
        Db::table('t_wx_user_extra')
            ->insertAll($extraData);

        $model = $this->findForRedis($model);
        return $model;
    }
    public function find($data, $error = true)
    {
        $data['redis'] = true;
        $userid = $data['userid'] ?? false;
        $model = false;
        if ($userid) {
            $model = $this->redis->get('wb:user:' . $userid, false);
        }
        if (!$model || !empty($data['redis']) || empty($model['redis_version']) || $model['redis_version'] !== $this->redis_version) {
            $model = $this->findForRedis($data);
        }
        if ($model || !$error) {
            if (!empty($model['password'])) {
                unset($model['password']);
            }
            return $model;
        } else {
            throw new \think\Exception('未查询到用户信息', -1000);
        }
    }
    public function findForRedis($data)
    {
        $where = [];
        if (!empty($data['userid'])) {
            $where[] = ['userid', '=', $data['userid']];
        } else if (!empty($data['user_id'])) {
            $where[] = ['userid', '=', $data['user_id']];
        } else if (!empty($data['phone'])) {
            $where[] = ['phone', '=', $data['phone']];
        } else if (!empty($data['email'])) {
            $where[] = ['email', '=', $data['email']];
        } else {
            return false;
        }
        $model = $this->model->where($where)->find();
        if ($model) {
            $model['user_id'] = $model['userid'];
            $model = $model->toArray();
            $extra = Db::table('t_wx_user_extra')
                ->alias('ue')
                ->leftJoin('t_dt_department d', 'ue.department_id=d.departmentid')
                ->leftJoin('t_dt_role r', 'ue.role_id=r.roleid')
                ->where([
                    ['ue.user_id', '=', $model['user_id']]
                ])
                ->field([
                    'ue.*',
                    'r.roleid', 'r.rolename', 'r.rolecode',
                    'd.departmentid', 'd.name' => 'deptName', 'd.code' => 'deptCode',
                ])
                ->select()
                ->toArray();
            // $model['extra'] = $extra;
            $roleIdList = [];
            $roleList = [];
            $roleFormatList = [];
            $deptIdList = [];
            $deptList = [];
            $deptFormatList = [];
            $role_id = [];
            $roleid = [];
            $departmentid = [];
            $department_id = [];
            $deptAndRole = [];
            $deptAndRoleList = [];
            $deptAndRoleFormatList = [];
            foreach ($extra as $v2) {
                if (isset($v2['role_id'])) {
                    $roleIdList[] = $v2['role_id'];
                    $role_id[] = $v2['role_id'];
                    $roleid[] = $v2['role_id'];
                    $roleList[] = [
                        'value' => $v2['role_id'],
                        'label' => $v2['rolename'],
                        'code' => $v2['rolecode'],
                    ];
                    $roleFormatList[] = $v2['rolename'];
                }
                if (isset($v2['department_id'])) {
                    $deptIdList[] = $v2['department_id'];
                    $departmentid[] = $v2['department_id'];
                    $department_id[] = $v2['department_id'];
                    if (!empty($v2['department_id'])) {
                        $deptList[] = [
                            'value' => $v2['department_id'],
                            'label' => $v2['deptName'],
                            'code' => $v2['deptCode'],
                        ];
                        $deptFormatList[] = $v2['deptName'];
                    }
                }
                $deptAndRole[] = [$v2['department_id'], $v2['role_id']];
                $deptAndRoleFormatList[] = $v2['department_id'] ? $v2['deptName'] . '-' . $v2['rolename'] : $v2['rolename'];
            }
            $model['roleIdList'] = $roleIdList;
            $model['roleList'] = $roleList;
            $model['roleFormatList'] = $roleFormatList;
            $model['deptIdList'] = $deptIdList;
            $model['deptList'] = $deptList;
            $model['deptFormatList'] = $deptFormatList;
            $model['role_id'] = $role_id;
            $model['roleid'] = $roleid;
            $model['departmentid'] = $departmentid;
            $model['department_id'] = $department_id;
            $model['deptAndRole'] = $deptAndRole;
            $model['deptAndRoleFormatList'] = $deptAndRoleFormatList;
            $this->redis->set('wb:user:' . $model['userid'], $model, 86400);
        } else {
            $model = false;
        }

        return $model;
    }

    public function update($data)
    {
        $userid = $data['userid'] ?? $data['target_id'];
        // throw new \think\Exception(json_encode(['ue.user_id', '=', $userid]), 1);
        $time = time();
        $year = date('Y', $time);
        $model0 = $this->findForRedis($data);
        $model = User::update($data);
        if (json_encode($model0['deptAndRole']) != json_encode($data['deptAndRole'])) {
            Db::table('t_wx_user_extra')
                ->where([
                    ['user_id', '=', $userid]
                ])
                ->delete(true);
            $extraData = [];
            foreach ($data['deptAndRole'] as $v) {
                $extraData[] = [
                    'user_id' => $userid,
                    'department_id' => $v[0],
                    'role_id' => $v[1],
                    'create_time' => time(),
                ];
            }
            Db::table('t_wx_user_extra')
                ->insertAll($extraData);
        }

        if (!empty($data['target'])) {
            $where = [['user_id', '=', $userid], ['year', '=', $year], ['type', '=', 'user'], ['label', '=', 'target']];
            $targetModel = Db::table('t_wx_user_target')->where($where)->find();
            $updateData = [
                'value' => $data['target'],
                'update_time' => $time,
            ];
            if ($targetModel) {
                $where = [
                    ['user_target_id', '=', $targetModel['user_target_id']]
                ];
                $updateData['user_target_id'] = $targetModel['user_target_id'];
                Db::table('t_wx_user_target')->where($where)->save($updateData);
            } else {
                $updateData['type'] = 'user';
                $updateData['label'] = 'target';
                $updateData['year'] = $year;
                $updateData['user_id'] = $userid;
                $updateData['create_time'] = $time;
                Db::table('t_wx_user_target')->save($updateData);
            }
        }

        $model = $this->findForRedis($data);
        return $model;
    }

    public function list($data)
    {
        $pageNo = isset($data['pageNo']) ? (int) $data['pageNo'] : 1;
        $pageSize = isset($data['pageSize']) ? (int) $data['pageSize'] : 10;

        $order = ['u.createtime' => 'desc'];
        $where = [
            // ['u.status', '<>', 0]
        ];
        $filter = [];
        $year = date('Y', time());
        if (!empty($data['name']) || !empty($data['realname'])) {
            $name = $data['name'] ?? $data['realname'];
            $where[] = ['u.realname', 'like', '%' . $name . '%'];
        }
        if (!empty($data['status'])) {
            $where[] = ['u.status', '=', $data['status']];
        }
        if (!empty($data['roleid'])) {
            $roleid = explode(',', (string)$data['roleid']);
            $filter['roleIdList'] = $roleid;
        }
        $data['isSearch'] = null;
        $isSearch = $data['isSearch'] ?? false;
        if ($isSearch) {
            $field = [
                'u.userid', 'u.userid' => 'user_id', 'u.realname' => 'name'
                // , 'u.roleid' => 'roleid'
            ];
            if (!empty($data['email'])) {
                $field[] = 'u.email';
            }
        } else {
            $field = [
                'u.*', 'u.userid' => 'user_id', 'u.realname' => 'name'
                // , 'r.rolename', 'r.rolecode', 'd.name' => 'deptName', 'd.code' => 'deptCode'
            ];
        }
        $whereOr = [];
        if ($isSearch) {
            if (!isset($data['status'])) {
                $where[] = ['u.status', '=', 1];
            }

            // $whereOr[] = ['d.status', '=', 1];
            // $whereOr[] = ['u.departmentid', '=', 0];
        } else {
            // $where[] = ['ut.year', '=', $year];
        }

        $total = $this->model->alias('u')
            ->where($where)
            // ->leftJoin('t_dt_department d', 'u.departmentid=d.departmentid')
            // ->leftJoin('t_dt_role r', 'r.roleid=u.roleid')
            // ->leftJoin('t_wx_user_extra ue', 'u.userid=ue.user_id')
            ->where(function ($query) use ($whereOr) {
                $query->whereOr($whereOr);
            })
            ->count();
        $model = $this->model
            ->alias('u')
            ->where($where)
            // ->leftJoin('t_dt_department d', 'u.departmentid=d.departmentid')
            // ->leftJoin('t_dt_role r', 'r.roleid=u.roleid')
            ->where(function ($query) use ($whereOr) {
                $query->whereOr($whereOr);
            })
            ->order($order)
            ->limit($pageSize)
            ->page($pageNo)
            ->field($field)
            ->select();
        $time = time();
        $year = date('Y', $time);
        $list = [];
        foreach ($model as &$v) {
            $targetData = [
                'userid' => $v['userid'],
                'target' => $v['target'],
                'year', $year
            ];
            $this->getTarget($targetData);
            $extra = Db::table('t_wx_user_extra')
                ->alias('ue')
                ->leftJoin('t_dt_department d', 'ue.department_id=d.departmentid')
                ->leftJoin('t_dt_role r', 'ue.role_id=r.roleid')
                ->where([
                    ['ue.user_id', '=', $v['user_id']]
                ])
                ->field([
                    'ue.*',
                    'r.roleid', 'r.rolename', 'r.rolecode',
                    'd.departmentid', 'd.name' => 'deptName', 'd.code' => 'deptCode',
                ])
                ->select()
                ->toArray();
            // $v['extra'] = $extra;
            $roleIdList = [];
            $roleList = [];
            $roleFormatList = [];
            $deptIdList = [];
            $deptList = [];
            $deptFormatList = [];
            $role_id = [];
            $roleid = [];
            $departmentid = [];
            $department_id = [];
            $deptAndRole = [];
            $deptAndRoleList = [];
            $deptAndRoleFormatList = [];
            foreach ($extra as $v2) {
                if (isset($v2['role_id'])) {
                    $roleIdList[] = $v2['role_id'];
                    $role_id[] = $v2['role_id'];
                    $roleid[] = $v2['role_id'];
                    $roleList[] = [
                        'value' => $v2['role_id'],
                        'label' => $v2['rolename'],
                        'code' => $v2['rolecode'],
                    ];
                    $roleFormatList[] = $v2['rolename'];
                }
                if (isset($v2['department_id'])) {
                    $deptIdList[] = $v2['department_id'];
                    $departmentid[] = $v2['department_id'];
                    $department_id[] = $v2['department_id'];
                    if (!empty($v2['department_id'])) {
                        $deptList[] = [
                            'value' => $v2['department_id'],
                            'label' => $v2['deptName'],
                            'code' => $v2['deptCode'],
                        ];
                        $deptFormatList[] = $v2['deptName'];
                    }
                }
                $deptAndRole[] = [$v2['department_id'], $v2['role_id']];
                // $deptAndRoleList = [

                // ];
                $deptAndRoleFormatList[] = $v2['department_id'] ? $v2['deptName'] . '-' . $v2['rolename'] : $v2['rolename'];
            }
            $v['roleIdList'] = $roleIdList;
            $v['roleList'] = $roleList;
            $v['roleFormatList'] = $roleFormatList;
            $v['deptIdList'] = $deptIdList;
            $v['deptList'] = $deptList;
            $v['deptFormatList'] = $deptFormatList;
            $v['role_id'] = $role_id;
            $v['roleid'] = $roleid;
            $v['departmentid'] = $departmentid;
            $v['department_id'] = $department_id;
            $v['deptAndRole'] = $deptAndRole;
            $v['deptAndRoleFormatList'] = $deptAndRoleFormatList;
            if (count($filter) > 0) {
                $tag = [];
                foreach ($filter as $k3 => $v3) {
                    $tag[$k3] = false;
                    foreach ($v[$k3] as $k4 => $v4) {
                        if (in_array($v4, $v3)) {
                            $tag[$k3] = true;
                            break;
                        }
                    }
                }
                foreach ($tag as $v2) {
                    if (!$v2) {
                        continue;
                    }
                }
            }
            $list[] = $v;
        }
        unset($v);
        return [
            'rows'       => $list,
            'pageSize'   => $pageSize,
            'pageNo'     => $pageNo,
            'totalPage'  => ceil($total / $pageSize),
            'totalCount' => $total,
            'where' => $where,
        ];
    }

    /**
     * 删除
     *
     * @return bool
     */
    public function del($data)
    {
        $id = $data['userid'] ?? $data['target_id'];
        $status = $data['status'] ?? 2;
        Db::table('t_dt_user')->where([['userid', '=', $id]])->update([
            'status' => $status,
        ]);

        return true;
    }
    public function getTarget($data)
    {
        $userid = $data['userid'] ?? $data['target_id'];
        $time = time();
        $year = $data['year'] ?? date('Y', $time);
        $where = [['user_id', '=', $userid], ['year', '=', $year], ['type', '=', 'user'], ['label', '=', 'target']];
        $targetModel = Db::table('t_wx_user_target')->where($where)->find();

        if ($targetModel) {
            return (int)$targetModel['value'];
        } else {
            $target = $data['target'] ?? null;
            if (empty($target) && $target !== 0) {
                $user = Db::table('t_dt_user')->where([['userid', '=', $userid]])->find();
                $target = $user['target'];
            }
            $updateData = [
                'value' => $target,
                'type' => 'user',
                'label' => 'target',
                'year' => $year,
                'user_id' => $userid,
                'update_time' => $time,
                'create_time' => $time,
            ];
            Db::table('t_wx_user_target')->save($updateData);
            return $target;
        }
    }
}
