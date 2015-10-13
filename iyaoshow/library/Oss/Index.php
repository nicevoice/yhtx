<?php
/**
 * 加载sdk包以及错误代码包
 */
require_once 'sdk.class.php';

class Oss_Index
{
    private static $alioss = null;

    /**
     * 初始化
     */
    public static function getAliOss()
    {
        if (!self::$alioss) {
            self::$alioss = new ALIOSS();
            //设置是否打开curl调试模式
            self::$alioss->set_debug_mode(false);
        }
        return self::$alioss;
    }

    /**
     * 图片上传(通过路径上传)
     * @param $sFrom上传来源，对应OSS中的文件夹名
     * @return string
     */
    public static function uploadImage($sFrom)
    {
        $oss_sdk_service = self::getAliOss();
        $aImageType = explode('/',$_FILES['file']['type']);
        $sImageFix = $aImageType[1];
        $bucket = OSS_BUCKET;
        $folder = OSS_UPLOAD_PATH;

        $object = $folder .$sFrom.'/'. md5($_FILES['file']['name'].time()) .'.'.$sImageFix;
        $file_path = $_FILES['file']['tmp_name'];
        $options['Content-Type'] = $_FILES['file']['type'];

        $response = $oss_sdk_service->upload_file_by_file($bucket,$object,$file_path,$options);
        return $response->isOk() ? $object : '';
    }

    /**
     * 获取所有上传的文件信息
     *
     * @return array
     */
    protected static function getUploadFiles()
    {
        $aFiles      = $_FILES;
        $aMultiFiles = array();

        foreach ($aFiles as $sKey => $mFiles) {
            if (is_array($mFiles['name'])) {
                $iCnt = count($mFiles['name']);
                for ($i = 0; $i < $iCnt; ++$i) {
                    $aMultiFiles[] = array(
                        'key'      => $sKey . '_' . $i,
                        'name'     => $mFiles['name'][$i],
                        'tmp_name' => $mFiles['tmp_name'][$i],
                        'error'    => $mFiles['error'][$i],
                        'size'     => $mFiles['size'][$i]
                    );
                }
            } else {
                $aMultiFiles[] = array(
                    'key'      => $sKey,
                    'name'     => $mFiles['name'],
                    'tmp_name' => $mFiles['tmp_name'],
                    'error'    => $mFiles['error'],
                    'size'     => $mFiles['size']
                );
            }
        }

        return $aMultiFiles;
    }

}




