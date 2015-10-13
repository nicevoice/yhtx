<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/8/15
 * Time: 11:21
 */

namespace Message\Model;


use Common\Model\BaseModel;

class SnsModel extends BaseModel{

    const CONTENT_LENGTH_ERROR = '内容长度应为1-200位之间';
    const SNS_INTERVAL_TIME = '发布内容过于频繁，请等待';
    const NOT_LOGIN = '请先登录';
    /**
     * 根据最后一条信息获取最近信息（时间排序）
     * @param Integer $lastOneId 最后一条信息id
     * @param Integer $count 获取数量
     * @param Boolean $withoutUserId 发布者信息
     * @return Array
    */
    public function getNewTextSnsListByLastOne($lastOneId = null,$count,$withoutUserId = true){
        if($withoutUserId) $where['user_id'] = array('NEQ',user_id());
        $where['is_del'] = 0;
        $where['type'] = 'text';
        if(!empty($lastOneId)) $where['sns_id'] = array('GT',$lastOneId);
        return $this->where($where)->order('create_time DESC')->limit($count)->select();
    }
    /**
     * 添加一条信息（text）
     * @param String $content 内容
     * @param Integer $user_id 发布者id
     * @return Array|Boolean
    */
    public function addTextSns($content,$user_id = null){
        $t = $this->checkAdd();
        if($t > 0){
            $this->setErrorMsg(self::SNS_INTERVAL_TIME.$t.'秒');
            return false;
        }
        if(!check_var_length($content,1,200)){
            $this->setErrorMsg(self::CONTENT_LENGTH_ERROR);
            return false;
        }
        $data['content'] = $content;
        $data['user_id'] = empty($user_id) ? user_id() : $user_id;
        if(empty($data['user_id'])){
            $this->setErrorMsg(self::NOT_LOGIN);
            return false;
        }
        $data['create_time'] = time();
        $data['type'] = 'text';
        $data['is_del'] = 0;
        $sns_id = $this->add($data);
        return empty($sns_id) ? false : $this->find($sns_id);
    }
    /**
     * 发布间隔判断
     * @param Integer $user_id 发布者id
     * @return Integer 剩余间隔时间
     */
    public function checkAdd($user_id = null){
        $where['user_id'] = empty($user_id) ? user_id() : $user_id;
        $sns = $this->where($where)->order('create_time DESC')->find();
        $intervalTime = $sns['create_time'] + C('SNS_INTERVAL') - time();
        if($intervalTime > 0){//剩余时间
            return $intervalTime;
        }else{
            return 0;
        }
    }
}