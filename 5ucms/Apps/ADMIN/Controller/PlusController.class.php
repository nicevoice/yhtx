<?php
//插件管理
namespace ADMIN\Controller;
use Common\Controller\CommonController;
//继承公共控制器
class PlusController extends CommonController { 
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
		$qedit = D('plus'); //实例化数据表;
		//$pnum = 3; //每页显示条数 ->page(!empty($_GET["p"])?$_GET["p"]:1,$pnum) 
        $Pluss = $qedit -> getAllPlus();
		//$page = new \Common\Util\Page($qedit->count(),$pnum);
		
        //使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('插件列表')  //设置页面标题
                ->addTopButton('resume') //添加启用按钮
                ->addTopButton('forbid') //添加禁用按钮
                ->addTableColumn('name', '标识')
                ->addTableColumn('title', '名称')
                ->addTableColumn('description', '描述')
                ->addTableColumn('state', '状态')
                ->addTableColumn('author', '作者')
                ->addTableColumn('version', '版本')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($Pluss) //数据列表 
				//->setTableDataPage($page->show()) //数据列表分页 
				->display();
    }

    //设置插件页面 
    public function config(){
        if(IS_POST){
            $id     = (int)I('id');
            $config = I('config');
            $flag = M('Plus')->where("id={$id}")->setField('config', json_encode($config));
            if($flag !== false){
                $this->success('保存成功', U('index'));
            }else{
                $this->error('保存失败');
            }
        }else{
            $id     = (int)I('id');
            $Plus  = M('Plus')->find($id);
            if(!$Plus)
                $this->error('插件未安装');
            $Plus_class = get_Plus_class($Plus['name']);
            if(!class_exists($Plus_class))
                trace("插件{$Plus['name']}无法实例化,",'PlusS','ERR');
            $data = new $Plus_class;
            $Plus['Plus_path'] = $data->Plus_path;
            $Plus['custom_config'] = $data->custom_config;
            $this->meta_title = '设置插件-'.$data->info['title'];
            $db_config = $Plus['config'];
            $Plus['config'] = include $data->config_file;
            if($db_config){
                $db_config = json_decode($db_config, true);
                foreach ($Plus['config'] as $key => $value) {
                    if($value['type'] != 'group'){
                        $Plus['config'][$key]['value'] = $db_config[$key];
                    }else{
                        foreach ($value['options'] as $gourp => $options) {
                            foreach ($options['options'] as $gkey => $value) {
                                $Plus['config'][$key]['options'][$gourp]['options'][$gkey]['value'] = $db_config[$gkey];
                            }
                        }
                    }
                }
            }
            //构造表单名
            foreach($Plus['config'] as $key => $val){
                if($val['type'] == 'group'){
                    foreach($val['options'] as $key2 => $val2){
                        foreach($val2['options'] as $key3 => $val3){
                            $Plus['config'][$key]['options'][$key2]['options'][$key3]['name'] = 'config['.$key3.']';
                        }
                    }
                }else{
                    $Plus['config'][$key]['name'] = 'config['.$key.']';
                }
            }
            $this->assign('data', $Plus);
            $this->assign('form_items', $Plus['config']);
			//构建上传图片路径，插件的图只给传插件目录里，后台不作记录
			$Plus_uploadpicpath = PLUS_PATH . $data->info['name'] .'/uploadfile/';
			$Plus_uploadpicpath = str_replace('./','/',$Plus_uploadpicpath);
			$this->assign('Plus_uploadpicpath', $Plus_uploadpicpath);  
			//输出页面
            if($Plus['custom_config']){
                $this->assign('custom_config', $this->fetch($Plus['Plus_path'].$Plus['custom_config']));
                $this->display();
            }else{
                //使用FormBuilder快速建立表单页面。
                $builder = new \Common\Builder\FormBuilder();
                $builder->setMetaTitle($this->meta_title)  //设置页面标题
                        ->setPostUrl(U('config')) //设置表单提交地址
                        ->addFormItem('id', 'hidden', 'ID', 'ID')
                        ->setExtraItems($Plus['config']) //直接设置表单数据
                        ->setFormData($Plus)
                        ->display();
						 
            } 
			
        }
    }

    //安装插件 
    public function install(){
        $Plus_name = trim(I('Plus_name'));
        $class = get_Plus_class($Plus_name);
        if(!class_exists($class))
            $this->error('插件不存在');
        $Pluss  = new $class;
        $info = $Pluss->info; 
        if(!$info || !$Pluss->checkInfo())//检测信息的正确性
            $this->error('插件信息缺失');
        session('Pluss_install_error',null);
        $install_flag = $Pluss->install();
        if(!$install_flag){
            $this->error('执行插件预安装操作失败'.session('Pluss_install_error'));
        } 
        //安装数据库 如 ./plus/email/sql/xxx.sql 
        $sql_file = realpath(PLUS_PATH.$Plus_name).'/Sql/install.sql';
        if(file_exists($sql_file)){
            $sql_state = execute_sql_from_file($sql_file);
            if(!$sql_state){
                $this->error('执行插件SQL安装语句失败'.session('Pluss_install_error'));
            }
			echo '安装插件数据库成功！';
        } 
		//更新插件列表信息
        $Plus_object = D('plus');  
        $data = $Plus_object->create($info); //通过模型过滤数据 
		//判断是否有后台列表
        if(is_array($Pluss->admin_list) && $Pluss->admin_list !== array()){
            $data['adminlist'] = 1;
        }else{
            $data['adminlist'] = 0;
        }
        if(!$data) $this->error($Plus_object->getError()); //如果数据不支持 
        if($Plus_object->add($data)){ //增加配置信息
            $config = array('config'=>json_encode($Pluss->getConfig())); 
            $Plus_object->where("name='{$Plus_name}'")->save($config);
            $hooks_update = D('plushook')->updateHooks($Plus_name);
            if($hooks_update){
                S('hooks', null);
                $this->success('安装成功');
            }else{
                $Plus_object->where("name='{$Plus_name}'")->delete();
                $this->error('更新钩子处插件失败,请卸载后尝试重新安装');
            }
        }else{
            $this->error('写入插件数据失败');
        }
    }

    //卸载插件
    public function uninstall(){
        $Plus_object = M('Plus');
        $id = trim(I('id'));
        $db_Pluss = $Plus_object->find($id);
        $class = get_Plus_class($db_Pluss['name']);
        $this->assign('jumpUrl',U('index'));
        if(!$db_Pluss || !class_exists($class))
            $this->error('插件不存在');
        session('Pluss_uninstall_error',null);
        $Pluss = new $class;
        $uninstall_flag = $Pluss->uninstall();
        if(!$uninstall_flag)
            $this->error('执行插件预卸载操作失败'.session('Pluss_uninstall_error'));
        $hooks_update = D('plushook')->removeHooks($db_Pluss['name']);
        if($hooks_update === false){
            $this->error('卸载插件所挂载的钩子数据失败');
        }
        S('hooks', null);
        $delete = $Plus_object->where("name='{$db_Pluss['name']}'")->delete();

        //卸载数据库
        $sql_file = realpath(PLUS_PATH.$db_Pluss['name']).'/Sql/uninstall.sql';
        if(file_exists($sql_file)){
            $sql_state = execute_sql_from_file($sql_file);
            if(!$sql_state){
                $this->error('执行插件SQL卸载语句失败'.session('Pluss_uninstall_error'));
            }
        }

        if($delete === false){
            $this->error('卸载插件失败');
        }else{
            $this->success('卸载成功');
        }
    }

    //外部执行插件方法
    public function execute($_Pluss = null, $_controller = null, $_action = null){
        if(C('URL_CASE_INSENSITIVE')){
            $_Pluss     = ucfirst(parse_name($_Pluss, 1));
            $_controller = parse_name($_controller,1);
        }

        $TMPL_PARSE_STRING = C('TMPL_PARSE_STRING');
        $TMPL_PARSE_STRING['__PlusROOT__'] = __ROOT__ . "/Pluss/{$_Pluss}";
        C('TMPL_PARSE_STRING', $TMPL_PARSE_STRING);

        if(!empty($_Pluss) && !empty($_controller) && !empty($_action)){
            $Pluss = A("plus://{$_Pluss}/{$_controller}")->$_action();
        } else {
            $this->error('没有指定插件名称，控制器或操作！');
        }
    }

    //插件后台显示页面
    public function adminList($name, $tab = 1){
        //获取插件实例
        $Plus_class = get_Plus_class($name);
        if(!class_exists($Plus_class)){
            $this->error('插件不存在');
        }else{
            $Plus = new $Plus_class();
        }

        //自定义插件后台页面
        if($Plus->custom_adminlist){
            $this->assign('custom_adminlist', $this->fetch($Plus->custom_adminlist));
            $this->display($Plus->custom_adminlist);
        }else{

            //获取插件的$admin_list配置
            $admin_list = $Plus->admin_list;
            $tab_list = array();
            foreach($admin_list as $key => $val){
                $tab_list[$key]['title'] = $val['title'];
                $tab_list[$key]['href']  = U('plus/adminList/name/'.$name.'/tab/'.$key);
            }
            $admin = $admin_list[$tab];//读取插件后台功能数组
            $param = D('plus://'.$name.'/'.$admin['model'].'')->adminList;
            if($param){
                //搜索
                $keyword   = (string)I('keyword');
                $condition = array('like','%'.$keyword.'%');
                $map['id|'.$param['search_key']] = array($condition, $condition,'_multi'=>true);

                //获取数据列表
                $data_list = M($param['model'])->page(!empty($_GET["p"])?$_GET["p"]:1, 10) ->where($map)->field(true)->order($param['order'])->select();
                $page = new \Common\Util\Page(M($param['model'])->where($map)->count(), 10);
				
				$qmenu = 0;
				if(!empty($data_list[0]['state'])){
					$qmenu = 1; //存在state字段才显示启用禁用按钮	
				}
				//如果是常见时间字段 则转换显示
				foreach($data_list as $key=>$val){
					if(!empty($data_list[$key]['modifytime'])){$data_list[$key]['modifytime'] = date('Y-m-d H:i:s',$data_list[$key]['modifytime']);}
					if(!empty($data_list[$key]['createtime'])){$data_list[$key]['createtime'] = date('Y-m-d H:i:s',$data_list[$key]['createtime']);}
					if(!empty($data_list[$key]['time']))      {$data_list[$key]['time']       = date('Y-m-d H:i:s',$data_list[$key]['time']);}
				} 

                //使用Builder快速建立列表页面。
                $builder = new \Common\Builder\ListBuilder(); 
				if($qmenu==1){
					$builder->setMetaTitle($Plus->info['title']) //设置页面标题
							->AddTopButton('addnew', array('href'  => U('plus/adminAdd/name/'.$name.'/tab/'.$tab))) //添加新增按钮
							->AddTopButton('resume', array('model' => $param['model'])) //添加启用按钮
							->AddTopButton('forbid', array('model' => $param['model'])) //添加禁用按钮
							->AddTopButton('delete', array('model' => $param['model'])) //添加删除按钮
							->setSearch('请输入关键字', U('plus/adminList/name/'.$name, array('tab' => $tab)))
							->SetTabNav($tab_list, $tab) //设置Tab按钮列表
							->setTableDataList($data_list) //数据列表
							->setTableDataPage($page->show()); //数据列表分页
				} else {
					$builder->setMetaTitle($Plus->info['title']) //设置页面标题
							->AddTopButton('addnew', array('href'  => U('plus/adminAdd/name/'.$name.'/tab/'.$tab))) //添加新增按钮
							->AddTopButton('delete', array('model' => $param['model'])) //添加删除按钮
							->setSearch('请输入关键字', U('plus/adminList/name/'.$name, array('tab' => $tab))) 
							->SetTabNav($tab_list, $tab) //设置Tab按钮列表
							->setTableDataList($data_list) //数据列表
							->setTableDataPage($page->show()); //数据列表分页
				}
                //根据插件的list_grid设置后台列表字段信息
                foreach($param['list_grid'] as $key => $val){
                    $builder->addTableColumn($key, $val['title'], $val['type']);
                }

                //根据插件的right_button设置后台列表右侧按钮
                foreach($param['right_button'] as $key => $val){
                    $builder->addRightButton('self', $val);
                }

                //定义编辑按钮
                $attr = array();
                $attr['title'] = '编辑';
                $attr['class'] = 'label label-info';
                $attr['href']  = U('plus/adminEdit', array('name' => $name, 'tab' => $tab, 'id' => '__data_id__'));

                //显示列表
				if($qmenu==1){
					$builder->addTableColumn('right_button', '操作', 'btn')
							->addRightButton('self', $attr) //添加编辑按钮
							->addRightButton('forbid', array('model' => $param['model'])) //添加禁用/启用按钮
							->addRightButton('delete', array('model' => $param['model'])) //添加删除按钮
							->display();
				} else {
					$builder->addTableColumn('right_button', '操作', 'btn')
							->addRightButton('self', $attr) //添加编辑按钮
							->addRightButton('delete', array('model' => $param['model'])) //添加删除按钮
							->display();	
				}
            }else{
                $this->error('插件列表信息不正确');
            }
        }
    }

    //插件后台数据增加
     public function adminAdd($name, $tab){
        //获取插件实例
		$name = I('get.name');
        $Plus_class = get_Plus_class($name);
        if(!class_exists($Plus_class)){
            $this->error('插件'.$name.'不存在呐！');
        }else{
            $Plus = new $Plus_class();
        }

        //获取插件的$admin_list配置
        $admin_list = $Plus->admin_list;
        $admin = $admin_list[$tab];
        $Plus_model_object = D('plus://'.$name.'/'.$admin['model']);
        $param = $Plus_model_object->adminList;
        if($param){
            if(IS_POST){
                $data = $Plus_model_object->create();
                if($data){
                    $result = $Plus_model_object->add($data);
                }else{
                    $this->error($Plus_model_object->getError());
                }
                if($result){
                    $this->success('新增成功', U('plus/adminlist/name/'.$name.'/tab/'.$tab));
                }else{
                    $this->error('更新错误');
                }
            }else{
                //使用FormBuilder快速建立表单页面。
                $builder = new \Common\Builder\FormBuilder();
                $builder->setMetaTitle('新增数据')  //设置页面标题
                        ->setPostUrl(U('plus/adminAdd/name/'.$name.'/tab/'.$tab)) //设置表单提交地址
                        ->setExtraItems($param['field'])
                        ->display();
            }
        }else{
            $this->error('插件列表信息不正确');
        }
     }

    //插件后台数据编辑 
     public function adminEdit($name, $tab, $id){
        //获取插件实例
		$name = I('get.name');
        $Plus_class = get_Plus_class($name);
        if(!class_exists($Plus_class)){
            $this->error('插件不存在');
        }else{
            $Plus = new $Plus_class();
        }

        //获取插件的$admin_list配置
        $admin_list = $Plus->admin_list;
        $admin = $admin_list[$tab];
        $Plus_model_object = D('plus://'.$name.'/'.$admin['model']);
        $param = $Plus_model_object->adminList;
        if($param){
            if(IS_POST){
                $data = $Plus_model_object->create();
                if($data){
                    $result = $Plus_model_object->save($data);
                }else{
                    $this->error($Plus_model_object->getError());
                }
                if($result){
                    $this->success('更新成功', U('plus/adminlist/name/'.$name.'/tab/'.$tab));
                }else{
                    $this->error('更新错误');
                }
            }else{
                //使用FormBuilder快速建立表单页面。
                $builder = new \Common\Builder\FormBuilder();
                $builder->setMetaTitle('编辑数据')  //设置页面标题
                        ->setPostUrl(U('plus/adminedit/name/'.$name.'/tab/'.$tab)) //设置表单提交地址
                        ->addFormItem('id', 'hidden', 'ID', 'ID')
                        ->setExtraItems($param['field'])
                        ->setFormData(M($param['model'])->find($id))
                        ->display();
            }
        }else{
            $this->error('插件列表信息不正确');
        }
     }
	 
	
	 
}
