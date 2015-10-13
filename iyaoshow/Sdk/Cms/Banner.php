<?php
/**
 * @author pancke
 */
class Sdk_Cms_Banner extends Sdk_Base
{
    public static function getBanner($iModuleID,$iTypeID,$sCity)
    {
        $aParam = array(
            'iModuleID' => $iModuleID,
            'iTypeID' => $iTypeID,
            'sCity' => $sCity
        );
        return self::post('cms', 'api/banner/getBanner', $aParam);
    }

    //首页广告位1
    public static function getHomeIndexOne($sCity)
    {
        return self::getBanner(3,1,$sCity);
    }

    //首页广告位2
    public static function getHomeIndexTwo($sCity)
    {
        return self::getBanner(3,2,$sCity);
    }

    //列表广告位1
    public static function getListIndexOne($sCity)
    {
        return self::getBanner(3,3,$sCity);
    }

    //楼盘资讯
    public static function getLouNews($sCity)
    {
        return self::getBanner(4,'1,2,3',$sCity);//这里传字符串，该模块包含几个广告位就传几个
    }

    //首页轮播图
    public static function getHomeCarousel($sCity)
    {
        return self::getBanner(5,1,$sCity);
    }

    //首页专题页
    public static function getHomeVicepic($sCity)
    {
        return self::getBanner(6,1,$sCity);
    }
}