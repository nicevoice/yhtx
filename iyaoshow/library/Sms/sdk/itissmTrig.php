<?php
/**
 * Created by PhpStorm.
 * User: yaobiqing
 * Date: 15/4/8
 * Time: 上午11:10
 */

class Sms_sdk_itissmTrig
{

    private $sGateWay;
    private $sUserName;
    private $sPassword;
    private $iChannel;
    private $encoding = 'UTF-8';
    private $soap;

    /**
     * @var array
     *
     */
    static $aSendMsgMsg = [
        '-1' => '提交接口错误',
        '-3' => '用户名密码错误或者用户无效',
        '-4' => '短信内容和备案的模板不一样',
        '-5' => '签名不正确(格式为:XXXX【签名内容】)注意,短信内容最后一 个字符必须是】',
        '-7' => '余额不足',
        '-8' => '通道错误',
        '-9' => '无效号码',
        '-10' => '签名内容不符合长度',
        '-11' => '用户有效期过期',
        '-12' => '黑名单',
        '-13' => '语音验证码的 Amount 参数必须是整形字符串',
        '-14' => '语音验证码的内容只能为数字',
        '-15' => '语音验证码的内容最长为 6 位',
        '-16' => '余额请求过于频繁，5 秒才能取余额一次',
    ];

    public function __construct($sGateWay, $sUserName, $sPassword,$iChannel){

        $this->sGateWay = $sGateWay;
        $this->sUserName = $sUserName;
        $this->sPassword = $sPassword;
        $this->iChannel = $iChannel;

        $this->soap = new SoapClient($sGateWay);
    }

    /*
     *  短信息发送接口（相同内容群发）
     *  aPszMobis 目标号码 数组，最大100个号码。
     *  sPszMsg 短信内容， 内容长度不大于350个汉字
     *  iChannel
     *  return 信息编号 如：1362120689344 //批次号
     */
    public function sendSmsNew($aPszMobis, $sPszMsg){

        $parma = array();
        $sMobileList  = $aPszMobis;
        $parma['userCode']     = $this->sUserName;
        $parma['userPass']   = $this->sPassword;
        $parma['DesNo']   = $sMobileList;
        $parma['Msg']     = $sPszMsg;
        $parma['Channel'] = $this->iChannel;
        $sResult = $this->soap->__soapCall('SendMsg', ['parameters' => $parma]);
        return $sResult;

    }


    /*
     *  获取状态报告接口
     *  返回值：
     *  返回格式：
     *  批次号,手机号,状态值|
     *	例如：
     * 0278387837488834,13900000000,DELIVRD| 0278387837488834,13900000001,DELIVRD|
     * 0278387837488834,13900000002,DELIVRD| 0278387837488834,13900000003,DELIVRD|
     * 0278387837488834,13900000004,UNDELIVRD|
     *
     */
    public function getReport(){


        $parma = array();
        $parma['userCode']   = $this->sUserName;
        $parma['userPass'] = $this->sPassword;
        $sResult = $this->soap->__soapCall('GetReport',$parma);

        return $sResult;
    }

    /*
     * 查询余额接口
     */
    public function queryBalance(){

        $parma = array();
        $parma['userCode']   = $this->sUserName;
        $parma['userPass'] = $this->sPassword;
        $sResult = $this->soap->__soapCall('GetBalance',$parma);

        return $sResult;
    }


}