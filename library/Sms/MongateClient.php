<?php
class Sms_MongateClient implements Sms_ClientInf{
	private $oClient;
	public function __construct(){
		$aConfig = get_config('mongate', 'sms');
		$this->oClient = new mongatesoap($aConfig['sGateWay'], $aConfig['sUserName'], $aConfig['sPassword'], '*');
	}

	public function sendSMS($p_mCellPhone, $p_sContent, $p_iPriority=1, $p_iSerialID = 0){
		if(!is_array($p_mCellPhone)){
			$p_mCellPhone = array( 
					$p_mCellPhone 
			);
		}
		$sResult = $this->oClient->sendSmsNew($p_mCellPhone, $p_sContent);
		if(in_array($sResult, mongatesoap::$aSendMsgMsg) || in_array($sResult, mongatesoap::$aSystemMsg)){
			return -1;
		} else {
			return 0;
		}		
	}
	
	public function sendSMSNew($p_aParam, $iSerialID = 0){
	
		$sContent = '';
		if(isset($p_aParam['sMsg']) && '' != $p_aParam['sMsg']){
			$sContent = $p_aParam['sMsg'];
		}
		if('' != $p_aParam['sCellPhone']){
			return $this->sendSMS($p_aParam['sCellPhone'], $sContent);
		}
	}

	public function getBalance(){
		return $this->oClient->queryBalance();
	}

	public function getReport(){
		return $this->oClient->getStatusReportExEx();
	}
	
	public function getMOMessage(){
		//return $this->oClient->getMOMessageNew();
		return [];
	}
}