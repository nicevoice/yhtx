<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/7/29
 * Time: 15:56
 */

namespace Home\Controller;


class ArticleController extends BaseController{

    //需要被记录uri记录集合(不算参数)
    protected $canSaveRequestUriArray = array(
        'Home/Article/detail',
    );
    //需要登录后访问uri记录集合(不算参数)
    protected $mustLoginRequestUriArray = array(
        'Home/Article/add',
        'Home/Article/doAdd',
        'Home/Article/doAddComment',
    );
    //机构用户可访问uri记录集合(不算参数)
    protected $mustOrgUserRequestUriArray = array(
        'Home/Article/add',
        'Home/Article/doAdd',
        'Home/Article/myArticleList'
    );

    const ADD_ARTICLE_SUCCESS = '提交文章成功，请等待审核';
    const NO_ARTICLE_ID = '参数错误';
    const COMMENT_SUCCESS = '评论成功';
    const ARTICLE_ERROR = '该文章您不能编辑';

    //添加文章
    public function add(){
        $categoryList = D('Article/Category')->getCanWriteList();
        $this->assign('_category_list',$categoryList);
        $this->display();
    }
    public function doAdd(){
        $title = I('title');
        $categoryId = I('category_id');
        $des = I('des');
        $content = $_REQUEST['content'];
        if(empty($title) || empty($categoryId) || empty($des) || empty($content)){
            $this->error(self::MUSE_SET_ERROR);
        }
        $picture = I('picture');
        if(!empty($picture)){
            $picture = $this->getUrlByName($picture);
        }
        $srcLink = I('src_link');
        $articleDao = D('Article/Article');
        $article = $articleDao->addArticle($title,$des,$content,$categoryId,$picture,$srcLink);
        if(empty($article)){
            $this->error($articleDao->getErrorMsg());
        }
        $this->success(self::ADD_ARTICLE_SUCCESS,U('Home/Article/detail',array('article_id'=>$article['article_id'])));
    }
    //详情
    public function detail(){
        $articleId = I('article_id');
        if(empty($articleId)){
            $this->error(self::NO_ARTICLE_ID);
        }
        $articleDao = D('Article/Article');
        $article = $articleDao->detail($articleId);
        if(empty($article)){
            $this->error($articleDao->getErrorMsg());
        }
        //评论列表
        $commonList = D('Article/Comment')->getCommentList($articleId);
        $this->assign('_commentList',$commonList);
        $this->assign('_article',$article);
        $this->display();
    }
    //评论
    public function doAddComment(){
        $articleId = I('article_id');
        $content = I('content');
        if(empty($articleId) || empty($content)){
            $this->error(self::MUSE_SET_ERROR);
        }
        $commentDao = D('Article/Comment');
        $comment = $commentDao->addComment($articleId,$content);
        if(empty($comment)){
            $this->error($commentDao->getErrorMsg());
        }
        $this->success(self::COMMENT_SUCCESS);
    }
    /**
     * 文章列表
    */
    public function articleList(){
        $categoryId = I('category_id');
        $keyword = I('keyword');
        if(empty($categoryId)){
            $this->error(self::NO_ARTICLE_ID);
        }
        $articleDao = D('Article/Article');
        $articleList = $articleDao->getArticleList($categoryId,$keyword,1,20);
        $this->assign('_articleList',$articleList);
        $this->display('pic_article_list');
    }
    //我的文章
    public function myArticleList(){
        $keyword = I('keyword');
        $articleDao = D('Article/Article');
        $articleList = $articleDao->getMyArticleList($keyword,1,20);
        $this->assign('_articleList',$articleList);
        $this->display('article_list');
    }
    public function edit(){
        $articleId = I('article_id');
        if(empty($articleId)){
            $this->error(self::NO_ARTICLE_ID);
        }
        $articleDao = D('Article/Article');
        $where['user_id'] = user_id();
        $where['article_id'] = $articleId;
        $article = $articleDao->where($where)->find();
        if(empty($article)){
            $this->error(self::ARTICLE_ERROR);
        }
        $categoryList = D('Article/Category')->getCanWriteList();
        $this->assign('_category_list',$categoryList);
        $this->assign('_article',$article)->display();
    }
    public function doEdit(){
        $articleId = I('article_id');
        $articleDao = D('Article/Article');
        $article = $articleDao->find($articleId);
        if(empty($article) || $article['user_id'] != user_id()){
            $this->error(self::ARTICLE_ERROR);
        }
        $data['title'] = I('title');
        $data['category_id'] = I('category_id');
        $data['src_link'] = I('src_link');
        $data['des'] = I('des');
        $data['content'] = $_REQUEST['content'];
        $picture = I('picture');
        if(!empty($picture)){
            $data['picture_url'] = $this->getUrlByName($picture);
        }
        $article = $articleDao->edit($articleId,$data);
        if(empty($article)){
            $this->error($articleDao->getErrorMsg());
        }
        $this->success(self::ADD_ARTICLE_SUCCESS,U('Home/Article/detail',array('article_id'=>$article['article_id'])));
    }
}