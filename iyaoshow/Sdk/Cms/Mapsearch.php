<?php
/**
 * @author pancke
 */
class Sdk_Cms_Mapsearch extends Sdk_Base
{

    // 地图找房
    public static function city($param=array())
    {
        return self::post('cms', '/Mapi/map/search', $param);
    }



}