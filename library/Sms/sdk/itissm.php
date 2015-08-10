<?php
/**
 * Created by PhpStorm.
 * User: yaobiqing
 * Date: 15/4/8
 * Time: 上午11:10
 */

class Sms_sdk_itissm
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
        '-1' => '应用程序异常',
        '-3' => '用户名密码错误或者用户无效',
        '-5' => '签名不正确(格式为:XXXX【签名内容】)注意,短信内容最后一 个字符必须是】',
        '-6' => '含有关键字 keyWords',
        '-7' => '余额不足',
        '-8' => '没有可用通道,或不在时间范围内',
        '-9' => '发送号码一次不能超过 1000 个',
        '-10' => '号码数量大于允许上限(不设置上限时,不可超过 1000)',
        '-11' => '号码数量小于允许下限',
        '-12' => '模板不匹配',
        '-13' => 'Invalid Ip ip 绑定用户,未绑定该 ip',
        '-14' => '用户黑名单',
        '-15' => '系统黑名单',
        '-16' => '号码格式错误',
        '-17' => '无效号码(格式正常,可不是正确的电话号码,如 12345456765)',
        '-18' => '没有设置用户的固定下发扩展号,不能自定义扩展',
        '-19' => '强制模板通道,不能使用个性化接口',
        '-20' => '包含非法字符',
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
    public function sendSmsNew(array $aPszMobis, $sPszMsg){

        $parma = array();
        $sMobileList  = implode(",", $aPszMobis);
        $parma['userCode']     = $this->sUserName;
        $parma['userPass']   = $this->sPassword;
        $parma['DesNo']   = $sMobileList;
        $parma['Msg']     = $sPszMsg;
        $parma['Channel'] = $this->iChannel;

        $sResult = $this->soap->__soapCall('SendMes', ['parameters' => $parma]);

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