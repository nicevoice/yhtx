<?php   
//5ucms 模板标签引擎 $c是模板内容 $id是栏目或内容ID $type是类别 首页0 列表1 内容2 或 插件3+
function qss5ucms($c,$type=0,$id=0){
	//简单标签替换
	$c = Parser_My($c);  // 自定义标签 
	$c = Parser_Sys($c); // 系统标签 
	$c = Parser_Field($c,$type,$id); //直接调用标签 
	$c = Parser_Url($c);//网址转换快捷标签
	$c = Parser_C($c);  // 系统配置标签
	$c = Parser_Com($c,$type,$id); //列表标签处理  
	$c = Parser_Tag($c,$type,$id); //特殊标签 放最后是因为有分页tag:page要加载com里生成的分页信息
	$c = Parser_IF($c); //IF处理 IF ELSE END 	
	return $c  ;
}
 
//替换自定义标签自定义标签 150821 my标签里可能存在其他标签 所以优先  
function Parser_My($m){ 
	$p = '/\{(my|m)\:(.+?)\}/i'; //格式 {my:xxx} 简写{m:xx}
	preg_match_all($p, $m, $matches); 
	for($k=0;$k<count($matches[0]);$k++){
		$c = $matches[0][$k]; 
		$name = $matches[2][$k]; 
		$name = strtolower($name); //值设为小写 
		$name2 = getnamevalue($name);//判断是否有函数 进行信息分割 
		$map['name'] = $name2;  
		$info 	 = M('label') -> field("code") -> where($map)->find(); 
		if(!is_null($info)){
			$value = $info['code'];
			$value = Parser_attr($name,$value);//加载各种处理函数 
		}else{
			$value = '<font color=red>:( 自定义标签{my:'.$name.'}不存在</font>'; 
		} 
		$m = str_ireplace($c,$value,$m);
	} 
	return $m;
}

//替换系统标签 150821 系统标签等 加/i 忽略大小写 
function Parser_Sys($m){ 
	$p = '/\{(sys|s)\:(.+?)\}/i'; //格式 {sys:xxx} 简写{s:xx}
	preg_match_all($p, $m, $matches); 
	for($k=0;$k<count($matches[0]);$k++){
		$c = $matches[0][$k]; 
		$name = $matches[2][$k]; 
		$name = strtolower($name); //值设为小写 
		$name2 = getnamevalue($name);//判断是否有函数 进行信息分割 
		$info = C($name2); //直接从缓存配置里获取所需内容
		if(!empty($info)){
			  $value = $info;
			  $value = Parser_attr($name,$value);//加载各种处理函数 
		}else{
			return '<font color=blue>:( 系统标签{sys:'.$name.'}不存在</font>'; 
		}
		$m = str_ireplace($c,$value,$m);
	} 
	return $m;
}

//替换数据库中直接属性
function Parser_Field($m,$type,$id){
	$p = '/\{(field|f)\:(.+?)\}/i';  //格式 {field:xxx} 简写{f:xx}
	preg_match_all($p, $m, $matches); 
	for($k=0;$k<count($matches[0]);$k++){
		$c2 = $matches[0][$k]; 
		$name = $matches[2][$k]; 
		$name = strtolower($name); //值设为小写 
		$name2 = getnamevalue($name);//判断是否有函数 进行信息分割 
		//一些简写
		if($name2=='gjc'){$name2='keywords';}
		if($name2=='ms') {$name2='description';}
		switch($type){ 
		   case 0: //首页
					$table = "config"; 
					if($name2=='title' or $name2=='bt') { $name2 = "webname"; } //首页名称标签转换
					if($name2=='keywords') { $name2 = "indexkeywords"; } //首页关键词标签转换
					if($name2=='description') { $name2 = "indexdescription"; } //首页描述标签转换
					if($name2=='id' or $name2=='cid') { $value = 0; break;} //首页ID标签转换 为IF判断高亮等情况作准备
					$map['name'] = $name2 ;
					$info = M($table) -> field('value') -> where($map)->find();
					if($info>0){
						$value = $info['value'];
						$value = Parser_attr($name,$value);//加载各种处理函数 
					}else{
						$value = '此条件['.$type.']['.$name2.']下暂不支持field标签'; 
					}
					break;   
		   case 1: //栏目
					$table = "channel";
					if($name2=='curl') { $value = U('channel/index','id='.$id); break;} 
					if($name2=='id' or $name2=='cid'){ $value = $id;}  
					if($name2=='cname' or $name2=='title' or $name2=='bt') { $name2 = "name"; } //栏目标题标签转换  
					$getchannel = C('getchannel.'.$id); //获取栏目配置
					$value = $getchannel[$name2];
					break;
		   case 2: //内容
					$table = "content";
					if($name2=='bt'){$name2='title';}
					if($name2=='zz'){$name2='author';}
					if($name2=='ly'){$name2='source';}
					if($name2=='aurl') { $value = U('content/index','id='.$id); break; } 
					if($name2=='id' or $name2=='aid'){ $value = $id; break; }  
					//内容表其他内容处理
					$map['id'] = $id; 
					$info = M($table) -> where($map) -> find();
					$info = array_change_key_case($info,CASE_LOWER);//将数组中的键值转换为小写 
					if(!is_null($info)){   
						$c = $info[$name2];//获取字段值
						switch($name2){ 
		   					case 'createtime': //内容表时间处理 
								$c = date('Y-m-d H:i:s',$c); break;
							case 'modifytime':
								$c = date('Y-m-d H:i:s',$c); break;
 							case 'content'://文章模式 内容字段 转换站内链接 
								$title2 =  $info['title']; //获取文章标题 
								$c = replacesitelink($info['content']);//增加站内链接
								$c = addimgalt($c,$title2);//增加图片alt属性 有利SEO 
								break;
						} 
						//结束内容处理 
						$value = Parser_attr($name,$c);//加载各种处理函数  
					}else{
						$value = '<font color=blue>:( 直接标签{field:'.$name2.'}不存在！</font>'; 
					} 
					break;
		   default:	//其他	
				$value = '此条件['.$type.']['.$name2.']下暂不支持field标签'; 
		} 
					
		$m = str_ireplace($c2,$value,$m);
	} 
	return $m;
}

//替换地址快捷标签 150821 系统标签等 加/i 忽略大小写 
function Parser_Url($m){ 
	$p = '/\{(aurl|curl|cname|title)\:(\d+)\}/i'; //格式 {aurl:xxx}  等等 xxx必须为数字
	preg_match_all($p, $m, $matches); 
	for($k=0;$k<count($matches[0]);$k++){
		$c = $matches[0][$k]; 
		$key = $matches[1][$k];
		$id = trim($matches[2][$k]);
		switch($key){
			case 'aurl':
				$value = U('content/index','id='.$id);
				break;
			case 'curl':
				$value = U('channel/index','id='.$id);
				break;	
			case 'cname':
				$getchannel = C('getchannel.'.$id); //获取栏目配置
				$value = $getchannel['name'];
				break;	
			case 'title':
				$value = M('content') -> where('id = '.$id) -> getField('name'); 
				break;	
		}
 
		if(empty($value)){
			 $value = '<font color=blue>:( 此快捷标签{'.$key.':'.$id.'}语法错误</font>'; 
		}
		$m = str_ireplace($c,$value,$m);
	} 
	return $m;
}

//替换地址快捷标签 150821 系统标签等 加/i 忽略大小写 
function Parser_C($m){ 
	$p = '/\{(c)\:(\w+)\}/i'; //格式 {aurl:xxx}  等等 xxx必须为数字
	preg_match_all($p, $m, $matches); 
	for($k=0;$k<count($matches[0]);$k++){
		$c = $matches[0][$k]; 
		$key = $matches[1][$k];
		$id = trim($matches[2][$k]);
		$value = C($id); 
		if(empty($value)){
			 $value = '<font color=blue>:( 此系统配置标签{'.$key.':'.$id.'}不存在</font>'; 
		}
		$m = str_ireplace($c,$value,$m);
	} 
	$times++; //限制循环次数 
	if(preg_match($p,$m,$matche) and $times<=9) { $m = Parser_C($m,$type,$id,$times); } //多次循环 
	return $m;
}

//替换特殊标签Tag 150821 系统标签等 加/i 忽略大小写 
function Parser_Tag($m,$type,$id,$times=0){ 
	$p = '/\{(tag|t)\:(.+?)\}/i'; //格式 {tag:xxx} 简写{t:xx}
	preg_match_all($p, $m, $matches); 
	for($k=0;$k<count($matches[0]);$k++){
		$c = $matches[0][$k]; 
		$name = $matches[2][$k]; 
		$name = strtolower($name); //值设为小写  
		$name2 = getnamevalue($name);//判断是否有函数 进行信息分割
		//上一篇下一篇功能开始
		if(strstr($name2,"pre") or strstr($name2,"next")){ 
			$Indexname = C('indexname'); //首页名称
			$Indexview = C('indexview'); //首页路径   
			switch($type){
				case 1: //栏目 
					$preid = M('channel') -> where('id<'.$id) -> getField('id');
					$nextid = M('channel') -> where('id>'.$id) -> getField('id'); 
					if(!empty($preid)){ //有结果则展示栏目的链接和标题 
						  $preurl = U('channel/index','id='.$preid);
						  $getchannel = C('getchannel.'.$preid);
						  $pretitle = $getchannel['name'];
						  $pre =' <a href="'.$preurl.'" title="'.$pretitle.'">'.$pretitle.'</a> '; 
					}
					if(!empty($nextid)){ //下一篇 有结果则展示栏目的链接和标题 
						  $nexturl = U('channel/index','id='.$nextid);
						  $getchannel = C('getchannel.'.$nextid);
						  $nexttitle = $getchannel['name'];
						  $next =' <a href="'.$nexturl.'" title="'.$nexttitle.'">'.$nexttitle.'</a> '; 
					}
					$getchannel = C('getchannel.'.$cid);  
				break; 
				case 2: //内容
					//获取CID
					$cid = M('content') -> where('id='.$id) -> getField('Cid');
					//获取当前栏目信息
					$getchannel = C('getchannel.'.$cid);
					$channelurl = U('channel/index','id='.$cid);
					$channeltitle = $getchannel['name'];
					$channelinfo = ' <a href="'.$channelurl.'" title="'.$channeltitle.'">'.$channeltitle.'</a> ';
					//获取上下文模式
					$Prenextmode = C('prenextmode');
					$qmode = ''; 
					if($Prenextmode==1){ //栏目模式 配置里可设定 也可设为全局模式
						$qmode = ' and cid = '.$cid ;
					} 
					$preinfo = M('content') -> where('id<'.$id .$qmode) -> getField('id,title');
					$nextinfo = M('content') -> where('id>'.$id .$qmode) -> getField('id,title'); 
					if(!empty($preinfo)){ //有结果则展示文章链接和标题
						foreach ($preinfo as $k => $v) { 
							$preurl = U('content/index','id='.$k);
							$pretitle = $v;
							$pre =' <a href="'.$preurl.'" title="'.$pretitle.'">'.$pretitle.'</a> ';

						}
					} else { //无结果则显示当前栏目信息
							$pre = $channelinfo;
							$preurl = $channelurl;
							$pretitle = $channeltitle;
					}
					if(!empty($nextinfo)){ //下一篇 有结果则展示文章链接和标题
						foreach ($nextinfo as $k => $v) { 
							$nexturl = U('content/index','id='.$k);
							$nexttitle = $v;
							$next =' <a href="'.$nexturl.'" title="'.$nexttitle.'">'.$nexttitle.'</a> ';
						}
					} else { //无结果则显示当前栏目信息
							$next = $channelinfo; 
							$nexturl = $channelurl;
							$nexttitle = $channeltitle;
					} 
				break;
				default: //其他情况 
							$pre = ' <a href="'.$Indexview.'" title="'.$Indexname.'">'.$Indexname.'</a> ';
							$next = ' <a href="'.$Indexview.'" title="'.$Indexname.'">'.$Indexname.'</a> '; 
							$preurl = $Indexview;
							$nexturl = $Indexview; 
							$pretitle = $Indexname;
							$nexttitle = $Indexname;
			}
		}
		//上一篇下一篇功能结束
		
		switch($name2){
			case 'sitepath': //当前路径
				$value = getsitepath($type,$id); //调用funciton内函数
				$value = Parser_attr($name,$value);//加载各种处理函数 
				break; 
			case 'pre': //上一个栏目/内容
				$value = $pre;
				$value = Parser_attr($name,$value);//加载各种处理函数 
				break;
			case 'next': //下一个栏目/内容
				$value = $next;
				$value = Parser_attr($name,$value);//加载各种处理函数 
				break;
			case 'preurl': //上一个栏目/内容 链接
				$value = $preurl;
				$value = Parser_attr($name,$value);//加载各种处理函数 
				break;
			case 'nexturl': //下一个栏目/内容 链接
				$value = $nexturl;
				$value = Parser_attr($name,$value);//加载各种处理函数 
				break;
			case 'pretitle': //上一个栏目/内容 标题
				$value = $pretitle;
				$value = Parser_attr($name,$value);//加载各种处理函数 
				break;
			case 'nexttitle': //下一个栏目/内容 标题
				$value = $nexttitle;
				$value = Parser_attr($name,$value);//加载各种处理函数 
				break;
			case 'inside': //嵌入
				$value = '<font color=blue>:( 嵌入标签{tag:inside}只允许在自定义页和插件中使用！</font>'; 
				break;
			case 'page': //分页
				$value = session('qpage'.$id); 
				break;
		    default:	//其他	
				$value = '<font color=blue>:( 此条件['.$type.']下暂不支持tag标签</font>'; 
		}
		$m = str_ireplace($c,$value,$m);
	}
	$times++; //限制循环次数 
	if(preg_match($p,$m,$matche) and $times<=9) { $m = Parser_Tag($m,$type,$id,$times); } //多次循环 
	return $m;
}

//第三步 列表标签处理 自建模板引擎 150814- 150821 本程序最有价值的地方 好好研究吧
function Parser_Com($c,$qtype,$id,$times=0){ 
	$p = '/<!--(\w+):\{(.+?)\}-->([\s\S]*?)<!--\1-->/i'; 
	preg_match_all($p, $c, $matches); 
	for($k2=0;$k2<count($matches[0]);$k2++){
		$com      = $matches[0][$k2]; //整体
		$listname = $matches[1][$k2]; //前缀
		$attr     = $matches[2][$k2]; //条件
		$content  = $matches[3][$k2]; //循环体  
		$attr 	  = str_replace('[', '', $attr);
		$attr 	  = str_replace(']', '', $attr); //去掉ASP版5U标签里多余的[ ]符号 
		$table	  = getattr($attr,'table'); if(empty($table)){$table='content';}//默认表 不填为内容表 
		$mode 	  = getattr($attr,'mode'); //是否推荐 
		$cid  	  = getattr($attr,'cid'); //栏目CID 
		$aid  	  = getattr($attr,'aid'); //排除内容Aid
		$type  	  = getattr($attr,'type'); //图片类型
		$row  	  = getattr($attr,'row'); if(empty($row)){$row=10;} //调用数量 默认10
		$size  	  = getattr($attr,'size'); if(empty($size)){$size=10;} //栏目调用数量 默认10
		$field 	  = getattr($attr,'field'); //读取字段 默认全部
		$order	  = getattr($attr,'order'); if(empty($order)){$order='id desc';} //排序 默认ID从大到小
		$where	  = getattr($attr,'where'); //范围
		$sql  	  = getattr($attr,'sql'); //sql语句  
		if(empty($qtype)){$qtype=0;} //默认首页模式
		if($qtype==1){$pagecid  = $id ;} else {$pagecid = 0 ;} //栏目分页特殊用途 栏目模式下cid为当前cid 其他模式下为全部cid
		if(empty($pageid)){$pageid=1;} //默认页码
		//设定where
		if(!empty($where)){ 
			$w = $where;	
		}else{
			//where首条件
			if($table == 'content'){
				$w = 'display=1 '; //默认条件 隐藏的不显示
			}else{
				$w = 'id>0' ;
			}
		} 
		//order数组处理
		$order = str_replace('，', ',', $order); 
		if(strstr($order,',')){
			$order = str2arr($order,',',' ');//多个order转为数组
		} 
		//内容表专用标签
		if(strtolower($table)=='content'){  
			//是否推荐 
			switch($mode){ 
			   case "commend": 
						$w = $w . ' and commend=1 ';
						break;   
			   case "uncommend":
						$w = $w . ' and commend=0 ';
						break;
			   case "hot": 
						$order = ' views desc ';
						break;
			}
			//是否带图
			switch($type){ 
			   case "images": 
						$w = $w . ' and indexpic<>"" ';
						break;   
			   case "noimages":

						$w = $w . ' and Indexpic="" ';
						break;
			}
			if($listname=='page'){ //page分页模式 
				$table = 'content'; // page模式下表名强行设为content;
				//设定分页栏目CID 不存在则定位为首页
				if(is_numeric($pagecid) and $pagecid>0){ 
					$w = $w . ' and cid = '.$pagecid ;
				}  
				$qcount = M($table)->where($w) -> count(); //统计条数
				$page = getpage2($qcount,$size,$pageid);   //page模式下分页数量用$size;
				$row = $page['limit'];//排序方式 
				session('qpage'.$pagecid,$page['pages']);
			}
			else { //非page分页模式
				//排除AID
				if(!empty($aid)){
					$w = $w . ' and id not in ('.$aid.') ';	
				}
				//设定归属栏目CID
				if(!empty($cid)){ 
					$w = $w . ' and cid in ('.$cid.') ';	
				} 
			}
		} 
		//SQL模式下 只执行SQL语句 其他条件将无效 page模式不支持SQL
		if(!empty($sql) and $listname<>'page'){
			$list = new \Think\Model();
			$list->query($sql);
		} else { 
			$list = M($table)->where($w)->limit($row)->order($order)->select();  
		}   

     	//对生成的数据进行额外处理，并导入$array数组
		$array = $list; //再创建一个数组;
        for($i=0;$i<count($list);$i++){ 
            $array[$i][i] = $i+1; //i标签 从1开始
			$array[$i][j] = $i; //j标签 从0开始 需要别的再自己定义
			if(strtolower($table)=='content'){ //文章表特殊标签
				$array[$i]['aurl'] = U('content/index','id='.$list[$i]['id']);  //处理内容网址
				$array[$i]['curl'] = U('channel/index','id='.$list[$i]['cid']); //处理栏目网址
				$getchannel = C('getchannel.'.$list[$i]['cid']);
				$array[$i]['cname'] = $getchannel['name']; //处理内容中栏目名称 
				$array[$i]['createtime'] = date('Y-m-d H:i:s',$list[$i]['createtime']); //处理发布时间戳
				$array[$i]['modifytime'] = date('Y-m-d H:i:s',$list[$i]['modifytime']); //处理修改时间戳
			}
			if(strtolower($table)=='channel'){  //栏目表特殊标签
				$array[$i]['cname'] = $list[$i]['name']; //处理栏目名称
				$array[$i]['title'] = $list[$i]['name']; //处理栏目名称
				$array[$i]['curl'] = U('channel/index','id='.$list[$i]['id']); //处理栏目网址
			}
			if(!empty($list[$i]['time'])){  //处理其他栏目时间戳
				$array[$i]['time'] = date('Y-m-d H:i:s',$list[$i]['time']); //处理修改时间戳
			} 
			//处理titlex 为标题加上样式
			$array[$i]['titlex'] = $list[$i]['title'] ;
			$style = $list[$i]['style'];
			if(!empty($style)){
				$stylearr = explode(',',$style); 
				if(!empty($stylearr[0])){$array[$i]['titlex'] = '<'.$stylearr[0].'>'.$array[$i]['title'].'</'.$stylearr[0].'>';} //加粗 斜体
				if(!empty($stylearr[1])){
					if(!empty($array[$i]['titlex'])){$qcolor = $array[$i]['titlex'];}else{$qcolor = $array[$i]['title'];}
					$array[$i]['titlex'] = '<font color="'.$stylearr[1].'">'.$qcolor.'</font>';
				} //颜色
			}  
		} 
		//替换标签组内容里子标签
        preg_match_all('/\['.$listname.':(.*?)\]/i',$content,$arry); //读取此次循环下的 [list:xxx] 格式的值并替换
        $tag = $arry[0]; //匹配标签数组 比如 0=>[qlist:ID],1=>[qlist:Title]
        $key = $arry[1]; //标签字段数组 比如 0=>ID,1=>Name,2=>Title 
        $str = '';
		for($i=0;$i<count($array);$i++) //把数据库中调用出来的数据 和 标签中数据 进行对比 替换
        {  
			$array[$i] = array_change_key_case($array[$i],CASE_LOWER);//将数组中的键值转换为小写
			$c2 = $content; //每次循环时，读取标签块中间内容 进行替换 
			$title2 =$array[$i]['title']; //如果有文章标题 则读取
            //替换标签 $v 是单独的 一个 比如  [qlist:name]
            foreach($tag as $k=>$v){ //$k是数组序号0 1 2 3 4，$v是 类似 [qlist:Title] 结构
				//$key[$k] 是 比如读取[qlist:Title]中的后缀 就是 Title
				$arr = getnamevalue($key[$k]);//判断是否有函数 进行信息分割
				//找到和数据库里一样的数值，比如Title
				$qkey = strtolower($arr); //就是 比如Title 同时转为小写模式 变成title 方便下方替换 			
                $th = $array[$i][$qkey];
				if(!is_null($th)){  
					if(strstr($v,':content]')){ //如果是文章内容 则额外处理
						$th = replacesitelink($th);//增加站内链接
						$th = addimgalt($th,$title2);//增加图片alt属性 有利SEO 	
					}
					$th = Parser_attr($key[$k],$th);//加载各种处理函数
					$c2 = str_ireplace($v,$th,$c2); //不区分大小写替换  
				} 
            } 
			$str .= $c2; //处理过的循环数据拼合
        }
		//$str为当次循环后的内容结果
		$value = $str; 
		$c = str_ireplace($com,$value,$c); //逐个替换
	}
	$times++;//限制循环次数 以免出错
	if(preg_match($p,$c,$matche) and $times<=9) { $c = Parser_Com($c,$type,$id,$times); } //多次循环
	return $c; 
} 

//处理if判断标签 格式为 {if:条件} axxx {else} xxxb {end if}
function Parser_IF($c,$times=0){
	//return $c; 
	$p = '/\{if:(.+?)\}([\s\S]*?)\{end if\}/i';
	preg_match_all($p, $c, $matches);  
	for($k=0;$k<count($matches[0]);$k++){
		$value = '';
		$iff      = $matches[0][$k]; 
		$conditin = $matches[1][$k];
		$r        = $matches[2][$k];
		if(strstr($r,'{else}')){ //如果存在else
			$rr = explode('{else}',$r);
			$r1 = $rr[0];
			$r2 = $rr[1];
			$goeval = 'if('.$conditin.'){$value = "'.htmlspecialchars($r1).'";}else{$value = "'.htmlspecialchars($r2).'";}';
		} else {
			$goeval = 'if('.$conditin.'){$value = "'.htmlspecialchars($r).'";}'; 
		} 
		//var_dump($goeval);
		if(!empty($conditin)){eval($goeval);
			$value =  htmlspecialchars_decode($value); 
		} else { $value = 'if判断语句失败！';} 
		
		$c = str_ireplace($iff,$value,$c);
	} 
	$times++; //限制循环次数  
	if(preg_match($p,$c,$matche) and $times<=9) { $c = Parser_IF($c,$times);} //多次循环可能存在的嵌套
	return $c; 
}

//处理单个标签内容 比如截断 去html代码 大小写 等等 150817-150821
function Parser_attr($name,$namevalue){ 
	$r = $namevalue; //获取字段值 
	$name2 = strtolower(getnamevalue($name));//获取字段名
	//图片处理
	$width = getattr($name,'width');
	$height = getattr($name,'height');
	if($name2=='indexpic' or $name2=='picture'){ //只有图片类型字段名才给缩放
		if(!empty($width) or !empty($height)){
			$t='';//错误提示
			if(empty($width) ){$width==0;}
			if(empty($height)){$height==0;}   
				$filemininame = basename($namevalue); //获取原图文件名
				$filemininame = str_ireplace('.','/'.$width.'x'.$height.'.',$filemininame); //生成所略图子路径 201508181439863987.jpg变201010291312299925/80x76.jpg 
				$smalldir = explode("/",$filemininame);
				$smalldir = '.'.C('installdir').'uploadfile/small/'.$smalldir[0];
				if(!file_exists($smalldir)){createdir($smalldir);}else{$t='生成文件夹失败 请检查权限！';}//缩略图所需文件夹不存在就生成下 
				if(!file_exists($smalldir)){$t='生成文件夹失败 请检查权限 生成路径='.$smalldir;}
				$file_mini='.'.C('installdir').'uploadfile/small/'.$filemininame; 
				if(!file_exists($file_mini)){ //不存在则生成 存在则跳过
					$image = new \Think\Image(); //初始化图片功能
					if(!file_exists('.'.$namevalue)){
						$t .= '原图在绝对路径上不存在';
						$file_mini= $namevalue;
					}else{
						$image->open('.'.$namevalue); //打开原图
						if(!$image){$t.='-打开原图失败，请检查图片是否存在！';}
						$ThumbType = C('thumbtype');//读取缩略图配置
						if(!is_numeric($ThumbType)){$ThumbType=1;}//如若配置异常，默认1，等比例缩放模式
						$image->thumb($width,$height,$ThumbType)->save($file_mini); 
						if(!$image){$t.='-生成缩略图失败，请检查权限或功能！';}
					}
				} 
			if(!empty($t)){$r = $file_mini.'?'.$t.$f;}else{$r = $file_mini;} //有错误提示则显示在图片路径+?错误
		}	 
	}
	//长度限制 左取
	$len = getattr($name,'len');
	if(!empty($len)){
		$r = substr($r,0,$len);
	}
	//长度限制 右取
	$len2 = getattr($name,'len2');
	if(!empty($len2)){
		$r = substr($r,-$len2);
	}
	//超出显示
	$lenext = getattr($name,'lenext');
	if(!empty($lenext)){
		$r = $r.$lenext;
	} 
	//格式化时间 $format=yy-mm-dd hh:nn:ss 150819
	$format = getattr($name,'format');
	if(!empty($format)){
		$r = strtotime($r); //先转为时间戳
		if(strstr($format,"WEEKA"))	{ $format = str_replace("WEEKA",date('l',$r),$format);} //星期几  英文全名; 如: "Friday"
		if(strstr($format,"weeka"))	{ $format = str_replace("WEEKA",date('D',$r),$format);} //星期几 三个英文字母; 如: "Fri"
		if(strstr($format,"WEEK"))	{ $weekarray=array("日","一","二","三","四","五","六");$format = str_replace("WEEK","星期".$weekarray[date('w',$r)],$format);} //星期几 中文
		if(strstr($format,"week"))	{ $format = str_replace("week",date('w',$r),$format);} //星期几 数字 0~6
		//if(strstr($format,"MONTHA")) { $format = str_replace("MONTHA",date('F',$r),$r);}
		if(strstr($format,"MONTH")) { $format = str_replace("MONTHA",date('F',$r),$format);}//月份，英文全名; 如: "January"
		if(strstr($format,"yyyy"))  { $format = str_replace("yyyy",date('Y',$r),$format);} //年，四位数字; 如: "1999"
		if(strstr($format,"yy"))    { $format = str_replace("yy",date('y',$r),$format);} //年，二位数字; 如: "99"
		if(strstr($format,"M")) 	{ $format = str_replace("M",date('M',$r),$format);} //月份，三个英文字母; 如: "Jan"
		if(strstr($format,"mm")) 	{ $format = str_replace("mm",date('m',$r),$format);} //月份，二位数字，若不足二位则在前面补零; 如: "01" 至 "12"
		if(strstr($format,"m"))		{ $format = str_replace("m",date('n',$r),$format);}//月份，二位数字，若不足二位则不补零; 如: "1" 至 "12"
		if(strstr($format,"dd"))	{ $format = str_replace("dd",date('d',$r),$format);}//几日，二位数字，若不足二位则前面补零; 如: "01" 至 "31"
		if(strstr($format,"d"))		{ $format = str_replace("d",date('j',$r),$format);}//几日，二位数字，若不足二位不补零; 如: "1" 至 "31"
		if(strstr($format,"hh"))	{ $format = str_replace("hh",date('H',$r),$format);}//24 小时制的小时; 如: "00" 至 "23"
		if(strstr($format,"h"))		{ $format = str_replace("h",date('G',$r),$format);}//24 小时制的小时，不足二位不补零; 如: "0" 至 "23"
		if(strstr($format,"nn"))	{ $format = str_replace("nn",date('i',$r),$format);}//分钟; 如: "00" 至 "59"
		if(strstr($format,"n"))		{ $format = str_replace("n",floor(date('i',$r)),$format);}//分钟; 如: "0" 至 "59"
		if(strstr($format,"ss"))	{ $format = str_replace("ss",date('s',$r),$format);} //秒; 如: "00" 至 "59"
		if(strstr($format,"s"))		{ $format = str_replace("s",floor(date('s',$r)),$format);}//秒; 如: "0" 至 "59"
		if(strstr($format,"z"))		{ $format = str_replace("z",date('z',$r),$format);} //一年中的第几天; 如: "0" 至 "365"
		$r = $format;
	} 	 
	return $r;
}


//获取当前栏目路径 栏目ID 分隔符 上次循环结果 15015-150817
function getchannelpath($id,$SitePathSplit,$prepath){
	$getchanel = C('getchannel.'.$id);
	$cname = $getchanel['name'];
	$curl = U('channel/index','id='.$id);
	$fatherid = $getchanel['fatherid'];
	$getchannelpath = ' <a href="'.$curl.'" title="'.$cname.'">'.$cname.'</a> ' . $SitePathSplit . $prepath;
	if($fatherid>0){
		$getchannelpath = getchannelpath($fatherid,$SitePathSplit,$getchannelpath);
	}
	return $getchannelpath;
} 


//获取当前路径 $type是类别 首页0 列表1 内容2 或 插件3+  15015-150817
function getsitepath($type=0,$id=0){
	$Indexname = C('indexname'); //首页名称
	$Indexview = C('indexview'); //首页路径
	$SitePathSplit = C('sitepathsplit'); //分隔符
	$getsitepath = ' <a href="'.$Indexview.'" title="'.$Indexname.'">'.$Indexname.'</a> ' . $SitePathSplit;
	switch($type){  
		   case 1: //栏目
					$table = "Channel";
					$getsitepath .= getchannelpath($id,$SitePathSplit); 
					break;
		   case 2: //内容 格式为 首页 栏目 文章标题
					$table = "Content";
					$cid = M('Content') -> where('id='.$id) -> getField('Cid');
					$getsitepath .= getchannelpath($cid,$SitePathSplit) . ' 正文 ';
					break;
		   default:	//首页 或 其他	
					$getsitepath .= $title; 
	}
	return  $getsitepath;
}

//返回值本身 去掉多余函数 150817-150821 
function getnamevalue($name){ 
	$p = '/(\w+) \$(.+?)/i';
	preg_match($p, $name, $matches); 
	if($matches){
		return $matches[1];
	}else{
		return $name;
	}
}

//判断值内有无指定函数 150817-150821
function getattr($name,$func){ 
	$name = $name. ' $';
 	$name = str_replace('$','$$',$name);
	$p = '/\$(\w+)=(.+?)\$/i';
	preg_match_all($p, $name, $matches); 
	for($k=0;$k<count($matches[0]);$k++){ 
		$funname  = strtolower($matches[1][$k]); //函数名称 如 $len=3 中的 len
		$funvalue = $matches[2][$k]; //函数值 如 $len=3 中的 3
		$funvalue = trim($funvalue);//去除两侧多余空格
		if($funname == $func){return $funvalue; }
	}
	return '';//存在函数 反馈函数值 方便计算 不存在则不反馈
}


//为图片增加alt属性，并写入值 150823-150824
function addimgalt($c,$t) { 
	$p="/<img(.*?)>/i";
	preg_match_all($p, $c, $arr); //如果存正常的图片
	for($i=0;$i<count($arr);$i++) //把数据库中调用出来的数据 和 标签中数据 进行对比 替换
	{  
		$imgall = $arr[0][$i];//图片整体
		$imgcon = $arr[1][$i];//图片内容区
		if(stripos($url,'alt')==false){ //如果图片中没有alt标记  class="img-responsive" 为选填 如果你用了拼图框架 用的上这个 否则可删除
			$value = '<img'.$imgcon.' class="img-responsive" alt="'.$t.'" onload="size(this)" onclick="op(this.src)" >';
			$c = str_ireplace($imgall,$value,$c); //不区分大小写替换  
		} else { //如果已有alt 则只增加图片大小限制js
			$value = '<img'.$imgcon.' class="img-responsive" onload="size(this)" onclick="op(this.src)" >';
			$c = str_ireplace($imgall,$value,$c); //不区分大小写替换  
		}
	}
	return $c;
}

//替换站内链接 150823
function replacesitelink($c){
  $list = S('sitelink'); //读取站内链接信息缓存
  if(empty($list)){ //不存在则重获取并生成
	  $list = M('sitelink') -> where('state = 1') -> order(array('order'=>'Desc','id'=>'Desc')) -> select(); //取出全部站内链接 按权重排序
	  S('sitelink',$list,600); //将站内链接信息生成缓存600秒 足够用的了
  }
  for($i=0;$i<count($list);$i++){  //循环调用全部信息
	  $Text = $list[$i]['text']; //链接关键词名称
	  $Description = $list[$i]['description']; //链接描述
	  $Link = $list[$i]['link']; //链接地址
	  $Replace = $list[$i]['replace']; //替换次数 为0时是替换全部 
	  if($Replace==0){$Replace = '99';}  //如果为0 替换全部 默认99次
	  $Target = $list[$i]['target']; //是否新窗口打开 
	  if($Target==1){$Target = 'target="_blank"';}else{$Target = '';}  
	  if(strstr($c,$Text)){//如果正文中包含这个词 
		  unset($qimg); unset($qa); //清空设定
		  // 第一步获取字符串里所有图片的正则表达式
		  preg_match_all("/<img(.*?)\>/i",$c,$arryimg);
		  $imgtimes = count($arryimg);
		  for($j=0;$j<$imgtimes;$j++){ //读取出符合的关键词名称
			  $img = $arryimg[0][$j]; 
			  $c = str_ireplace($img,'@img['.$j.']@',$c);//替换掉 变成链接
			  $qimg[$j] = $img;//存入数组
		  } 
		  // 第二步获取字符串里所有超链接的正则表达式
		  preg_match_all("/<a(.*?)>(.*?)<\/a>/i",$c,$arrya);
		  $atimes = count($arrya);
		  for($j=0;$j<$atimes;$j++){ //读取出符合的关键词名称
			  $a = $arrya[0][$j]; 
			  $c = str_ireplace($a,'@a['.$j.']@',$c);//替换掉 变成链接
			  $qa[$j] = $a;//存入数组
		  }
		  // 第三步 按指定次数替换
		  $c = preg_replace('/'.$Text.'/','<a href="'.$Link.'" title="'.$Text.'" '.$Target.'>'.$Text.'</a>',$c,$Replace);//替换掉 指定次数 变成链接 
		  // 第四步 恢复旧的图片
		  for($j2=0;$j2<count($qimg);$j2++){ 
			  $c = str_replace('@img['.$j2.']@',$qimg[$j2],$c);//替换掉 指定次数 变成链接
		  } 
		  //第五步 恢复旧的链接
		  for($j2=0;$j2<count($qa);$j2++){ 
			  $c = str_replace('@a['.$j2.']@',$qa[$j2],$c);//替换掉 指定次数 变成链接
		  } 
	  }//处理完成
  }
  return $c;
	
}