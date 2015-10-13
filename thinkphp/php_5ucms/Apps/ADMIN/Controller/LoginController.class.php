<?php
//后台登陆页
namespace ADMIN\Controller;
use Think\Controller;
class LoginController extends Controller {

    function Index(){   
		$auth = session('admin');
		if($auth['uid']){
			$this->redirect('Index/index');
		} 
		$content = $this->fetch(); 
		$content = ChageATurl($content);//修正素材文件路径
		$this->show($content);  
    }
    function Login(){  
		$sn = I('post.sn');  
		//验证传入的验证码是否正确
		$verify = new \Think\Verify(); 
		if(!$verify->check($sn)){
			$this -> success('验证码错误，请重试！');exit;
		}else{
			$qedit = M('Admin'); 
			$username = I('post.username',null); 
			$password = newmd5(I('post.password',null));  //新md5加密
			$user     = $qedit -> where('username = "'.$username.'"') -> find();  
			if(!$user){$this->error('帐号不存在或被禁用');} 
			if($user['password'] !== $password){
				//echo I('post.password',null) .' 数据库ID = '  . $user['id'] .' 数据库密码 = ' .$user['password'] .' 输入 = '. $password;
				$this->error('帐号不存在或密码错误');
			} 
			$auth = array(
				'uid'				=> $user['id'],
				'username'			=> $user['username'],
				'password'			=> $user['password'],
				'levels'			=> $user['levels'],
				'manageplus'		=> $user['manageplus'],
				'managechannel'		=> $user['managechannel'],
				'uploadfileexts'	=> $user['uploadfileexts'],
				'uploadfilesize'	=> $user['uploadfilesize'] 
			); 
			session('admin',$auth);
			cookie('admin',$auth); 		
			$user = true ? $this -> success('登陆成功！',U('Index/index'),3) : $this->error("登陆失败");   
		}
    }
	
	//验证码显示功能
	function verifyimg(){
		$config =    array(
			'expire'      =>    30,    //验证码的有效期（秒）
			'imageW'      =>    120,   //验证码宽度 设置为0为自动计算
			'imageH'      =>    30,    //验证码高度 设置为0为自动计算
			'fontSize'    =>    15,    // 验证码字体大小
			'length'      =>    4,     // 验证码位数
			'useNoise'    =>    false, // 关闭验证码杂点
		);	
		
		$verfy = new \Think\Verify($config);
		$verfy -> entry();
	} 

    function Adminname(){
		return 'qss';
    }
	
	//退出登录
    function loginout(){ 
		session('admin',null); //销毁session
		cookie('admin',null); //销毁cookie
		$this -> success('退出成功！',U('index'),3);
    }
	
	function _empty($name){
		error_404($name);
    }
}