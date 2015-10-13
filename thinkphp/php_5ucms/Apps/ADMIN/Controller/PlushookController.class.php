<?php
//插件钩子管理
namespace ADMIN\Controller;
use Common\Controller\CommonController;
//继承公共控制器
class PlushookController extends CommonController { 
	//前置操作
	function _initialize(){ 
		//登陆状态及权限检测
		if(!checklogin()){
			//$this -> error('您尚未登陆后台 或 登陆已超时，请您重新登陆！',U('Login/index'),3);
		}
		if(!checklevel("plus")){
			//$this -> error('您没有此项功能的操作权限，请您联系技术人员或总管理员！',U('Index/index'),3);
		}
		
		//定义安装目录
		$installdir = C('installdir');
		if(empty($installdir)) { $installdir = '/';}
		$this-> assign('installdir',$installdir); 
	} 
    /*显示插件列表*/
    public function index(){
        //获取所有插件信息
		$qedit = D('plushook'); //实例化数据表;
        $Plushooks = $qedit  -> getAllPlushook();
		$extra_html = '说明 禁用钩子 将使得挂载在该钩子下的全部插件失效。禁用启用操作后，需更新缓存。<br>系统钩子禁止删除和修改，个人钩子可自定义。 <br> <br> 什么是钩子：钩子的作用是加载插件，在模板中使用钩子调用代码后，然后我们安装的插件是挂载到钩子上运行的。比如 Count插件是挂到内容页底部钩子ContentFooter上的，然后它才可以被执行。如果你在模板中没调用对应的钩子，则插件不会执行。<br>新增钩子的功能一般是给开发人员用的，详情见帮助手册中的插件开发部分。'; 
        //使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('插件钩子列表')  //设置页面标题
                ->addTopButton('resume') //添加启用按钮
                ->addTopButton('forbid') //添加禁用按钮
				->addTopButton('addnew') //添加新增按钮
				->addTableColumn('id', '编号') 
                ->addTableColumn('name', '标识') 
                ->addTableColumn('description', '描述')
                ->addTableColumn('pluss', '已挂载')
                ->addTableColumn('type', '类型')
                ->addTableColumn('state', '状态')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($Plushooks) //数据列表 
				->setExtraHtml($extra_html) 
                ->display(); 
    }

    //设置插件页面 
    public function config(){
        if(IS_POST){
            $id     = (int)I('id');
            $config = I('config');
            $flag = M('Plushook')->where("id={$id}")->save($config);
            if($flag !== false){
                $this->success('保存成功', U('index'));
            }else{
                $this->error('保存失败');
            }
        }else{
            $id     = (int)I('id');
            $Plushook  = M('Plushook')->find($id);
            if(!$Plushook) $this->error('插件钩子不存在'); 
            $this->meta_title = '设置钩子-'.$data->info['title'];  
			
            //构造表单名
            foreach($Plushook as $key => $val){
				switch($key){
					case 'name':
						$Plushook['config'][$key]['name'] = 'config['.$key.']';
						$Plushook['config'][$key]['type'] = 'text';
						$Plushook['config'][$key]['value'] = $val; 
						$Plushook['config'][$key]['title'] = '钩子名称';
						break;
					case 'description':
						$Plushook['config'][$key]['name'] = 'config['.$key.']';
						$Plushook['config'][$key]['type'] = 'text';
						$Plushook['config'][$key]['value'] = $val; 
						$Plushook['config'][$key]['title'] = '钩子描述';
						break;	
					/*		
					case 'pluss':
						$Plushook['config'][$key]['name'] = 'config['.$key.']';
						$Plushook['config'][$key]['type'] = 'text';
						$Plushook['config'][$key]['value'] = $val; 
						$Plushook['config'][$key]['title'] = '钩子挂载点 格式为 插件标识名称（多个以,号分割） （此处禁止修改 否则对应插件需重新安装了）';
						break;	
					*/			
				}
            }  
			
            $this->assign('data', $Plushook);
            $this->assign('form_items', $Plushook['config']); 
 
			//使用FormBuilder快速建立表单页面。
			$builder = new \Common\Builder\FormBuilder();
			$builder->setMetaTitle($this->meta_title)  //设置页面标题
					->setPostUrl(U('config')) //设置表单提交地址
					->addFormItem('id', 'hidden', 'ID', 'ID')
					->setExtraItems($Plushook['config']) //直接设置表单数据
					->setFormData($Plushook)
					->display(); 
        }
    }
	
	
    //设置插件页面 
    public function add(){
        if(IS_POST){ 
            $config = I('config'); 
			$qedit = D('plushook');
			$z = $qedit -> create($config,1);
			if($z){
            	$flag = $qedit -> add($z); 
				if($flag !== false){
					$this->success('添加成功', U('index'));
				}else{
					$this->error('添加失败');
				}
			}else{  
				$this->error($qedit->getError());
			}

        }else{
			$this->meta_title = '新增插件钩子';  
             
            //构造表单名
 			$Plushook = array();
			$Plushook['config']['name']['name'] = 'config[name]';
			$Plushook['config']['name']['type'] = 'text';
			$Plushook['config']['name']['value'] = ''; 
			$Plushook['config']['name']['title'] = '新增钩子名称（必须为英文和数字 不能带空格）';

			$Plushook['config']['description']['name'] = 'config[description]';
			$Plushook['config']['description']['type'] = 'text';
			$Plushook['config']['description']['value'] = ''; 
			$Plushook['config']['description']['title'] = '新增钩子描述（可以是中文，方便后台查看识别）';
	 
            $this->assign('data', $Plushook);
            $this->assign('form_items', $Plushook['config']); 
 
			//使用FormBuilder快速建立表单页面。
			$builder = new \Common\Builder\FormBuilder();
			$builder->setMetaTitle($this->meta_title)  //设置页面标题
					->setPostUrl(U('add')) //设置表单提交地址
					->addFormItem('id', 'hidden', 'ID', 'ID')
					->setExtraItems($Plushook['config']) //直接设置表单数据
					->setFormData($Plushook)
					->display(); 
        }
    }

	 
}
