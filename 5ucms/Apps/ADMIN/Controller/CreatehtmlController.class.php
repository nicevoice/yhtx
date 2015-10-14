<?php
namespace ADMIN\Controller;
use Think\Controller;
class CreatehtmlController extends Controller {
	
	//前置操作
	function _initialize(){ 
		//登陆状态及权限检测
		if(!checklogin()){
			$this -> error('您尚未登陆后台 或 登陆已超时，请您重新登陆！',U('Login/index'),3);
		}
		if(!checklevel("create")){
			$this -> error('您没有此项功能的操作权限，请您联系技术人员或总管理员！',U('Index/index'),3);
		}
	} 
	
	//首页
    function Index(){ 
		echo '待制作 得先等前台部分功能完善 才能处理这里 敬请期待' ;
		//$this->buildHtml('静态文件', '静态路径','目标模块');
		//$this->buildHtml("index",'',"index"); 
    }

	
	//违规信息
	function _empty($name){
		error_404($name);
    }
}