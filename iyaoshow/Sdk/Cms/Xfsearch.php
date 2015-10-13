<?php
/**
 * Created by PhpStorm.
 * Author: wangsufei
 * CreateTime: 2015/4/28 14:35
 * Description: XfSearch.php
 */
/**
 * 搜索*/
class Sdk_Cms_Xfsearch extends Sdk_Base
{
    /**
     * 搜索列表*/
    public static function getList($aParam)
    {
        return self::post('cms', '/mapi/newbuilding/list', $aParam);
    }

    /**
     * 搜索的筛选条件*/
    public static function getFilter($aParam)
    {
        return self::post('cms', '/mapi/newbuilding/filter', $aParam);
    }

    /**
     * 搜索的关键词*/
    public static function getFeatures($aParam)
    {
        return self::post('cms', '/mapi/newbuilding/filterfeatures', $aParam);
    }
}