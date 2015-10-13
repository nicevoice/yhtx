<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/7/29
 * Time: 9:37
 */

namespace Common\Model;


use Think\Model;

class BaseModel extends Model{

    protected $error = '系统繁忙，请稍后再试';
    const NOT_ADMIN = '对不起，您无权这么做';
    //分页html
    protected $html = '';

    protected function setErrorMsg($msg){
        $this->error = $msg;
    }

    public function getErrorMsg(){
        return $this->error;
    }

    public function getHtml(){
        return $this->html;
    }
}