<?php
/**
 * Created by PhpStorm.
 * User: yaobiqing
 * Date: 15/4/8
 * Time: 上午10:59
 */
class Sms_ItissmTrigClient extends Sms_Abstract
{
    private $_oClient;

    public function __construct($p_sUrl, $p_sUserName, $p_sPassword, $p_iChannel){
        $this->_oClient = new Sms_sdk_itissmTrig($p_sUrl, $p_sUserName, $p_sPassword, $p_iChannel);
    }

    public function sendSMS($p_mCellPhone, $p_sContent, $p_iPriority=1, $p_iSerialID = 0){
        $result = $this->_oClient->sendSmsNew($p_mCellPhone, $p_sContent);
        //var_dump($result);exit;
        $aTmp = explode(':', $result->SendMsgResult);
        if (!empty($aTmp)) {
            if ($aTmp[0] > 0) {
                return ['iCode' => 0, 'sMsg' => $aTmp[0]];
            } else {
                return ['iCode' => $aTmp[0], 'sMsg' => Sms_sdk_itissmTrig::$aSendMsgMsg[$aTmp[0]]];
            }
        }
        return ['iCode' => -1, 'sMsg' => Sms_sdk_itissmTrig::$aSendMsgMsg['-1']];


    }



    public function getBalance(){
        return $this->_oClient->queryBalance();
    }


    public function getReport(){
        return $this->_oClient->getReport();
    }

    public function sendSMSNew($p_aParam, $iSerialID = 0)
    {
        // TODO: Implement sendSMSNew() method.
    }

    public function getMOMessage()
    {
        // TODO: Implement getMOMessage() method.
    }
}