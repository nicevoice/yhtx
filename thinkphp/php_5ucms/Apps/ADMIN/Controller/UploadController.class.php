<?php
namespace ADMIN\Controller;
use Think\Controller;
class uploadController extends Controller {
	
	//前置操作
	function _initialize(){ 
		//登陆状态及权限检测
		if(!checklogin()){
			$this -> error('您尚未登陆后台 或 登陆已超时，请您重新登陆！',U('Login/index'),3);
		}
		if(!checklevel("upload")){
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
				if($dfieldkey == 'id' or $dfieldkey == 'aid' or $dfieldkey == 'cid'){
					$map[C('DB_PREFIX').'upload.'.$dfieldkey] = $key;
				} else {
					$map[C('DB_PREFIX').'upload.'.$dfieldkey] = array('like',"%$key%");				
				}
			}
		}
		$this-> assign('dfieldkey',$dfieldkey);
		$this-> assign('key',$key); // 赋值搜索条件
		
		$qcount = M('upload') -> where($map) -> count(); //统计条数
		$page = getpage2($qcount,10);   //分页数量
		$qtable = M('upload') -> field(C('DB_PREFIX').'upload.id,'.C('DB_PREFIX').'upload.aid,'.C('DB_PREFIX').'upload.cid,'.C('DB_PREFIX').'upload.ext,'.C('DB_PREFIX').'upload.time,'.C('DB_PREFIX').'upload.dir,'.C('DB_PREFIX').'content.title') -> where($map) -> join (''.C('DB_PREFIX').'content on '.C('DB_PREFIX').'upload.aid = '.C('DB_PREFIX').'content.id') -> order("id desc") -> limit($page['limit']) -> select(); 
 	
		$this-> assign('qtable',$qtable); // 赋值数据集
        $this->assign('page',$page['pages']); // 赋值分页输出 
		$content = $this->fetch();
		$content = ChageATurl($content);//修正素材文件路径
		$this->show($content);  
		
    }
 
	//删除信息
    function del(){
		$qid = $_GET['id']; 
		//删除多条记录 delete("1,3,56") 
		$fdir = M("upload") -> field('dir') -> find($qid); 
		$qnewid = M("upload") -> delete($qid);
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
	
	//清理上传文件
    function clear(){
		$qnewid = M("upload") -> where('aid = -1')-> delete();
		if ($qnewid >0) {
			$this -> success('清理上传文件成功！记得更新缓存哦！否则前台缩略图可能不显示！');
		}else{ 
			$this -> error('清理失败，可能因为是没有需要清理的文件，请重试！');
		}  
    }
	//清理缩略图
	function clearsl(){ 
		if(deletedir('./uploadfile/small/')){
			$this -> success('清理缩略图成功！',U('upload/index'),3); 
		}else{ 
			$this -> error('清理失败，可能因为是没有需要清理的缩略图文件，请重试！');
		}  
    } 
	
	//违规信息
	function _empty($name){
		error_404($name);
    }
}