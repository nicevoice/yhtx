<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/8/5
 * Time: 13:57
 */

namespace Weixin\Model;


use Think\Model;

class LogModel extends Model{

    protected $tableName = 'weixin_log';
    /**
     * 记录消息日志
     * @param Array Wechat格式数组
     * @return Boolean
    */
    public function addLog($param){
        $id = $this->add($param);
        return !empty($id);
    }
}