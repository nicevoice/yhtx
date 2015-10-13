<?php
/**
 * Created by PhpStorm.
 * User: yaobiqing
 * Date: 15/4/8
 * Time: 上午10:01
 */

/**
 * Class Sms_Factory
 * 测试用例
public function testSmsAction()
{
$smsClient = new Sms_ItissmClient('http://yes.itissm.com/api/MsgSend.asmx?WSDL', 'shfwxs', 'shfwxs588', 78);
$sms = new Sms_Factory($smsClient);
var_dump($sms->sendMsg(['sMsg' => '测试短信【克而瑞】', 'sCellPhone' => ['18964842042']]));
}
 */
class Sms_Factory
{

    private $oSms = NULL;

    public function __construct($oSms)
    {
        $this->oSms = $oSms;
    }

    public function sendMsg($p_aParam)
    {
        $sContent = '';
        if(isset($p_aParam['sMsg']) && '' != $p_aParam['sMsg']){
            $sContent = $p_aParam['sMsg'];
        }
        if(!empty($p_aParam['sCellPhone'])){
            return $this->oSms->sendSMS($p_aParam['sCellPhone'], $sContent);
        }
    }

    public function getBalance()
    {
        $this->oSms->getBalance();
    }

    public function getReport()
    {
        $this->oSms->getReport();
    }

}