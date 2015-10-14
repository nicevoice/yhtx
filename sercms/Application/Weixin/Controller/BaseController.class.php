<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/8/5
 * Time: 10:54
 */

namespace Weixin\Controller;


use Think\Controller;

class BaseController extends Controller{

    protected $weiOption;
    protected $weiObj;

    public function _initialize(){
        $config = C('WEI_XIN_CONFIG');
        $this->weiOption = array(
 			'token'=>$config['Token'], //填写你设定的key
 			'encodingaeskey'=>$config['EncodingAESKey'], //填写加密用的EncodingAESKey
 			'appid'=>$config['AppID'], //填写高级调用功能的app id
 			'appsecret'=>$config['AppSecret'], //填写高级调用功能的密钥
            'logcallback' => 'weixin_log'
 		);
        vendor('wechat', VENDOR_PATH . 'Wechat/', $ext='.class.php');
        $this->weiObj = new \Wechat($this->weiOption);
    }
}