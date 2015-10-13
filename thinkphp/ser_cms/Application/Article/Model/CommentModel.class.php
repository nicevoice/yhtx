<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/7/30
 * Time: 10:48
 */

namespace Article\Model;


use Common\Model\BaseModel;

class CommentModel extends BaseModel{

    protected $tableName = 'article_comment';

    const ARTICLE_ERROR = '评论失败';
    const COMMENT_LENGTH_ERROR = '评论内容长度应为1-255位之间';

    /**
     * 评论文章
     * @param Integer $articleId
     * @param String $content 内容
     * @return Boolean|Array
    */
    public function addComment($articleId,$content){
        $data['article_id'] = $articleId;
        if(!check_var_length($content,1,255)){
            $this->setErrorMsg(self::COMMENT_LENGTH_ERROR);
            return false;
        }
        $data['content'] = $content;
        //判断评价间隔时间
        $comment = $this->where($data)->order('create_time DESC')->find();
        $commentInterval = C('COMMENT_INTERVAL');
        if(!empty($comment)){//有过历史评论
            if(time() - $comment['create_time'] < $commentInterval){
                $this->setErrorMsg("评论间隔时间为{$commentInterval}秒");
                return false;
            }
        }
        //判断文章有效性
        if(!D('Article/Article')->checkArticle($articleId)){
            $this->setErrorMsg(self::ARTICLE_ERROR);
            return false;
        }
        $data['user_id'] = user_id();
        $data['create_time'] = time();
        $comment_id = $this->add($data);
        if(empty($comment_id)){
            $this->setErrorMsg($this->error);
            return false;
        }
        return $this->find($comment_id);
    }
    /**
     * 获取文章评论列表
     * @param Integer $articleId
     * @param Integer $pageNo 页码
     * @param Integer $count 查询数量
     * @return Array
    */
    public function getCommentList($articleId,$pageNo = 1,$count = 10){
        $where['user_id'] = array('GT',0);
        $where['article_id'] = $articleId;
        $where['status'] = array('EGT',0);
        return $this->limit(($pageNo-1)*$count,$count)->where($where)->order('create_time DESC')->select();
    }
}