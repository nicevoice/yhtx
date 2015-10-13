<?php
/**
 * @author pancke
 */
class Sdk_Cms_Unit extends Sdk_Base
{


    // 通过楼盘ID 获取城市code
    public static function getCityCodeByUnitID($unitID)
    {
        if (empty($unitID)) {
            return null;
        }
        return self::post('cms', 'api/unit/getCityCode', array('unitID' => $unitID));
    }

    public static function roomTypeDetail($buildingID, $typeCode)
    {
        return self::post('cms', 'api/unit/roomTypeDetail', array('buildingID' => $buildingID, 'typeCode' => $typeCode));
    }

    //通过城市获取预开盘推荐
    public static function getAdvanceOpen($cityName){
        return self::post('cms', 'api/unit/getAdvanceOpen', array('cityName' => $cityName, 'ListType'=>'近期预开楼盘推荐榜','CustomType'=>1));
    }

    //获取每月新开盘
    public static function getNewPan($cityName){
        return self::post('cms', 'api/unit/getNewTray', array('cityName' => $cityName, 'ListType'=>'新开楼盘性价比测评榜','CustomType'=>1));
    }

    //通过城市code获取评级上调
    public static function getPingJi($cityCode,$type){
        return self::post('cms', 'api/unit/pingJiUnits', array('cityCode' => $cityCode,'type'=>$type));
    }

    //热销滞销
    public static function reXiaoOrZhiXiao($cityName){
        return self::post('cms', 'api/unit/reXiaoZhiXiao', array('cityName' => $cityName));
    }

    //性价比评测榜
    public static function getXingJiaBi($cityName){
        return self::post('cms', 'api/unit/homeBangdan', array('cityName' => $cityName));
    }

    //首页楼盘资讯->最新开盘
    public static function getZuiXinKaiPan($cityCode){
        return self::post('cms', 'api/unit/getZuiXin', array('cityCode' => $cityCode));

    }


    //首页分析师
    public static function getAnalyst($cityCode){
        return self::post('cms', 'api/unit/getAnalyst', array('cityCode' => $cityCode));
    }

    //保存首页理想家数据
    public static function saveLiXiangJia($UserName,$UnitName,$Phone,$MiniPrice,$accountName,$cityName){
        return self::post('cms', 'api/unit/saveLiXiangJia', array('UserName' => $UserName,'UnitName'=>$UnitName,'Phone'=>$Phone,
                                                                          'MiniPrice'=>$MiniPrice,'accountName'=>$accountName,'cityName'=>$cityName));
    }


    //处理楼盘资讯->最新开盘
    public static  function formatNewOpen($data){
        $arr = array();
        if(!empty($data)){
            foreach($data as $key=>$value){
                if($value['sOpenTime'] == $value['sOpenTime']){
                    $arr[$value['sOpenTime']][] = $value;
                }
            }
            return $arr;
        }
    }
}