<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/9/16
 * Time: 10:11
 */

namespace Api\Controller;


class UserController extends ApiController
{
    /**
     * 获取社工列表
     * @param Integer $pageNo
     * @param Integer $count
    */
    public function getSgUser($pageNo = 1,$count = 20){
        $data = D('User/User')->getUserList(null,$pageNo,$count,2);
        if(empty($data)){
            $this->error(self::EMPTY_DATA_CODE);
        }else{
            $this->success($data);
        }
    }
}