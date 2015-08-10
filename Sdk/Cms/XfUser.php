<?php
/**
 * @author ddc
 */
class Sdk_Cms_XfUser extends Sdk_Base
{
    //用户检测
    public static function checkUser($mobile)
    {
        return self::post('cms', '/api/Xfuser/checkUser', ['mobile'=>$mobile]);
    }

    //获取验证码
    public static function smsCode($mobile,$type)
    {
        return self::post('cms', '/mapi/common/verifycode', ['mobile'=>$mobile,'type'=>$type]);
    }

    //检验验证码
    public static function checkCode($mobile,$code,$type)
    {
        return self::post('cms', '/mapi/common/checkverify', ['mobile'=>$mobile,'code'=>$code,'type'=>$type]);
    }

    //用户注册
    public static function register($mobile,$code,$pwd,$city)
    {
        return self::post('cms', '/mapi/common/register', ['mobile'=>$mobile,'code'=>$code,'pwd'=>$pwd,'city'=>$city]);
    }

    //用户登录
    public static function login($mobile,$pwd)
    {
        return self::post('cms', '/mapi/common/login', ['mobile'=>$mobile,'pwd'=>$pwd]);
    }

    //用户登出
    public static function logout($userID,$token)
    {
        return self::post('cms', '/mapi/common/logout', ['userID'=>$userID,'token'=>$token]);
    }

    //忘记密码
    public static function forgetpwd($mobile,$newpwd,$code)
    {
        return self::post('cms', 'mapi/user/forgetpwd', ['mobile'=>$mobile,'newPwd'=>$newpwd,'verifyCode'=>$code]);
    }

    //获取个人信息
    public static function getUserInfo($userName)
    {
        return self::post('cms', 'api/Xfuser/getUserInfo', ['userName'=>$userName]);
    }

    //更新个人信息
    public static function updateUserInfo($userName,$realName,$sex,$budget,$buyInfo,$birthday)
    {
        return self::post('cms', 'api/Xfuser/updateUserInfo', ['userName'=>$userName,'realName'=>$realName,'sex'=>$sex,'budget'=>$budget,'buyInfo'=>$buyInfo,'birthday'=>$birthday]);
    }

    //修改密码
    public static function changepwd($userid,$newpwd,$code,$token)
    {
        return self::post('cms', 'mapi/user/changepwd', ['userid'=>$userid,'token'=>$token,'newPwd'=>$newpwd,'verifyCode'=>$code]);
    }
}