<?php
//后台进程配置 s:i:H:d:m:w
$config = array(
    array('path' => '/cmd/cricin/syncall', 'cron' => '0 0 1 * * *', 'num' => 1 ),
    array('path' => '/cmd/Evaluation/index', 'cron' => '1 */10 * * * *', 'num' => 1 ),
    array('path' => '/cmd/datatrans/unittrans', 'cron' => '0 0 3 * * *', 'num' => 1 ),

);

return $config;
