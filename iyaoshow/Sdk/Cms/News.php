<?php
/**
 * @author pancke
 */
class Sdk_Cms_News extends Sdk_Base
{
    public static function getNews($page, $iCityID, $iTagID = 0, $iPublishStatus = 1)
    {
        $options = array('page'=>$page, 'iCityID'=>$iCityID, 'iPublishStatus'=>$iPublishStatus);
        if(!empty($iTagID)) {
            $options['iTagID'] = $iTagID;
        }

        return self::post('cms', 'api/news/newsList', $options);
    }

    public static function getHot($iCityID, $pageSize = 6)
    {
        return self::post('cms', 'api/news/newsHotToday', array('iCityID'=>$iCityID, 'len'=>$pageSize));
    }

    public static function getDetail($newsID)
    {
        return self::post('cms', 'api/news/newsDetail', array('id'=>$newsID));
    }

    public static function getRelated($iNewsID, $iTagID,$iCityID, $pageSize = 6)
    {
        return self::post('cms', 'api/news/relatedNews', array('iNewsID'=>$iNewsID, 'iTagID'=>$iTagID, 'iCityID'=>$iCityID, 'pageSize'=>$pageSize));
    }

    public static function getTopShare($iCityID, $pageSize = 5)
    {
        return self::post('cms', 'api/news/topShare', array('len'=>$pageSize, 'iCityID'=>$iCityID));
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
    public static function getSpecialBuilds($iNewsID)
    {
        return self::post('cms', 'api/unit/newsrecommendunit', array('iNewsID'=>$iNewsID));
    }


    public static function updVisit($iNewsID)
    {
        return self::post('cms', 'api/newsupdate/statisNewsVisit', array('iNewsID'=>$iNewsID), 0);
    }

    public static function updShare($iNewsID)
    {
        return self::post('cms', 'api/newsupdate/statisNewsShare', ['iNewsID'=>$iNewsID], 0);
    }

    public static function getBanner($iCityID)
    {
        return self::post('cms', 'api/banner/newscarousel', ['iCityID'=>$iCityID]);
    }

    public static function getAdv($iCityID)
    {
        return self::post('cms', 'api/banner/newsadvertise', ['iCityID'=>$iCityID]);
    }

    // 获取楼盘新闻
    public static function getUnitNews($aParam)
    {
        if (empty($aParam)) {
            return null;
        }
        return self::post('cms', 'api/news/unitNews', $aParam);
    }

    public static function changeNewsLouName($aNews)
    {
        if (!empty($aNews)) {
            return self::post('cms','api/news/changeNewsLouName',$aNews);
        }
        return null;
    }

    public static function updGood($iNewsID)
    {
        return self::post('cms', 'api/newsupdate/statisNewsGood', ['iNewsID'=>$iNewsID], 0);
    }

}