<?php
/**
 * @author pancke
 */
class Sdk_Cms_Ucenter extends Sdk_Base
{

    /**
     * @param $param lat:精度,lon:维度,通过手机定位接口获得,withCode:0,1格式不一样
     * @return Ambigous
     */
    public static function city($param=array())
    {
        return self::post('cms', '/Mapi/Common/city', $param);
    }

    /**
     * @param $param mobile:手机,code:短信验证码,pwd:密码,city:城市
     * @return Ambigous
     */
    public static function register($param)
    {
        return self::post('cms', '/Mapi/Common/register', $param);
    }

    /**
     * @param $param mobile:手机,type:1,注册，2,找回密码，3,理想家用户提交，4,修改密码
     * @return Ambigous
     */
    public static function verifycode($param)
    {
        return self::post('cms', '/Mapi/Common/verifycode', $param);
    }

    /**
     * @param $param mobile:手机,pwd:密码
     * @return Ambigous
     */
    public static function login($param)
    {
        return self::post('cms', '/mapi/common/login', $param);
    }

    /**
     * @param $param mobile:手机,newPwd:新密码,verifyCode:验证码
     * @return Ambigous
     */
    public static function forgetpwd($param)
    {
        return self::post('cms', 'mapi/user/forgetpwd', $param);
    }

    /**
     * @param $param userID:用户ID,token
     * @return Ambigous
     */
    public static function logout($param)
    {
        return self::post('cms', '/mapi/common/logout', $param);
    }

    /**
     * 修改密码
     * @param $param userid:用户ID,token,verifyCode,newPwd
     * @return Ambigous
     */
    public static function changepwd($param)
    {
        return self::post('cms', 'mapi/user/changepwd', $param);
    }

    /**
     * 我的通知   //  这版没有
     * @param $param userid:用户ID,token
     * @return Ambigous
     */
    public static function mynews($param)
    {
        return self::post('cms', 'mapi/user/mynews', $param);
    }

    /**
     * 添加收藏楼盘
     * @param $param userID:用户ID,token,type:1:新房, 2:二手房 3:评测,targetID对于楼盘来说就是楼盘id，对于评测来说，就是评测id
     * @return Ambigous
     */
    public static function addfav($param)
    {
        return self::post('cms', 'mapi/user/addfav', $param);
    }

    /**
     * 取消收藏楼盘
     * @param $param userID:用户ID,token,type:1:新房, 2:二手房 3:评测,targetID
     * @return Ambigous
     */
    public static function removefav($param)
    {
        return self::post('cms', 'mapi/user/removefav', $param);
    }

    /**
     * 取消全部收藏楼盘
     * @param $param userID:用户ID,token,type:1:新房, 2:二手房 3:评测
     * @return Ambigous
     */
    public static function removeallfav($param)
    {
        return self::post('cms', 'mapi/user/removeallfav', $param);
    }

    /**
     * 我的收藏列表
     * @param $param userID:用户ID,token,type:1:新房, 2:二手房 3:评测,iPage,iRows
     * @return Ambigous
     */
    public static function favbuildings($param)
    {
        return self::post('cms', 'mapi/user/favbuildings', $param);
    }

    /**
     * 评测列表
     * @param $param cityID:城市ID,iPage,iRows,regionID,priceID,layoutID
     * @return Ambigous
     */
    public static function evaluationList($param)
    {
        return self::post('cms', 'mapi/evaluation/list', $param);
    }

    /**
     * 楼盘列表
     * @param $param cityID城市ID
     * @return Ambigous
     */
    public static function louList($param)
    {
        return self::post('cms', 'mapi/newbuilding/filter', $param);
    }

}