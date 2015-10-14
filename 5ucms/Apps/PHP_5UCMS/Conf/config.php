<?php
return array( 
	'VIEW_PATH'=>'.'.C('installdir').'Template/'.C('templatedir').'/',//配置默认模板路径
 	//'URL_MODEL' => 2, //重写,去掉index.php

	
	//修改普通标签的界定符号 以防止和5UCMS模板引擎冲突
	'TMPL_L_DELIM'    =>    '{{', // 模板引擎普通标签开始标记
	'TMPL_R_DELIM'    =>    '}}', // 模板引擎普通标签结束标记
	
    // Think模板引擎标签库相关设定
    'TAGLIB_LOAD'           =>  false, // 是否使用内置标签库之外的其它标签库，默认自动检测
   
 
	
	//网址相关定义
	'URL_ROUTER_ON'   => true, //开启路由
	'URL_ROUTE_RULES'=>array(  
		//'a/:id\d'    => 'content/index',
		//'c/:id\d'    => 'channel/index',
	),
	'URL_HTML_SUFFIX' => 'html|shtml|xml', //伪静态后缀
	'URL_DENY_SUFFIX' => 'pdf|ico|png|gif|jpg', //禁止访问的URL后缀
	'HTML_FILE_SUFFIX' => '.html',//静态文件后缀

);