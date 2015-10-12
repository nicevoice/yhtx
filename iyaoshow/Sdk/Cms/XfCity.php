<?php
/**
 * @author pancke
 */
class Sdk_Cms_XfCity extends Sdk_Base
{
    //获取城市
    public static function getCity()
    {
        return self::post('cms', '/mapi/common/city', '');
    }

    //根据城市code获取城市房价区间信息
    public static function getCityPriceSection($sCityCode)
    {
        return self::post('cms', '/api/Xfcity/getCityPriceSection', ['CityCode' => $sCityCode]);
    }

    //获取城市所属的区域
    public static function getCityRegion($sCityCode)
    {
        return self::post('cms', '/api/Xfcity/getCityRegionList', ['CityCode' => $sCityCode]);
    }

    //获取所属城市小区首字母
    public static function getShouzIMu($sCityCode)
    {
        return self::post('cms', '/api/Xfcity/getFirstLettersList', ['CityCode' => $sCityCode]);
    }

    //获取友情链接
    public static function getLinks($sCityCode)
    {
        return self::post('cms', '/api/Xfcity/getLink', ['CityCode' => $sCityCode]);
    }
}