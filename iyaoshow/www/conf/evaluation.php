<?php
$config['room'] = array(
    1 => '一房',
    2 => '二房',
    3 => '三房',
    4 => '四房',
    5 => '五房',
    6 => '六房',
);
$config['hall'] = array(
    1 => '一厅',
    2 => '二厅',
    3 => '三厅',
    4 => '四厅',
    5 => '五厅',
);
$config['toilet'] = array(
    1 => '一卫',
    2 => '二卫',
    3 => '三卫',
    4 => '四卫',
    5 => '五卫',
);
$config['toward'] = array(
    1 => '东',
    2 => '南',
    3 => '西',
    4 => '北',
    5 => '东南',
    6 => '东北',
    7 => '西南',
    8 => '西北',
);

$config['chapter']['parent'] = array(
    1 => '户型分析',
    2 => '装修标准',
    3 => '社区品质',
    4 => '物业服务',
    5 => '交通出行',
    6 => '区域配套',
    7 => '不利因素',
);
$config['chapter']['child'] = array(
    1 => array(
        1 => '整体评价',
        2 => '户型分析',
    ),
    2 => array(
        3 => '品牌配置',
        4 => '装修分析',
    ),
    3 => array(
        5 => '整体规划',
        6 => '社区景观',
        7 => '建筑立面',
        8 => '公共部位',
        9 => '社区配置',
        10 => '车位情况',
    ),
    4 => array(
        11 => '物业费用',
        12 => '物业服务',
    ),
    5 => array(
        13 => '自驾出行',
        14 => '轨交出行',
        15 => '公交出行',
    ),
    6 => array(
        16 => '区位简介',
        17 => '教育资源',
        18 => '医疗资源',
        19 => '周边商圈',
        20 => '公共资源',
    ),
    7 => array(
        21 => '社区内部',
        22 => '社区外部',
    ),
);
$config['yls'] = array(
    1 => '优势',
    2 => '劣势',
);

$config['bus']['aType'] = ['无', '上行', '下行', '内环', '外环'];
$config['metro']['aOpen'] = ['规划中', '开通'];
$config['bus']['status'] = ['禁用', '启用'];

$config['hospital']['aType'] = [1 => '综合医院', 2 => '专科医院', 3 => '社区卫生站'];
$config['hospital']['aLevel'] = [99 => '未知等级', 1 => '一级丙等', 2 => '一级乙等', 3 => '一级甲等',
    4 => '二级丙等', 5 => '二级乙等', 6 => '二级甲等', 7 => '三级丙等',
    8 => '三级乙等', 9 => '三级甲等', 10 => '三级特等'];
$config['hospital']['aProperty'] = [1 => '公立', 2 => '私立'];
$config['hospital']['aIsMedicalPoint'] = [1 => '非医保定点医院', 2 => '医保定点医院'];
$config['medical']['clinicType'] = [1 => '综合诊所', 2 => '中医诊所',3 => '专科诊所']; // add by cjj
$config['medical']['pharmacyTs'] = [1 => '24小时药房', 2 => '医保定点药房'];
$config['hx']['iRoom'] = array(
    1 => '一房',
    2 => '二房',
    3 => '三房',
    4 => '四房',
    5 => '五房',
    6 => '六房',
    7 => '七房',
    8 => '八房',
    9 => '九房',
    10 => '复式',
    11 => '错层',
    12 => '独栋',
    13 => '双拼',
    14 => '联排',
    15 => '叠加',
    16 => '其他',
    17 => '跃层'
);
$config['hx']['iHall'] = array(
    0 => '',
    1 => '一厅',
    2 => '两厅',
    3 => '三厅',
    4 => '四厅',
    5 => '五厅'
);
$config['hx']['iToilet'] = array(
    0 => '',
    1 => '一卫',
    2 => '两卫',
    3 => '三卫',
    4 => '四卫',
    5 => '五卫'
);
$config['hx']['iRoomToward'] = array(
    0=> '',
    1 => '东',
    2 => '南',
    3 => '西',
    4 => '北',
    5 => '东南',
    6 => '东北',
    7 => '西南',
    8 => '西北',
);
$config['chapter']['controller'] = array(
    1 => 'Hxanalyse', //户型分析
    2 => 'Zxstandard', //装修标准
    3 => 'Sqpz',//社区品质
    4 => 'Wyfw',//物业服务
    5 => 'Traffic',//交通出行
    6 => 'Region',//区域配套
    7 => 'Badfactor',//不利因素

);

$config['chapter']['action'] = array(
    1 => array(
        1 => 'index',
        2 => 'hx',
    ),
    2 => array(
        3 => 'index',
        4 => 'analysis',
    ),
    3 => array(
        5 => 'index',
        6 => 'scenery',
        7 => 'build',
        8 => 'public',
        9 => 'config',
        10 => 'parking',
    ),
    4 => array(
        11 => 'index',
        12 => 'service',
    ),
    5 => array(
        13 => 'index',
        14 => 'rail',
        15 => 'bus',
    ),
    6 => array(
        16 => 'index',
        17 => 'educate',
        18 => 'medical',
        19 => 'business',
        20 => 'public',
    ),
    7 => array(
        21 => 'index',
        22 => 'outSide'
    )

);
$config['hx']['sType'] = array(
    // 户型分析
    'hd' => 101, //户型图
    'sp' => 102, // 实拍图
    'dp' => 103, // 点评图
    // 装修标准 --品牌配置
    'cftm' => 204, // 厨房台面
    'cfcg' => 205, // 厨房橱柜
    'cfsc' => 206, // 厨房水槽
    'cflt' => 207, // 厨房水龙头
    'cfyj' => 208, // 厨房抽油烟机
    'cfrq' => 209, // 厨房抽燃气灶
    'cfxd' => 210, // 厨房抽消毒柜
    'cfkx' => 211, // 厨房抽烤箱
    'cfzl' => 212, // 厨房抽蒸炉
    'cfbx' => 213, // 厨房抽冰箱
    'cfqz' => 214, // 厨房抽墙砖
    'cfdz' => 215, // 厨房抽地砖
    'wybq' => 216, // 卫浴坐便器
    'wytp' => 217, // 卫浴坐台盆
    'wyyg' => 218, // 卫浴坐浴缸
    'wylt' => 219, // 卫浴坐龙头
    'wyqm' => 220, // 卫浴坐墙面
    'wydz' => 221, // 卫浴坐地砖
    'wyyb' => 222, // 卫浴坐浴霸
    'wyzh' => 223, // 卫浴柜镜柜组合
    'wsm' => 224, // 卧室门
    'wsdb' => 225, // 卧室地板
    'wsqm' => 226, // 卧室墙面
    'wsdd' => 227, // 卧室吊灯
    'qwxf' => 228, // 全屋新风系统
    'qwkt' => 229, // 全屋新空调
    'qwdn' => 230, // 全屋新地暖
    'qwhw' => 231, // 全屋新红外布防系统
    // 装修标准 --装修分析
    'zxms' => 232, // 装修描述
    // 社区品质
    'sqpm' => 301, // 小区平面图
    'sqnk' => 302, // 全景鸟瞰图
    // 社区品质 -- 社区景观
    'sqxg' => 303, // 社区景观效果图
    'sqsp' => 304, // 社区景观实拍图
    // 社区品质 -- 建筑立面
    'sqws' => 305, // 社区景观外立面实拍图
    'sqwx' => 306, // 社区景观外立面效果图
    // 社区品质 -- 公共部位
    'scdy' => 307, // 首层门厅单元门
    'scmj' => 308, // 首层门厅门禁系统
    'scdj' => 309, // 首层门厅灯具
    'scbx' => 310, // 首层门厅报箱
    'scdz' => 311, // 首层门厅地砖
    'scqm' => 312, // 首层门厅墙面
    'dt' => 313, // 电梯厅电梯
    'dtdj' => 314, // 电梯厅灯具
    'dtdz' => 315, // 电梯厅地砖
    'dtqm' => 316, // 电梯厅墙面
    'zldj' => 317, // 走廊灯具
    'zldz' => 318, // 走廊地砖
    'zlqm' => 319, // 走廊墙面
    // 社区品质 -- 车位情况
    'cwpm' => 320, // 地下车位平面图
    'cwsj' => 321, // 车位实景图
    'xclx' => 322, // 行车路线
    // 社区品质 -- 社区配置
    'etgh' => 323, // 儿童活动场 规划图
    'etsj' => 324, // 儿童活动场 实景图
    'lngh' => 325, // 老年活动区 规划图
    'lnsj' => 326, // 老年活动区 实景图
    'lqgh' => 327, // 篮球场 规划图
    'lqsj' => 328, // 篮球场 实景图
    'wqgh' => 329, // 网球场 规划图
    'wqsj' => 330, // 网球场 实景图
    'swyg' => 331, // 室外游泳池 规划图
    'swys' => 332, // 室外游泳池 实景图
    'slyg' => 333, // 室内游泳池 规划图
    'slys' => 334, // 室内游泳池 实景图
    'jsgh' => 335, // 健身房 规划图
    'jssj' => 336, // 健身房 实景图
    'qpgh' => 337, // 棋牌房 规划图
    'qpsj' => 338, // 棋牌房 实景图
    'hsqt' => 339, // 会所其他图
    // 区域配套 -- 区位简介
    'qytp' => 601, // 区位图
    // 区域配套 -- 周边商圈
    'zbmc' => 602, // 卖场图
    'zbyh' => 603, // 银行图
    'zbcy' => 604, // 餐饮图
    'zbfw' => 605, // 生活服务,
    'zbcc' => 606, // 周边菜场
    //交通出行 -- 自驾出行
	'zjxl' => 513, //自驾线路
    'gjcx' => 514, //轨交出行
    'ggcx' => 515, //公交出行
    // 区域配套 -- 公共资源
    'qyjg' => 607, // 景观实拍图
    // 不利因素
    'blfb' => 701, // 不利因素分布图
    'blsp' => 702, // 不利因素实拍
    //教育资源
    'kindergarten' => 801, // 幼儿园
    'primary' => 802, //小学
    'middle' => 803, //中学
    // 区域配套 -- 医疗资源
    'zbyy' => 901,// 医院图片

    'ztqt' => 2099,// 整体评价其他图片
    'ppqt' => 2199,// 品牌其他图片
    'zxqt' => 2299,// 装修分析其他图片
    'ghqt' => 2399,// 整体规划其他图片
    'jgqt' => 2499,// 社区景观其他图片
    'jzqt' => 2599,// 建筑立面其他图片
    'ggqt' => 2699,// 公共部位其他图片
    'pzqt' => 2799,// 社区配置其他图片
    'cwqt' => 2899,// 车位情况其他图片
    'wyqt' => 2999,// 物业费用其他图片
    'fwqt' => 3099,// 物业服务其他图片
    'zjqt' => 3199,// 自驾出行其他图片
    'ddqt' => 3299,// 轨交出行其他图片
    'gjqt' => 3399,// 公交出行其他图片
    'qwqt' => 3499,// 区位简介其他图片
    'jyqt' => 3599,// 教育资源其他图片
    'ylqt' => 3699,// 医疗资源其他图片
    'sqqt' => 3799,// 周边商圈其他图片
    'zyqt' => 3899,// 公共资源其他图片
    'nbqt' => 3999,// 社区内部其他图片
    'wbqt' => 4099,// 社区外部其他图片

);
$config['standard']['qllx'] = array(
    1 => '灯暖',
    2 => '风暖',
);
$config['standard']['gqlx'] = array(
    1 => '光纤到楼',
    2 => '光纤到户',
);
$config['standard']['xjb'] = array(
    1 => '高',
    2 => '中',
    3 => '低',
);
// 影响源
$config['hx']['yxy'] = array(
        1 => array(
            1 => '变电站',
            2 => '辐射干扰'
        ),
        2 => array(
           1 => '车库入口',
           2 => '噪音干扰、灯光干扰'
        ),
        3 => array(
            1 => '垃圾收集站',
            2 => '空气污染'
        ),
);
//编辑开关
$config['evaluationSwitch'] = 1;  //0为可编辑 1为不可编辑
// 评测报告接口url
$config['filterChapter'] = array(14,15,17,18,19,); // 接口过来的评测报告需要去除的章节
return $config;