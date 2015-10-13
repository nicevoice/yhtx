<?php
/**
 * @author cjj
 */
class Sdk_Cms_Newhouse extends Sdk_Base
{
    // 搜索条件
    public static function getSearchFilter($cityID)
    {
        return self::post('cms', '/Mapi/Newbuilding/filter', ['cityID' => $cityID]);
    }


    // 搜索
    public static function Search($searchParam,$cityCode,$pageSize)
    {

        return self::post('cms', '/Api/Newhouse/search', ['searchParam' => $searchParam,'cityCode' => $cityCode,'pageSize'=>$pageSize]);
    }


    // 分析师评楼
    public static function unitRecommended($cityCode)
    {
        return self::post('cms', '/Api/Newhouse/unitRecommended', ['cityCode' => $cityCode]);
    }

}