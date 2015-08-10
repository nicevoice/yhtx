<?php
/**
 * @author pancke
 */
class Sdk_Cms_Analyst extends Sdk_Base
{

	public static function getDetail($p_iID) {
		return self::post('cms', 'mapi/analyst/detail', array('analystID' => $p_iID) );        
    }

    public static function getComment($p_aParam) {
    	return self::post('cms', 'mapi/analyst/comments', $p_aParam );        
    }

    public static function sendQuestion($p_aParam) {
    	return self::post('cms', 'mapi/analyst/sendquestion', $p_aParam );        
    }

    public static function getEvaluationList($p_aParam) {
    	return self::post('cms', 'mapi/evaluation/list', $p_aParam );     	
    }

    //房价点评网首页分析师
    public static function getAnalyst($cityCode){
        return self::post('cms', 'api/unit/getAnalyst', array('cityCode' => $cityCode));
    }

    //房价点评网首页分析师点评
    public static function getAnalystDP($cityName){
        return self::post('cms', 'api/unit/getAnalystDP', array('cityName' => $cityName));
    }
}