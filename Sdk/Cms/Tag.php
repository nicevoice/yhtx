<?php
/**
 * @author pancke
 */
class Sdk_Cms_Tag extends Sdk_Base
{
    /*
     * 根据tagid获得tag名字，多个tagid以逗号隔开
     */
    public static function getTag($iTagID, $iStatus=1)
    {
        return self::post('cms', 'api/tag/getTag', array('iTagID'=>$iTagID, 'iStatus'=>$iStatus));
    }

    /*
     * 取得热门标签
     */
    public static function getHotTag($iCityID, $orderBy = 'iNewsNum', $pageSize = 6, $iTypeID = 1)
    {
        $options = array('iTypeID'=>$iTypeID, 'iCityID'=>$iCityID, 'orderBy' => $orderBy, 'pageSize' => $pageSize);
        return self::post('cms', 'api/tag/tags', $options);
    }
}