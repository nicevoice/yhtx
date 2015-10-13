<?php
abstract class Sms_Abstract{
	
	abstract public function sendSMS($p_mCellPhone, $p_sContent, $p_iPriority=1, $p_iSerialID = 0);
	abstract public function sendSMSNew($p_aParam, $iSerialID = 0);
	abstract public function getBalance();
	abstract public function getReport();
	abstract public function getMOMessage();
	
	 
	protected function curlPost($url, $data = array(), $debug = 0, $timeout = 5,$cacert = ''){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	
		curl_setopt($ch, CURLOPT_VERBOSE, $debug);
		
		if($cacert){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   // 只信任CA颁布的证书
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 检查证书中是否设置域名
			
			curl_setopt($ch, CURLOPT_SSLCERT, $cacert); // CA根证书（用来验证的网站证书是否是CA颁布）
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名，并且是否与提供的主机名匹配
		}
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); //避免data数据过长问题
	
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: text/xml; charset="GBK"',
		'Content-Length: ' . strlen($data))
		);
	
		$ret = curl_exec($ch);
	
		curl_close($ch);
		if ($debug) {
			var_dump($ret);
		}
		return $ret;
	}
	
	protected function getSmsMsg($templete, array $trans){
		return strtr ( $templete ,  $trans );
		//if(util_string::chkStrLength($sMsg, 10, 100)){
	}
	
	protected function addSignature($p_sMsg, $p_iBID){
		switch($p_iBID){
			default:
				$sSig = '【平安好房】';
				break;
		}
		return $sSig . $p_sMsg;
	}
}