<?php
namespace ADMIN\Controller;
use Think\Controller;
class LabelController extends Controller {
	
	//前置操作
	function _initialize(){ 
		//登陆状态及权限检测
		if(!checklogin()){
			$this -> error('您尚未登陆后台 或 登陆已超时，请您重新登陆！',U('Login/index'),3);
		}
		if(!checklevel("label")){
			$this -> error('您没有此项功能的操作权限，请您联系技术人员或总管理员！',U('Index/index'),3);
		}
	} 
	
	//首页
    function Index(){  
		$map = '';
		if(IS_GET){
			$dfieldkey = I('get.dfieldkey');
			$key = I('get.key');
			if(!empty($key) and !empty($dfieldkey)){
				if($dfieldkey == 'id'){
					$map[$dfieldkey] = $key;
				} else {
					$map[$dfieldkey] = array('like',"%$key%");				
				}
			}
		}
		
		$this-> assign('dfieldkey',$dfieldkey);
		$this-> assign('key',$key); // 赋值搜索条件
		$qcount = M('label') -> field("id,name,info") -> where($map) -> count(); //统计条数
		$page = getpage2($qcount,10); 
		$qtable = M('label') -> field("id,name,info") -> where($map) -> order("id desc") -> limit($page['limit']) -> select();  
		$this-> assign('qtable',$qtable); // 赋值数据集
		$this->assign('page',$page['pages']); //分页代码显示 150814
		$content = $this->fetch();
		$content = ChageATurl($content);//修正素材文件路径
		$this->show($content);  
		
    }
	//处理信息  
    function edit(){ 
		//定义安装目录
		$installdir = C('installdir');
		if(empty($installdir)) { $installdir = '/';}
		$this-> assign('installdir',$installdir); 
	
		$qid = I('get.id');
		$qedit = D('Label');
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
				}
				//若备注字段为空，则自动复制上方字段代码
				$code2 = I('post.code2');
				if(empty($code2)){
					$qedit -> code2 = I('post.code');
				}
				$qnewid = $qedit -> add();
				if ($qnewid >0) {
					$this -> success('添加成功！',U('index'),3);
				}else{ 
					$this -> error('添加失败，请重试！',U('index'),3);
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
				}
				//若备注字段为空，则自动复制上方字段代码
				$code2 = I('post.code2');
				if(empty($code2)){
					$qedit -> code2 = I('post.code');
				}
				$qnewid = $qedit -> where('id='.$qid ) -> save();
				if ($qnewid >0) {
					$this -> success('修改成功！',U('index'),3);
				}else{ 
					$this -> error('修改失败，请重试！(数据无任何修改 会导致失败)',U('index'),3);
				}
			}
		} 
    }
	//删除信息
    function del(){ 
		$qid = $_GET['id']; 
		//删除多条记录 delete("1,3,56") 
		$qnewid = M("Label") -> delete($qid);
		if ($qnewid >0) {
			$this -> success('删除成功！');
		}else{ 
			$this -> error('删除失败，请重试！');
		} 
    }
	
	//标签导出
    function listlabel(){ 
	
		$qtable = M('label') -> order('id asc') -> select();  
	
		foreach($qtable as $key => $val){ 	
			echo "(".$val['id'].",'".$val['name']."','".$val['info']."','".$val['code']."',''),\n";
			
			
		}
	
		//$this -> error('制作中，敬请期待！');
    }
	//标签导入
	function addlabel(){
		$this -> error('制作中，敬请期待！');
    }
	
	//违规信息
	function _empty($name){ 
		error_404($name);
    }
}