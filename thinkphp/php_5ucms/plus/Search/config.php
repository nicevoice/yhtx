<?php
return array(
    'state'=>array(
        'title'=>'是否开启:',
        'type'=>'radio',
        'options'=>array(
            '1'=>'开启',
            '0'=>'关闭',
        ),
        'value'=>'0',
    ),
    'pagenum'=>array(
        'title'=>'每页条数:',
        'type'=>'text',
        'value'=>'10',
		'tip'=>'搜索结果每页显示的最大条数'
    ),
    'maxnum'=>array(
        'title' => '最大记录数:',
        'type'  => 'text',
        'value' => '1000',
		'tip'=>'每次搜索最多反馈回来的信息条数'
    ),
    'method'=>array(
        'title'=>'搜索范围:',
        'type'=>'radio',
        'options'=>array(
            '0'=>'仅标题',
            '1'=>'标题+描述',
        ),
        'value'=>'1',
    ),
);
