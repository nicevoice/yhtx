<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/7/29
 * Time: 13:50
 */

namespace Home\Controller;


class UserController extends BaseController{

    protected $mustLoginRequestUriArray = array(
        'Home/User/doEdit',
    );

    const LOGIN_SUCCESS = '登陆成功';
    const LOGIN_ERROR = '邮箱或密码错误';
    const PASSWORD_DIFFERENT = '两次密码不一致';
    const MUSE_SET_ERROR = '请先完成基本信息';
    const REGISTER_SUCCESS = '注册成功';
    const EDIT_SUCCESS = '设置成功';
    const SEND_SMS_SUCCESS = '验证码发送成功';
    //登陆
    public function login(){
        $this->display();
    }

    public function doLogin(){
        $email = I('email');
        $password = I('password');
        if(empty($email) || empty($password)){
            $this->error(self::LOGIN_ERROR);
        }
        $userDao = D('User/User');
        $user = $userDao->login($email,$password);
        if(empty($user)){
            $this->error($userDao->getErrorMsg());
        }
        $this->success(self::LOGIN_SUCCESS,U('Home/Index/index'));
    }
    //注册
    public function register(){
        $this->display();
    }
    public function doRegister(){
        $email = I('email');
        $password = I('password');
        $rePassword = I('re_password');
        $nickname = I('nickname');
        $phone = I('phone');
        $code = I('code');
        if(empty($email) || empty($password) || empty($rePassword) || empty($nickname) || empty($phone)){
            $this->error(self::MUSE_SET_ERROR);
        }
        if($password != $rePassword){
            $this->error(self::PASSWORD_DIFFERENT);
        }
        $picture = I('picture');
        if(!empty($picture)){
            $picture = $this->getUrlByName($picture);
        }
        $des = I('summary');
        $realname = I('realname');
        $qq = I('qq');
        $sex = I('sex');
        if(empty($sex)){
            $sex = 0;
        }
        $userDao = D('User/User');
        $user = $userDao->register($nickname,$email,$password,$realname,$picture,$des,$sex ,$phone,$qq,$code);
        if(empty($user)){
            $this->error($userDao->getErrorMsg());
        }
        $this->success(self::REGISTER_SUCCESS,U('Home/User/login'));
    }
    /**
     * 退出
     */
    public function logout(){
        session('user',null);
        redirect(U('Home/User/login'));
    }
    //信息编辑
    public function set(){
        $user = D('User/User')->find(user_id());
        $this->assign('_user',$user);
        $this->display();
    }
    public function doEdit(){
        $nickname = I('nickname');
        if(empty($nickname)){
            $this->error(self::MUSE_SET_ERROR);
        }
        $picture = I('picture');
        if(!empty($picture)){
            $picture = $this->getUrlByName($picture);
        }
        $des = I('summary');
        $realname = I('realname');
        $phone = I('phone');
        $qq = I('qq');
        $sex = I('sex');
        if(empty($sex)){
            $sex = 0;
        }
        $userDao = D('User/User');
        $user = $userDao->edit(user_id(),$nickname,$realname,$picture,$des,$sex,$phone,$qq);
        if(empty($user)){
            $this->error($userDao->getErrorMsg());
        }
        $this->success(self::EDIT_SUCCESS,U('Home/User/set'));
    }
    //发送验证码短信
    public function sendSMS(){
        $phone = I('phone');
        $userDao = D('User/User');
        $res = $userDao->sendSms($phone);
        if(empty($res)){
            $data['status'] = 0;
            $data['info'] = $userDao->getErrorMsg();
        }else{
            $data['status'] = 1;
            $data['info'] = self::SEND_SMS_SUCCESS;
        }
        echo json_encode($data);
    }
}