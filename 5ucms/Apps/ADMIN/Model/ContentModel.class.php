<?php 
//管理员Content表操作 150714-150714
namespace ADMIN\Model;
use Think\Model;

class ContentModel extends Model{
	
	//新数据允许提交的字段
    //protected $insertFields = array('Dir','Tpl','Title','keywords','Description','Html');
	//修改数据允许提交的字段
    //protected $updateFields = array('nickname','email'); 
	

    //一次性获得全部验证错误
    //protected $patchValidate    =   true;
 
	 
    //实现表单项目验证 通过重写父类属性_validate实现表单验证
    protected $_validate        =   array(
        
        //验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        //验证用户名,require必须填写项目
        array('title','require','内容标题必须填写'),
		array('title','1,200','内容标题长度必须在1-200范围内',1,'length',3),
		//array('Text','','标签名称存在！',0,'unique',1), // 在新增的时候验证值是否唯一
        //array('Content','require','标签名称必须填写'),
    ); 
	
	//自动完成
     protected $_auto        =   array ( 
         //array('Html','1'),  // 新增的时候把status字段设置为1 
         //array('Password','md5',3,'function') , // 对password字段在新增和编辑的时候使md5函数处理
         //array('name','getName',3,'callback'), // 对name字段在新增和编辑的时候回调getName方法
         //array('CheckCode','time',2,'function'), // 对update_time字段在更新的时候写入当前时间戳 
     ); 
}

