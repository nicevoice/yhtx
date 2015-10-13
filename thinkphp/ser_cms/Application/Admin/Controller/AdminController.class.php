<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/8/6
 * Time: 10:43
 */

namespace Admin\Controller;


use Think\Controller;

class AdminController extends Controller{

    public function _initialize(){
        if(!is_admin()){
            $this->error('对不起，您无权访问',U('Home/Index/index'));
        }
        $this->setMenu();
    }

    protected function setMenu(){
        $categoryDao = D('Article/Category');
        $categoryList = $categoryDao->getCategoryList(5);
        $this->assign('_categoryList',$categoryList);
    }
}