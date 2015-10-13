<?php 
//管理员admin表操作 
namespace ADMIN\Model;
use Think\Model;

class AdminModel extends Model{
	
	//新数据允许提交的字段
    //protected $insertFields = array('account','password','nickname','email');
	//修改数据允许提交的字段
    //protected $updateFields = array('nickname','email'); 
	//字段映射 把表单中name映射到数据表的username字段
/*      protected $_map = array(
         'dir' =>'Dir', // 把表单中name映射到数据表的username字段
         'title'  =>'Title', // 把表单中的mail映射到数据表的email字段
     ); */
	 
    //一次性获得全部验证错误
    //protected $patchValidate    =   true;
    
    //实现表单项目验证 通过重写父类属性_validate实现表单验证
    protected $_validate        =   array(
        
        //验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        //验证用户名,require必须填写项目
        array('username','require','用户名必须填写'),
		array('username','3,16','用户名长度必须在3-16范围内',1,'length',3),
		array('username','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
        array('password','require','密码必须填写'),
        //可以为同一个项目设置多个验证
        //array('password2','require','确认密码必须填写'),
        //与密码的值得是一致的
        //array('password2','password','与密码的信息必须一致',0,'confirm'),
        //邮箱验证
        //array('user_email','email','邮箱格式不正确',2),
        //验证qq
        //都是数字的、长度5-10位、 首位不为0
        //正则验证  /^[1-9]\d{4,9}$/
        //array('user_qq',"/^[1-9]\d{4,9}$/",'qq格式不正确'),
        
        //学历，必须选择一个，值在2,3,4,5范围内即可
        //array('user_xueli',"2,3,4,5",'必须选择一个学历',0,'in'),
        
        //爱好项目至少选择两项以上
        //爱好的值是一个数组，判断其元素个数即可知道结果
        //callback利用当前model里边的一个指定方法进行验证
        //array('user_hobby','check_hobby','爱好必须两项以上',1,'callback'),
    ); 
	//自动完成
     protected $_auto        =   array ( 
         //array('status','1'),  // 新增的时候把status字段设置为1 
         array('password','md5',3,'function') , // 对password字段在新增和编辑的时候使md5函数处理
         //array('name','getName',3,'callback'), // 对name字段在新增和编辑的时候回调getName方法
         array('checkcode','time',3,'function'), // 对update_time字段在更新的时候写入当前时间戳
     );
	 
 
}
