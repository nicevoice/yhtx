<?php
namespace ADMIN\Controller;
use Think\Controller;
class TagsController extends Controller {
	
	//前置操作
	function _initialize(){ 
		//登陆状态及权限检测
		if(!checklogin()){
			$this -> error('您尚未登陆后台 或 登陆已超时，请您重新登陆！',U('Login/index'),3);
		}
		if(!checklevel("tags")){
			$this -> error('您没有此项功能的操作权限，请您联系技术人员或总管理员！',U('Index/index'),3);
		}
	} 
	
	//首页
    function Index(){ 
		echo '
		
			<style type="text/css">
			*{ padding: 0; margin: 0; } 
			div{ padding: 4px 48px;} 
			body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:14px} 
			h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } 
			p{ line-height: 1.8em; font-size: 36px }
			</style>
			<div style="padding: 24px 48px;font-size:24px;">
			  <h1>:)</h1>
			  <p>欢迎您访问帮助手册！</p>
			  <br/>
			  [<a target="_blank" href="http://www.5ucms.com">点我进入官方网站</a>] [<a target="_blank" href="http://help.5ucms.com">点我进入帮助手册文档</a>]<br>
			  本来是打算写个标签生成器的，但精力实在有限，将来有时间再说吧，有需要学习的直接看手册，和ASP版要的几乎一样的。<br>
			  有问题QQ群里搜索5ucms随便找一个，我们的人几乎都在里边。<br>
              我们需要先把精力放在更重要的功能上。www.5ucms.com QQ3876307 邱嵩松 
			</div>
		
		' ;
		
    }

	
	//违规信息
	function _empty($name){
		error_404($name);
    }
}