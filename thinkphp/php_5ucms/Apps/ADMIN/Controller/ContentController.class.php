<?php
namespace ADMIN\Controller;
use Think\Controller;
class ContentController extends Controller {
	
	//前置操作
	function _initialize(){ 
		//登陆状态及权限检测
		if(!checklogin()){
			$this -> error('您尚未登陆后台 或 登陆已超时，请您重新登陆！',U('Login/index'),3);
		}
		//内容部分不进行权限验证，仅对栏目权限进行验证
	} 
	
	//首页
    function Index(){ 
		$dbpre = C('DB_PREFIX');//获取数据库表前缀
		$order = 'id desc';
		if(IS_GET){
			$dfieldkey = I('get.dfieldkey');
			$key = I('get.key'); 
			$dfieldorders = I('get.dfieldorders');
			$orders = I('get.orders'); 
			if(!empty($key) and !empty($dfieldkey)){
				if($dfieldkey == 'id' or $dfieldkey == 'cid' or $dfieldkey == 'order' or $dfieldkey == 'display'){
					$map[''.$dbpre.'content.'.$dfieldkey] = $key;
				} else {
					$map[''.$dbpre.'content.'.$dfieldkey] = array('like',"%$key%");
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
		
		//按栏目选择
		$qcid = I('get.qcid',0);
		if(!empty($qcid)){ 
			$map[$dbpre.'content.cid'] = $qcid; 
		} 
		$this-> assign('qcid',$qcid);
		
		//统计条数
		$qcount = M('content') -> where($map) -> count(); 
		
		$qpage = I('get.qpage');
		if(empty($qpage)){ //分页数量控制
			$qpage = 15; //默认分页数量
		}
		$page = getpage2($qcount,$qpage); 
		$this-> assign('qpage',$qpage);
		
		$qtable = M('content') -> field($dbpre.'content.id,'.$dbpre.'content.cid,'.$dbpre.'content.title,'.$dbpre.'content.views,'.$dbpre.'content.order,'.$dbpre.'content.commend,'.$dbpre.'content.display,'.$dbpre.'content.indexpic,'.$dbpre.'channel.name') -> where($map) -> join (''.$dbpre.'channel on '.$dbpre.'content.cid = '.$dbpre.'channel.id') -> order($order)  -> limit($page['limit']) -> select(); 
		$this-> assign('qtable',$qtable); // 赋值数据集
        $this->assign('page',$page['pages']); // 赋值分页输出 
		
	    $ctable = D('channel') -> order(array('order'=>'Desc','id'=>'Desc')) -> select();//实现同级节点排序
	    $ctable = list_to_tree($ctable,'id','fatherid'); 
		$ctable = $this -> findchild($ctable); 
		$this-> assign('ctable',$ctable); 
		
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
		$qedit = D('Content'); 
	    $ctable = D('channel') -> order(array('order'=>'Desc','id'=>'Desc')) -> select();//实现同级节点排序
	    $ctable = list_to_tree($ctable,'id','fatherid'); 
		$ctable = $this -> findchild($ctable); 
		$this-> assign('ctable',$ctable); 
		//定义安装目录
		$installdir = C('installdir');
		if(empty($installdir)) { $installdir = '/';}
		$this-> assign('installdir',$installdir); 
		
		if(!IS_POST){
		//添加/修改 显示内容'; 
			$authchannel = session('admin');
			$managechannel = $authchannel['managechannel'];
			$this-> assign('managechannel',$managechannel); //限定栏目权限
			$this-> assign('remotepic',C('remotepic')); //设定默认远程图片权限
			if (!empty($qid)) {
			//echo '修改 显示旧的'; 
				$qinfo = $qedit -> find($qid);
				$this-> assign('qinfo',$qinfo);
				$content = $this->fetch();
				$content = ChageATurl($content);//修正素材文件路径 
				$this->show($content); 
			}else{
			//echo '新增 显示空的'; 
				$cid = session('admincid');
				if(!is_numeric($cid)){$cid = 0;}
				$qinfo = array(
						"display" => 1,
						"cid" => $cid,
						"commend" => 0,
						"iscomment" => 1,
						"createtime" => strtotime("now"),
						"modifytime" => strtotime("now")
				);
				$this-> assign('qinfo',$qinfo);
				$content = $this->fetch();
				$content = ChageATurl($content);//修正素材文件路径
				$this->show($content); 
			}
		}else{
		//保存数据 
			$cid = I('post.cid'); 
			session('admincid',$cid); //保存下 下次添加时调用 显示效果为 上次添加的栏目
			if($cid == -2){ $this -> error('灰色的栏目 您没有权限添加或修改！'); } //判断权限
 			//检验时间是否有效 无效则设定为当前时间
			$createtime = strtotime("now"); //发布时间默认设为现在
			if(checkDateIsValid(I('post.createtime'))){ $createtime = strtotime(I('post.createtime')); } //如果有人修改 那判断是否符合时间格式 若符合 则重置。
			$modifytime = strtotime("now"); //修改时间总是自动变 
			//保存远程图片到本地
			$remotepic = I('post.remotepic',0); //保存远程图片
			$indexpicmode = C('indexpicmode'); //首张图设为形象图
			$content = I('post.content');//获取正文内容
			$content = htmlspecialchars_decode($content);//把预定义的HTML实体转换为字符
			if($remotepic == 1){
				echo '若存在远程图片 则自动下载替换并保存到本地...<br>'; 
				$path =  getimgpath($content); //正文内的图片远程路径数组 
				$savePath = saveimage($path); //保存图片并获取本地保存绝对路径 数组
				$content = str_ireplace($path, $savePath, $content); //远程图片路径替换为本地绝对路径 数组换数组
				
			}
			//保存第一张图为形象图
			$indexpic = I('post.indexpic');
			if($indexpicmode == 1 and empty($indexpic)){ //如果设置为保存 且 当前无形象图
				echo '若存在文章内图片 则自动保存首张为形象图...<br>';
				$path =  getimgpath($content); //正文内的图片远程路径数组
				$indexpic = $path[0]; //只取第一张^_^
			}
			//$content = htmlspecialchars($content);//转回 HTML 实体 用不着这个了
			//样式处理 
			$style = I('post.style').','.I('post.color');
			//描述处理 若空 取前250
			$description = I('post.description');
			$descriptionupdate = C('descriptionupdate');//是否自动更新描述
			if(empty($description) or $descriptionupdate==1){ $description = substr(strip_tags($content),0,250);}
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
				$qedit -> createtime = $createtime;
				$qedit -> modifytime = $modifytime;
				$qedit -> content    = $content;
				$qedit -> indexpic   = $indexpic;
				$qedit -> style      = $style;
				$qedit -> description= $description; 
				$qnewid = $qedit -> add();
				if ($qnewid >0) {   
					$qedit -> where('id = '.$qnewid) -> save($data2);
					//更新图片信息
					$model = M('upload'); 
					$data['aid'] = $qnewid;
					$data['cid'] = I('post.cid');
					$model -> where('aid = 0') -> save($data);
					//跳转
					$this -> success('添加成功！',U('content/index'),3);
				}else{ 
					$this -> error('添加失败，请重试！',U('content/index'),3);
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
				$qedit -> createtime = $createtime;
				$qedit -> modifytime = $modifytime;
				$qedit -> content    = $content;
				$qedit -> indexpic   = $indexpic;
				$qedit -> style      = $style;
				$qedit -> description= $description; 
				$qnewid = $qedit -> where('id='.$qid ) -> save();
				if ($qnewid >0) { 
					//更新图片信息
					$model = M('upload'); 
					$data['aid'] = $qid;
					$data['cid'] = I('post.cid'); 
					$model -> where('aid = 0') -> save($data); 
					//跳转
					$this -> success('修改成功！',U('content/index'),3);
				}else{ 
					$this -> error('修改失败，请重试！(数据无任何修改 会导致失败)',U('content/index'),3);
				}
			}
		} 
    }
 
	//ajax 文章状态
    function contentstate(){
		$qid = $_GET['id'];  
		$qedit = M('content');  
		if(is_numeric($qid) and $qid >= 1){
			$qnewid = $qedit -> where('id='.$qid ) -> getField('display');
			if (is_numeric($qnewid)) { 
				 if($qnewid == 0){
					 echo 'True|显示';
					 $date['display'] = 1; 
				 } else {
					 echo 'True|<font color=blue>隐藏</font>';
					 $date['display'] = 0;
				 }
				 $qedit -> where('id='.$qid ) -> save($date);
			} else {
				echo 'False|文章不存在！';
			}
		} else {
			echo 'False|参数格式不正确！';
		}
    } 
	
	//ajax 文章推荐状态
    function contentcommend(){
		$qid = $_GET['id'];  
		$qedit = M('content');  
		if(is_numeric($qid) and $qid >= 1){
			$qnewid = $qedit -> where('id='.$qid ) -> getField('commend');
			if (is_numeric($qnewid)) {  
				 if($qnewid == 0){
					 echo 'True|<font color=red>推荐</font>';
					 $qnewid = 1; 
				 } else {
					 echo 'True|普通';
					 $qnewid = 0;
				 }
				 $qedit -> where('id='.$qid)->setField('commend',$qnewid);
			} else {
				echo 'False|文章不存在！';
			}
		} else {
			echo 'False|参数格式不正确！';
		}
    } 
	
	//删除信息
    function del(){ 
		$id = $_GET['id'];  
		$ids = input_treat($_GET['ids']); 
		if(!empty($ids)){$id = $ids; $id = rtrim($id, ',');} 
		$qnewid = M("content") -> where('id in ('.$id.')') -> delete();
		if ($qnewid) {
			//更新图片信息
			$model = M('upload'); 
			$data['aid'] = -1; $data['cid'] = -1;
			$pid = $model -> where('aid in ('.$id.')') -> save($data);
			$msg = '删除成功！';
			if(is_numeric($pid)){$msg.=' 对应图片信息抹去成功';} 
			$this -> success($msg,U('content/index'),3); 
		}else{ 
			$this -> error('删除失败，请重试！',U('content/index'),3);
		} 
    }

	//移动信息
    function move(){ 
		$ids = input_treat($_GET['ids']);//数组转字符串
		$ids = rtrim($ids, ',');//去掉结尾的,号
		$data['cid'] = $_GET['newcid']; 
		if(!empty($ids)){
			$id = $ids;  
			$qnewid = M("content") -> where('id in ('.$ids.')') -> save($data);
			if ($qnewid) { 
				$this -> success('移动成功！',U('content/index'),3); 
			}else{ 
				$this -> error('移动失败，请重试！',U('content/index'),3);
			} 
		}
    }
 
 	//上传文件
	function upload(){ 
     if (!empty($_FILES)) {
		 	$admin = session('admin'); //获取当前管理员的上传类型记录
			$isrecord = 1; //是否记录到上传文件表
			$extraurl = I('get.url',null); //获取额外上传地址
			if(!empty($extraurl)){ //如果存在
				$extraurl =  '.'.$extraurl;//增加./前缀
				if(file_exists($extraurl)){//目录真实存在 
					$rootPath = $extraurl;
					$subName = '';
					$isrecord = 0;
				}else{
					echo 'error:指定上传目录'.$extraurl.'不存在！为安全起见，请手工建立！'	;
					die;
				} 
			}else{
				$rootPath = '.'.C('installdir').'uploadfile/';
				$subName = array('date','Ym/d');
			}
			
			$uploadfileexts = explode("/",$admin['uploadfileexts']);
			$uploadfilesize = $admin['uploadfilesize'] * 1024;
            //图片上传设置 
            $config = array(   
                'maxSize'    =>    $uploadfilesize, 
                'rootPath'	 =>    $rootPath,
                'savePath'   =>    '',  
                'saveName'   =>    array('uniqid',''), 
                'exts'       =>    $uploadfileexts,
                'autoSub'    =>    true,   
                'subName'    =>    $subName, //二级目录建立格式
            );
            $upload = new \Think\Upload($config);// 实例化上传类
            $images = $upload->upload();
            //判断是否有图 反馈的$images是一个数组File,下设以下内容举例
			//[name] => 5u.jpg  
			//[type] => image/jpeg  
			//[size] => 34436  
			//[key] => File  
			//[ext] => jpg  
			//[md5] => 3a53b9785c49b537d4cfd5a8f0e1a57b  
			//[sha1] => 27ab1f16c594729e04976cf031097bb5ea73d312 
			//[savename] => 1440058756.jpg  
			//[savepath] => 201508/20/
            if($images){
                $info = $images['Filedata']['savepath'].$images['Filedata']['savename']; 
				$ext = $images['Filedata']['ext'];
                //返回文件地址和名给JS作回调用 
                //图片信息入库upload表 默认CID AID为0
				$savepath = C('installdir').'uploadfile/'.$images['Filedata']['savepath'].$images['Filedata']['savename'];
				if($isrecord==1){ //是否记录和打水印
					$model = M('Upload');  
					$data['dir'] = $savepath;
					$data['ext'] = $ext;
					$data['aid'] = 0;
					$data['cid'] = 0;
					$data['time'] = strtotime("now");
					$qnewid = $model->add($data);  
					addwatermark($savepath); //图片格式文件则添加水印
				}
            }
            else{
				$info = $upload->getError();
				$info = 'error:'.$info;  //获取失败信息
            }
			echo $info;
        }else{ 
			$this->display();
		} 
    }
	
	//违规信息
	function _empty($name){
		error_404($name);
    }
}