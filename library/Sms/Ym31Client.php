<?php
class Sms_Ym31Client implements Sms_CientInf{
	function __construct($p_sGateWay, $p_sSN, $p_sPassword){
		$this->oClient = new Client($p_sGateWay, $p_sSN, $p_sPassword, '840104', false, false, false, false, 2, 10);
		$this->oClient->setOutgoingEncoding('UTF-8');
		$this->oClient->login();
	}

	function sendSMS($p_mCellPhone, $p_sContent, $p_iPriority = 1, $p_iSerialID = 0){
		if(!is_array($p_mCellPhone)){
			$p_mCellPhone = array( 
					$p_mCellPhone 
			);
		}
		return $this->oClient->sendSMS($p_mCellPhone, $p_sContent, '', '', 'utf-8', $p_iPriority, $p_iSerialID);
	}

	function getBalance(){
		return $this->oClient->getBalance();
	}

	function getReport(){
		return $this->oClient->getReport();
	}
	
	function getMO(){
		return $this->oClient->getMO();
	}
}