<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/8/10
 * Time: 14:33
 */

namespace Admin\Controller;


class OrgController extends AdminController{

    public function index(){
        $keyWord = I('key_word');
        $p = I('p');
        if(empty($p)) $p = 1;
        $status = I('status');
        $orgDao = D('Organization/Organization');
        $orgList = $orgDao->getOrgList($keyWord, $p, 12, empty($status) ? null : 0);
        $this->assign('_orgList',$orgList);
        $this->assign('_html',$orgDao->getHtml());
        $this->display();
    }

}