<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/8/17
 * Time: 10:29
 */

namespace Admin\Controller;


class UserController extends AdminController{

    const SET_STATUS_SUCCESS = '设置身份成功';

    public function index(){
        $nickname = I('nickname');
        $p = I('p');
        if(empty($p)) $p = 1;
        $userDao = D('User/User');
        $userList = $userDao->getUserList($nickname,$p,10);
        $this->assign('_html',$userDao->getHtml());
        $this->assign('_userList',$userList);
        $this->display();
    }

//    //TODO 添加测试用户
//    public function addTestUser(){
//        $userDao = D('User/User');
//        $user = $userDao->find(3);
//        for($i=0;$i<100;$i++){
//            $data = $user;
//            unset($data['user_id']);
//            $data['nickname'] = 'test'.$i;
//            $data['email'] = rand(1111111,9999999).$i.'@qq.com';
//            $data['des'] = "这是第{$i}个测试用户";
//            D('User/User')->add($data);
//        }
//    }

    //更改用户身份
    public function setStatus(){
        $userId = I('user_id');
        $status = I('status');
        $userDao = D('User/User');
        $res = $userDao->setStatus($userId,$status);
        if($res){
            $data['status'] = 1;
            $data['info'] = self::SET_STATUS_SUCCESS;
        }else{
            $data['status'] = 0;
            $data['info'] = $userDao->getErrorMsg();
        }
        echo json_encode($data);
    }
}