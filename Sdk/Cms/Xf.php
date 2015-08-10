<?php
/**
 * @author pancke
 */
class Sdk_Cms_Xf extends Sdk_Base
{

	public static function getXfList($p_aParam) {
		return self::post('cms', 'mapi/newbuilding/list', $p_aParam);        
    }

    public static function getFilter($p_iCityID) {
		return self::post('cms', 'mapi/newbuilding/filter', array('cityID'=>$p_iCityID));
    }    

    public static function getFeature($p_iCityID) {
    	return self::post('cms', 'mapi/newbuilding/filterfeatures', array('cityID'=>$p_iCityID));
    }

    public static function getDetail($p_iID) {
    	return self::post('cms', 'mapi/newbuilding/detail', array('buildingID'=>$p_iID));
    }

    public static function getImage($p_iID) {
    	return self::post('cms', 'mapi/newbuilding/buildimages', array('buildingID'=>$p_iID));	
    }

    public static function getHouseType($p_iID) {
    	return self::post('cms', 'mapi/newbuilding/housetypedetail', array('buildingID'=>$p_iID));
    }

    public static function getHouseUnit($p_iID, $p_sType='A') {
    	return self::post('cms', 'mapi/newbuilding/getunitinfo', array('buildingID'=>$p_iID, 'houseTypeCode'=>$p_sType));
    }

    public static function getLoan($p_iID, $p_sRoomId='') {
		return self::post('cms', 'mapi/newbuilding/getloaninfo', array('buildingID'=>$p_iID, 'roomID'=>$p_sRoomId));
    }

    public static function getAnalyst($p_iID) {
    	return self::post('cms', 'mapi/newbuilding/getanalystlist', array('buildingID'=>$p_iID));    	
    }

    public static function getGuide($p_aParam) {
    	return self::post('cms', 'mapi/newbuilding/sellguidelist', $p_aParam);
    }

    public static function getComment($p_aParam) {
    	return self::post('cms', 'mapi/newbuilding/remarklist', $p_aParam, 0);
    }

    public static function addComment($p_aParam) {
    	return self::post('cms', 'mapi/newbuilding/commitremark', $p_aParam);    	
    }

    public static function addDreamer($p_aParam) {
        return self::post('cms', 'mapi/idea/commitbuilding', $p_aParam);       
    }

}