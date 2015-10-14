<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/8/10
 * Time: 16:58
 */

namespace Organization\Model;


use Common\Model\BaseModel;
use Think\Page;

class OrganizationModel extends BaseModel{

    const ORG_NAME_LENGTH_ERROR = '机构名称应为1-20位之间';
    const ORG_DES_LENGTH_ERROR = '机构描述应为1-255位之间';
    const HAS_ORG_ERROR = '您已提交机构申请，请勿重复提交';

    protected $tableName = 'org';

    public function getOrgList($keyWord = null,$pageNo = 1,$count = 10,$status = null){
        $where['name'] = array('LIKE',"%{$keyWord}%");
        if(!$status === 99){
            $where['status'] = empty($status) ? array('EGT',0) : $status;
        }
        //分页
        $page = new Page($this->where($where)->count(),$count);
        $this->html = $page->show();
        return $this->where($where)->order('create_time DESC')->limit(($pageNo-1)*$count,$count)->select();
    }
    /**
     * 添加机构
     * @param String $name 机构名称
     * @param String $des 机构描述
     * @param String $main_http_url 机构主站地址
     * @param String $sh_file_url 机构审核文件地址
     * @return Boolean
    */
    public function addOrg($name,$des,$main_http_url= null,$sh_file_url){
        if($this->checkAdd()){
            $this->setErrorMsg(self::HAS_ORG_ERROR);
            return false;
        }
        if(!check_var_length($name,1,20)){
            $this->setErrorMsg(self::ORG_NAME_LENGTH_ERROR);
            return false;
        }
        $data['name'] = $name;
        if(!check_var_length($des,1,255)){
            $this->setErrorMsg(self::ORG_NAME_LENGTH_ERROR);
            return false;
        }
        $data['des'] = $des;
        $data['main_http_url'] = $main_http_url;
        $data['sh_file_url'] = $sh_file_url;
        $data['create_user_id'] = user_id();
        $data['create_time'] = time();
        $data['type'] = 1;
        $data['status'] = 0;
        $org_id = $this->add($data);
        return !empty($org_id);
    }
    /**
     * 检查是够已经申请
     * @return Null|Array
    */
    public function checkAdd(){
        $where['create_user_id'] = user_id();
        $orgInfo = $this->where($where)->find();
        return $orgInfo;
    }
}