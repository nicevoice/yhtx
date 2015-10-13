<?php
class Sms_Pa95511Client extends Sms_Abstract 
{
	private $sGateWay;
	private $sSendSeriesID;
	private $sSenderID;
	private $sSendUser;
	private $sTranCode;
	private $cacert;
	private $debug;
	private $xml = '<?xml version="1.0" encoding="GBK"?>
					<REQUEST>
						<tranCode>%sTranCode%</tranCode>
						<SENDERID>%sSenderID%</SENDERID>
					<ELEMENTS>%sElements%</ELEMENTS></REQUEST>';
	
	private $element = '<ELEMENT>
						<SENDUSER>%sSendUser%</SENDUSER>
						<SENDSERIESID>%sSendSeriesID%</SENDSERIESID>
						<REQUESTID>%iMsgID%</REQUESTID>
						<TEMPLATEID>%sTemplateId%</TEMPLATEID>
						<MOBILENO>%sMobile%</MOBILENO>
			             %aParams%
						</ELEMENT>';
	
	public function __construct(){
		$aConfig = get_config('95511', 'sms');
		$this->sGateWay      = $aConfig['sGateWay'];
		$this->sSendSeriesID = $aConfig['sSendSeriesID'];
		$this->sSendUser     = $aConfig['sSendUser'];
		$this->sTranCode = $aConfig['sTranCode'];
		$this->sSenderID = $aConfig['sSenderID'];
		$this->cacert = $aConfig['sPemPath'];
		$this->debug = $aConfig['isDebug'];
		
		$aReplaces = array('%sTranCode%'=>$this->sTranCode,'%sSenderID%'=>$this->sSenderID);
		$this->xml = strtr ($this->xml , $aReplaces);		
	}
	
	public function sendSMS($p_mCellPhone, $p_sContent){
		
		return -1;
	}
		
	public function sendSMSNew($p_aParam, $iSerialID){
		$aReplaces = array();
		$aReplaces['%sSendUser%'] = $this->sSendUser;
		$aReplaces['%sSendSeriesID%'] = $this->sSendSeriesID;
		$aReplaces['%iMsgID%'] = $iSerialID;
		$aReplaces['%sTemplateId%'] = $p_aParam['sTemplateId'];
		$aReplaces['%sMobile%'] = $p_aParam['sCellPhone'];
		$sParams = '';
		foreach($p_aParam['aReplace'] as $key => $value){
			$sParams .= "<{$key}>$value</{$key}>"; 
		}
		$aReplaces['%aParams%'] = $sParams;
		
		$sElements = strtr($this->element ,$aReplaces);
		
		$sMsg = strtr($this->xml ,array('%sElements%'=>$sElements));
		echo $sMsg;
		$sResult = $this->curlPost($this->sGateWay , $sMsg, $this->debug ,5 , $this->cacert);
		
	    if (strpos($sResult, "<SUCCESSLIST><item>")) {
            return 0;
        } else {
            return -1;
        }
	}
	
	public function getBalance(){
		return 0;
	}
	
	public function getReport(){
		return [];
	}
	public function getMOMessage(){
		return [];
	}
}