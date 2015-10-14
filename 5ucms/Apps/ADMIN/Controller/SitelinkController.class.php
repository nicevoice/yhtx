<?php
namespace ADMIN\Controller;
use Think\Controller;
class SitelinkController extends Controller {
	
	//前置操作
	function _initialize(){ 
		//登陆状态及权限检测
		if(!checklogin()){
			$this -> error('您尚未登陆后台 或 登陆已超时，请您重新登陆！',U('Login/index'),3);
		}
		if(!checklevel("sitelink")){
			$this -> error('您没有此项功能的操作权限，请您联系技术人员或总管理员！',U('Index/index'),3);
		}
	} 
	
	//首页
    function Index(){ 
		$order = 'id desc';
		if(IS_GET){
			$dfieldkey = I('get.dfieldkey');
			$key = I('get.key');
			$dfieldorders = I('get.dfieldorders');
			$orders = I('get.orders'); 
			if(!empty($key) and !empty($dfieldkey)){
				if($dfieldkey == 'id' or $dfieldkey == 'order' or $dfieldkey == 'replace' or $dfieldkey == 'target' or $dfieldkey == 'state'){
					$map[$dfieldkey] = $key;
				} else {
					$map[$dfieldkey] = array('like',"%$key%");				
				}
			}
			if(!empty($dfieldorders) and !empty($orders)){
				$order = array($dfieldorders=>$orders);
				$this-> assign('dfieldorders',$dfieldorders);
				$this-> assign('orders',$orders);
			}
		}
		
		$p =  I('get.page',1);
		$this-> assign('p',$p);
		
		$this-> assign('dfieldkey',$dfieldkey);
		$this-> assign('key',$key); // 赋值搜索条件
		
		$qcount = M('sitelink') -> where($map) -> count(); //统计条数
		$page = getpage2($qcount,10);   //分页数量
		$qtable = M('sitelink') -> where($map) -> order($order) -> limit($page['limit']) -> select(); 
		$this-> assign('qtable',$qtable); // 赋值数据集
        $this->assign('page',$page['pages']); // 赋值分页输出 
		$content = $this->fetch();
		$content = ChageATurl($content);//修正素材文件路径
		$this->show($content);  
		
    }
	//处理信息  
    function edit(){
		$qid = $_GET['id']; 
		$qedit = D('Sitelink');
		if(!IS_POST){
		//添加/修改 显示内容'; 
			if (!empty($qid)) {
			//echo '修改 显示旧的'; 
				$qinfo = $qedit -> find($qid);
				$this-> assign('qinfo',$qinfo);
				$content = $this->fetch();
				$content = ChageATurl($content);//修正素材文件路径 
				$this->show($content); 
			}else{
			//echo '新增 显示空的'; 
				$qinfo = array( 
						"link" => 'http://', 
						"order" => 0,
						"state" => 1,
						"target" => 1,
						"replace" => 1
				);
				$this-> assign('qinfo',$qinfo);//默认值
				$content = $this->fetch();
				$content = ChageATurl($content);//修正素材文件路径
				$this->show($content); 
			}
		}else{
			//echo '添加新数据'; 
			if (empty($qid)) {
				$z = $qedit -> create($_POST,1);
				//show_bug($z);
				if (!$z) {
					echo '验证失败！'; 
					show_bug($qedit->getError()); //支持输出二维数组
					exit($qedit->getError()); 
				}else{ 
					//echo '验证成功！'."<br>";
				}
				//若备注字段为空，则自动复制上方字段代码
				$description = I('post.description');
				if(empty($description)){
					$qedit -> description = I('post.text');
				}
				$qnewid = $qedit -> add();
				if ($qnewid >0) {
					$this -> success('添加成功！',U('Sitelink/index'),3);
				}else{ 
					$this -> error('添加失败，请重试！',U('Sitelink/index'),3);
				}
			}else{
			//echo '修改旧数据'; 
				$z = $qedit -> create($_POST,2);
				//show_bug($z);
				if (!$z) {
					echo '验证失败！'; 
					show_bug($qedit->getError()); //支持输出二维数组
					show_bug($_POST); //支持输出二维数组
					exit($qedit->getError()); 
				}else{ 
					//echo '验证成功！'."<br>";
				}
				//若备注字段为空，则自动复制上方字段代码
				$description = I('post.description');
				if(empty($description)){
					$qedit -> description = I('post.text');
				}
				$qnewid = $qedit -> where('id='.$qid ) -> save();
				if ($qnewid >0) {
					$this -> success('修改成功！',U('Sitelink/index'),3);
				}else{ 
					$this -> error('修改失败，请重试！(数据无任何修改 会导致失败)',U('Sitelink/index'),3);
				}
			}
		} 
    }
	//删除信息
    function del(){
		$qid = $_GET['id']; 
		//删除多条记录 delete("1,3,56") 
		$qnewid = M("Sitelink") -> delete($qid);
		if ($qnewid >0) {
			$this -> success('删除成功！',U('Sitelink/index'),3);
		}else{ 
			$this -> error('删除失败，请重试！',U('Sitelink/index'),3);
		} 
    }
	
	//ajax 内链状态
    function sitelinkstate(){
		$qid = $_GET['id'];  
		$qedit = M('sitelink');  
		if(is_numeric($qid) and $qid >= 1){
			$qnewid = $qedit -> where('id='.$qid ) -> getField('state');
			if (is_numeric($qnewid)) { 
				 if($qnewid == 0){
					 echo 'True|启用';
					 $date['state'] = 1; 
				 } else {
					 echo 'True|<font color=blue>禁用</font>';
					 $date['state'] = 0;
				 }
				 $qedit -> where('id='.$qid ) -> save($date);
			} else {
				echo 'False|内链不存在！';
			}
		} else {
			echo 'False|参数格式不正确！';
		}
    } 
	
	//ajax 内链窗口状态
    function sitelinktarget(){
		$qid = $_GET['id'];  
		$qedit = M('sitelink');  
		if(is_numeric($qid) and $qid >= 1){
			$qnewid = $qedit -> where('id='.$qid ) -> getField('target');
			if (is_numeric($qnewid)) { 
				 if($qnewid == 0){
					 echo 'True|<font color=red>新窗口</font>';
					 $date['target'] = 1; 
				 } else {
					 echo 'True|原窗口';
					 $date['target'] = 0;
				 }
				 $qedit -> where('id='.$qid ) -> save($date);
			} else {
				echo 'False|内链不存在！';
			}
		} else {
			echo 'False|参数格式不正确！';
		}
    } 
	
	//新窗口
    function target(){
		echo '新窗口';
    }
	//禁用
	function state(){
		echo '禁用';
    }
	
	//违规信息
	function _empty($name){
		error_404($name);
    }
}