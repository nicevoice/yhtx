<?php
class mongatesoap {
	private $sGateWay;
	private $sUserName;
	private $sPassword;
	private $soap;
	private $pszSubPort;
	private $encoding = 'UTF-8';
	private $namespace;
	static $aSystemMsg = [
		'-999' => '服务器内部错误',
		'-10001' => '用户登陆不成功(帐号不存在/停用/密码错误)',
		'-10002' => '提交格式不正确',
		'-10003' => '用户余额不足',
		'-10004' => '手机号码不正确',
		'-10005' => '计费用户帐号错误',
		'-10006' => '计费用户密码错',
		'-10007' => '账号已经被停用',
		'-10008' => '账号类型不支持该功能',
		'-10009' => '其它错误',
		'-10010' => '企业代码不正确',
		'-10011' => '信息内容超长',
		'-10012' => '不能发送联通号码',
		'-10013' => '操作员权限不够',
		'-10014' => '费率代码不正确',
		'-10015' => '服务器繁忙',
		'-10016' => '企业权限不够',
		'-10017' => '此时间段不允许发送',
		'-10018' => '经销商用户名或密码错',
		'-10019' => '手机列表或规则错误',
		'-10021' => '没有开停户权限',
		'-10022' => '没有转换用户类型的权限',
		'-10023' => '没有修改用户所属经销商的权限',
		'-10024' => '经销商用户名或密码错',
		'-10025' => '操作员登陆名或密码错误',
		'-10026' => '操作员所充值的用户不存在',
		'-10027' => '操作员没有充值商务版的权限',
		'-10028' => '该用户没有转正不能充值',
		'-10029' => '此用户没有权限从此通道发送信息(用户没有绑定该性质的通道，比如：用户发了小灵通的号码)',
		'-10030' => '不能发送移动号码',
		'-10031' => '手机号码(段)非法',
		'-10032' => '用户使用的费率代码错误',
		'-10033' => '非法关键词',
	];
	
	static $aSendMsgMsg = [
		'-1' => '参数为空',
		'-2' => '电话号码个数超过100',
		'-10' => '申请缓存空间失败',
		'-11' => '电话号码中有非数字字符',
		'-12' => '有异常电话号码',
		'-13' => '电话号码个数与实际个数不相等',
		'-14' => '实际号码个数超过100',
		'-101' => '发送消息等待超时',
		'-102' => '发送或接收消息失败',
		'-103' => '接收消息超时',
		'-200' => '其他错误',
		'-999' => 'web服务器内部错误',
	];
	
	public function __construct($sGateWay, $sUserName, $sPassword,$pszSubPort){
		
		$this->sGateWay = $sGateWay;
		$this->sUserName = $sUserName;
		$this->sPassword = $sPassword;
		$this->pszSubPort = $pszSubPort;
		
		$this->soap = new nusoap_client($sGateWay, false);
		//$this->soap->soap_defencoding = $this->encoding;
		//$this->soap->decode_utf8 = false;
	}
	
	/*
	 *  短信息发送接口（相同内容群发）
	 *  aPszMobis 目标号码 数组，最大100个号码。
	 *  sPszMsg 短信内容， 内容长度不大于350个汉字
	 *  return 信息编号 如：-8485643440204283743或1485643440204283743
	 */
	public function sendSmsNew(array $aPszMobis, $sPszMsg){
		
		$parma = array();
		$iMobileTotal = count($aPszMobis);
		$sMobileList  = implode(",", $aPszMobis); 
		$parma['userId']     = $this->sUserName;
		$parma['password']   = $this->sPassword;
		$parma['pszSubPort'] = $this->pszSubPort;
		$parma['pszMobis']   = $sMobileList;
		$parma['pszMsg']     = $sPszMsg;
		$parma['iMobiCount'] = $iMobileTotal;
			
		$sResult = $this->soap->call('MongateCsSpSendSmsNew',$parma);
		
		return $sResult;
		
	}
	
	/*
	 *  短信息发送接口（相同内容群发，可自定义流水号）
	 *  aPszMobis 目标号码 数组，最大100个号码。
	 *  sPszMsg 短信内容， 内容长度不大于350个汉字
	 *  $sMsgId 一个8字节64位的大整型（INT64），格式化成的字符串
	 */
	public function sendSubmit($sPszMobis , $sPszMsg,$sMsgId){
		$parma = array();
		$iMobileTotal = count($sPszMobis);
		$sMobileList  = implode(",", $sPszMobis);
		$parma['userId']     = $this->sUserName;
		$parma['password']   = $this->sPassword;
		$parma['pszSubPort'] = $this->pszSubPort;
		$parma['pszMobis']   = $sMobileList;
		$parma['pszMsg']     = $sPszMsg;
		$parma['iMobiCount'] = $iMobileTotal;
		$parma['MsgId'] = $sMsgId;
		
		$sResult = $this->soap->call('MongateSendSubmit',$parma);
		
		return $sResult;
	}
	
	/*
	 * 短信息发送接口（不同内容群发，可自定义不同流水号，自定义不同扩展号）
	 * $aMultix_mt  批量短信请求包。该字段中包含N个短信包结构体。
	 * 结构体 array('sMsgId'=>'','mobile'=>'','sPszMsg');
	 */
	public function multixSend($aMultix_mt){
		$aTmpMt = array();
		if(is_array($aMultix_mt)){
			foreach ($aMultix_mt as $mt){
				$tmp = $mt['sMsgId'] . '|' . $this->pszSubPort . '|' . $mt['mobile'] .'|' . $mt['sPszMsg'];
				$aTmpMt[] = $tmp;
			}
		}
		$sMultix_mt = implode(',', $aTmpMt);
		$parma = array();
		$parma['userId']   = $this->sUserName;
		$parma['password'] = $this->sPassword;
		$parma['multixmt'] = $sMultix_mt;
		
		$sResult = $this->soap->call('MongateMULTIXSend',$parma);
		
		return $sResult;
	}
	
	/*
	 * 获取上行信息接口
	 * 接收信息内容列表(最高维数为500)格式说明：
	 * 日期,时间,上行源号码,上行目标通道号,*,信息内容
	 * 例如：2008-01-23,15:43:34,15986756631,10657302056780408,*,信息内容1
	 * 注：格式中的*号是备用字段
	 */
	public function getSmsExEx(){
		
		$parma = array();
		$parma['userId']   = $this->sUserName;
		$parma['password'] = $this->sPassword;
		$sResult = $this->soap->call('MongateCsGetSmsExEx',$parma);
		
		return $sResult;
	}
	
	/*
	 *  获取状态报告接口
	 *  返回值：
	 *  null 无上行信息
	 *  接收状态报告内容列表(最高维数为500)，返回格式：
	 *  日期,时间,信息编号,*,状态值,详细错误原因
	 *  状态值：
	 *    0 接收成功
	 *    1 发送暂缓
	 *    2 发送失败
	 *	例如：2008-01-23,15:43:34,0518153837115735,*,0,DELIVRD
	 *  
	 */
	public function getStatusReportExEx(){
		

		$parma = array();
		$parma['userId']   = $this->sUserName;
		$parma['password'] = $this->sPassword;
		$sResult = $this->soap->call('MongateCsGetStatusReportExEx',$parma);
		
		return $sResult;		
	}
	
	/*
	 * 查询余额接口
	 * 正数或0：帐户可发送条数
	 * -1    登陆失败
	 */
	public function queryBalance(){
		
		$parma = array();
		$parma['userId']   = $this->sUserName;
		$parma['password'] = $this->sPassword;
		$sResult = $this->soap->call('MongateQueryBalance',$parma);
		
		return $sResult;
	}
	
	/*
	 * 获取上行/状态报告等
	 * $iReqType 请求类型(0: 上行&状态报告 1:上行 2: 状态报告)
	 * 返回值 
	 *   null 无信息
	 *   接收信息内容列表(最高维数为500)格式说明：
	 *   信息类型(上行标志0),日期,时间,上行源号码,上行目标通道号,*,*,上行信息内容 
	 *   或
	 *   信息类型(状态报告标志1),日期,时间,信息编号,通道号,手机号,MongateSendSubmit时填写的MsgId,*,状态值,详细错误原因
	 *   例如：
	 *   1,2008-01-23 15:43:34,15986756631,10657302056780408,*,*,上行信息内容1
	 *   2,2008-01-23 15:43:34,0518153837115735,10657302056780408,13265661403,
	 *   	456123457895210124,*,0,DELIVRD
	 *   注：格式中的*号是备用字段 第一标志位的0表示上行和状态报告，1表示上行，2表示状态报告 
	 */
	public function getDeliver($iReqType){
		$parma = array();
		$parma['userId']   = $this->sUserName;
		$parma['password'] = $this->sPassword;
		$parma['iReqType'] = $iReqType;
		$sResult = $this->soap->call('MongateGetDeliver',$parma);
		
		return $sResult;
	}
}