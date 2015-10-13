<?php
namespace PHP_5UCMS\Controller;
use Think\Controller; 

class ChannelController extends Controller { 

    public function index(){ 
 		$id = I('get.id');
		if(!is_numeric($id)){
			$this -> error('内容参数不正确！',U('index/index'),3);
		}
  
		$getchannel = C('getchannel.'.$id); //获取栏目配置
		$ChildID = $getchannel['childid'];
		$Templatechannel = $getchannel['templatechannel'];
		$Templateclass = $getchannel['templateclass'];
		$Templateview = $getchannel['templateview'];
		
		if(!empty($ChildID)){ //有子栏目 则使用大类模板
			$template = $Templatechannel;
		} else { //无子栏目 则使用小类模板
			$template = $Templateclass;
		}
		//去掉后缀 方便调用
		$template = str_replace('.html', '', $template); 
		$content = $this->fetch($template);
		$content = qss5ucms($content,1,$id); //模板标签转换 1表示 栏目页 
		$content = ChageATurl($content,C('templatedir')); //修改资源文件路径
		$this->show($content);  
    }

	//空操作
	public function _empty(){
		error_404();
    }
}