<?php
namespace ADMIN\Controller;
use Think\Controller;
class ChannelController extends Controller { 
	//前置操作
	function _initialize(){ 
		//登陆状态及权限检测
		if(!checklogin()){
			$this -> error('您尚未登陆后台 或 登陆已超时，请您重新登陆！',U('Login/index'),3);
		}
		if(!checklevel("channel")){
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
				if($dfieldkey == 'id' or $dfieldkey == 'fatherid' or $dfieldkey == 'order'){
					$map[$dfieldkey] = $key;
				} else {
					$map[$dfieldkey] = array('like',"%$key%");		
				}
			}
		}
		
		$this-> assign('dfieldkey',$dfieldkey);
		$this-> assign('key',$key); // 赋值搜索条件
	    $qtable = D('channel') -> where($map) -> order(array('order'=>'Desc','id'=>'Desc')) -> select(); 
		if($dfieldkey !== 'id' and $dfieldkey !== 'fatherid' and $dfieldkey !== 'order'){ //特别查询
			$qtable = list_to_tree($qtable,'id','fatherid'); //实现无级排序
			$qtable = $this -> findchild($qtable); 
		}
		$this-> assign('qtable',$qtable);
		//show_bug($qtable);
		$content = $this -> fetch();
		$content = ChageATurl($content);//修正素材文件路径
        $this -> show($content);  
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
		$qedit = D('channel'); //实例化数据表
		$qid = I('get.id');
		$topid =  I('get.topid');
		if (empty($topid)) {$topid = 0;} //如果为空 则设定为0 用于更新顶级分类

		if(!IS_POST){
		//列出下拉目录; 
			$qtable = $qedit -> order(array('order'=>'desc','id'=>'desc')) -> select();//实现同级节点排序
			$qtable = list_to_tree($qtable,'id','fatherid'); 
			$qtable = $this -> findchild($qtable); 
			$this-> assign('qtable',$qtable);

			//添加/修改 显示内容'; 
			if (!empty($qid)) {
			//echo '修改 显示旧的';  
				$qinfo = $qedit -> find($qid);
				$this-> assign('qinfo',$qinfo);
				$content = $this->fetch();
				$content = ChageATurl($content);//修正素材文件路径 
				$this -> show($content); 
			}else{
			//echo '新增 显示空的';
				$qinfo = array(
						"id" => "0",
						"fatherid" => $topid,
						"childid" => "",
						"deeppath" => "0",
						"name" => "",
						"order" => "0",
						"table" => "",
						"domain" => "",
						"outsidelink" => "0",
						"templatechannel" => "Channel_channel",
						"templateclass" => "Channel_list.html",
						"templateview" => "Content_article.html",
						"ruleindex" => "{installdir}{cid}/",
						"rulechannel" => "page_{page}.html",
						"ruleview" => "{aid}.html",
						"picture" => "",
						"keywords" => "",
						"description" => "",
						"needcreate" => "1",
						"Modeext=" => "",
						"childidon" => "",
						"childidson" => "",
						"c" => "c.html",
						"l" => "l.html",
						"a" => "a.html" 
				);
				$this-> assign('qinfo',$qinfo);
				$content = $this -> fetch();
				$content = ChageATurl($content);//修正素材文件路径
				$this -> show($content); 
			}
		}else{
		//保存数据
			refreshdata(true); //更新缓存
			//查出本栏目id对应的 上级id 层级 等信息  
			$fatherid = I('post.fatherid',0); 
			if (!$fatherid==0) {
				$qinfo = $qedit -> info($fatherid,'id,fatherid,deeppath,childid,childids');  
				$deeppath = $qinfo['deeppath'] + 1; 
			}else
			{
				$deeppath = 0;
			}
			 
			//echo '添加新数据';  
			
			if (empty($qid)) {
				$z = $qedit -> create($_POST,1);
				if (!$z) {
					echo '验证失败！'; 
					show_bug($qedit->getError()); //支持输出二维数组
					exit($qedit->getError()); 
				}else{ 
					//echo '验证成功！'."<br>";
				}
				$qedit -> deeppath = $deeppath;
				$qnewid = $qedit -> add(); 
				if ($qnewid >0) {
					$data['cid'] = $qnewid;
					$qnewid = M('channel') -> field('cid') -> where('id='.$qnewid ) -> save($data);	 //将新添加的cid值改为id值
					Reloadechildid(); //重置子分类合集
					$this -> success('添加成功！',U('channel/index'),3);
				}else{ 
					$this -> error('添加失败，请重试！',U('channel/index'),3);
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
				$qedit -> deeppath = $deeppath; 
				$qnewid = $qedit  -> where('id='.$qid ) -> save();
				if ($qnewid >0) {
					Reloadechildid(); //重置子分类合集
					$this -> success('修改成功！',U('channel/index'),3);
				}else{ 
					$this -> error('修改失败，请重试！(数据无任何修改 会导致失败)',U('channel/index'),3);
				}
			}
		} 
    }

	
	//删除信息 150527
    function del(){
		$qid = $_GET['id']; 
		
        //判断该分类下有没有子分类，有则不允许删除
        $child = M('channel')->where(array('fatherid'=>$qid))->field('id')->select();
        if(!empty($child)){
            $this->error('请先删除该分类下的子分类');
        }

        //判断该分类下有没有内容
        $document_list = M('content')->where(array('cid'=>$qid))->field('id')->select();
        if(!empty($document_list)){
            $this->error('请先删除该分类下的文章（包含回收站）');
        }
		
		//删除多条记录 delete("1,3,56") 
		$qnewid = M("channel") -> delete($qid);
		if ($qnewid >0) {
			$this -> success('删除成功！');
		}else{ 
			$this -> error('删除失败，请重试！');
		} 
    }
 
	
	//违规信息
	function _empty($name){
		error_404($name);
    }
}