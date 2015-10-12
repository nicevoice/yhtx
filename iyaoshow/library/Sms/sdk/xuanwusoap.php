<?php
/**
* 玄武短信接口
* 
**/
class xuanwusoap
{
	//帐号
	private $account = "cadmin@shpahf";
	//密码
	private $passwd  = "123456";
	//webservice
	private $url     = "http://211.147.239.62/Service/WebService.asmx?wsdl";
	//port
	private $port    = "";
	//type  方式,1为mos 2为200
	private $type    = "1";
	//扩展id
    private $subid   = "";
	
	//MT提交状态码  返回值
	private $response = array(
				"0" =>"成功",
				"-1"=>"账号无效",
				"-2"=>"参数：无效",
				"-3"=>"连接不上服务器",
				"-5"=>"无效的短信数据，号码格式不对",
				"-6"=>"用户名密码错误",
				"-7"=>"旧密码不正确",
				"-9"=>"资金账户不存在",
				"-11"=>"包号码数量超过最大限制",
				"-12"=>"余额不足",
				"-13"=>"账号没有发送权限",
				"-99"=>"系统内部错误",
				"-100"=>"其它错误"
				);
	static $single = null;

	public function __construct( $url , $account , $passwd ){
		if(!class_exists("SoapClient")){
			die("请开启Soap扩展");
			exit;
		}
		$this->account = $account;
		$this->url = $url;
		$this->passwd = $passwd;
				
		if(self::$single == null){
			self::$single = new SoapClient($this->url);
		}			
	} 

	/*
	 * 单一接口发送
	 */
	public function postSingle($mobile,$content){
		$params = array(
			'account'    => $this->account,
			'password'	 => $this->passwd,
			'mobile'	 => $mobile,
			'content'	 => $content,
			'subid'		 => '123456'
		);

		$result = self::$single->PostSingle($params); 
		return $result->PostSingleResult;
	}


	/*
	 * 批量发送
	 */
	public function post($mobiles,$content){
		//批量发送数组
		 $batchMobile = array();
		 if(is_array($mobiles)){
			foreach($mobiles as $val){
				$messageData = new messageData();
				$messageData->content = $content;
				$messageData->Phone = $val;
				$messageData->vipFlag = true;
				$messageData->customMsgID = "";
				$batchMobile[] = $messageData;
			}
			$mtpack = new mtpack();
			$mtpack->msgs  = $batchMobile;
			$params = array(
				'account'    => $this->account,
				'password'	 => $this->passwd,
				'mtpack'	 => $mtpack
			); 
			print_r(self::$single->Post($params));
		 }else
			$this->postSingle($mobiles,$content);
	}


	/*
	 * 按组发送
	 */
	public function postgroup(){
		
	}

	/*
	 * 群发短信
	 */
	public function postmass($mobiles,$content){
		$params = array(
			'account'    => $this->account,
			'password'	 => $this->passwd,
			'mobiles'	 => $mobiles,
			'content'	 => $content,
			'subid'		 => ''
		);
		
		return self::$single->PostMass($params);
	}
	
	/*
	 * 获取返回结果
	 */
	public function getResponse(){
		$params = array(
			'account'    => $this->account,
			'password'	 => $this->passwd,
			'PageSize'	 => 500
		);
		print_r(self::$single->GetResponse($params));
	}

	/*
	 * 获取发送报告
	 */
	public function getReport(){
		
	}	
}


/**
*  批量发送类
**/
class mtpack{
	public $batchID	= "00000000-0000-0000-0000-000000000000";
	//public $batchID    = "";
	public $batchName	= "";
	public $sendType	= "0";
	public $msgType	= "1";
	public $msgs		= "";
	public $bizType	= 0;
	public $distinctFlag = true;	
	public $scheduleTime = "";
	//public $remark		  = "";
	//public $customNum	  = "";
	public $deadline	  = "";
	public $uuid		= "00000000-0000-0000-0000-000000000000";
}

//发送信息类
class messageData{
	public $content;
	public $Phone;
	public $vipFlag;
	public $customMsgID;
}