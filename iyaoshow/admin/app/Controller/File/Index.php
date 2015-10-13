<?php

/**
 * Created by PhpStorm.
 * User: yaobiqing
 * Date: 14/12/24
 * Time: 下午2:32
 */
class Controller_File_Index extends Yaf_Controller
{
    /**
     * 文件上传
     *
     * @return bool
     */
    public function uploadAction()
    {
    	header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With');
        $sFrom = $this->getParam('from');//上传来源，对应oss中的二级文件夹名
        $aData = Oss_Index::uploadImage($sFrom);
        if(empty($aData)) {
            return $this->showMsg('图片上传失败',false);
        }
        return $this->showMsg($aData,true);
    }
}