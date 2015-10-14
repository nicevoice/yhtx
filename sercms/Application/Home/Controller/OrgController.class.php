<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/8/13
 * Time: 14:11
 */

namespace Home\Controller;


class OrgController extends BaseController{

    const ADD_ORG_SUCCESS = '申请机构加入成功，请等待审核结果';

    protected $mustLoginRequestUriArray = array(
        'Home/Org/join',
    );
    //加入服务商
    public function join(){
        $organizationDao = D('Organization/Organization');
        $orgInfo = $organizationDao->checkAdd();
        if(!empty($orgInfo)){
            $this->assign('_orgInfo',$orgInfo);
        }
        $this->display();
    }
    //加入表单
    public function add(){
        $this->display();
    }
    public function doAdd(){
        $name = I('name');
        $main_http_url = I('main_http_url');
        $sh_file = I('sh_file');
        $des = I('des');
        if(empty($name) || empty($sh_file) || empty($des) ){
            $this->error(self::MUSE_SET_ERROR);
        }
        $organizationDao = D('Organization/Organization');
        $org_id = $organizationDao->addOrg($name,$des,$main_http_url,$this->getUrlByName($sh_file));
        if(empty($org_id)){
            $this->error($organizationDao->getErrorMsg());
        }
        $this->success(self::ADD_ORG_SUCCESS,U('Home/Index/index'));
    }
}