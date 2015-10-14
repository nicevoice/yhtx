<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/8/20
 * Time: 10:39
 */

namespace Weixin\Controller;


use Think\Controller;

class QixiController extends Controller{

    public function set(){
        $qid = $_SESSION['QIXI_ID'];
        if(!empty($qid)){
            redirect(U('Weixin/Qixi/index',array('qid'=>$qid)));
        }
        $_SESSION['QIXI_TOKEN'] = I('token');
        $this->display('set');
    }

    public function doAdd(){
        $data['boy'] = I('boy');
        $data['girl'] = I('girl');
        $data['content'] = I('content');
        $data['create_openid'] = $_SESSION['QIXI_TOKEN'];
        $data['create_time'] = time();
        $qid = M('qixi')->add($data);
        $_SESSION['QIXI_ID'] = $qid;
        if(empty($qid)){
            $this->error('系统忙，请稍后');
        }else{
            $this->success('快去分享给她/他',U('Weixin/Qixi/index',array('qid'=>$qid)));
        }
    }

    public function index(){
        $qid = I('qid');
        $qixi = M('qixi')->where("q_id = {$qid}")->find();
        $this->assign('_qixi',$qixi);
        $this->display();
    }

    public function zan(){
        $qid = I('qid');
        M('qixi')->where("q_id = {$qid}")->setInc('zan',1);
        redirect(U('Weixin/Qixi/index',array('qid'=>$qid)));
    }

    public function setOk(){
        $qid = I('qid');
        M('qixi')->where("q_id = {$qid}")->setInc('ok',1);
        M('qixi')->where("q_id = {$qid}")->setInc('ok_time',time());
        redirect(U('Weixin/Qixi/index',array('qid'=>$qid)));
    }
}