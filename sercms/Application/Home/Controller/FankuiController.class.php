<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/8/27
 * Time: 15:35
 */

namespace Home\Controller;


use Think\Controller;

class FankuiController extends Controller{

    const ADD_ERROR = '反馈失败，请稍后再试';
    const ADD_SUCCESS = '感谢您的反馈~';

    public function add(){
        $data['content'] = I('content');
        $data['create_time'] = time();
        $data['user_id'] = user_id();
        $id = M('fankui')->add($data);
        if(empty($id)){
            $data['status'] = 0;
            $data['info'] = self::ADD_ERROR;
        }else{
            $data['status'] = 1;
            $data['info'] = self::ADD_SUCCESS;
        }
        echo json_encode($data);
    }
}