<?php
/**
 * Created by PhpStorm.
 * User: yaobiqing
 * Date: 15/4/8
 * Time: 上午10:59
 */
class Sms_ItissmClient extends Sms_Abstract
{
    private $_oClient;

    public function __construct($p_sUrl, $p_sUserName, $p_sPassword, $p_iChannel){
        $this->_oClient = new Sms_sdk_itissm($p_sUrl, $p_sUserName, $p_sPassword, $p_iChannel);
    }

    public function sendSMS($p_mCellPhone, $p_sContent, $p_iPriority=1, $p_iSerialID = 0){
        if(!is_array($p_mCellPhone)){
            $p_mCellPhone = array(
                $p_mCellPhone
            );
        }

        $result = $this->_oClient->sendSmsNew($p_mCellPhone, $p_sContent);
        $aTmp = explode(':', $result->sendMesResult);
        if (!empty($aTmp)) {
            if ($aTmp[0] > 0) {
                return ['iCode' => 0, 'sMsg' => $aTmp[0]];
            } else {
                return ['iCode' => $aTmp[0], 'sMsg' => Sms_sdk_itissm::$aSendMsgMsg[$aTmp[0]]];
            }
        }
        return ['iCode' => -1, 'sMsg' => Sms_sdk_itissm::$aSendMsgMsg['-1']];


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