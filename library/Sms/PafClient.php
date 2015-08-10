<?php
class Sms_PafClient implements Sms_ClientInf 
{
	private $_sWebServerIP;

	private $_sUserName;

	private $_sPassword;

	private $_sGateWay;

	function __construct($p_sWebServerIP, $p_sUserName, $p_sPassword, $p_sGateWay){
		$this->_sWebServerIP = $p_sWebServerIP;
		$this->_sUserName = $p_sUserName;
		$this->_sPassword = $p_sPassword;
		$this->_sGateWay = $p_sGateWay;
	}

	function sendSMS($p_mCellPhone, $p_sContent, $p_iPriority=1, $p_iSerialID = 0){
		$p_sContent = iconv('utf-8', 'gbk', $p_sContent);
		if(is_array($p_mCellPhone)){
			return $this->Msg_PostBlockNumber($p_mCellPhone, $p_sContent);
		}else{
			return $this->Msg_PostSingle($p_mCellPhone, $p_sContent);
		}
	}

	function getBalance(){
		return $this->Msg_GetRemainFee();
	}

	function getReport(){
		return array();
	}
	
	//发送单条信息
	function Msg_PostSingle($to, $text){
		$maxMessageTextLen = 0;
		switch($this->_Msg_MobileNumberType($to)){
			case 1:
				$maxMessageTextLen = 70; //手机信息是70个字(包含后缀)
				break;
			case 0:
				$maxMessageTextLen = 45; //小灵通是45个字(包含后缀)
				break;
			default:
				return -15; //号码出错
		}
		
		if($this->_Msg_strlen($text) > $maxMessageTextLen) //判断不确切,因为实际的长度必须减去后缀
			return -5;
		
		return $this->_Msg_FinalPostMessage($to, $text);
	}

	function Msg_PostBlockNumber($numberArray, $text){
		$count = count($numberArray);
		if($count > 100)
			return -5; //群发的号码数量太多
		

		$mobileType = $this->_Msg_MobileNumberType($numberArray[0]);
		$to = $numberArray[0];
		for($i = 1; $i < $count; $i++){
			if($mobileType != $this->_Msg_MobileNumberType($numberArray[$i]))
				return -5; //混合的号码
			$to = $to . "+" . $numberArray[$i];
		}
		
		$maxMessageTextLen = 0;
		switch($mobileType){
			case 1:
				$maxMessageTextLen = 70; //手机信息是70个字(包含后缀)
				break;
			case 0:
				$maxMessageTextLen = 45; //小灵通是45个字(包含后缀)
				break;
			default:
				return -15; //号码出错
		}
		
		return $this->_Msg_FinalPostMessage($to, $text);
	}
	
	//获取余额
	function Msg_GetRemainFee(){
		$result = $this->_Msg_SendSoapRequest("getRemainFee", "getRemainFeeReturn");
		if($result == "ERROR")
			return -6; //用户名密码出错
		if($result == "")
			return -1;
		
		return intval($result);
	}

	private function _Msg_FinalPostMessage($to, $text){
		$textUrl = urlencode($text); //必须编成URL码
		$POST_STRING = "http://%s/cgi-bin/sendsms?";
		$POST_STRING .= "username=%s&password=%s&to=%s&text=%s&subid=%s&msgtype=1";
		$httpRequest = sprintf($POST_STRING, $this->_sGateWay, $this->_sUserName, $this->_sPassword, $to, $textUrl, 0);
		if(file_get_contents($httpRequest) != "0") //发送信息
			return -99;
		return 0; //发送成功
	}

	private function _Msg_strlen($str){
		$count = 0;
		$i = 0;
		$len = strlen($str);
		while($i < $len){
			if(ord($str[$i]) > 128)
				$i += 2;
			else
				$i += 1;
			$count++;
		}
		return $count;
	}

	/**
 * 发送短信的函数，发送成功则返回真。其中的$isPhoneNumber表示短信是否发送到小灵通。
 * 如果短信的长度超过规定的字数，信息将分段发送
 * 如果号码的个数超过100个，则不予发送
 * @param unknown $mobileNumber
 * @return number
 */
	private function _Msg_MobileNumberType($mobileNumber){
		if(($mobileNumber[0] == '1') && (strlen($mobileNumber) == 11))
			return 1; //手机号码
		if(($mobileNumber[0] == '0') && ((strlen($mobileNumber) == 10) || (strlen($mobileNumber) == 12)))
			return 0; //小灵通号码
		return -1; //无效号码
	}

	private function _Msg_SendSoapRequest($soapAction, $returnTag){
		$HTTP_HEADER = "SOAPAction: \"http://%s/services/EsmsService/%s\"\r\n";
		$HTTP_HEADER .= "User-Agent: SOAP Toolkit 3.0\r\n";
		$HTTP_HEADER .= "Host: %s:8080\r\n";
		$HTTP_HEADER .= "Content-Length: %d\r\n";
		$HTTP_HEADER .= "Connection: Keep-Alive\r\n";
		$HTTP_HEADER .= "Pragma: no-cache\r\n\r\n";
		$HTTP_REQUEST_DATA = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>";
		$HTTP_REQUEST_DATA .= "<SOAP-ENV:Envelope SOAP-ENV:encodingStyle=\"\" ";
		$HTTP_REQUEST_DATA .= "xmlns:SOAPSDK1=\"http://www.w3.org/2001/XMLSchema\" ";
		$HTTP_REQUEST_DATA .= "xmlns:SOAPSDK2=\"http://www.w3.org/2001/XMLSchema-instance\" ";
		$HTTP_REQUEST_DATA .= "xmlns:SOAPSDK3=\"http://schemas.xmlsoap.org/soap/encoding/\" ";
		$HTTP_REQUEST_DATA .= "xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\">";
		$HTTP_REQUEST_DATA .= "<SOAP-ENV:Body SOAP-ENV:encodingStyle=\"\">";
		$HTTP_REQUEST_DATA .= "<%s SOAP-ENV:encodingStyle=\"\">"; //soap请求动作
		$HTTP_REQUEST_DATA .= "<n1 SOAP-ENV:encodingStyle=\"\">%s</n1>"; //用户名
		$HTTP_REQUEST_DATA .= "<n2 SOAP-ENV:encodingStyle=\"\">%s</n2>"; //密码
		$HTTP_REQUEST_DATA .= "</%s>"; //soap请求动作
		$HTTP_REQUEST_DATA .= "</SOAP-ENV:Body>";
		$HTTP_REQUEST_DATA .= "</SOAP-ENV:Envelope>";
		$soapError = "ERROR";
		
		//HTTP请求的数据
		$requestData = sprintf($HTTP_REQUEST_DATA, $soapAction, $this->_sUserName, $this->_sPassword, $soapAction);
		//HTTP请求头
		$httpHeader = sprintf($HTTP_HEADER, $this->_sWebServerIP, $soapAction, $this->_sWebServerIP, strlen($requestData));
		$url = "POST /services/EsmsService?wsdl HTTP/1.1\r\n";
		
		$sock = fsockopen($this->_sWebServerIP, 8080);
		
		if($sock == 0){
			return $soapError;
		}
		fputs($sock, $url . $httpHeader . $requestData); //发送HTTP请求到服务器
		

		//跳过HTTP的文件头
		for($i = 0; $i < 7; $i++){
			fgets($sock, 100);
		}
		
		$tagBegin = sprintf("<%s", $returnTag);
		$tagEnd = sprintf("</%s>", $returnTag);
		
		//获取XML字符串
		$buffer = "";
		$segGets = fgets($sock, 4096 * 3);
		while(strpos($segGets, $tagEnd) == FALSE){
			$buffer .= $segGets;
			$segGets = fgets($sock, 4096 * 3);
			if($segGets == FALSE)
				break;
		}
		fclose($sock);
		$buffer .= $segGets;
		
		$beginPos = strpos($buffer, $tagBegin);
		if($beginPos == FALSE)
			return "";
		
		$beginPos = strpos($buffer, ">", $beginPos + strlen($tagBegin)) + 1;
		$endPos = strPos($buffer, $tagEnd, $beginPos);
		if($endPos == FALSE)
			return "";
		
		return substr($buffer, $beginPos, $endPos - $beginPos);
	}
}