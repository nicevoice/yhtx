<?php
return array(
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'ser_cms',          // 数据库名
    'DB_USER'               =>  'zfx',      // 用户名
    'DB_PWD'                =>  '111111',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'URL_MODEL'             =>  3,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    //七牛上传配置
    'qiniu_config' => array(
        'AccessKey' => '2ylH9TSMNhehEkVx4qa0J9d8MUVAbrpL7cpq31Xk',
        'SecretKey' => 'ajdjtuw90Od1f94atipJVX3l2uR8mcQpPAGoyQId',
        'bucket' => 'demoto-cmf',
        'url' => 'http://7xkon4.com1.z0.glb.clouddn.com/',
    ),
    //评论间隔时间(秒)
    'COMMENT_INTERVAL' => 60,
    //微信配置
    'WEI_XIN_CONFIG' => array(
        'AppID' => 'wx56b36aa0e9489f27',
        'AppSecret' => 'abd370b2792784a0fcc171a10074cb27',
        'EncodingAESKey' => '76q65xNcsFAxlbhWuJ8jSLLbQAzzC4mqRxbPWfo94yC',
        'Token' => 'sd_cms'
    ),
    //容联配置
    'RL_CONFIG' => array(
        //主帐号,对应开官网发者主账号下的 ACCOUNT SID
        'accountSid' => 'aaf98f8947c493ab0147c8492c3b0538',
        //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
        'accountToken' => '6a577569cdcb4f69b2c41fe9c69ed92d',
        //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
        //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
        'appId' => '8a48b5514f1702fd014f25a9d97b1427',
        //请求地址
        //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
        //生产环境（用户应用上线使用）：app.cloopen.com
        'serverIP' => 'app.cloopen.com',
        //请求端口，生产环境和沙盒环境一致
        'serverPort' => '8883',
        //REST版本号，在官网文档REST介绍中获得。
        'softVersion'=>'2013-12-26',
    ),
    //唠嗑间隔时间(秒)
    'SNS_INTERVAL' => 60,
);