<?php
/**
 * @author pancke
 */
class Sdk_Cms_Lounews extends Sdk_Base
{
    public static function getLounews($page, $iCityID, $iTagID = 0, $iPublishStatus = 1)
    {
        $options = array('page'=>$page, 'iCityID'=>$iCityID, 'iPublishStatus'=>$iPublishStatus);
        if(!empty($iTagID)) {
            $options['iTagID'] = $iTagID;
        }

        return self::post('cms', 'api/Lounews/LounewsList', $options);
    }

    public static function getHot($iCityID, $pageSize = 6)
    {
        return self::post('cms', 'api/Lounews/LounewsHotToday', array('iCityID'=>$iCityID, 'len'=>$pageSize));
    }

    public static function getDetail($LounewsID)
    {
        return self::post('cms', 'api/Lounews/LounewsDetail', array('id'=>$LounewsID));
    }

    public static function getRelated($iLounewsID, $iTagID,$iCityID, $pageSize = 5)
    {
        return self::post('cms', 'api/Lounews/relatedLounews', array('iLounewsID'=>$iLounewsID, 'iTagID'=>$iTagID, 'iCityID'=>$iCityID, 'pageSize'=>$pageSize));
    }

    public static function getTopShare($iCityID, $pageSize = 5)
    {
        return self::post('cms', 'api/Lounews/topShare', array('len'=>$pageSize, 'iCityID'=>$iCityID));
    }


    /*
     * 资讯首页和列表页分析师评楼接口
     */
    public static function getBuilds($sCityCode)
    {
        return self::post('cms', 'api/unit/analystrecommendunit', array('sCityCode'=>$sCityCode));
    }

    /*
     * 资讯详情页分析师评楼接口
     */
    public static function getSpecialBuilds($iLounewsID)
    {
        return self::post('cms', 'api/unit/Lounewsrecommendunit', array('iLounewsID'=>$iLounewsID));
    }


    public static function updVisit($iLounewsID)
    {
        return self::post('cms', 'api/Lounewsupdate/statisLounewsVisit', array('iLounewsID'=>$iLounewsID), 0);
    }

    public static function updShare($iLounewsID)
    {
        return self::post('cms', 'api/Lounewsupdate/statisLounewsShare', ['iLounewsID'=>$iLounewsID], 0);
    }

    public static function getBanner($iCityID)
    {
        return self::post('cms', 'api/banner/Lounewscarousel', ['iCityID'=>$iCityID]);
    }

    public static function getAdv($iCityID)
    {
        return self::post('cms', 'api/banner/Lounewsadvertise', ['iCityID'=>$iCityID]);
    }

    // 获取楼盘新闻
    public static function getUnitLounews($aParam)
    {
        if (empty($aParam)) {
            return null;
        }
        return self::post('cms', 'api/Lounews/unitLounews', $aParam);
    }

    public static function changeLounewsLouName($aLounews)
    {
        if (!empty($aLounews)) {
            return self::post('cms','api/Lounews/changeLounewsLouName',$aLounews);
        }
        return null;
    }

}