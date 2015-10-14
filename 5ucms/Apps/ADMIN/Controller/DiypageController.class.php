<?php
namespace ADMIN\Controller;
use Think\Controller;
use Think\Storage;
class DiypageController extends Controller {
	
	//前置操作
	function _initialize(){ 
		//登陆状态及权限检测
		if(!checklogin()){
			$this -> error('您尚未登陆后台 或 登陆已超时，请您重新登陆！',U('Login/index'),3);
		}
		if(!checklevel("diypage")){
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
		$qcount = M('diypage') -> field("id,title,dir") -> where($map) -> count(); //统计条数
		$page = getpage2($qcount,10);   //分页数量
		$qtable = M('diypage') -> field("id,title,dir") -> where($map) -> order("id desc") -> limit($page['limit']) -> select(); 
		$this-> assign('qtable',$qtable); // 赋值数据集
        $this->assign('page',$page['pages']); // 赋值分页输出 
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
		$qedit = D('Diypage');
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
				$Html2 = I('post.Html2');
				if(empty($Html2)){
					$qedit -> Html2 = I('post.Html');
				}
				
				//数据库记录
				$qnewid = $qedit -> add(); 
				if ($qnewid >0) {
					$this -> success('添加成功！',U('index'),3); 
					if(createhtml(3,$qnewid)){ //3 指自定义页
						$this -> success('id为'.$qnewid.'的页生成成功！');	
					} else {
						$this -> error('id为'.$qnewid.'的生成失败！');
					} 
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
				$Html2 = I('post.Html2');
				if(empty($Html2)){
					$qedit -> Html2 = I('post.Html');
				}  
				$qnewid = $qedit -> where('id='.$qid ) -> save();
				if ($qnewid >0) {
					$this -> success('修改成功！',U('index'),3);
					if(createhtml(3,$qid)){ //3 指自定义页
						$this -> success('id为'.$qid.'的页生成成功！');	
					} else {
						$this -> error('id为'.$qid.'的生成失败！');
					} 
				}else{ 
					$this -> error('修改失败，请重试！(数据无任何修改 会导致失败)',U('index'),3);
				}
			}
		} 
    }
	//删除信息
    function del(){
		$qid = $_GET['id'];  
		$fdir = M("Diypage") -> field('dir') -> find($qid); 
		$qnewid = M("Diypage") -> delete($qid);
		if ($qnewid >0) {
			if(unlink('.'.$fdir['dir'])){ 
				$this -> success('物理文件及文件记录均删除成功！');
			} 
			else {
				$this -> error('物理文件删除失败，可能权限不足或文件不存在，请人工处理！');
			}
		}else{ 
			$this -> error('文件记录删除失败，请重试！');
		}
    }
	
	//生成信息
    function refresh(){ 
		$qid = $_GET['id'];
		if (empty($qid)) {
 			if(createhtml(3,0)){ //3 指自定义页
				$this -> success('全部自定义页生成成功！');	
			} else {
				$this -> error('生成失败！');
			}
		}else{ 
 			if(creatediypage($qid)){ //3 指自定义页
				$this -> success('id为'.$qid.'的页生成成功！');	
			} else {
				$this -> error('id为'.$qid.'的页生成失败！');
			}
		}
    }
	
	//违规信息
	function _empty($name){
		error_404($name);
    }
}