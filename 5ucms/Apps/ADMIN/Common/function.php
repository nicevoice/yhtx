<?php
//admin部分使用

//重置栏目子分类合集 
function Reloadechildid($fid , $ids, $deeppath , $deeppathMax){
	if (empty($deeppathMax)){ //首次执行
		$ids = M('channel') -> field('deeppath') -> order('deeppath desc') -> find(); 
		$deeppathMax = end($ids); //获取当前最大层级值
		$deeppath = $deeppathMax; //默认当前层级为最大层级
	} 
	if (empty($fid)){ //首次执行
		$cids = M('channel') -> field('id,deeppath,fatherid,childid,childids') -> where('deeppath='.$deeppath) -> select(); //找指定层级的栏目 默认第一次为最大
	}else //重复执行
	{
		$cids = M('channel') -> field('id,deeppath,fatherid,childid,childids') -> where('id='.$fid) -> select(); //找指定层级的栏目 默认第一次为最大		
	} 
	for($i=0;$i<count($cids);$i++){ //循环处理层级
		$id = $cids[$i]['id'];
		$deeppath = $cids[$i]['deeppath'];
		$fatherid = $cids[$i]['fatherid'];
		$childid = $cids[$i]['childid'];
		$childids = $cids[$i]['childids'];
		if($deeppath == $deeppathMax){
			//最底层 子栏目设为自己和空并保存
			$newid = $id;
			$data['childids'] = $newid;
			$data['childid'] = ""; 
		}
		else
		{
			//非最底层 子栏目设为自己 和 传递过来的 子栏目合集并保存
			$newid = ChannelRepeat($id . ',' . $childid . ',' . $ids); //去重复
			$newid2 = ChannelRepeat($childid . ',' . $ids);
			$data['childids'] = $newid;
			$data['childid'] = $newid2; 
		}
		$ids = M('channel') -> field('childid,childids') -> where('id='.$id) -> save($data); //保存当前新的子栏目合集
 		//是否需要向上层栏目继续循环
		if($fatherid >0){
			Reloadechildid($fatherid,$newid,$deeppath,$deeppathMax);
		}
	}
}

//栏目合集去重复
function ChannelRepeat ($a){
	 $r = implode(",",array_unique(explode(",",$a)));
	 return str_replace(',,',',',$r); //去掉多余的,,
} 
//后台登陆检测
function checklogin(){
		$auth = session('admin');
		if(!$auth['uid']){
			return false;
		}else{return true;}	
}

//后台权限检测
function checklevel($c){
		$auth = session('admin'); 
		$level = ','.strtolower($auth['levels']).',';
		$c = ','.strtolower($c).','; 
		if(strpos($level,$c)!==false){
			return true;
		}else{return false;}	
}

//删光指定目录 150803
function unlink_dir($path){
	$_path = realpath($path);
	if (!file_exists($_path)) return false;
	if (is_dir($_path)) {
		$list = scandir($_path);
		foreach ($list as $v) {
			if ($v == '.' || $v == '..') continue;
			$_paths = $_path.'/'.$v;
			if (is_dir($_paths)) {
				unlink_dir($_paths);
			} elseif (unlink($_path) === false) {
				return false;
			}
		}
		return true;
	}
	return !is_file($_path) ? false : unlink($_path);
}

//读取需要的模板名称 150817
function gettemplatedir($qname = 'Index'){ 
  $templatedir = C('templatedir'); 
  $r = '<option value="">请点我选择合适的模板</option>'; 
  $alltemplatehtml = glob('.'.C('installdir').'Template/'.$templatedir.'/*.html'); //读取全部html文件 
  foreach ($alltemplatehtml as $k => $v) {
	  $v = str_ireplace('.'.C('installdir').'Template/'.$templatedir.'/','',$v);  
	  if(strstr($v,$qname)){ 
		  switch($qname){
			  case 'Index' : //为首页时 
					  if(!strstr($v,'Common')){
						  $r .= '<option value="'.$v.'">'.$v.'</option>';	
					  }
					  break;
			  case 'Common' :
					  if(!strstr($v,'_index')){
						  $r .= '<option value="'.$v.'">'.$v.'</option>';	
					  } 
					  break; 
			  default:
			  		 $r .=  '<option value="'.$v.'">'.$v.'</option>';	
		  } 
	 } 
  }
  return $r;
}

//更新缓存 150823
function refreshdata($e){
			$r = '<span style="font-size:10px;">'; 
			//读取congfi配置 
			$str="<?php\r\nreturn array(\r\n";
			$config=M('config');
			$result=$config->getField('name,value',true);
			$result = array_change_key_case($result,CASE_LOWER);//将数组中的键值转换为小写
			foreach ($result as $k => $v) {
				$str.="    '$k'=>'$v',\r\n";
			}
			$str.=");";
			//字符串写入配置文件 
			$file='.'.C('installdir').'Apps/Common/Conf/setting.php';//配置文件路径
			file_put_contents($file, $str);
			$r .= '配置已更 '; 
			//读取栏目配置 
			$str="<?php\r\nreturn array(\r\n";
			$result = $config=M('channel')->getField('id,cid,fatherid,childid,childids,deeppath,name,order,table,domain,outsidelink,templatechannel,templateclass,templateview,ruleindex,rulechannel,ruleview,picture,keywords,description',true);  
			$str.=" 'getchannel' => array(\r\n";
			foreach($result as $k=>$v) {
				$str.="            '$k'=>array(\r\n";
				$v = array_change_key_case($v,CASE_LOWER);//将数组中的键值转换为小写
				foreach($v as $k2=>$v2) {
					$str.="                             '$k2'=>'$v2',\r\n";
				} 
				$str.="                ),\r\n";
			} 
			$str.=")\r\n);"; 
			//字符串写入配置文件 
			$file='.'.C('installdir').'Apps/Common/Conf/channel.php';//配置文件路径
			file_put_contents($file, $str);
			$r .= '栏目已更<br>'; 
			//清空站内链接缓存
			S('sitelink',null); 
			//清空缓存目录
 			if(deletedir('.'.C('installdir').'Apps/Runtime/')){
				$r .= '缓存清除完毕!<br>'; 
			}else{
				$r .= '缓存清除失败!<br>'; 	
			}   
			$r .=  date('Y-m-d H:i:s',time()); 
			$r .= '</span><br>'; 
			if($e){echo $r;}{return true;}
}
?>