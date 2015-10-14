<?php
//5UCMS的后台文件
//设定头文件 这样其他页面不设定 也不会乱码咯~ 
header("content-type:text/html;charset=utf-8"); 

// 检测PHP版本 必须PHP5.3以上哦
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 ! PHP版本必须PHP5.3以上哦'); 

// 开启调试模式 研究程序或者想要知道错误详情时 填True 正式使用填False 或 注释掉
define('APP_DEBUG',True);
//绑定模型 网址无需填写模型名称了
define('BIND_MODULE','ADMIN'); //PHP版5UCMS走起！ 
// 定义应用目录 简化点 好写
define('APP_PATH','./Apps/'); 
// 定义安全文件名称
define('DIR_SECURE_FILENAME', 'index.html');

//==============一些常量的定义============\\
//定义后台入口文件名 如果后台文件名改了 这里也要修改 必须指定路径
define('ADMINFILE',"/admin.php");
//==========必须用大写哦，以防出错============\\
define('HTML_PATH', './'); //（将静态路径义为网站根目录）
// 引入ThinkPHP入口文件 配置信息 必须在上方
require './ThinkPHP/ThinkPHP.php'; 

// 亲^_^ 后面不需要任何代码了 如需5UCMS相关技术支持请访问www.5ucms.com QQ：3876307