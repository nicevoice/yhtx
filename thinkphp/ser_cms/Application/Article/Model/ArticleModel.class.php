<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/7/29
 * Time: 16:04
 */

namespace Article\Model;


use Common\Model\BaseModel;
use Think\Page;

class ArticleModel extends BaseModel{

    const ARTICLE_TITLE_LENGTH_ERROR = '文章标题长度应为1-50位之间';
    const ARTICLE_DES_LENGTH_ERROR = '文章描述长度应为1-200位之间';
    const CATEGORY_ID_ERROR = '您不可以发布该分类的文章';
    const SRC_LINK_LENGTH_ERROR = '原文链接长度应为1-255之间';
    const SRC_USER_ID_ERROR = '原创者id不正确';
    const ARTICLE_DETAIL_ERROR = '对不起，该文章不可查看';
    const ARTICLE_EDIT_ERROR = '文章编辑失败，请稍后再试';
    /**
     * 添加文章
     * @param String $title 标题
     * @param String $des 描述
     * @param String $content 内容
     * @param Integer $categoryId 分类id
     * @param String $pictureUrl 封面url
     * @param String $srcLink 来源地址
     * @param Integer $srcUserId 原创者user_id
     * @param Json $expandJson 拓展内容
     * @return Array|Boolean
    */
    public function addArticle($title,$des,$content,$categoryId,$pictureUrl = null,$srcLink = null,$srcUserId = null,$expandJson = null){
        if(!check_var_length($title,1,50)){
            $this->setErrorMsg(self::ARTICLE_TITLE_LENGTH_ERROR);
            return false;
        }
        $data['title'] = $title;
        if(!check_var_length($des,1,200)){
            $this->setErrorMsg(self::ARTICLE_DES_LENGTH_ERROR);
            return false;
        }
        $data['des'] = $des;
        $data['content'] = $content;
        //检查分类有效性
        if(!D('Article/Category')->checkCategory($categoryId)){
            $this->setErrorMsg(self::CATEGORY_ID_ERROR);
            return false;
        }
        $data['category_id'] = $categoryId;
        $data['picture_url'] = $pictureUrl;
        if(!empty($srcLink) && !check_var_length($srcLink,1,255)){
            $this->setErrorMsg(self::SRC_LINK_LENGTH_ERROR);
            return false;
        }
        $data['src_link'] = $srcLink;
        if(!empty($srcUserId)){
            if(!D('User/User')->checkUser($srcUserId)){
                $this->setErrorMsg(self::SRC_USER_ID_ERROR);
                return false;
            }
        }
        $data['src_user_id'] = $srcUserId;
        $data['expand_json'] = $expandJson;
        $data['create_time'] = time();
        $data['user_id'] = user_id();
        $articleId = $this->add($data);
        if(empty($articleId)){
            return false;
        }
        return $this->find($articleId);
    }
    /**
     * 查看文章
     * @param Integer $articleId
     * @return Boolean|Array
    */
    public function detail($articleId){
        $article = $this->find($articleId);
        if(empty($article)){
            $this->setErrorMsg(self::ARTICLE_DETAIL_ERROR);
            return false;
        }
        if($article['status'] == 0 && !is_admin()){//TODO 未审核文章，发布者可访问……
            if($article['user_id'] <> user_id()){
                $this->setErrorMsg(self::ARTICLE_DETAIL_ERROR);
                return false;
            }
        }
        //添加阅读数
        D('Article/ReadLog')->addLog(user_id(),$articleId);
        $this->where("article_id = {$articleId}")->setInc('read_count');
        return $article;
    }
    /**
     * 检查文章有效性
     * @param Integer $articleId 文章id
     * @return Boolean
    */
    public function checkArticle($articleId){
        $article = $this->find($articleId);
        return empty($article) ? false : true;
    }
    /**
     * 列表
     * @param Integer $categoryId 分类id
     * @param String $keyWord 关键字
     * @param Integer $pageNo 页码
     * @param Integer $count 查询数量
     * @param Integer $status 状态 99-全部
    */
    public function getArticleList($categoryId = null,$keyWord = null,$pageNo = 1,$count = 10,$status = null){
        $where = '1 = 1';
        if($status === null){
            $where .= " AND status > 0 ";
        }else{
            $status == 99 ? $where .= " AND status <> -1 " : $where .= " AND status = {$status} ";
        }
        if(!empty($categoryId)){
            $where .= " AND category_id = {$categoryId} ";
        }
        if(!empty($keyWord)){
            $where .= " AND (title LIKE '%{$keyWord}%' OR des LIKE '%{$keyWord}%') ";
        }
        //分页
        $page = new Page($this->where($where)->count(),$count);
        $this->html = $page->show();
        return $this->where($where)->order('create_time DESC')->limit(($pageNo-1)*$count,$count)->select();
    }
    /**
     * 列表(我的)
     * @param String $keyWord 关键字
     * @param Integer $pageNo 页码
     * @param Integer $count 查询数量
     */
    public function getMyArticleList($keyWord = null,$pageNo = 1,$count = 10){
        $where = "status > 0 AND user_id =" . user_id();
        if(!empty($keyWord)){
            $where .= " AND (title LIKE '%{$keyWord}%' OR des LIKE '%{$keyWord}%') ";
        }
        //分页
        $page = new Page($this->where($where)->count(),$count);
        $this->html = $page->show();
        return $this->where($where)->order('create_time DESC')->limit(($pageNo-1)*$count,$count)->select();
    }
    /**
     * 编辑文章
     * @param Integer $article_id 文章id
     * @param Array $data title des content category_id picture_url src_link src_user_id expand_json
     * @return Array|Boolean
    */
    public function edit($article_id,$data){
        array_filter($data);
        if(!empty($data['title']) && !check_var_length($data['title'],1,50)){
            $this->setErrorMsg(self::ARTICLE_TITLE_LENGTH_ERROR);
            return false;
        }
        if(!empty($data['des']) && !check_var_length($data['des'],1,200)){
            $this->setErrorMsg(self::ARTICLE_DES_LENGTH_ERROR);
            return false;
        }
        if(!empty($data['src_link']) && !check_var_length($data['src_link'],1,255)){
            $this->setErrorMsg(self::SRC_LINK_LENGTH_ERROR);
            return false;
        }
        if(!empty($data['src_user_id'])){
            if(!D('User/User')->checkUser($data['src_user_id'])){
                $this->setErrorMsg(self::SRC_USER_ID_ERROR);
                return false;
            }
        }
        if(!empty($data['category_id'])){
            //检查分类有效性
            if(!D('Article/Category')->checkCategory($data['category_id'])){
                $this->setErrorMsg(self::CATEGORY_ID_ERROR);
                return false;
            }
        }
        $data['status'] = 0;
        $data['push_time'] = null;
        $data['create_time'] = time();
        $res = $this->where("article_id = {$article_id}")->save($data);
        if($res === false){
            $this->setErrorMsg(self::ARTICLE_EDIT_ERROR);
            return false;
        }
        return $this->find($article_id);
    }
    /**
     * 设置内容状态 默认通过审核
     * @param Integer $articleId 文章id
     * @param Integer $status 默认1-通过审核
     * @return Boolean
    */
    public function setStatus($articleId,$status = 1){
        if(!is_admin()){
            return false;
        }
        $where['article_id'] = $articleId;
        $res = $this->where($where)->setField('status',$status);
        return $res !== false;
    }
    /**
     * 删除
     * @param Integer $articleId 文章id
     * @return Boolean
    */
    public function deleteArticle($articleId){
        return $this->setStatus($articleId,-1);
    }
    /**
     * 根据分类获取全部文章 TODO:需要加缓存
     * @param Integer $categoryId 分类id
     * @return Array
    */
    public function getArticleListByCategoryId($categoryId){
        $where['status'] = array('EGT',0);
        $where['category_id'] = $categoryId;
        return $this->where($where)->select();
    }
}