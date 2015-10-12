<?php
class Sms_XuanwuClient extends Sms_Abstract {
	
	private $oClient;
	
	public function __construct(){
		$aConfig = get_config('xuanwu', 'sms');
		$this->oClient = new xuanwusoap($aConfig['sGateWay'], $aConfig['sUserName'], $aConfig['sPassword']);
	}
	
	public function sendSMS($p_mCellPhone, $p_sContent,$p_iPriority=1, $p_iSerialID = 0){
		if(!is_array($p_mCellPhone)){
			$p_mCellPhone = array( 
					$p_mCellPhone 
			);
		}
		$iResult = $this->oClient->postmass($p_mCellPhone, $p_sContent);
		var_dump($iResult);
		return $iResult == 0 ? 0 : -1;
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
		return $this->oClient->getAccountInfo();
	}
	
	public function getReport(){
		$iDays = 10;
		$iPagesize = 10;
		return $this->oClient->getReport($iDays, $iPagesize);
	}
	
	public function getMOMessage(){
		return $this->oClient->getMOMessageNew();
	}
}