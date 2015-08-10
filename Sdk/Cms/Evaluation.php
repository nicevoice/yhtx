<?php
/**
 * @author pancke
 */
class Sdk_Cms_Evaluation extends Sdk_Base
{
    //获取菜单
    public static function getMenus($aParam)
    {
        return self::post('cms', '/api/Evaluationchapter/', $aParam);
    }

    //分析师接口
    public static function analysts($aParam)
    {
        return self::post('cms', '/api/Evaluationanalysts/', $aParam);
    }

    //户型分析-整体评价
    public static function evaluationhxanalyse($aParam=array())
    {
        return self::post('cms', '/api/Evaluationhxanalyse/', $aParam);
    }

    //户型分析-户型分析
    public static function evaluationhxanalysehx($aParam=array())
    {
        return self::post('cms', '/api/Evaluationhxanalyse/hx', $aParam);
    }

    public static function evaluationtrafficindex($aParam=array())
    {
        return self::post('cms', '/api/evaluationtraffic/getZiJia', $aParam);
    }

    public static function evaluationtrafficrail($aParam=array())
    {
        return self::post('cms', '/api/evaluationtraffic/getRail', $aParam);
    }

    public static function evaluationtrafficbus($aParam=array())
    {
        return self::post('cms', '/api/evaluationtraffic/getBus', $aParam);
    }

    public static function evaluationregionindex($aParam=array())
    {
        return self::post('cms', '/api/evaluationregion/getRegion', $aParam);
    }

    public static function evaluationregioneducate($aParam=array())
    {
        return self::post('cms', '/api/evaluationregion/getEducate', $aParam);
    }

    public static function evaluationregionmedical($aParam=array())
    {
        return self::post('cms', '/api/evaluationregion/getMedical', $aParam);
    }

    public static function evaluationregionbusiness($aParam=array())
    {
        return self::post('cms', '/api/evaluationregion/getBusiness', $aParam);
    }

    public static function evaluationregionpublic($aParam=array())
    {
        return self::post('cms', '/api/evaluationregion/getPublic', $aParam);
    }

    public static function evaluationbadfactor($aParam=array())
    {
        return self::post('cms', '/api/Evaluationbadfactor/getBadfactor', $aParam);
    }

    public static function evaluationoutside($aParam=array())
    {
        return self::post('cms', '/api/Evaluationbadfactor/getOutSide', $aParam);
    }





    //装修标准-品牌配置
    public static function zxstandardIndex($aParam=array())
    {
        return self::post('cms', '/api/Evaluationzxstandard/getBrand', $aParam);
    }

    //装修标准-装修分析
    public static function getAnalysis($aParam=array())
    {
        return self::post('cms', '/api/Evaluationzxstandard/getAnalysis', $aParam);
    }

    //社区品质-整体规划
    public static function sqpzIndex($aParam=array())
    {
        return self::post('cms', '/api/Evaluationsqpz/getWhole', $aParam);
    }

    //社区品质-社区景观
    public static function sqpzScenery($aParam=array())
    {
        return self::post('cms', '/api/Evaluationsqpz/getScenery', $aParam);
    }

    //社区品质-建筑立面
    public static function sqpzBuild($aParam=array())
    {
        return self::post('cms', '/api/Evaluationsqpz/getBuild', $aParam);
    }

    //社区品质-公共部位
    public static function sqpzPublic($aParam=array())
    {
        return self::post('cms', '/api/Evaluationsqpz/getPublic', $aParam);
    }

    //社区品质-社区配套
    public static function sqpzConfig($aParam=array())
    {
        return self::post('cms', '/api/Evaluationsqpz/getConfig', $aParam);
    }

    //社区品质-车位情况
    public static function sqpzParking($aParam=array())
    {
        return self::post('cms', '/api/Evaluationsqpz/getParking', $aParam);
    }

    //物业服务-物业费用
    public static function wyfwIndex($aParam=array())
    {
        return self::post('cms', '/api/Evaluationwyfw/getWyfw', $aParam);
    }
    //物业服务-物业服务
    public static function wyfwService($aParam=array())
    {
        return self::post('cms', '/api/Evaluationwyfw/getService', $aParam);
    }


    //房价点评网首页评测报告
    public static function getEvaluation($cityCode){
        return self::post('cms', '/api/unit/getEvaluationList', array('cityCode' => $cityCode));
    }

    //房价点评网首页评级推荐
    public static function getPingJi($cityCode,$type){
        return self::post('cms', '/api/unit/getPinJiList', array('cityCode' => $cityCode,'type'=>$type));
    }

    public static function evaluationUnitName($eID)
    {
        return self::post('cms', '/api/evaluationunit/evaluationunitname', array('eID' => $eID));
    }

    public static function evaluationImage($aParam=array()) {
        return self::post('cms', '/api/evaluationimages', $aParam);
    }
}
