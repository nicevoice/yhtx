<?php
/**
 * @author pancke
 */
class Sdk_Cms_City extends Sdk_Base
{
    public static function getCity()
    {
        return self::post('cms', '/api/city/list', '');
    }

    public static function getCityByCode($sCityCode)
    {
        return self::post('cms', '/api/city/getcitybycode', ['sCityCode' => $sCityCode]);
    }


    public static function getCityByName($sCityName)
    {
        return self::post('cms', '/api/city/getcitybyname', ['sCityName' => $sCityName]);
    }
    // $cityID 城市ID 数组
    public static function getCityById($cityID)
    {
        return self::post('cms', '/api/city/getCityById',array('cityID'=>$cityID));
    }

}