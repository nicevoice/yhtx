<?php
namespace ADMIN\Controller;
use Think\Controller;
class SettingController extends Controller {
	
	//前置操作
	function _initialize(){ 
		//登陆状态及权限检测
		if(!checklogin()){
			$this -> error('您尚未登陆后台 或 登陆已超时，请您重新登陆！',U('Login/index'),3);
		}
		if(!checklevel("setting")){
			$this -> error('您没有此项功能的操作权限，请您联系技术人员或总管理员！',U('Index/index'),3);
		}
	} 
	
	//首页
    function Index(){ 
		$config=M('config'); //读取配置内容，按权重从小到大排序
		$qtable = $config -> order(array('order'=>'asc')) -> select(); 
		$this-> assign('qtable',$qtable);
		$content = $this->fetch();
		$content = ChageATurl($content);//修正素材文件路径
		$this->show($content); 
    }
 
	
    function edit(){
		$config = M('config');//实例化数据表
		//获取新配置
		if(!IS_POST){
			$this->error('不要干坏事哦~请从后台正常提交！');
		} 
		else
		{
			$qtable = $config -> field('title,name,value,data') -> select(); //读取表字段名
			for($i=0;$i<count($qtable);$i++){//循环出来
				$qvalue = $qtable[$i]['name']; //找到具体字段名称 
				$map['name'] = $qvalue; 
				$qnewid = $config -> where($map) -> setField('value',I('post.'.$qvalue,''));
				$qnewid2 = $qnewid2 + $qnewid;
			} 
			
			if ($qnewid2 >0) {
				refreshdata(true); //更新缓存
				$this -> success('修改成功！');
			}else{ 
				$this -> error('修改失败，请重试！(数据无任何修改 会导致失败)');
			}
		} 
    }
	
 
	
	function _empty($name){
		error_404($name);
    }
}