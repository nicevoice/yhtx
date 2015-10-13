<?php
namespace PHP_5UCMS\Controller;
use Think\Controller;
use Think\Storage;

class ContentController extends Controller { 

    public function index(){ 
 		$id = I('get.id');
		if(!is_numeric($id)){
			$this -> error('内容参数不正确！',U('index/index'),3);
		}
		//先获取内容对应栏目的CID
		$qc = M('content');
		$r = $qc -> where('id='.$id) -> getField('cid,jumpurl,title,description');  
		foreach($r as $k=>$v){
			$cid = $v['cid'];
			if(!is_numeric($cid)){
				$this -> error('该内容不存在或已被删除！',U('index/index'),3);
			}
			//跳转
			if(!empty($v['jumpurl'])){
				//默认方法
				echo '<head><meta http-equiv="Content-Type" content="text/html; charset="utf-8" /><title>'.$v['title'].'</title><meta http-equiv="Refresh" content="2;URL='.$v['jumpurl'].'" /></head><body><div style="text-align:left;margin:10px;"><div style="padding:6px;font-size:14px;border:solid 1px #CCCCCC;background-color:#F8F8F5;"><h4 style="padding:5px;font-size:16px;font-weight:bold;display:inline;">'.$v['title'].'</h4><div style="padding:5px;color:#666666;font-size:12px;">'.$v['description'].'( 2秒后进行跳转...)</div></div></div></body></html>';
				
				//方法二 直接跳： 需要这个就注释上一行 释放下一行 
				//redirect($v); die; 
				
				die;
			}
		}
		//获取栏目配置
		$getchannel = C('getchannel.'.$cid);
		$Templateview = $getchannel['templateview'];
		//去掉后缀 方便调用
		$Templateview = str_replace('.html', '', $Templateview);
		
		$content = $this->fetch($Templateview); 
		$content = qss5ucms($content,2,$id); //模板标签转换 2表示 内容页
		$content = ChageATurl($content,C('templatedir')); //修改资源文件路径
		$this->show($content); 
		
    }
	
	//测试
	public function test(){
 	 
    }	
	
	
	//空操作
	public function _empty(){
		error_404();
    }

}