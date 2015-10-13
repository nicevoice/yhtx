<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/8/15
 * Time: 11:37
 */

namespace Home\Controller;


use Think\Controller;

class SnsController extends Controller{

    //发布
    public function add(){
        $userId = user_id();
        if(empty($userId)){
            $res['result'] = 0;
            $res['info'] = '亲~请先登录';
            echo json_encode($res);
            exit;
        }
        $content = I('content');
        $snsDao = D('Message/Sns');
        $sns = $snsDao->addTextSns($content);
        if(empty($sns)){
            $res['result'] = 0;
            $res['info'] = $snsDao->getErrorMsg();
            echo json_encode($res);
        }else{
            $sns['result'] = 1;
            $sns['nickname'] = get_nickname($sns['user_id']);
            $sns['head_pic_url'] = get_head_picture($sns['user_id']);
            $sns['time_str'] = date('Y-m-d H:i',$sns['create_time']);
            echo json_encode($sns);
        }
    }
    //获取
    public function getList(){
        $lastId = I('last_id');
        $snsList = D('Message/Sns')->getNewTextSnsListByLastOne($lastId,5);
        if(empty($snsList)){
            echo 0;
            exit;
        }else{//整理数据
            foreach($snsList as $key => $sns){
                $sns['result'] = 1;
                $sns['nickname'] = get_nickname($sns['user_id']);
                $sns['head_pic_url'] = get_head_picture($sns['user_id']);
                $sns['time_str'] = date('Y-m-d H:i',$sns['create_time']);
                $snsList[$key] = $sns;
            }
        }
        $list = array(
            'last_id' => $snsList[0]['sns_id'],
            'list' => $snsList
        );
        echo json_encode($list);
    }
}