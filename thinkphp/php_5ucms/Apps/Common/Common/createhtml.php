<?php   
//5ucms 静态引擎 $type=0 首页 1列表页 2内容页 3自定义页
function createhtml($type,$id){ 
	if(!is_numeric($id) or $id<0){$id=0;} //判断下ID
	switch($type){
		case 0:	//首页
				  return true;
				  break;
		case 1:	//栏目页
				  return true;
				  break;
		case 2:	//内容页 
				  return true;
				  break;
		case 3: //自定义页 
				if($id>0){
					$do = creatediypage($id); //生成单个自定义页
				} else {
					//echo '正在生成全部自定义页<br>';
					$qinfo = M('diypage') -> getfield($id,true);  
					foreach($qinfo as $qid=>$val){
						//echo $qid;
						$do = creatediypage($qid);
					}
				}
				return $do;
				break; 
	}
	return false;
}

//静态生成自定义页
function creatediypage($id,$method=1){
	  //echo '正在生成的自定义页ID为'.$id.'<br>';
	  $qinfo = M('diypage') -> find($id); 
	  if(empty($qinfo)){return false;} 
	  $tpl = $qinfo['tpl']; 
	  //是否存在模板嵌套
	  if(empty($tpl)){ //不使用模板文件
		  //echo '不使用模板文件模式<br>';
		  $content = $qinfo['html'];
	  } else { //使用模板文件嵌套
		  $tpl = '.'.C('installdir').'Template/'.C('templatedir').'/'.$tpl;
		  //echo '使用模板文件模式:'.$tpl.'<br>';
		  $content = file_get_contents($tpl); //读取模板数据
		  $content = str_ireplace('{field:id}',$id,$content);
		  $content = str_ireplace('{field:diypageurl}',U('channel/diypage','id='.$id),$content);
		  $content = str_ireplace('{field:dir}',$qinfo['dir'],$content);
		  $content = str_ireplace('{field:title}',$qinfo['title'],$content);
		  $content = str_ireplace('{field:keywords}',$qinfo['keywords'],$content);
		  $content = str_ireplace('{field:description}',$qinfo['description'],$content);
		  $content = str_ireplace('{tag:inside}',$qinfo['html'],$content); 
	  } 
	  $content = ChageATurl($content,C('templatedir')); //修改资源文件路径 
	  $content = qss5ucms($content); //模板标签转换  
	  
	  if($method==1){
	 	 file_put_contents('.'.$qinfo['dir'],$content); //生成静态文件
	  } else {
	     echo '以下是'.$id.'预览结果(嵌套模板：'.$tpl.')：<hr>'.$content;
	  }
	  return true;
}