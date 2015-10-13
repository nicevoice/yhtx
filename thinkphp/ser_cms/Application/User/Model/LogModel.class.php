<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/7/29
 * Time: 13:21
 */

namespace User\Model;


use Common\Model\BaseModel;

class LogModel extends BaseModel{

    protected $tableName = 'login_log';
    /**
     * 添加登陆日志
     * @param Integer $userId 用户id
     * @return Boolean|Integer
    */
    public function addLog($userId){
        $data['user_id'] = $userId;
        $data['login_time'] = time();
        $data['ip'] = get_client_ip();
        return $this->add($data);
    }
}