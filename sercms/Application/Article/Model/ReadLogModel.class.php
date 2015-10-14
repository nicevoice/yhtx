<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/7/30
 * Time: 10:00
 */

namespace Article\Model;


use Common\Model\BaseModel;

class ReadLogModel extends BaseModel{

    protected $tableName = 'article_read_log';
    /**
     * 添加阅读记录
     * @param Integer $userId
     * @param Integer $articleId
     * @return Boolean
    */
    public function addLog($userId,$articleId){
        $data['user_id'] = $userId;
        $data['article_id'] = $articleId;
        $data['create_time'] = time();
        $data['ip'] = get_client_ip();
        $res = $this->add($data);
        if(empty($res)){
            return false;
        }
        return true;
    }
}