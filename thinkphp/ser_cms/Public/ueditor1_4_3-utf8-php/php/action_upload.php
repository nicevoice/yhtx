<?php
/**
 * 上传附件和上传视频
 * User: Jinqn
 * Date: 14-04-09
 * Time: 上午10:17
 */
include "Uploader.class.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/ThinkPHP/Library/Vendor/Qiniu/autoload.php';
$qiniu_config = include 'qiniu.config.php';
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

/* 上传配置 */
$base64 = "upload";
switch (htmlspecialchars($_GET['action'])) {
    case 'uploadimage':
        $config = array(
            "pathFormat" => $CONFIG['imagePathFormat'],
            "maxSize" => $CONFIG['imageMaxSize'],
            "allowFiles" => $CONFIG['imageAllowFiles']
        );
        $fieldName = $CONFIG['imageFieldName'];
        break;
    case 'uploadscrawl':
        $config = array(
            "pathFormat" => $CONFIG['scrawlPathFormat'],
            "maxSize" => $CONFIG['scrawlMaxSize'],
            "allowFiles" => $CONFIG['scrawlAllowFiles'],
            "oriName" => "scrawl.png"
        );
        $fieldName = $CONFIG['scrawlFieldName'];
        $base64 = "base64";
        break;
    case 'uploadvideo':
        $config = array(
            "pathFormat" => $CONFIG['videoPathFormat'],
            "maxSize" => $CONFIG['videoMaxSize'],
            "allowFiles" => $CONFIG['videoAllowFiles']
        );
        $fieldName = $CONFIG['videoFieldName'];
        break;
    case 'uploadfile':
    default:
        $config = array(
            "pathFormat" => $CONFIG['filePathFormat'],
            "maxSize" => $CONFIG['fileMaxSize'],
            "allowFiles" => $CONFIG['fileAllowFiles']
        );
        $fieldName = $CONFIG['fileFieldName'];
        break;
}

/* 生成上传实例对象并完成上传 */
$up = new Uploader($fieldName, $config, $base64);

/**
 * 得到上传文件所对应的各个参数,数组结构
 * array(
 *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
 *     "url" => "",            //返回的地址
 *     "title" => "",          //新文件名
 *     "original" => "",       //原始文件名
 *     "type" => ""            //文件类型
 *     "size" => "",           //文件大小
 * )
 */

/* 返回数据 */
$res = $up->getFileInfo();
if($res['state'] == 'SUCCESS'){
    //传七牛
    $auth = new Auth($qiniu_config['AccessKey'],$qiniu_config['SecretKey']);
    $token = $auth->uploadToken($qiniu_config['bucket']);
    $uploadMgr = new UploadManager();
    list($ret, $err) = $uploadMgr->putFile($token,null,$_SERVER['DOCUMENT_ROOT'] . $res['url']);
    if ($err !== null) {
        return false;
    } else {
        unlink($_SERVER['DOCUMENT_ROOT'].$res['url']);
        $res['url'] = $qiniu_config['url'] . $ret['key'];
    }
}
return json_encode($res);


