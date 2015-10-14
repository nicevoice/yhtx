<?php
namespace PHP_5UCMS\Controller;
use Think\Controller;
use Think\Storage;

class IndexController extends Controller {
	
	//如果没安装，则跳到安装界面
	protected function _initialize(){
		$lockpath = APP_PATH . 'Install/Data/install.lock';
		if(!Storage::has($lockpath)){
			redirect('./install.php');
			die;
		}
	} 
	
	//首页代码
    public function index(){
		$content = $this->fetch(); 
		$content = qss5ucms($content); //模板标签转换
		$content = ChageATurl($content,C('templatedir')); //修改资源文件路径
		$this->show($content);  
		if(Get_Spider()){echo C('indexcopyright');};//获取蜘蛛信息
    }
	
	//内容跳转
    public function redirectcontent(){  
		$id = $_GET['id'];
		if(is_numeric($id) and $id > 0){
			redirect(U('content/index','id='.$id));
		} else {
			$this -> error('内容参数不正确！',U('index'),3);	
		}
    }
	
	//栏目跳转
    public function redirectchannel(){  
		$id = $_GET['id'];
		if(is_numeric($id) and $id > 0){
			redirect(U('channel/index','id='.$id));
		} else {
			$this -> error('栏目参数不正确！',U('index'),3);	
		}
    }
	
	//自定义页跳转
    public function redirectdiypage(){  
		$id = $_GET['id'];
		if(is_numeric($id) and $id > 0){
			redirect(U('index/diypage','id='.$id));
		} else {
			$this -> error('自定义页参数不正确！',U('index'),3);	
		}
    }
	
	//自定义页功能
    public function diypage(){  
		$id = $_GET['id'];
		if(!is_numeric($id)){
			$this -> error('自定义页参数不正确！',U('index'),3);	
		}
		//读取自定义页面信息
		$qinfo = M('diypage') -> find($id); 
		if(is_null($qinfo)){
			$this -> error('该自定义页不存在或已被删除！',U('index'),3);
		} 
		creatediypage($id,0); 
		
    }  
	
	//前台插件功能
    public function plus(){  
		$name = I('get.name');
		//读取自定义页面信息
		$qinfo = M('plus') -> where('name="'.$name.'"') -> find();
		if(is_null($qinfo)){
			$this -> error('该插件不存在！',U('index'),3);
		} 
		//加载指定插件类
        $Plus_class = get_Plus_class($name);
        if(!class_exists($Plus_class)){
            $this->error('插件不存在',U('index'),3);
        }else{
            $Plus = new $Plus_class();
        }
		//执行插件方法  
		$Plus->Taginside(); 
    }  
	 
	 
	//功能测试
    public function test(){ 
 
    }
	
	//测试功能2
	public function test2(){  
 
	 
    }	
	
	//测试功能3
	public function test3(){  
 		 
    }
	

	
	//空操作
	public function _empty(){
		error_404();
    }
}