-- -----------------------------
-- 5ucms php mysql
-- Date : 20150902
-- 如果存在一些固定表，则先卸载掉
-- -----------------------------
DROP TABLE IF EXISTS `{pre}channel`;
DROP TABLE IF EXISTS `{pre}content`;
DROP TABLE IF EXISTS `{pre}comment`;
DROP TABLE IF EXISTS `{pre}sitelink`;
DROP TABLE IF EXISTS `{pre}label`;
DROP TABLE IF EXISTS `{pre}admin`;
DROP TABLE IF EXISTS `{pre}upload`;
DROP TABLE IF EXISTS `{pre}diypage`;
DROP TABLE IF EXISTS `{pre}tags`;
DROP TABLE IF EXISTS `{pre}bots`;
DROP TABLE IF EXISTS `{pre}config`;
DROP TABLE IF EXISTS `{pre}plus`; 
DROP TABLE IF EXISTS `{pre}plushook`; 
DROP TABLE IF EXISTS `{pre}plus_sync_login`; 
-- -----------------------------
-- 开始创建表啦~
-- -----------------------------  

CREATE TABLE `{pre}channel` (
	`id` int NOT NULL primary key AUTO_INCREMENT COMMENT '主键',
	`cid` int DEFAULT 0 COMMENT '栏目编号',
	`fatherid` int DEFAULT 0 COMMENT '父级栏目编号',
	`childid` text COMMENT '子栏目编号合集',
	`childids` text COMMENT '子栏目编号合集 包括自己',
	`deeppath` int DEFAULT 0 COMMENT '栏目层级',
	`name` Varchar(250) COMMENT '栏目名称',
	`order` int DEFAULT 0 COMMENT '排序',
	`table` Varchar(20) COMMENT '所在表名',
	`domain` Varchar(100) COMMENT '绑定域名',
	`outsidelink` int DEFAULT 0 COMMENT '是否外部链接',
	`templatechannel` Varchar(100) COMMENT '大类列表页模板',
	`templateclass` Varchar(100) COMMENT '小类列表页模板',
	`templateview` Varchar(100) COMMENT '内容预览页模板',
	`ruleindex` Varchar(100) COMMENT '首页伪静态规则',
	`rulechannel` Varchar(100) COMMENT '列表页伪静态规则',
	`ruleview` Varchar(100) COMMENT '内容页伪静态规则',
	`picture` Varchar(100) COMMENT '栏目形象图',
	`keywords` Varchar(100) COMMENT '栏目关键词',
	`description` Varchar(250) COMMENT '栏目描述',
	`needcreate`  int DEFAULT 0 COMMENT '是否需要生成静态',
	`modeext` text COMMENT '扩展字段定义'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目信息表'; 

-- -----------------------------
-- 安装初始栏目
-- -----------------------------   

INSERT INTO `{pre}channel` VALUES ('1', '1', '0', '', '1', '0', '关于我们', '0', '{pre}content_01', '', '0', 'Channel_channel', 'Channel_list.html', 'Content_article.html', '{installdir}{cid}/', 'page_{page}.html', '{aid}.html', '', '', '', '1', '');
INSERT INTO `{pre}channel` VALUES ('2', '2', '0', '', '2', '0', '服务范围', '0', '{pre}content_01', '', '0', 'Channel_channel', 'Channel_list.html', 'Content_article.html', '{installdir}{cid}/', 'page_{page}.html', '{aid}.html', '', '', '', '1', '');
INSERT INTO `{pre}channel` VALUES ('3', '3', '0', '', '3', '0', '新闻动态', '0', '{pre}content_01', '', '0', 'Channel_channel', 'Channel_list.html', 'Content_article.html', '{installdir}{cid}/', 'page_{page}.html', '{aid}.html', '', '', '', '1', '');


CREATE TABLE `{pre}content` (
	`id` int NOT NULL primary key AUTO_INCREMENT COMMENT '主键',
	`cid` int DEFAULT 0 COMMENT '对应栏目ID',
	`sid` int DEFAULT 0 COMMENT '对应专题栏目ID',
	`title` Varchar(250) COMMENT '内容标题',
	`style` Varchar(20) COMMENT '内容标题样式',
	`author` Varchar(100) COMMENT '内容作者',
	`source` Varchar(250) COMMENT '内容来源',
	`jumpurl` Varchar(200) COMMENT '跳转地址',
	`keywords` Varchar(250) COMMENT '内容关键词',
	`description` Varchar(250) COMMENT '内容描述',
	`commend` int DEFAULT 0 COMMENT '内容推荐',
	`indexpic` Varchar(250) COMMENT '内容形象图',
	`views` int DEFAULT 0 COMMENT '内容浏览量',
	`diggs` int DEFAULT 0 COMMENT '内容顶一下数量',
	`comments` int DEFAULT 0 COMMENT '内容评论数量',
	`iscomment` int DEFAULT 0 COMMENT '是否允许评论',
	`order` int DEFAULT 0 COMMENT '内容排序权重',
	`filepath` Varchar(250) COMMENT '内容生成路径',
	`viewpath` Varchar(250) COMMENT '内容显示路径',
	`diyname` Varchar(200) COMMENT '内容自定义文件名',
	`createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '内容创建时间',
	`modifytime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '内容修改时间',
	`display` int DEFAULT 0 COMMENT '内容是否隐藏',
	`modeIndex` Varchar(250) COMMENT '内容扩展字段信息',
	`content` text COMMENT '内容正文信息'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章内容表'; 

-- -----------------------------
-- 安装初始内容
-- -----------------------------  

INSERT INTO `{pre}content` VALUES ('1', '1', '0', '关于5ucms', ',', '作者', '', '', '', '5ucms是一款采用PHP开发，THINKPHP为内核，基于HMVC规则开发适合中小企业、公司、新闻、个人等相关行业的网站内容管理。程序具有良好的用户体验用建议的操作，适合美工人员快速建立站点', '1', '', '4', '0', '0', '1', '20', '', '', '', '1440235814', '1441703606', '1', '', '5ucms是一款采用PHP开发，THINKPHP为内核，基于HMVC规则开发适合中小企业、公司、新闻、个人等相关行业的网站内容管理。程序具有良好的用户体验用建议的操作，适合美工人员快速建立站点，您也可以根据您的需要进行应用扩展来达到更加强大功能。');
INSERT INTO `{pre}content` VALUES ('2', '1', '0', '联系我们', ',', '', '', '', '', '电话：17098715006 邮箱：3876307@qq.com 5ucms.com', '1', '', '9', '0', '0', '1', '0', '', '', '', '1970', '1441703598', '1', '', '电话：17098715006 邮箱：3876307@qq.com 5ucms.com');
INSERT INTO `{pre}content` VALUES ('3', '2', '0', '网站建设', '', '', '', '', '', '', '1', 'images/2014103012162628359.jpg', '0', '0', '0', '1', '0', '', '', '', '1970', '1440160804', '1', '', '网站建设');
INSERT INTO `{pre}content` VALUES ('4', '2', '0', '仿站培训', '', '', '', '', '', '', '1', 'images/2014103012163559972.jpg', '0', '0', '0', '1', '0', '', '', '', '1970', '1440160817', '1', '', '仿站培训');
INSERT INTO `{pre}content` VALUES ('5', '2', '0', 'CSS切图', ',', '', '', '', '', '', '1', 'images/2014103012164751613.jpg', '1', '0', '0', '1', '0', '', '', '', '1970', '1440410325', '1', '', 'CSS切图');
INSERT INTO `{pre}content` VALUES ('6', '2', '0', '插件开发', '', '', '', '', '', '', '1', 'images/2014103012165619751.jpg', '143', '0', '0', '1', '0', '', '', '', '1440242049', '1440242049', '1', '', '插件开发');
INSERT INTO `{pre}content` VALUES ('7', '1', '0', '服务支持', ',', '', '', '', '', '', '0', '', '11', '0', '0', '1', '0', '', '', '', '1440161210', '1441703587', '1', '', '服务支持');
INSERT INTO `{pre}content` VALUES ('8', '1', '0', '人才战略', ',', '', '', '', '', '', '0', '', '53', '0', '0', '1', '0', '', '', '', '1440161228', '1441703577', '1', '', '人才战略');
INSERT INTO `{pre}content` VALUES ('9', '3', '0', '欢迎大家体验5ucms php版本！', 'strong,#FF0000', '', '', '', '', '欢迎大家体验5ucms php版本！我是百度', '0', '', '11', '0', '0', '1', '0', '', '', '', '1755855207', '1441703567', '1', '', '欢迎大家体验5ucms php版本！');


CREATE TABLE `{pre}comment` (
	`id` int NOT NULL primary key AUTO_INCREMENT COMMENT '主键',
	`aid` int DEFAULT 0 COMMENT '评论内容编号',
	`cid` int DEFAULT 0 COMMENT '评论栏目编号',
	`user` Varchar(10) COMMENT '评论者',
	`content` Varchar(250) COMMENT '评论信息',
	`recomment` Varchar(250) COMMENT '评论推荐',
	`ip` Varchar(20) COMMENT '评论者IP',
	`time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论时间',
	`state` tinyint(1) DEFAULT 0 COMMENT '评论审核状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论信息表';

CREATE TABLE `{pre}sitelink` (
	`id` int NOT NULL primary key AUTO_INCREMENT COMMENT '主键',
	`text` Varchar(20) COMMENT '内链名称',
	`description` Varchar(250) COMMENT '内链描述',
	`link` Varchar(250) COMMENT '内链地址',
	`order` int DEFAULT 0 COMMENT '内链排序权重',
	`replace` int DEFAULT 0 COMMENT '内链替换次数',
	`target` int DEFAULT 0 COMMENT '内链打开方式',
	`state` tinyint(1) DEFAULT 0 COMMENT '内链审核状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='站内链接表';

CREATE TABLE `{pre}label` (
	`id` int NOT NULL primary key AUTO_INCREMENT COMMENT '主键',
	`name` Varchar(50) COMMENT '自定义标签调用名称',
	`info` Varchar(250) COMMENT '自定义标签描述名称',
	`code` text COMMENT '自定义标签信息内容',
	`code2` text COMMENT '自定义标签信息备用内容'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='自定义标签表';

-- -----------------------------
-- 安装标签
-- -----------------------------
INSERT INTO `{pre}label` VALUES ('1', 'indextitle', '首页标题', '欢迎使用5ucms php!', '欢迎使用5ucms php!');
INSERT INTO `{pre}label` VALUES ('2', 'banner', '全站通用banner图', '            <li class=\"item\"><img src=\"images/1.jpg\" class=\"img-responsive\" /></li>\r\n            <li class=\"item\"><img src=\"images/2.jpg\" class=\"img-responsive\" /></li>\r\n            <li class=\"item\"><img src=\"images/3.jpg\" class=\"img-responsive\" /></li>\r\n            <li class=\"item\"><img src=\"images/4.jpg\" class=\"img-responsive\" /></li>\r\n            <li class=\"item\"><img src=\"images/5.jpg\" class=\"img-responsive\" /></li>\r\n            <li class=\"item\"><img src=\"images/6.jpg\" class=\"img-responsive\" /></li>', '            &lt;li class=&quot;item&quot;&gt;&lt;img src=&quot;images/1.jpg&quot; class=&quot;img-responsive&quot; /&gt;&lt;/li&gt;\r\n            &lt;li class=&quot;item&quot;&gt;&lt;img src=&quot;images/2.jpg&quot; class=&quot;img-responsive&quot; /&gt;&lt;/li&gt;\r\n            &lt;li class=&quot;item&quot;&gt;&lt;img src=&quot;images/3.jpg&quot; class=&quot;img-responsive&quot; /&gt;&lt;/li&gt;\r\n            &lt;li class=&quot;item&quot;&gt;&lt;img src=&quot;images/4.jpg&quot; class=&quot;img-responsive&quot; /&gt;&lt;/li&gt;\r\n            &lt;li class=&quot;item&quot;&gt;&lt;img src=&quot;images/5.jpg&quot; class=&quot;img-responsive&quot; /&gt;&lt;/li&gt;\r\n            &lt;li class=&quot;item&quot;&gt;&lt;img src=&quot;images/6.jpg&quot; class=&quot;img-responsive&quot; /&gt;&lt;/li&gt;');
INSERT INTO `{pre}label` VALUES ('3', 'banner2', '内页banner', '            <li class=\"item\" style=\"background:#212e41;\"><img src=\"images/bannerx.jpg\" class=\"img-responsive\" /></li>\r\n            <li class=\"item\" style=\"background:#212e41;\"><img src=\"images/bannerx2.jpg\" class=\"img-responsive\" /></li>', '            &lt;li class=&quot;item&quot; style=&quot;background:#212e41;&quot;&gt;&lt;img src=&quot;images/bannerx.jpg&quot; class=&quot;img-responsive&quot; /&gt;&lt;/li&gt;\r\n            &lt;li class=&quot;item&quot; style=&quot;background:#212e41;&quot;&gt;&lt;img src=&quot;images/bannerx2.jpg&quot; class=&quot;img-responsive&quot; /&gt;&lt;/li&gt;');
INSERT INTO `{pre}label` VALUES ('4', 'gg', '公告', '<p>欢迎访问 {s:webname}{c:version}\r\n<p>【<a href=\"/admin.php\">点我进入后台</a>】 【<a href=\"/install.php\">点我重新安装</a>】 \r\n<p>默认账号密码 admin admin \r\n<p>不要搞破坏哦 看看就好\r\n<p>【<a href=\"http://git.oschina.net/5ucms/php_5ucms/\" target=\"_blank\">点我下载最新程序回家自己玩</a>】\r\n<p>【<a href=\"http://www.kancloud.cn/www5ucmscom/i5ucms\" target=\"_blank\">点我查看帮助与开发手册</a>】\r\n<p>程序正在紧张完善和制作中，敬请期待！ \r\n<p>前台使用拼图框架制作 \r\n<p>www.5ucms.com QQ3876307 邱嵩松 ', '<p>欢迎访问 {s:webname}\r\n<p>【<a href=\"/admin.php\">点我进入后台</a>】 【<a href=\"/install.php\">点我重新安装</a>】 \r\n<p>默认账号密码 admin admin \r\n<p>不要搞破坏哦 看看就好\r\n<p>【<a href=\"http://git.oschina.net/5ucms/php_5ucms/\" target=\"_blank\">点我下载最新程序回家自己玩</a>】\r\n<p>【<a href=\"http://www.kancloud.cn/www5ucmscom/i5ucms\" target=\"_blank\">点我查看帮助与开发手册</a>】\r\n<p>程序正在紧张完善和制作中，敬请期待！ \r\n<p>前台使用拼图框架制作 \r\n<p>www.5ucms.com QQ3876307 邱嵩松 ');
INSERT INTO `{pre}label` VALUES ('5', 'js', '底部js', '<script>window._bd_share_config={\"common\":{\"bdSnsKey\":{},\"bdText\":\"\",\"bdMini\":\"2\",\"bdMiniList\":false,\"bdPic\":\"\",\"bdStyle\":\"0\",\"bdSize\":\"16\"},\"share\":{}};with(document)0[(getElementsByTagName(\'head\')[0]||body).appendChild(createElement(\'script\')).src=\'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=\'+~(-new Date()/36e5)];</script>', '&lt;script&gt;window._bd_share_config={&quot;common&quot;:{&quot;bdSnsKey&quot;:{},&quot;bdText&quot;:&quot;&quot;,&quot;bdMini&quot;:&quot;2&quot;,&quot;bdMiniList&quot;:false,&quot;bdPic&quot;:&quot;&quot;,&quot;bdStyle&quot;:&quot;0&quot;,&quot;bdSize&quot;:&quot;16&quot;},&quot;share&quot;:{}};with(document)0[(getElementsByTagName(\'head\')[0]||body).appendChild(createElement(\'script\')).src=\'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=\'+~(-new Date()/36e5)];&lt;/script&gt;');
INSERT INTO `{pre}label` VALUES ('6', 'copyright', '版权说明', '电话：17098715006  邮箱：3876307@qq.com 5ucms.com 版权所有 Copyright &copy; All Rights Reserved <br />\r\n前台基于<a rel=\"nofollow\" class=\"text-gray\" target=\"_blank\" href=\"http://www.pintuer.com\">拼图</a> 后台基于<a href=\"http://www.5ucms.com\" target=_blank>5ucms</a>内核 构建\r\n<a class=\"text-gray\" target=\"_blank\" href=\"#\">备案号:我讨厌备案</a>', '电话：17098715006  邮箱：3876307@qq.com 5ucms.com 版权所有 Copyright &copy; All Rights Reserved <br />\r\n前台基于<a rel=\"nofollow\" class=\"text-gray\" target=\"_blank\" href=\"http://www.pintuer.com\">拼图</a> 后台基于<a href=\"http://www.5ucms.com\" target=_blank>5ucms</a>内核 构建\r\n<a class=\"text-gray\" target=\"_blank\" href=\"#\">备案号:我讨厌备案</a>');
INSERT INTO `{pre}label` VALUES ('7', 'intr', '介绍', '5ucms是一款采用PHP开发，THINKPHP为内核，基于HMVC规则开发适合中小企业、公司、新闻、个人等相关行业的网站内容管理。程序具有良好的用户体验用建议的操作，适合美工人员快速建立站点，您也可以根据您的需要进行应用扩展来达到更加强大功能。', '5ucms是一款采用PHP开发，THINKPHP为内核，基于HMVC规则开发适合中小企业、公司、新闻、个人等相关行业的网站内容管理。程序具有良好的用户体验用建议的操作，适合美工人员快速建立站点，您也可以根据您的需要进行应用扩展来达到更加强大功能。');
INSERT INTO `{pre}label` VALUES ('8', 'menu', '菜单', '                    <li class=\"active\"><a href=\"/\">网站首页</a></li>\r\n                    \r\n                    <li class=\"\"><a href=\"{curl:1}\">走进5UCMS</a>\r\n                    <ul class=\"drop-menu \">\r\n<!--m:{ $row=8 $cid=1 $order=[order] desc,[id] desc }--> \r\n<li><a href=\"[m:aurl]\" title=\"[m:title]\">[m:titlex]</a></li>\r\n<!--m-->\r\n\r\n</ul>\r\n                    </li>\r\n                    \r\n                    <li class=\"\"><a href=\"{curl:2}\">公司项目</a>\r\n                    <ul class=\"drop-menu\"><!--m:{ $row=8 $cid=2 $order=[order] desc,[id] desc }--> \r\n<li><a href=\"[m:aurl]\" title=\"[m:title]\">[m:titlex]</a></li>\r\n<!--m--></ul>\r\n                    </li>\r\n                    \r\n                    <li class=\"\"><a href=\"{curl:3}\">新闻中心</a> </li>\r\n                    \r\n                    <li class=\"\"><a href=\"{aurl:7}\">服务支持</a> </li>\r\n                    \r\n                    <li class=\"\"><a href=\"{aurl:8}\">人才战略</a></li>\r\n                    \r\n                    <li class=\"\"><a href=\"{aurl:2}\">联系我们</a> </li>', '                    &lt;li class=&quot;active&quot;&gt;&lt;a href=&quot;/&quot;&gt;网站首页&lt;/a&gt;&lt;/li&gt;\r\n                    \r\n                    &lt;li class=&quot;&quot;&gt;&lt;a href=&quot;{curl:1}&quot;&gt;走进5UCMS&lt;/a&gt;\r\n                    &lt;ul class=&quot;drop-menu &quot;&gt;\r\n&lt;!--m:{ $row=8 $cid=1 $order=[order] desc,[id] desc }--&gt; \r\n&lt;li&gt;&lt;a href=&quot;[m:aurl]&quot; title=&quot;[m:title]&quot;&gt;[m:titlex]&lt;/a&gt;&lt;/li&gt;\r\n&lt;!--m--&gt;\r\n\r\n&lt;/ul&gt;\r\n                    &lt;/li&gt;\r\n                    \r\n                    &lt;li class=&quot;&quot;&gt;&lt;a href=&quot;{curl:2}&quot;&gt;公司项目&lt;/a&gt;\r\n                    &lt;ul class=&quot;drop-menu&quot;&gt;&lt;!--m:{ $row=8 $cid=2 $order=[order] desc,[id] desc }--&gt; \r\n&lt;li&gt;&lt;a href=&quot;[m:aurl]&quot; title=&quot;[m:title]&quot;&gt;[m:titlex]&lt;/a&gt;&lt;/li&gt;\r\n&lt;!--m--&gt;&lt;/ul&gt;\r\n                    &lt;/li&gt;\r\n                    \r\n                    &lt;li class=&quot;&quot;&gt;&lt;a href=&quot;{curl:3}&quot;&gt;新闻中心&lt;/a&gt; &lt;/li&gt;\r\n                    \r\n                    &lt;li class=&quot;&quot;&gt;&lt;a href=&quot;{aurl:7}&quot;&gt;服务支持&lt;/a&gt; &lt;/li&gt;\r\n                    \r\n                    &lt;li class=&quot;&quot;&gt;&lt;a href=&quot;{aurl:8}&quot;&gt;人才战略&lt;/a&gt;&lt;/li&gt;\r\n                    \r\n                    &lt;li class=&quot;&quot;&gt;&lt;a href=&quot;{aurl:2}&quot;&gt;联系我们&lt;/a&gt; &lt;/li&gt;');
INSERT INTO `{pre}label` VALUES ('9', 'menu2', '内容页顶部', '                  <li class=\"\"><a href=\"{curl:1}\">走进5UCMS</a></li>   \r\n                  <li class=\"\"><a href=\"{curl:3}\">新闻中心</a></li> \r\n                  <li class=\"\"><a href=\"{aurl:7}\">服务支持</a></li> \r\n                  <li class=\"\"><a href=\"{aurl:8}\">人才战略</a></li>  ', ' ');
INSERT INTO `{pre}label` VALUES ('10', 'search', '搜索', '        <form method=\'get\' target=\'_blank\' action=\'/index.php/index/plus/name/Search\'>\r\n          <div class=\"input-group padding-little-top\">\r\n            <input type=\"text\" class=\"input border-main\" name=\"keyword\" size=\"30\" placeholder=\"请输入关键词\" />\r\n            <span class=\"addbtn\"><button type=\"submit\" class=\"button bg-main\">搜!</button></span>\r\n          </div>\r\n        </form>', '        <form>\r\n          <div class=\"input-group padding-little-top\">\r\n            <input type=\"text\" class=\"input border-main\" name=\"keywords\" size=\"30\" placeholder=\"搜索功能制作中\" />\r\n            <span class=\"addbtn\"><button type=\"button\" class=\"button bg-main\">搜!</button></span>\r\n          </div>\r\n        </form>');
INSERT INTO `{pre}label` VALUES ('11', 'footmenu2', '缩小后的底部菜单', '        <div class=\"x3\"><a class=\"icon-home\" href=\"/\"> 首页</a></div>\r\n        <div class=\"x3\"><a class=\"icon-mobile\" href=\"tel:17098715006\"> 电话</a></div>\r\n        <div class=\"x3\"><a class=\"icon-envelope\" href=\"sms:17098715006\"> 短信</a></div>\r\n        <div class=\"x3\"><a class=\"icon-phone-square\" href=\"{aurl:2}\"> 联系</a></div>', '        &lt;div class=&quot;x3&quot;&gt;&lt;a class=&quot;icon-home&quot; href=&quot;/&quot;&gt; 首页&lt;/a&gt;&lt;/div&gt;\r\n        &lt;div class=&quot;x3&quot;&gt;&lt;a class=&quot;icon-mobile&quot; href=&quot;tel:17098715006&quot;&gt; 电话&lt;/a&gt;&lt;/div&gt;\r\n        &lt;div class=&quot;x3&quot;&gt;&lt;a class=&quot;icon-envelope&quot; href=&quot;sms:17098715006&quot;&gt; 短信&lt;/a&gt;&lt;/div&gt;\r\n        &lt;div class=&quot;x3&quot;&gt;&lt;a class=&quot;icon-phone-square&quot; href=&quot;{aurl:2}&quot;&gt; 联系&lt;/a&gt;&lt;/div&gt;');
INSERT INTO `{pre}label` VALUES ('12', 'footmenu', '底部菜单 active', '        <li class=\"\"><a href=\"/\">网站首页</a></li>\r\n        <li class=\"\"><a href=\"{curl:1}\">走进5UCMS</a></li>\r\n        <li class=\"\"><a href=\"{curl:2}\">公司项目</a></li>\r\n        <li class=\"\"><a href=\"{curl:3}\">新闻中心</a></li>\r\n        <li class=\"\"><a href=\"{aurl:7}\">服务支持</a></li>\r\n        <li class=\"\"><a href=\"{aurl:8}\">人才战略</a></li>\r\n        <li class=\"\"><a href=\"{aurl:1}\">联系我们</a></li>', '        &lt;li class=&quot;&quot;&gt;&lt;a href=&quot;/&quot;&gt;网站首页&lt;/a&gt;&lt;/li&gt;\r\n        &lt;li class=&quot;&quot;&gt;&lt;a href=&quot;{curl:1}&quot;&gt;走进5UCMS&lt;/a&gt;&lt;/li&gt;\r\n        &lt;li class=&quot;&quot;&gt;&lt;a href=&quot;{curl:2}&quot;&gt;公司项目&lt;/a&gt;&lt;/li&gt;\r\n        &lt;li class=&quot;&quot;&gt;&lt;a href=&quot;{curl:3}&quot;&gt;新闻中心&lt;/a&gt;&lt;/li&gt;\r\n        &lt;li class=&quot;&quot;&gt;&lt;a href=&quot;{aurl:7}&quot;&gt;服务支持&lt;/a&gt;&lt;/li&gt;\r\n        &lt;li class=&quot;&quot;&gt;&lt;a href=&quot;{aurl:8}&quot;&gt;人才战略&lt;/a&gt;&lt;/li&gt;\r\n        &lt;li class=&quot;&quot;&gt;&lt;a href=&quot;{aurl:1}&quot;&gt;联系我们&lt;/a&gt;&lt;/li&gt;');
INSERT INTO `{pre}label` VALUES ('13', 'indexfootmenu', '首页底部菜单', '            <ul class=\"nav nav-sitemap\">\r\n              <li><a href=\"{curl:1}\">新闻资讯</a>\r\n                <ul>\r\n<!--m:{ $row=3 $cid=3 $order=[order] desc,[id] desc }--> \r\n                <li><a href=\"[m:aurl]\" title=\"[m:title]\">[m:titlex]</a></li>\r\n<!--m-->\r\n                </ul>\r\n              </li>\r\n              <li><a href=\"{curl:2}\">服务项目</a>\r\n                <ul>\r\n<!--m:{ $row=3 $cid=2 $order=[order] desc,[id] desc }--> \r\n                <li><a href=\"[m:aurl]\" title=\"[m:title]\">[m:titlex]</a></li>\r\n<!--m-->\r\n                </ul>\r\n              </li>\r\n              <li><a href=\"#\">技术反馈</a>\r\n                <ul>\r\n                  <li><a href=\"{aurl:7}\">服务支持</a></li>\r\n                  <li><a href=\"{aurl:8}\">人才战略</a></li> \r\n                </ul>\r\n              </li>\r\n              <li><a href=\"{aurl:1}\">关于5ucms</a></li>\r\n              <li><a href=\"{aurl:2}\">联系方式</a></li>\r\n            </ul>', '            &lt;ul class=&quot;nav nav-sitemap&quot;&gt;\r\n              &lt;li&gt;&lt;a href=&quot;{curl:1}&quot;&gt;新闻资讯&lt;/a&gt;\r\n                &lt;ul&gt;\r\n&lt;!--m:{ $row=3 $cid=3 $order=[order] desc,[id] desc }--&gt; \r\n                &lt;li&gt;&lt;a href=&quot;[m:aurl]&quot; title=&quot;[m:title]&quot;&gt;[m:titlex]&lt;/a&gt;&lt;/li&gt;\r\n&lt;!--m--&gt;\r\n                &lt;/ul&gt;\r\n              &lt;/li&gt;\r\n              &lt;li&gt;&lt;a href=&quot;{curl:2}&quot;&gt;服务项目&lt;/a&gt;\r\n                &lt;ul&gt;\r\n&lt;!--m:{ $row=3 $cid=2 $order=[order] desc,[id] desc }--&gt; \r\n                &lt;li&gt;&lt;a href=&quot;[m:aurl]&quot; title=&quot;[m:title]&quot;&gt;[m:titlex]&lt;/a&gt;&lt;/li&gt;\r\n&lt;!--m--&gt;\r\n                &lt;/ul&gt;\r\n              &lt;/li&gt;\r\n              &lt;li&gt;&lt;a href=&quot;#&quot;&gt;技术反馈&lt;/a&gt;\r\n                &lt;ul&gt;\r\n                  &lt;li&gt;&lt;a href=&quot;{aurl:7}&quot;&gt;服务支持&lt;/a&gt;&lt;/li&gt;\r\n                  &lt;li&gt;&lt;a href=&quot;{aurl:8}&quot;&gt;人才战略&lt;/a&gt;&lt;/li&gt; \r\n                &lt;/ul&gt;\r\n              &lt;/li&gt;\r\n              &lt;li&gt;&lt;a href=&quot;{aurl:1}&quot;&gt;关于5ucms&lt;/a&gt;&lt;/li&gt;\r\n              &lt;li&gt;&lt;a href=&quot;{aurl:2}&quot;&gt;联系方式&lt;/a&gt;&lt;/li&gt;\r\n            &lt;/ul&gt;');
INSERT INTO `{pre}label` VALUES ('14', 'tel', '右上角电话', '17098715006', '17098715006');
INSERT INTO `{pre}label` VALUES ('15', 'toplink', '右上角链接', '<a href=\"#\" class=\"win-homepage\">设为首页</a> | <a href=\"#\" class=\"win-favorite\">加入收藏</a>', '&lt;a href=&quot;#&quot; class=&quot;win-homepage&quot;&gt;设为首页&lt;/a&gt; | &lt;a href=&quot;#&quot; class=&quot;win-favorite&quot;&gt;加入收藏&lt;/a&gt;');

CREATE TABLE `{pre}admin` (
	`id` int NOT NULL primary key AUTO_INCREMENT COMMENT '主键',
	`username` Varchar(20) COMMENT '管理员名称',
	`password` Varchar(50) COMMENT '管理员密码',
	`email` Varchar(50) COMMENT '管理员邮箱',
	`levels` Varchar(200) COMMENT '管理员后台权限',
	`manageplus` Varchar(200) COMMENT '管理员插件权限',
	`managechannel` Varchar(200) COMMENT '管理员栏目权限',
	`diymenu` text COMMENT '管理员自定义菜单',
	`uploadfileexts` Varchar(100) COMMENT '管理员上传文件类型',
	`uploadfilesize` Varchar(50) COMMENT '管理员上传文件大小',
	`checkcode` Varchar(100) COMMENT '登录验证码'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员表';

CREATE TABLE `{pre}upload` (
	`id` int NOT NULL primary key AUTO_INCREMENT COMMENT '主键',
	`aid` int DEFAULT 0 COMMENT '上传文件对应内容ID',
	`cid` int DEFAULT 0 COMMENT '上传文件对应栏目CID',
	`dir` Varchar(250) COMMENT '上传文件路径',
	`ext` Varchar(20) COMMENT '上传文件扩展名',
	`time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传文件时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='上传文件表';

CREATE TABLE `{pre}diypage` (
	`id` int NOT NULL primary key AUTO_INCREMENT COMMENT '主键',
	`dir` Varchar(200) COMMENT '自定义页路径',
	`tpl` Varchar(100) COMMENT '自定义页模板',
	`title` Varchar(200) COMMENT '自定义标题',
	`keywords` Varchar(200) COMMENT '自定义页关键词',
	`description` Varchar(250) COMMENT '自定义页描述',
	`html` text COMMENT '自定义页内容',
	`html2` text COMMENT '自定义内容备注'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='自定义页面表';

CREATE TABLE `{pre}tags` (
	`id` int NOT NULL primary key AUTO_INCREMENT COMMENT '主键',
	`name` Varchar(50) COMMENT '标签名称',
	`count` int DEFAULT 0 COMMENT '标签被搜索次数',
	`modifytime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '标签最后被搜索时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='关键词搜索标签表';

CREATE TABLE `{pre}bots` (
	`id` int NOT NULL primary key AUTO_INCREMENT COMMENT '主键',
	`botname` Varchar(50) COMMENT '蜘蛛名称',
	`lastdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '蜘蛛最后来访时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='蜘蛛统计表';

CREATE TABLE `{pre}config` (
	`id` int NOT NULL primary key AUTO_INCREMENT COMMENT '主键',
	`title` Varchar(50) COMMENT '配置名称',
	`name` Varchar(50) COMMENT '配置标识',
	`value` text COMMENT '配置值',
	`values` Varchar(250) COMMENT '配置值选项',
	`data` Varchar(20) COMMENT '配置值数据类型',
	`form` Varchar(20) COMMENT '配置值表单类型',
	`description` Varchar(250) COMMENT '配置描述',
	`order` int DEFAULT 0 COMMENT '配置显示顺序权重'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统配置表';

-- -----------------------------
-- config 插入默认配置 这是普通导入方式的SQL语句
-- -----------------------------  
INSERT INTO `{pre}config` VALUES ('1', '前台语言包', 'language', 'zh-cn', 'zh-cn=>简体中文|||en=>English', 'text', 'select', '网站前台语言包,zh-cn(简体中文),en(英文),语言包存放在/inc/language/目录下面', '0');
INSERT INTO `{pre}config` VALUES ('2', '网站名称', 'webname', '5ucmsPHP演示站', '', 'text', 'input', '在模板里使用{sys:webname}调用', '1');
INSERT INTO `{pre}config` VALUES ('3', '系统安装', 'installdir', '/', '', 'text', 'input', '/表示根目录,目前PHP版5UCMS只支持根目录安装', '2');
INSERT INTO `{pre}config` VALUES ('4', '模板路径', 'templatedir', 'blue2015', '', 'text', 'input', '当前所使用的模板,这里填写template目录下的文件夹名称即可', '3');
INSERT INTO `{pre}config` VALUES ('5', '首页链接名称', 'indexname', '首页', '', 'text', 'input', '在模板里使用{sys:indexname}调用', '4');
INSERT INTO `{pre}config` VALUES ('6', '首页链接地址', 'indexview', '/', '', 'text', 'input', '在模板里使用{sys:indexview}调用', '5');
INSERT INTO `{pre}config` VALUES ('7', '首页模板文件', 'indextemplate', 'index.html', '', 'text', 'templatefile,index', '网站首页模板路径,此模板应该在 templatedir 指定文件名下面', '6');
INSERT INTO `{pre}config` VALUES ('8', '首页存放地址', 'indexpath', '/', '', 'text', 'input', '网站首页文件存放地址,只能指定为路径,如/,不能指定文件名', '7');
INSERT INTO `{pre}config` VALUES ('9', '站内链接前缀', 'httpurl', '', '', 'text', 'input', '如果不使用请留空,如果使用则以http://开头,不要以/结尾,如http://www.5ucms.com', '8');
INSERT INTO `{pre}config` VALUES ('10', '缺省文件扩展', 'defaultext', 'html', '', 'text', 'input', '未指定生成文件扩展名时将使用这里所设置的扩展名', '9');
INSERT INTO `{pre}config` VALUES ('11', '站内路径间隔', 'sitepathsplit', ' &gt; ', '', 'text', 'input', '站内路径间隔符,{tag:sitepath}链接之间的分隔符', '10');
INSERT INTO `{pre}config` VALUES ('12', '图片水印LOGO', 'picwatermarkimg', '/inc/images/watermark.gif', '', 'text', 'input', '简单图片水印功能,留空则不进行水印,图片路径必需是绝对路径', '11');
INSERT INTO `{pre}config` VALUES ('13', '水印不透明度', 'picwatermarkalpha', '80', '', 'int', 'input', '水印不透明度,值的范围为 0~100，默认值是80', '12');
INSERT INTO `{pre}config` VALUES ('14', '水印LOGO位置', 'picwatermarktype', '9', '0=>关闭水印|||1=>左上角水印|||2=>上居中水印|||3=>右上角水印|||4=>左居中水印|||5=>居中水印|||6=>右居中水印|||7=>左下角水印|||8=>下居中水印|||9=>右下角水印', 'int', 'select', '水印LOGO在图片中的位置', '13');
INSERT INTO `{pre}config` VALUES ('15', '网站浏览模式', 'createhtml', '0', '0=>PHP动态|||1=>Html纯静态|||2=>Rewrite伪静态|||3=>PHP & Html栏目动态 其他纯静态', 'int', 'select', '请选择网站浏览模式', '14');
INSERT INTO `{pre}config` VALUES ('16', '自动分页大小', 'maxpageNum', '2000', '0=>不自动分页|||500=>500 个字符|||1000=>1000个字符|||1500=>1500个字符|||2000=>2000个字符|||2500=>2500个字符|||3000=>3000个字符|||4000=>4000个字符|||5000=>5000个字符', 'int', 'select', '如果设为0则不自动分页', '15');
INSERT INTO `{pre}config` VALUES ('17', '标题转成拼音', 'autopinyin', '1', '0=>禁用|||1=>启用', 'int', 'select', '添加文章时把标签转换成对应的拼音', '16');
INSERT INTO `{pre}config` VALUES ('18', '常见单词转换', 'getenglishstate', '1', '0=>禁用|||1=>启用', 'int', 'select', '针对常见单词的转换,比如将\"\"测试\"\"自动转换成\"\"test\"\"', '17');
INSERT INTO `{pre}config` VALUES ('19', '保存远程图片', 'remotepic', '1', '0=>禁用|||1=>启用', 'int', 'select', '保存远程图片需要一定的时间', '18');
INSERT INTO `{pre}config` VALUES ('20', '形象图获取', 'indexpicmode', '1', '0=>手动|||1=>自动', 'int', 'select', '如果是自动的话将把文章内第一张图片(本地)设置为形象图', '19');
INSERT INTO `{pre}config` VALUES ('21', '上下文模式', 'prenextmode', '1', '0=>全局|||1=>栏目', 'int', 'select', '内容页上下篇文章的调用模式,推荐使用栏目', '20');
INSERT INTO `{pre}config` VALUES ('22', '文件重命名', 'changdiyname', '1', '0=>禁止|||1=>允许', 'int', 'select', '是否允许修改文件名,经常改动文件名将会影响SEO效果', '21');
INSERT INTO `{pre}config` VALUES ('23', '文章描述', 'descriptionupdate', '1', '0=>手动|||1=>自动', 'int', 'select', '选择文章描述自动更新功能', '22');
INSERT INTO `{pre}config` VALUES ('24', '目录优化', 'seodir', '0', '0=>正常模式|||1=>特殊模式', 'int', 'select', '没什么特别嗜好请选择\"\"正常\"\"模式', '24');
INSERT INTO `{pre}config` VALUES ('25', '模板缓存', 'templatecache', '0', '0=>关闭|||1=>开启', 'int', 'select', '如果你开启了模板缓存,在修改模板后请先更新下缓存', '25');
INSERT INTO `{pre}config` VALUES ('26', '缓存标识', 'cacheflag', 'www.5ucms.com', '', 'text', 'input', '同一空间安装二套本系统时请保证这个值不重复', '51');
INSERT INTO `{pre}config` VALUES ('27', '缓存时间', 'cachetime', '60', '', 'int', 'input', '全局缓存时间间隔,单位秒', '52');
INSERT INTO `{pre}config` VALUES ('28', 'Rewrite网页扩展名', 'rewriteext', '.html', '', 'text', 'input', 'Rewrite网页扩展名,如果你不会改Rewrite规则,这里就不要动了,默认为.html', '53');
INSERT INTO `{pre}config` VALUES ('29', 'Rewrite栏目页前缀', 'rewritechannel', 'channel', '', 'text', 'input', 'Rewrite网页扩展名,如果你不会改Rewrite规则,这里就不要动了,默认为Channel', '54');
INSERT INTO `{pre}config` VALUES ('30', 'Rewrite内容页前缀', 'rewritecontent', '', '', 'text', 'input', 'Rewrite内容页前缀,如果你不会改Rewrite规则,这里就不要动了,默认为空', '55');
INSERT INTO `{pre}config` VALUES ('31', '前台缩略图生成模式', 'thumbtype', '2', '1=>等比例缩放类型|||2=>缩放后填充类型|||3=>居中裁剪类型|||4=>左上角裁剪类型|||5=>右下角裁剪类型|||5=>固定尺寸缩放类型', 'int', 'select', '需PHP支持缩略图功能 有文件夹生成权限；如若修改此处，请在上传文件中清空缩略图，然后更新缓存。', '70');
INSERT INTO `{pre}config` VALUES ('32', '首页关键字', 'indexkeywords', '无忧网络,无忧网络文章管理系统,PHP,MYSQL,Rewrite', '', 'text', 'textarea', '多个关键词用英文状态下的,分隔', '90');
INSERT INTO `{pre}config` VALUES ('33', '首页描述', 'indexdescription', '无忧网络文章管理系统基于PHP+MYSQL 技术开发,免费,开源,程序小巧,功能强大,可用于博客,企业站,信息综合类的建设,使用本系统请保留官方 www.5ucms.com 的链接', '', 'text', 'textarea', '多个来源用英文状态下的,分隔', '91');
INSERT INTO `{pre}config` VALUES ('34', '文章作者', 'author', 'Admin,5ucms', '', 'text', 'textarea', '多个作者用英文状态下的,分隔', '99');
INSERT INTO `{pre}config` VALUES ('35', '文章来源', 'source', '本站', '', 'text', 'textarea', '多个来源用英文状态下的,分隔', '100');


CREATE TABLE `{pre}plus` (
	`id` int NOT NULL primary key AUTO_INCREMENT COMMENT '主键',
	`name` Varchar(50) COMMENT '插件标识',
	`title` Varchar(50) COMMENT '插件名称',
	`description` text COMMENT '插件描述',
	`config` text COMMENT '插件配置',
	`author` Varchar(50) COMMENT '插件作者',
	`version` Varchar(10) COMMENT '插件版本',
	`adminlist` int DEFAULT 0 COMMENT '是否有后台功能',
	`createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '插件安装时间',
	`modifytime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '插件修改时间',
	`order` int DEFAULT 0 COMMENT '插件排序权重',
	`state` tinyint(1) DEFAULT 0 COMMENT '插件可用状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件信息表';

-- -----------------------------
-- 安装插件
-- -----------------------------
INSERT INTO `{pre}plus` VALUES ('1', 'AdFloat', '图片漂浮广告', '图片漂浮广告', '{\"state\":\"0\",\"url\":\"http:\\/\\/www.5ucms.com\",\"image\":\"\",\"width\":\"100\",\"height\":\"100\",\"speed\":\"10\",\"target\":\"1\"}', '5ucms.com', '1.0', '0', '1441704874', '1441704874', '0', '1');
INSERT INTO `{pre}plus` VALUES ('2', 'Count', '内容浏览量统计', '可自定义统计方式 卸载将导致内容无法统计浏览量', '{\"state\":\"1\",\"method\":\"0\",\"num\":\"1\"}', '5ucms.com', '1.0', '0', '1441704877', '1441704877', '0', '1');
INSERT INTO `{pre}plus` VALUES ('3', 'Email', '邮件插件', '实现系统发邮件功能', '{\"state\":\"1\",\"MAIL_SMTP_TYPE\":\"1\",\"MAIL_SMTP_SECURE\":\"0\",\"MAIL_SMTP_PORT\":\"25\",\"MAIL_SMTP_HOST\":\"smtp.qq.com\",\"MAIL_SMTP_USER\":\"\",\"MAIL_SMTP_PASS\":\"\",\"default\":\"\"}', '5ucms.com', '1.0', '0', '1441704880', '1441704880', '0', '1');
INSERT INTO `{pre}plus` VALUES ('4', 'ReturnTop', '返回顶部', '返回顶部', '{\"state\":\"1\",\"theme\":\"rocket\",\"customer\":\"\",\"case\":\"\",\"qq\":\"\",\"weibo\":\"\"}', '5ucms.com', '1.0', '0', '1441704883', '1441704883', '0', '1');
INSERT INTO `{pre}plus` VALUES ('5', 'Search', '内容搜索插件', '站内内容搜索', '{\"state\":\"0\",\"pagenum\":\"10\",\"maxnum\":\"1000\",\"method\":\"1\"}', '5ucms.com', '1.0', '1', '1441704886', '1441704886', '0', '1');
INSERT INTO `{pre}plus` VALUES ('6', 'SyncLogin', '第三方账号登陆', '第三方账号登陆', '{\"type\":null,\"meta\":\"\",\"WeixinKEY\":\"\",\"WeixinSecret\":\"\",\"QqKEY\":\"\",\"QqSecret\":\"\",\"SinaKEY\":\"\",\"SinaSecret\":\"\",\"RenrenKEY\":\"\",\"RenrenSecret\":\"\"}', '5ucms.com', '1.0', '1', '1441704890', '1441704890', '0', '1');

-- -----------------------------
-- plushook 插件钩子表 
-- -----------------------------  

CREATE TABLE `{pre}plushook` (
	`id` int NOT NULL primary key AUTO_INCREMENT COMMENT '主键',
	`name` Varchar(50) COMMENT '钩子名称',
	`description` text COMMENT '钩子描述',
	`pluss` text COMMENT '钩子包含插件表',
	`type` int DEFAULT 0 COMMENT '钩子类别',
	`createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '钩子创建时间',
	`modifytime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '钩子修改时间',
	`state` tinyint(1) DEFAULT 0 COMMENT '钩子可用状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件钩子表';


-- -----------------------------
-- plushook 插入默认钩子 这是锁定表 取消索引 导入 恢复索引 恢复表 的方式导入数据 效率较高
-- -----------------------------  
LOCK TABLES `{pre}plushook` WRITE;
/*!40000 ALTER TABLE `{pre}plushook` DISABLE KEYS */;

INSERT INTO `{pre}plushook` (`id`, `name`, `description`, `pluss`, `type`, `createtime`, `modifytime`, `state`)
VALUES
	(1,'PageHeader','页面公共头部header钩子，一般用于加载插件CSS文件和代码','SyncLogin',1,1407681961,1407681961,1),
	(2,'PageFooter','页面公共底部footer钩子，一般用于加载插件CSS文件和代码','ReturnTop,AdFloat',1,1407681961,1407681961,1),
	(3,'PageSide','页面侧边栏钩子','',1,1407681961,1407681961,1),
	(4,'ChannelHeader','列表页面公共顶部钩子','',1,1407681961,1407681961,1),
	(5,'ChannelFooter','列表页面公共底部钩子','',1,1407681961,1407681961,1),
	(6,'ContentHeader','内容页面公共顶部钩子','',1,1407681961,1407681961,1),
	(7,'ContentFooter','内容页面公共底部钩子','Count',1,1407681961,1407681961,1),
	(8,'UploadFile','上传文件钩子','',1,1407681961,1407681961,1),
	(9,'SendMessage','发送消息钩子','',1,1407681961,1407681961,1),
	(10,'SyncLogin','第三方登陆','SyncLogin',1,1407681961,1407681961,1),
	(11,'Taginside','独立插件钩子','Serach',1,1407681961,1407681961,1);

/*!40000 ALTER TABLE `{pre}plushook` ENABLE KEYS */;
UNLOCK TABLES;

-- -----------------------------
-- 安装第三方登陆表
-- -----------------------------
DROP TABLE IF EXISTS `{pre}plus_sync_login`;
CREATE TABLE `{pre}plus_sync_login` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `type` varchar(15) NOT NULL DEFAULT '' COMMENT '类别',
  `openid` varchar(64) NOT NULL DEFAULT '' COMMENT 'OpenID',
  `access_token` varchar(64) NOT NULL DEFAULT '' COMMENT 'AccessToken',
  `refresh_token` varchar(64) NOT NULL DEFAULT '' COMMENT 'RefreshToken',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='第三方登陆插件表';