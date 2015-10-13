<?php
namespace ADMIN\Controller;
use Think\Controller;
class AdminController extends Controller {
	
	//前置操作
	function _initialize(){ 
		//登陆状态及权限检测
		if(!checklogin()){
			$this -> error('您尚未登陆后台 或 登陆已超时，请您重新登陆！',U('Login/index'),3);
		}
		if(!checklevel("admin")){
			$this -> error('您没有此项功能的操作权限，请您联系技术人员或总管理员！',U('Index/index'),3);
		}
	} 
	
	//首页
    function Index(){
		//这里故意设定了id为2的管理员账号不显示，用于技术员维护
		$map['id'] <> 2  ;
		
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
		$qcount = M('admin') -> field("id,name,info") -> where($map) -> count(); //统计条数 
		$page = getpage2($qcount,10);   //分页数量
		$qtable = M('admin') -> field("id,username") -> where($map) -> order("id desc") -> limit($page['limit']) -> where("id <> 2") -> select(); 
		$this-> assign('qtable',$qtable); // 赋值数据集
        $this->assign('page',$page['pages']); // 赋值分页输出
		$content = $this->fetch();
		$content = ChageATurl($content);//修正素材文件路径
		$this->show($content);  
    }
	
	//处理无极栏目排序
	protected function findchild($arr){
		static $tree = array();
		foreach ($arr as $key=>$val){
			$tree[] = $val;
			if (isset($val['_child'])){
			  $this -> findchild($val['_child']);
			}		
		  }
	  return $tree;
	} 
	
	//处理信息  
    function edit(){
		$qid = $_GET['id']; 
		$qedit = D('Admin');
		if(!IS_POST){ 
		//添加/修改 显示内容';
			//显示栏目信息
			$qtable = D('channel') -> order(array('order'=>'desc','id'=>'desc')) -> select();//实现同级节点排序
			$qtable = list_to_tree($qtable,'id','fatherid'); 
			$qtable = $this -> findchild($qtable); 
			$this-> assign('qtable',$qtable); 
			
			//显示插件信息
			$qplus = D('plus') -> select();//实现同级节点排序 
			$this-> assign('qplus',$qplus); 
			
			if (!empty($qid)) {
			//echo '修改 显示旧的'; 
				$qinfo = $qedit -> find($qid);
				$this-> assign('qinfo',$qinfo);
				$content = $this->fetch();
				$content = ChageATurl($content);//修正素材文件路径 
				$this->show($content); 
			}else{
			//echo '新增 显示空的';
				//显示一些默认值 
				$qinfo = array(
						"managechannel"  => 0,
						"uploadfileexts" => "gif/jpg/png/bmp/jpeg/mp3/wma/rm/rmvb/ra/asf/avi/wmv/swf/rar/exe/zip/doc/xls",
						"uploadfilesize" => "1024" 
				);
				$this-> assign('qinfo',$qinfo);
				$content = $this->fetch();
				$content = ChageATurl($content);//修正素材文件路径
				$this->show($content); 
			}
		}else{
			//echo '添加新数据';  
		 	$levels = input_treat($_POST['levels']); //获取后台权限数组
			$manageplus = input_treat($_POST['manageplus']); //获取插件权限数组 
			if($_POST['managechannelall']=='yes'){ $managechannel = 0; }else{ $managechannel = input_treat($_POST['managechannel']); } //获取栏目权限数组
			$password = I('post.password',null); 
			$password1 = I('post.password1',null);
			if ($password !== $password1){ $password = newmd5($password); } //判断密码有无改过，改过则重建MD5加密

			if (empty($qid)) {
				$z = $qedit -> create($_POST,1);
				//show_bug($z);
				if (!$z) {
					echo '验证失败！'; 
					//show_bug($qedit->getError()); //支持输出二维数组
					exit($qedit->getError()); 
				}else{ 
					//echo '验证成功！'."<br>";
				}
				$qedit -> levels = $levels;
				$qedit -> manageplus = $manageplus;
				$qedit -> password = $password;
				$qedit -> managechannel = $managechannel; 
				$qnewid = $qedit -> add();
				if ($qnewid >0) {
					$this -> success('添加成功！',U('admin/index'),3);
				}else{ 
					$this -> error('添加失败，请重试！',U('admin/index'),3);
				}
			}else{
			//echo '修改旧数据'; 
				$z = $qedit -> create($_POST,2);
				//show_bug($z);
				if (!$z) {
					echo '验证失败！'; 
					//show_bug($qedit->getError()); //支持输出二维数组
					exit($qedit->getError()); 
				}else{ 
					//echo '验证成功！'."<br>";
				} 
				$qedit -> levels = $levels;
				$qedit -> manageplus = $manageplus;
				$qedit -> password = $password;
				$qedit -> managechannel = $managechannel;
				$qnewid = $qedit -> where('id='.$qid ) -> save();
				if ($qnewid >0) { 
					//如果修改的用户权限为当前用户 则更新其权限设定，不必重新登录。
					$admin = session('admin'); 
					if($admin['uid'] == $qid){
						echo '您现在修改的用户权限为当前登录用户 已更新您的权限设定，不必重新登录。';
						$auth = array(
							'uid'				=> $qid,
							'username'			=> I('post.username',null),
							'password'			=> I('post.password',null),
							'levels'			=> $levels,
							'manageplus'		=> $manageplus,
							'managechannel'		=> $managechannel,
							'uploadfileexts'	=> I('post.uploadfileexts',null),
							'uploadfilesize'	=> I('post.uploadfilesize',null) 
						);
						session('admin',$auth);
						cookie('admin',$auth);
					}
					$this -> success('修改成功！',U('admin/index'),3);
				}else{ 
					$this -> error('修改失败，请重试！(数据无任何修改 会导致失败)',U('admin/index'),3);
				}
			}
		} 
    }
	//删除信息
    function del(){
		$qid = $_GET['id'];
		$qadd = M("admin");
		//删除多条记录 delete("1,3,56")
		//可以加where设定范围
		$qnewid = $qadd -> delete($qid);
		if ($qnewid >0) {
			$this -> success('删除成功！',U('admin/index'),3);
		}else{ 
			$this -> error('删除失败，请重试！',U('admin/index'),3);
		} 
    }
	//违规信息
	function _empty($name){
		error_404($name);
    }
}