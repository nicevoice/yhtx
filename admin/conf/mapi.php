<?php
$config['condition_price'] = array(
    1 => '1万以下',
    2 => '1-1.5万',
    3 => '1.5-2万',
    4 => '2-2.5万',
    5 => '2.5-3万',
    6 => '3-5万',
    7 => '5万以上',
    8 => '价格待定'
);

$config['condition_layout'] = array(
    1 => '一房',
    2 => '二房',
    3 => '三房',
    4 => '四房',
    5 => '五房',
    6 => '复式',
    7 => '独栋',
    8 => '叠加',
    9 => '双拼',
    10 => '联排',
    11 => '花园洋房'
);

$config['condition_features'] = array(
    1 => '高端型榜单',
    2 => '刚需型榜单',
    3 => '改善型榜单',
    4 => '松江区榜单',
    5 => '浦东新区榜单',
    6 => '嘉定区榜单'
);

//'AAA' => '尽快入手', 'A+' => '推荐购买', 'BBB+' => '谨慎购买', 'C' => '持币观望'
//'1' => '尽快入手', '2' => '推荐购买', '3' => '谨慎购买', '4' => '持币观望'
$config['recommend'] = array(
    'AAA' => 1,
    'A+' => 2,
    'BBB+' => 3,
    'C' => 4
);
$config['recommend_text'] = array(
    'AAA' => '尽快入手',
    'A+' => '推荐购买',
    'BBB+' => '谨慎购买',
    'C' => '持币观望',
);
$config['recommend_color'] = array(
    'AAA' => '#4481DC',
    'A+' => '#E6A70C',
    'BBB+' => '#F13E34',
    'C' => '#999966',
);

$config['rate'] = 0.059; //商贷月利率
$config['token'] = 'cric'; //生成token的key

$config['code_life'] = 30; //code生命周期（分钟）
$config['code_pre'] = 'verifycode'; //code生命周期（分钟）
$config['welcome_message'] = 'Hi,我是您的专属分析师！'; //欢迎词
$config['signkey'] = 'cric'; //数字签名key
$config['tel_400'] = '400-810-6999'; //400电话
$config['idea_tel_400'] = '400-810-6999'; //理想家400电话

return $config;