<?php
namespace ADMIN\Controller;
use Think\Controller;
//空控制器，用于提示错误150703
class EmptyController extends Controller {
	public function _empty(){
		error_404();
    }
}