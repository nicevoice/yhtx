<?php

namespace Install\Controller;
use Think\Controller;
use Think\Storage;

class IndexController extends Controller{
	//安装首页
	public function index(){
		if(Storage::has(MODULE_PATH . 'Data/install.lock')){ 
			$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>已经成功安装了5UCMS PHP</b>！</p><br/>[请不要重复安装!如需重复安装，请删除 /app/install/data/install.lock 文件。<br><a href="/admin.php">点我进入后台</a> <a href="/index.php">点我返回首页</a> <br>www.5ucms.com QQ3876307 邱嵩松 ]</div>','utf-8');
			die;
		}else{
			$this->display();
		}
	}

	//安装完成
	public function complete(){
		$step = session('step');

		if(!$step){
			$this->redirect('index');
		} elseif($step != 3) {
			$this->redirect("Install/step{$step}");
		}

		Storage::put(MODULE_PATH . 'Data/install.lock', 'lock');
		//创建配置文件
		$this->assign('info',session('config_file'));

		session('step', null);
		session('error', null);
		$this->display(); 
		  
	}
}
