<?php
//全局配置文件
const PLUS_PATH = './plus/'; //定义插件控制器目录 
/*
 * 全局配置
 * 系统升级需要此配置
 * 根据5UCMS用户协议：
 * 免费版您可以免费用于项目开发
 * 但不允许更改本产品后台的版权信息，请您尊重我们的劳动成果及知识产权，违者追究法律责任。
 * 商业授权版可更改所有的产品名称及公司名称，授权联系：QQ3876307
 */
$_config = array (
    'URL_MODEL'          	=> 1, //URL模式 
    'SESSION_AUTO_START' 	=> true, //是否开启session 
	'TMPL_FILE_DEPR'		=> '_', //配置简化模板的目录层 
	'URL_CASE_INSENSITIVE'	=> True, //指定URL大小写 不敏感
	'URL_HTML_SUFFIX'		=> '',//定义默认URL后缀为空 
	 
	'AUTOLOAD_NAMESPACE'	=> array('plus' => PLUS_PATH), //扩展插件功能 有了这个 plus下插件才可以被自动识别
	
	/* SESSION 和 COOKIE 配置 如果同一空间有多个本套程序，需修改这里  避免冲突*/
    'SESSION_PREFIX' 		=> 'qss', //session前缀
    'COOKIE_PREFIX' 		=> 'qss_', // Cookie前缀  
    
    //多语言支持 暂时尚未开发此功能
    //'LANG_SWITCH_ON'        => true,   // 默认关闭语言包功能
    //'LANG_AUTO_DETECT'      => true,   // 自动侦测语言 开启多语言功能后有效
    //'LANG_LIST'             => 'zh-cn,zh-tw,en-us', // 允许切换的语言列表 用逗号分隔
    //'VAR_LANGUAGE'          => 'hl',		// 默认语言切换变量
	
	//让页面显示追踪日志信息 调试用
    //'SHOW_PAGE_TRACE'   => true, 
	
	//额外加载配置文件 方便更新各种数据数据
	'LOAD_EXT_CONFIG' 		=> 'setting,channel,version,db'  
);

//返回合并的配置
return array_merge (
    $_config, //系统全局默认配置 
    include APP_PATH.'/Common/Builder/config.php' //包含Builder配置
);
