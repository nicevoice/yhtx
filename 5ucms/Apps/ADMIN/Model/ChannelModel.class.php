<?php
//栏目表channel操作 
namespace ADMIN\Model;
use Think\Model;

class ChannelModel extends Model{
	
	//新数据允许提交的字段
    //protected $insertFields = array('id','cid','fatherid','childids','deeppath','name','order','table','domain','outsidelink','templatechannel','templateclass','templateview','ruleindex','rulechannel','ruleview','picture','keywords','description','needcreate','modeext');
	//修改数据允许提交的字段
    //protected $updateFields = array('nickname','email'); 
	

    //一次性获得全部验证错误
    //protected $patchValidate    =   true;
 
	 
    //实现表单项目验证 通过重写父类属性_validate实现表单验证
    protected $_validate        =   array(
        
        //验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        //验证用户名,require必须填写项目 
        array('name','require','栏目名称必须填写'),
		array('name','1,200','栏目名称长度必须在1-200范围内',1,'length',3),
		//array('text','','标签名称存在！',0,'unique',1), // 在新增的时候验证值是否唯一
        //array('link','1,200','标签说明长度必须在1-200范围内',1,'length',3), 
    ); 
	
	//自动完成
     protected $_auto        =   array ( 
         //array('Modeext','1'),  // 新增的时候把status字段设置为1 
         //array('password','md5',3,'function') , // 对password字段在新增和编辑的时候使md5函数处理
         //array('order','getdeeppath',3,'callback'), // 对name字段在新增和编辑的时候回调getname方法
         //array('Checkcode','time',2,'function'), // 对update_time字段在更新的时候写入当前时间戳
     );
	
	//获取指定分类id的指定字段信息
    public function info($id, $field = true){ 
        $map = array();
        if(is_numeric($id)){ //通过id查询
            $map['id'] = $id;
        } else { //通过标识查询
            $map['name'] = $id;
        }
        return $this->field($field)->where($map)->find($id);
    }
 
	
}
