<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/7/29
 * Time: 9:31
 */

namespace User\Model;


use Common\Model\BaseModel;
use Think\Page;

class UserModel extends BaseModel{

    const NOT_EMAIL_ERROR = '账号格式错误，请使用邮箱登陆';
    const PASSWORD_LENGTH_ERROR = '密码长度应为6-30位之间';
    const EMPTY_USER_ERROR = '用户不存在';
    const DELETE_USER_ERROR = '用户已被禁用';
    const HAS_SAME_USER = '存在相同用户';
    const HAS_SAME_PHONE = '存在相同用户';
    const NICKNAME_LENGTH_ERROR = '昵称长度应为1-8位之间';
    const REALNAME_LENGTH_ERROR = '姓名长度应为2-5位之间';
    const SEX_PARAM_ERROR = '性别参数异常';
    const DES_LENGTH_ERROR = '描述长度应为1-200位之间';
    const SET_STATUS_ERROR = '设置身份有误，请刷新后再试';
    const SEND_SMS_ERROR = '短信系统异常，请稍后再试';
    const SEND_SMS_LIMIT_ERROR = '短信发送次数过多，请稍后再试';
    const SEND_SMS_TIME_ERROR = '短信发送频率过快，请等待';
    const SEND_SMS_TIMEOUT = '验证码已经失效';
    const SMS_ERROR = '短信验证码错误';

    protected $error = '系统繁忙，请稍后再试';
    /**
     * 登陆
     * @param String $email 邮箱
     * @param String $password 密码
     * @return Boolean|Array
    */
    public function login($email,$password){
        if(!check_is_email($email)){
            $this->setErrorMsg(self::NOT_EMAIL_ERROR);
            return false;
        }
        if(!check_var_length($password,6,30)){
            $this->setErrorMsg(self::PASSWORD_LENGTH_ERROR);
            return false;
        }
        $where['email'] = $email;
        $where['password'] = make_password($password);
        $user = $this->where($where)->find();
        if(empty($user)){
            $this->setErrorMsg(self::EMPTY_USER_ERROR);
            return false;
        }
        if($user['status'] == -1){
            $this->setErrorMsg(self::DELETE_USER_ERROR);
            return false;
        }
        //记录登陆日志
        D('User/Log')->addLog($user['user_id']);
        //记录Session
        session('user',$user);
        return $user;
    }
    /**
     * 注册
     * @param String $nickname 昵称
     * @param String $email 邮箱
     * @param String $password 密码
     * @param String $realname 真实姓名
     * @param String $headPicUrl 头像地址
     * @param String $des 个人简介
     * @param Integer $sex 性别 0,1,2
     * @param String $phone 电话
     * @param String $qq QQ号
     * @param String $code 短信验证码
     * @return Array|Boolean
    */
    public function register($nickname,$email,$password,$realname = null,$headPicUrl = null,$des = null,$sex = null,$phone,$qq = null,$code){
        if(!check_var_length($nickname,1,8)){
            $this->setErrorMsg(self::NICKNAME_LENGTH_ERROR);
            return false;
        }
        $data['nickname'] = $nickname;
        if(!check_is_email($email)){
            $this->setErrorMsg(self::NOT_EMAIL_ERROR);
            return false;
        }
        //判断email是否重复
        if(!$this->checkEmail($email)){
            $this->setErrorMsg(self::HAS_SAME_USER);
            return false;
        }
        $data['email'] = $email;
        //判断手机号码
        if(!$this->checkPhone($phone)){
            $this->setErrorMsg(self::HAS_SAME_PHONE);
            return false;
        }
        //判断验证码
        if(!$this->checkSmsCode($phone,$code)){
            return false;
        }
        $data['phone'] = $phone;
        if(!check_var_length($password,6,30)){
            $this->setErrorMsg(self::PASSWORD_LENGTH_ERROR);
            return false;
        }
        $data['password'] = make_password($password);
        if(!empty($realname)){
            if(!check_var_length($realname,1,8)){
                $this->setErrorMsg(self::REALNAME_LENGTH_ERROR);
                return false;
            }
            $data['realname'] = $realname;
        }
        if(!empty($headPicUrl)){
            $data['head_pic_url'] = $headPicUrl;
        }
        if(!empty($des)){
            if(!check_var_length($des,1,255)){
                $this->setErrorMsg(self::DES_LENGTH_ERROR);
                return false;
            }
            $data['des'] = $des;
        }
        if(!empty($sex)){
            if(!in_array($sex,array(0,1,2))){
                $this->setErrorMsg(self::SEX_PARAM_ERROR);
                return false;
            }
            $data['sex'] = $sex;
        }
        if(!empty($qq)){
            $data['qq'] = $qq;
        }
        //注册时间
        $data['create_time'] = time();
        $userId = $this->add($data);
        return empty($userId) ? false : $this->find($userId);
    }

    /**
     * 是否登陆
     * @return Boolean|Array
    */
    public function is_login(){
        $user = session('user');
        return empty($user) ? false : $user;
    }
    /**
     * 判断email是否重复
     * @param String $email
     * @return Boolean
    */
    public function checkEmail($email){
        $where['email'] = $email;
        $user = $this->where($where)->find();
        return empty($user);
    }
    /**
     * 判断phone是否重复
     * @param String $phone
     * @return Boolean
     */
    public function checkPhone($phone){
        $where['phone'] = $phone;
        $user = $this->where($where)->find();
        return empty($user);
    }
    /**
     * 判断用户是否存在
     * @param Integer $userId
     * @return Boolean
    */
    public function checkUser($userId){
        $user = $this->find($userId);
        if(empty($user) || $user['status'] < 0){
            return false;
        }
        return true;
    }
    /**
     * 编辑用户
     * @param String $user_id 用户id
     * @param String $nickname 昵称
     * @param String $realname 真实姓名
     * @param String $headPicUrl 头像地址
     * @param String $des 个人简介
     * @param Integer $sex 性别 0,1,2
     * @param String $phone 电话
     * @param String $qq QQ号
     * @return Array|Boolean
    */
    public function edit($user_id = null,$nickname = null,$realname = null,$headPicUrl = null,$des = null,$sex = 0,$phone = null,$qq = null){
        if(empty($user_id)){
            $user_id = user_id();
        }
        $where['user_id'] = $user_id;
        if(!check_var_length($nickname,1,8)){
            $this->setErrorMsg(self::NICKNAME_LENGTH_ERROR);
            return false;
        }
        $data['nickname'] = $nickname;
        if(!empty($realname)){
            if(!check_var_length($realname,1,8)){
                $this->setErrorMsg(self::REALNAME_LENGTH_ERROR);
                return false;
            }
            $data['realname'] = $realname;
        }
        if(!empty($headPicUrl)){
            $data['head_pic_url'] = $headPicUrl;
        }
        if(!empty($des)){
            if(!check_var_length($des,1,255)){
                $this->setErrorMsg(self::DES_LENGTH_ERROR);
                return false;
            }
            $data['des'] = $des;
        }
        if(!empty($sex)){
            if(!in_array($sex,array(0,1,2))){
                $this->setErrorMsg(self::SEX_PARAM_ERROR);
                return false;
            }
            $data['sex'] = $sex;
        }
        if(!empty($phone)){
            $data['phone'] = $phone;
        }
        if(!empty($qq)){
            $data['qq'] = $qq;
        }
        //注册时间
        $res = $this->where($where)->save($data);
        if($res === false){
            return false;
        }else{
            $user = $this->find($user_id);
            //更新缓存
            cache_user($user_id,$user);
            //更新session
            session('user',$user);
            return $user;
        }
    }
    /**
     * 列表
     * @param String $keyWord 关键字
     * @param Integer $pageNo 页码
     * @param Integer $count 查询数量
     * @param Integer $status 用户身份状态
     * @return Boolean
     */
    public function getUserList($keyWord = null,$pageNo = 1,$count = 10,$status = null){
        $where = array();
        if(!empty($keyWord)) $where['nickname'] = array('LIKE',"%{$keyWord}%");
        if($status !== null) $where['status'] = $status;
        //分页
        $page = new Page($this->where($where)->count(),$count);
        $this->html = $page->show();
        $data = $this->where($where)->order('user_id DESC')->limit(($pageNo-1)*$count,$count)->select();
        //去除密码
        foreach($data as $key=>$value){
            unset($data[$key]['password']);
        }
        return $data;
    }
    /**
     * 设置用户状态
     * @param Integer $userId 用户id
     * @param Integer $status 状态码
     * @return Boolean
    */
    public function setStatus($userId,$status){
        $where['user_id'] = $userId;
        $user = $this->where($where)->find();
        if(empty($user)){
            $this->setErrorMsg(self::EMPTY_USER_ERROR);
            return false;
        }
        if($user['status'] == 99){
            return false;
        }
        switch($status){
            case 0://普通用户
                $data['status'] = 0;
                break;
            case 1://设置认证用户
                $data['status'] = 1;
                break;
            case 2://设置社工
                if($user['status'] <> 1){
                    $this->setErrorMsg(self::SET_STATUS_ERROR);
                    return false;
                }
                $data['status'] = 2;
                break;
            case -1://禁用
                $data['status'] = -1;
        }
        $res = $this->where($where)->save($data);
        if($res === false){
            $this->setErrorMsg(self::SET_STATUS_ERROR);
            return false;
        }
        return true;
    }
    /**
     * 发送验证码
     * @param String $phone 手机号
     * @return Boolean
    */
    public function sendSms($phone){
        if(empty($phone)) return false;
        if(!$this->checkPhone($phone)){
            $this->setErrorMsg(self::HAS_SAME_PHONE);
            return false;
        }
        $hasSend = S($phone . '_sms');
        if(!empty($hasSend)){
            if($hasSend['count'] >= 3){
                $this->setErrorMsg(self::SEND_SMS_LIMIT_ERROR);
                return false;
            }
            $ca = $hasSend['create_time'] + 60 - time();
            if($ca > 0){//发送时间差
                $this->setErrorMsg(self::SEND_SMS_TIME_ERROR . $ca . '秒');
                return false;
            }
        }
//        $code = rand(111111,999999);
//        $res = sendTemplateSMS($phone,$code);
//        if($res === false){
//            $this->setErrorMsg(self::SEND_SMS_ERROR);
//            return false;
//        }else{
//            S($phone . '_sms',array('code'=>$code,'create_time'=>time(),'count'=>$hasSend['count']+1),array('expire'=>60*20));
//            return true;
//        }
        $code = 123456;//TODO 测试代码
        S($phone . '_sms',array('code'=>$code,'create_time'=>time(),'count'=>1),array('expire'=>60*20));
        return false;
    }
    /**
     * 校验验证码
     * @param String $phone 手机号
     * @param String $code 验证码
     * @return Boolean
    */
    public function checkSmsCode($phone,$code){
        $hasSend = S($phone . '_sms');
        if($hasSend['create_time'] + 5 * 60 < time()){
            $this->setErrorMsg(self::SEND_SMS_TIMEOUT);
            return  false;
        }
        if($code == $hasSend['code']){
            return true;
        }else{
            $this->setErrorMsg(self::SMS_ERROR);
            return false;
        }
    }

}