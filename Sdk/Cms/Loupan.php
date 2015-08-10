<?php
/**
 * @author pancke
 */
class Sdk_Cms_Loupan extends Sdk_Base
{
    public static function getChart($buildingID)
    {
        return self::post('cms', '/api/Loupan/getchart', ['buildingID' => $buildingID]);
    }

}