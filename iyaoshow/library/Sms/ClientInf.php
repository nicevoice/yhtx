<?php
interface Sms_ClientInf{

	/**
	 * 发送短信
	 * @param mix $p_mCellPhone
	 * @param string $p_sContent
	 * @param int $p_iPriority
	 * @param int $p_iSerialID
	 * @return int 状态
	 */
	function sendSMS($p_mCellPhone, $p_sContent, $p_iPriority=1, $p_iSerialID = 0);

	/**
	 * 获取短信(条)
	 * @return int
	 */
	function getBalance();
	
	//function getMO();

	/**
	 * 获取回执报告
	 * @return array 报告结构
	 */
	function getReport();
}