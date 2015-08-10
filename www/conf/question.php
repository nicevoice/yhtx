<?php
//【请修改】联调时，请把该值设置为true，上线后请设置后false
$config['ZHIDAO_OPENAPI_TEST_FLAG'] = false;

//【请填充】知道为您分配的appkey
$config['ZHIDAO_OPENAPI_APPKEY'] = '1190';

//【请填充】知道为您分配的securitykey
$config['ZHIDAO_OPENAPI_SECURITYKEY'] = '4B23A709AA539535DF4D3920594920E1';

//【无须修改】知道的测试和线上域名
$config['ZHIDAO_OPENAPI_HOST_TEST'] = 'http://180.149.144.179:8080';

//【无须修改】知道的线上提交域名
$config['ZHIDAO_OPENAPI_HOST'] = 'http://zhidao.baidu.com';

$config['key'] = 'cric';
$config['token'] = '8rWQFrqNqXlwqOCcDJ8VUtSW7Rn6z3ttaon9';

//列表页每页显示条数
$config['rows'] = 20;
$config['operate'] = array(
    '回答',
    '修改城市',
    '批量修改城市',
    '隐藏',
    '显示'
);

return $config;