<?php
//全站公共使用 Apps/Common/Common

require_once(APP_PATH . '/Common/Common/addons.php'); //加载开发者二次开发公共函数库
require_once(APP_PATH . '/Common/Common/parser.php'); //加载5ucms模板引擎
require_once(APP_PATH . '/Common/Common/createhtml.php'); //加载5ucms静态引擎
//提示错误代码详情150711-150711
function show_bug($msg){
	header("content-type:text/html;charset=utf-8"); 
	echo "www.5ucms.com 错误代码详情:<hr><pre>";
	var_dump($msg);
	echo "</pre>";
}

//简单出错信息提示150711-150714 仅限DEBUG模式下显示
function error_404($name){
	if (APP_DEBUG == 1){
		header("content-type:text/html;charset=utf-8"); 
		echo "控制器：".CONTROLLER_NAME." 操作：".$name." 不存在! <br>what are you going to do? bad boy?! <br>你想干啥？干坏事是不好滴！ <br>need help? please QQ3876307 www.5ucms.com <br>-- powered by 5ucmsphp!";
	}
}
//分页类150713-150713 弃用
function getpage($count, $pagesize = 10) {
    $p = new Think\Page($count, $pagesize);
    $p->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
    $p->setConfig('prev', '上一页');
    $p->setConfig('next', '下一页');
    $p->setConfig('last', '末页');
    $p->setConfig('first', '首页');
    $p->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
    $p->lastSuffix = false;//最后一页不显示为总页数
    return $p; 
}

//分页类150814 自动识别链接追加跳转参数
function getpage2($count,$row = 10,$page){
	$isfront = $page; //是否前台
	if(empty($page)){$page=1;}
    if(!$count){return '';}//空数据将跳出
    $url      = $_SERVER["REQUEST_URI"]; //读取链接 
    $page     = $_GET['page']?$_GET['page']:$page; //读取分页坐标
    $acount   = $count; //数据条数
    $pcount   = ceil($acount/$row); //分页数

    $url      = $_GET['page']?str_replace(array('/page/'.$_GET['page'],'&page='.$_GET['page']),'',$url):$url; //去除page参数
    $limit    = ($page-1)*$row.','.$row; //limit调用
    $pagetpl  = '<ul><li><span>[info]</span></li>  [first] [link] [end] </ul>'; //链接坐标模板 
    //链接模板
    if(strstr($url,'&')||strstr($url,'=')){
        $url = $url.'&page=[url]';
    }elseif(substr($url,-5)=='.html'){
        $url = substr($url,0,-5).'/page/[url].html';
    }else{
        $url = $url.'/page/[url]';
    } 
	//首页模式150818
	if(!empty($isfront) and !strstr($url,'channel')){$url = 'http://'.$_SERVER['HTTP_HOST'].'/index.php/index/index'.'/page/[url]';} 
    //信息简介
    $info = '共 '.$acount.' 条信息 '.$page.'/'.$pcount.' 页'; 
    //第一页
    $first = ($page!=1)?'<li><a class="first" href="'.str_replace('[url]',1,$url).'">第一页</a></li>':''; 
    //最后一页
    $end = ($page!=$pcount)?'<li><a class="end" href="'.str_replace('[url]',$pcount,$url).'">最后一页</a></li>':''; 
    //上一页
    $prev = ($page-1)<1?'':$page-1;
    $prev = $prev?'<li><a class="prev" href="'.str_replace('[url]',$prev,$url).'">上一页</a></li>':$prev; 
    //下一页
    $next = ($page+1)>$pcount?'':$page+1;
    $next = $next?'<li><a class="next" href="'.str_replace('[url]',$next,$url).'">下一页</a></li>':$next; 
    //链接坐标
    $pagelist = '';
    for($i =1;$i<$pcount+1;$i++){
        if($i == $page){
            $pagelist .= "<li><span class='current'>$i</span></li>";
        }else{
            $pagelist .= "<li><a href='".str_replace('[url]',$i,$url)."'>$i</a></li>";
        }
    }
    $pagelist = $prev.$pagelist.$next;
    $pagelist = ($pcount>1)?$pagelist:'';
    $show = str_replace('[info]',$info,$pagetpl);
    $show = str_replace('[first]',$first,$show);
    $show = str_replace('[link]',$pagelist,$show);
    $show = str_replace('[end]',$end,$show);
    
    $r['pages'] = $show;
    $r['limit'] = $limit;
    return $r;
}

//替换生成正确的模板路径change admin template url path 支持前后台模板替换 150714-150815
function ChageATurl($content,$path = 'Admin'){ 
	$content = preg_replace('/(href\s*=\s*|url\(|src\s*=\s*)(["\']?)(images|js|css|Images|Js|Css|img)/', '$1$2'.C('installdir').'Template/'.$path.'/$3',$content);
	return $content;
}


//无限级栏目
function list_to_tree($list, $pk='ID',$pid = 'FatherID',$child = '_child',$root=0) {
  // 创建Tree
  $tree = array();
  if(is_array($list)) {
    // 创建基于主键的数组引用
    $refer = array();
    foreach ($list as $key => $data) {
      $refer[$data[$pk]] =& $list[$key];
    }
    foreach ($list as $key => $data) {
      // 判断是否存在parent
      $parentId = $data[$pid];
      if ($root == $parentId) {
        $tree[] =& $list[$key];
      }else{
        if (isset($refer[$parentId])) {
          $parent =& $refer[$parentId];
          $parent[$child][] =& $list[$key];
        }
      }
    }
  }
  return $tree;
}

/*对查询结果集进行排序*/
function list_sort_by($list,$field, $sortby='desc') {
   if(is_array($list)){
     $refer = $resultSet = array();
     foreach ($list as $i => $data)
       $refer[$i] = &$data[$field];
     switch ($sortby) {
       case 'asc': // 正向排序
        asort($refer);
        break;
       case 'desc':// 逆向排序
        arsort($refer);
        break;
       case 'nat': // 自然排序
        natcasesort($refer);
        break;
     }
     foreach ( $refer as $key=> $val)
       $resultSet[] = &$list[$key];
     return $resultSet;
   }
   return false;
}

/* 在数据列表中搜索 */
function list_search($list,$condition) {
  if(is_string($condition))
    parse_str($condition,$condition);
  // 返回的结果集合
  $resultSet = array();
  foreach ($list as $key=>$data){
    $find   =   false;
    foreach ($condition as $field=>$value){
      if(isset($data[$field])) {
        if(0 === stripos($value,'/')) {
          $find   =   preg_match($value,$data[$field]);
        }elseif($data[$field]==$value){
          $find = true;
        }
      }
    }
    if($find)
      $resultSet[]	 =   &$list[$key];
  }
  return $resultSet;
}

 

//md5 sha1混合加密函数 我也不知道为啥想这么变态的加密^_^ 150727
//这样应该就不会有任何网站可以解密了
function newmd5($Password){
	return md5(substr(md5(sha1(substr(md5(md5($Password)),8,16))),8,16));  
} 

//识别表单发送的数组，改为,分割
function input_treat($input){ if(gettype($input)=="string"){ return htmlentities(trim($input),ENT_QUOTES); }else if(gettype($input)=="array"){ $nd=""; foreach($input as $v){  $nd .=htmlentities(trim($v),ENT_QUOTES).","; } return $nd; }else{ return false; }}

//获取客户端IP地址
function GetIP() {  
    if (!emptyempty($_SERVER["HTTP_CLIENT_IP"])) {  
        $cip = $_SERVER["HTTP_CLIENT_IP"];  
    } else if (!emptyempty($_SERVER["HTTP_X_FORWARDED_FOR"])) {  
        $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];  
    } else if (!emptyempty($_SERVER["REMOTE_ADDR"])) {  
        $cip = $_SERVER["REMOTE_ADDR"];  
    } else {  
        $cip = '';  
    }  
    preg_match("/[\d\.]{7,15}/", $cip, $cips);  
    $cip = isset($cips[0]) ? $cips[0] : '未知IP';  
    unset($cips);  
    return $cip;  
}  

//获取IP地址 对应地区
function IPArea($ip) {  
    $curl = curl_init();  
    curl_setopt($curl, CURLOPT_URL, "http://www.ip138.com/ips138.asp?ip=".$ip);  
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
    $ipdz = curl_exec($curl);  
    curl_close($curl);  
    preg_match("/<ul class=\"ul1\"><li>(.*?)<\/li>/i", $ipdz, $jgarray);  
    preg_match("/本站主数据：(.*)/i", $jgarray[1], $ipp);  
    return "<div class=\"global_widht global_zj zj\" style=\"background: none repeat scroll 0% 0% rgb(226, 255, 191); font-size: 12px; color: rgb(85, 85, 85); height: 30px; line-height: 30px; border-bottom: 1px solid rgb(204, 204, 204); text-align: left;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;欢迎来自&nbsp;<b>".$ipp[1]."</b>&nbsp;的朋友！</div>";  
}   

// 获取服务端IP地址
function serverIP(){   
  return gethostbyname($_SERVER["SERVER_NAME"]);   
}

//将一个字符串转变成键值对数组 如 order desc,id desc 变 array('order'=>'desc','id'=>'desc')
function str2arr ($str,$sp=",",$kv="=")
{
    $arr = str_replace(array($kv,$sp),array('"=>"','","'),'array("'.$str.'")');
    eval("\$arr"." = $arr;"); 
    return $arr;
}

//清空目录，默认为缓存
function deleteDir($dir = null, $deleteRootToo = true) {
  if (null == $dir) $dir = './Apps/Runtime/';
  if (is_file($dir)) {
	  if (@unlink($dir));
	  return true;
  }
  elseif (is_dir($dir)) {
	  if (!$dh = @opendir($dir)) return false;
	  while (false !== ($obj = readdir($dh))) {
		  if ($obj == '.' || $obj == '..') continue;
		  if (!@unlink($dir . '/' . $obj)) deleteDir($dir . '/' . $obj, true);
	  }
	  closedir($dh);
	  if ($deleteRootToo) @rmdir($dir);
	  return true;
  }
  else {
	  return false;
  }
}

//循环检测并创建文件夹 
function createdir($path){
	if(!file_exists($path)){
		createdir(dirname($path));
		mkdir($path, 0777);
	}
}

//获取正文内容中的远程图片路径并返回 150823
function getimgpath($c) {
	$path = array(); //建立图片数组
	if ($c == null) return false;
	$p="/<img.*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png|\.bmp|\.jpeg]))[\'|\"].*?[\/]?>/i";
	if (preg_match_all($p, $c, $arr)) { //如果存正常的图片
		foreach ($arr[1] as $key => $value) { 
			$path[] = $value; //获取远程图片路径
		}
		return $path; //返回远程图片路径
	}
	return false;
}


//保存图片并返回本地绝对路径,参数远程图片路径数组 150823
function saveimage($path) {
	if ($path == '') return false;
	$pathArr = array();
	foreach ($path as $key => $value) {
		$url = $value; //远程图片路径
		if(stripos($url,'http://')!== false or stripos($url,'ftp://')!== false){ //仅处理外部路径
			$filename = substr($value, strripos($value, '/')); //图片名.后缀
			$ext = substr($value, strripos($value, '.')); //图片后缀
			$picdir = '.'.C('installdir').'uploadfile/' . date('Ym/d') . '/'; //组合图片路径
			if(!file_exists($picdir)){createdir($picdir);}else{$t='生成文件夹失败 请检查权限！';}//缩略图所需文件夹不存在就生成下 
			if(!file_exists($picdir)){$t='生成文件夹失败 请检查权限 生成路径='.$picdir;} 
			$savepath = $picdir . strtotime("now") . $ext; //保存新图片路径
			ob_start(); //开启缓冲
			readfile($url); //读取图片
			$img = ob_get_contents(); //保存到缓冲区
			ob_end_clean(); //关闭缓冲
			$fp2 = @fopen($savepath, "a"); //打开本地保存图片文件
			fwrite($fp2, $img); //写入图片
			fclose($fp2);
			//图片路径入库
			$model = M('Upload');  
			$data['dir'] = str_ireplace('./','/',$savepath);
			$data['ext'] = str_ireplace('.','',$ext);
			$data['aid'] = 0;
			$data['cid'] = 0;
			$data['time'] = strtotime("now");
			$qnewid = $model->add($data); 
			addwatermark($savepath); //图片添加水印
		} else {
			$savepath = $value; 
		}
		$pathArr[] = str_ireplace('./', '/', $savepath); //返回本地图片html版本路径
	}
	return $pathArr; //返回本地保存绝对路径
}

//增加本地图片水印
function addwatermark($savepath){
	$picwatermarktype = C('picwatermarktype'); //读取配置 是否添加水印
	if($picwatermarktype>0 and strstr($savepath,'uploadfile')){
		$savepath = str_ireplace('./', '/', $savepath);
		$savepath = '.'.$savepath; 
		$ext = substr($savepath,strripos($savepath,'.')); //图片后缀
		$ext = str_ireplace('.','',$ext);//去掉后缀的.号
		if(stripos('jpg,jpeg,gif,png,bmp',$ext) == false){return false;} //非图片就终止
		$image = new \Think\Image();
		if(!file_exists($savepath)){return false;}
		$preimg = $image -> open($savepath);
		$width = $image->width();
		$height = $image->height();
		if($width<150 and $height<150){return false;} //还没水印大呢 不打
		if($preimg){ 
			$picwatermarkimg = '.'.C('picwatermarkimg');//读取水印图片 
			if(!file_exists($picwatermarkimg)){return false;}
			$qid = $preimg -> water($picwatermarkimg,$picwatermarktype,C('picwatermarkalpha')) -> save($savepath); 
			if($qid){return true;}
		}
	}
	return false;
}

//校验日期格式是否正确 150819
function checkDateIsValid($date, $formats = array("Y-m-d H:i:s","Y-m-d H-i-s","Y/m/d H/i/s")) {
    $unixTime = strtotime($date);
    if (!$unixTime) { //strtotime转换不对，日期格式显然不对。
        return false;
    } 
    //校验日期的有效性，只要满足其中一个格式就OK
    foreach ($formats as $format) {
        if (date($format, $unixTime) == $date) {
            return true;
        }
    } 
    return false;
}

//蜘蛛来访判断和记录 150821
function Get_Spider(){ 
	$bot = '';
	$useragent = addslashes(strtolower($_SERVER['HTTP_USER_AGENT']));
	if (stripos($useragent, 'googlebot')!== false){$bot = 'Google';}
	elseif (stripos($useragent,'mediapartners-google') !== false){$bot = 'Google Adsense';}
	elseif (stripos($useragent,'baiduspider') !== false){$bot = 'Baidu';}
	elseif (stripos($useragent,'sogou spider') !== false){$bot = 'Sogou';}
	elseif (stripos($useragent,'sogou web') !== false){$bot = 'Sogou web';}
	elseif (stripos($useragent,'sosospider') !== false){$bot = 'SOSO';}
	elseif (stripos($useragent,'yahoo') !== false){$bot = 'Yahoo';}
	elseif (stripos($useragent,'msn') !== false){$bot = 'MSN';}
	elseif (stripos($useragent,'msnbot') !== false){$bot = 'msnbot';}
	elseif (stripos($useragent,'sohu') !== false){$bot = 'Sohu';}
	elseif (stripos($useragent,'yodaoBot') !== false){$bot = 'Yodao';}
	elseif (stripos($useragent,'twiceler') !== false){$bot = 'Twiceler';}
	elseif (stripos($useragent,'ia_archiver') !== false){$bot = 'Alexa_';}
	elseif (stripos($useragent,'iaarchiver') !== false){$bot = 'Alexa';}
	elseif (stripos($useragent,'slurp') !== false){$bot = '雅虎';}
	elseif (stripos($useragent,'360') !== false){$bot = '360好搜';}
	elseif (stripos($useragent,'bot') !== false){$bot = '其它蜘蛛';}
	if(!empty($bot)){  //如果是蜘蛛就存到数据库
		$Spider = M('bots');
		$data['botname'] = $bot;
		$data['lastdate'] = strtotime("now"); 
		$map['botname'] = array('like',"%$bot%");
		$qid = $Spider->where($map)->save($data);
		if($qid>0){
			return true;
		}else{
			$qid = $Spider->add($data);
			if($qid>0){return true;}else{return false;} 
		}
	}
}

//插件相关函数开始 参考自corethink 表示感谢！

//处理插件钩子 
function hook($hook, $params = array()){
    \Think\Hook::listen($hook,$params); 
}

//获取插件类的类名 
function get_plus_class($name){
    $class = "plus\\{$name}\\{$name}Plus";
    return $class;
}

//插件显示内容里生成访问插件的url 
function pluss_url($url, $param = array()){
    return D('plus')->getplusUrl($url, $param);
}

//执行文件中SQL语句函数
function execute_sql_from_file($file){
    $sql_data = file_get_contents($file);
    $sql_format = sql_split($sql_data, C('DB_PREFIX'));
    $counts = count($sql_format);
    for($i = 0; $i < $counts; $i++){
        $sql = trim($sql_format[$i]);
        D()->execute($sql);
    }
    return true;
}

//解析数据库语句函数
function sql_split($sql, $tablepre){
    if($tablepre != "ct_"){
        $sql = str_replace("ct_", $tablepre, $sql);
    }
    $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8", $sql);
    if($r_tablepre != $s_tablepre){
        $sql = str_replace($s_tablepre, $r_tablepre, $sql);
    }
    $sql = str_replace("\r", "\n", $sql);
    $ret = array();
    $num = 0;
    $queriesarray = explode(";\n", trim($sql));
    unset($sql);
    foreach($queriesarray as $query){
        $ret[$num] = '';
        $queries = explode("\n", trim($query));
        $queries = array_filter($queries);
        foreach($queries as $query){
            $str1 = substr($query, 0, 1);
            if($str1 != '#' && $str1 != '-'){
                $ret[$num] .= $query;
            }
        }
        $num++;
    }
    return $ret;
}

/**
 * 系统邮件发送函数
 * @param string $receiver 收件人
 * @param string $subject 邮件主题
 * @param string $body 邮件内容
 * @param string $attachment 附件列表 
 */
function send_mail($receiver, $subject, $body, $attachment){
    return R('plus://Email/Email/sendMail', array($receiver, $subject, $body, $attachment));
}


?>