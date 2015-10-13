<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/8/5
 * Time: 10:55
 */

namespace Weixin\Controller;


class IndexController extends BaseController{

    const OTHER_TYPE_MSG = '/:--b抱歉~该类型消息还在开发中~敬请期待~/:B-)';
    const WELCOME_MSG = "感谢的关注“水滴”/:,@-D，如有建议可直接发送消息，我们会尽快完善。\r\n回复 @+关键字 如：“@中街” 即可查询到相关信息\r\n回复 七夕 有惊喜哦~ ";
    const IMAGE_REPLY_MSG = '您发的我已收到~我们会尽快处理/:,@f';
    const TEXT_REPLY_MSG = '感谢您的反馈~“水滴”会继续完善的~/::*';
    const ARTICLE_IS_NULL = '抱歉~还没有您需要内容/::P';
    const QIXI_ACTIVE = '点击参与七夕节“敢爱？水滴帮你喊出来”活动吧';

    public function index(){
        //服务器配置验证
//        $this->valid($weiObj);
        $type = $this->weiObj->getRev()->getRevType();
        //消息记录
        D('Weixin/Log')->addLog($this->weiObj->getRev()->getRevData());
        switch ($type) {
            case \Wechat::MSGTYPE_TEXT:
                $this->make_text();
                break;
            case \Wechat::MSGTYPE_EVENT:
                $this->make_event();
                break;
            case \Wechat::MSGTYPE_IMAGE:
                $this->make_image();
                break;
            default:
                $this->weiObj->text(self::OTHER_TYPE_MSG)->reply();
        }
    }

    //验证
    private function valid(){
        $this->weiObj->valid();
    }
    //文本消息处理
    private function make_text(){
        $content = $this->weiObj->getRev()->getRevContent();
        $from = $this->weiObj->getRev()->getRevFrom();
        //TODO 特别回复
        if($content == '七夕'){
            $qxUrl = 'http://' . $_SERVER['HTTP_HOST'] . U('Weixin/Qixi/set',array('token'=>$from));
            $this->weiObj->text("<a href='{$qxUrl}' >" . self::QIXI_ACTIVE . "</a>")->reply();
            exit;
        }
        $first = mb_substr($content,0,1,'UTF-8');
        switch($first){
            case '@':
                //查看文章并返回
                $this->searchArticle(str_replace('@','',$content));
                break;
        }
        $this->weiObj->text(self::TEXT_REPLY_MSG)->reply();
    }
    //事件消息处理
    private function make_event(){
        $eventArray = $this->weiObj->getRev()->getRevEvent();
        switch (strtolower($eventArray['event'])){
            case 'subscribe'://关注
                $this->weiObj->text(self::WELCOME_MSG)->reply();
                break;
            case 'unsubscribe'://取消关注
                break;
            default:
                $this->weiObj->text(self::OTHER_TYPE_MSG)->reply();
        }
    }
    //图片消息处理
    private function make_image(){
        $this->weiObj->text(self::IMAGE_REPLY_MSG)->reply();
    }
    //查询相关文件
    private function searchArticle($keyWord){
        $articleList = D('Article/Article')->getArticleList(null,$keyWord);
        if(empty($articleList)){
            $this->weiObj->text(self::ARTICLE_IS_NULL)->reply();
            exit;
        }
        //生成wechat格式
        $news = array();
        foreach($articleList as $key => $value){
            $news[] = array(
                'Title'=>$value['title'],
                'Description'=>$value['des'],
                'PicUrl'=>$value['picture_url'],
                'Url'=>'http://' . $_SERVER['HTTP_HOST'] . U('Home/Article/detail',array('article_id'=>$value['article_id'])),
            );
        }
        $this->weiObj->news($news)->reply();
    }
}