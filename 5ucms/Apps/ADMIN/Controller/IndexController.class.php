<?php
namespace ADMIN\Controller;
use Think\Controller;
use Common\Helper\Category;
class IndexController extends Controller { 
	//前置操作
	function _initialize(){ 
		//登陆状态及权限检测
		if(!checklogin()){
			$this -> error('您尚未登陆后台 或 登陆已超时，请您重新登陆！',U('Login/index'),3);
		} 
	} 
	
	//首页
    public function index(){ 
		$this->assign('main',U('main'));
		$this->assign('refresh',U('refresh')); 
		$this->assign('admintitle',C('webname')); 
		$this->assign('adminname',cookie('php5ucms_Adminname'));
		$content = $this->fetch();
		$content = ChageATurl($content);//修正素材文件路径
		$this->show($content);  
    }
	
	//右下区域
    public function main(){
		$this->assign('version',C('version')); //版本号
		$this->assign('getip',serverIP()); //服务端IP
		$cnum = M('content') -> count();
		$this->assign('cnum',$cnum); //文章总数
		$createhtml = C('createhtml'); 
		switch($createhtml){ 
			 case 0: $createhtml = 'PHP动态';
					break;
			 case 1: $createhtml = '纯静态';
			  		break;
			 case 2: $createhtml = '伪静态';
					break;
			 default: $createhtml = 'PHP & Html栏目动态 其他纯静态'; 
		} 
		$this->assign('createhtml',$createhtml); //浏览模式
		$autopinyin = C('autopinyin');
		if($autopinyin==1){$autopinyin='启用';}else{$autopinyin='禁用';}
		$this->assign('autopinyin',$autopinyin); //标题转拼音
		$remotepic = C('remotepic');
		if($remotepic==1){$remotepic='启用';}else{$remotepic='禁用';}
		$this->assign('remotepic',$remotepic); //远程抓图	
		
		$qtable = M('bots') -> order('lastdate desc') -> select(); //读取蜘蛛信息
		$this-> assign('qtable',$qtable); // 赋值数据集

		$content = $this->fetch();
		$content = ChageATurl($content);//修正素材文件路径
		$this->show($content);  
    } 
	
	//左下更新缓存结果展示区域
    public function refresh(){
		$qid = $_GET['act'];
		if (!empty($qid)) { 
			refreshdata(true); //更新缓存
		}else{
			echo '缓存已开启'; 
		}
    }
 	//空操作
	function _empty($name){
		error_404($name);
    }
}