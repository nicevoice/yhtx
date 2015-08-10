<?php
/**
 * Created by PhpStorm.
 * Author: wangsufei
 * CreateTime: 2015/4/28 14:28
 * Description: Daogou.php
 */

/**
 * 楼盘导购*/
class Sdk_Cms_Xfguide extends Sdk_Base
{
    /**
     * 楼盘导购列表*/
    public static function getList($aParam)
    {
        return self::post('cms', '/mapi/newbuilding/sellguidelist', $aParam);
    }
}