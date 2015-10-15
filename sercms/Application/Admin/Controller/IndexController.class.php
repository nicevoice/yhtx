<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/8/6
 * Time: 10:42
 */

namespace Admin\Controller;


use Article\Model\ArticleModel;
use User\Model\UserModel;
use Organization\Model\OrganizationModel;

class IndexController extends AdminController{

    public function index(){
        //$articleDao = D('Article/Article');
        $articleDao = new ArticleModel();
        $userDao = new UserModel();
        $orgDao = new OrganizationModel();
        //文章数
        $this->assign('_articleCount',$articleDao->where('status <> -1')->count());
        //用户数
        $this->assign('_userCount',$userDao->where('status <> -1')->count());
        //机构数
        $this->assign('_orgCount',$orgDao->where('status <> -1')->count());
        //社工数
        $this->assign('_shegongCount',$userDao->where('status = 2')->count());
        $this->display();
    }
}