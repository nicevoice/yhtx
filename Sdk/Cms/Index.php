<?php
/**
 * @author pancke
 */
class Sdk_Cms_Index extends Sdk_Base
{

    /**
     * @param $param cityID
     * @return Ambigous
     */
    public static function maininfos($param=array())
    {
        return self::post('cms', '/Mapi/common/maininfos', $param,0);
    }

    /**
     * @param array $param  analystID 分析师ID userID
     * @return Ambigous
     */
    public static function zan($param=array())
    {
        return self::post('cms', '/Mapi/analyst/fans', $param,0);
    }

    /**
     * @param array $param  name phoneNum verifyCode
     * @return Ambigous
     */
    public static function lixiangjia($param=array())
    {
        return self::post('cms', '/Mapi/idea/commit', $param);
    }



}