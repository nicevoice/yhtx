<?php 
//标签关键词Tags表操作 
namespace Plus\Search\Model;
use Think\Model;
 
class TagsModel extends Model{


    /**
     * 后台列表管理相关定义
     */
    public $adminList = array(
        'title' => '第三方登录列表',
        'model' => 'tags',
        'search_key'=>'name', //搜索位默认字段
        'order'=>'count desc',//后台显示排序方式
        'map' => null,
        'list_grid' => array(
            'id' => array(
                'title' => '编号',
                'type'  => 'text',
            ),
            'name' => array(
                'title' => '关键词',
                'type'  => 'text',
            ),
            'count' => array(
                'title' => '搜索次数',
                'type'  => 'text',
            ),
            'modifytime' => array(
                 'title' => '最近时间',
                 'type'  => 'text',
             ),
        ),
        'field' => array( //后台新增、编辑字段
            'name' => array(
                 'name'  => 'name',
                 'title' => '关键词', 
                 'type'  => 'text',
             ),
             'count' => array(
                 'name'  => 'count',
                 'title' => '搜索次数',
                 'type'  => 'text',
             ), 
        ),
    );

}
