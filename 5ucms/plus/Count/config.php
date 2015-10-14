<?php
return array(
    'state'=>array(
        'title'=>'是否开启:',
        'type'=>'radio',
        'options'=>array(
            '1'=>'开启',
            '0'=>'关闭',
        ),
        'value'=>'1',
    ),
    'method'=>array(
        'title'=>'是否随机:',
        'type'=>'radio',
        'options'=>array(
            '1'=>'随机',
            '0'=>'正常',
        ),
        'value'=>'0',
    ),
    'num'=>array(
        'title'=>'每次统计增加的数字(随机模式下则填最大值 默认最小1)',
        'type'=>'text',
        'value'=>'1'
    ), 
);
