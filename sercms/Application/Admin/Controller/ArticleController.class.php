<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/8/6
 * Time: 14:00
 */

namespace Admin\Controller;


class ArticleController extends AdminController{

    protected $categoryName = '全部';

    public function articleList(){
        $categoryId = I('category_id');
        $keyWord = I('key_word');
        $p = I('p');
        if(empty($p)) $p = 1;
        $status = I('status');
        $categoryDao = D('Article/Category');
        $articleDao = D('Article/Article');
        if(!empty($categoryId)){
            //获取分类名称
            $this->categoryName = get_category_title($categoryId);
        }
        //分类列表
        $categoryList = $categoryDao->getCategoryList(999);
        $this->assign('_categoryList',$categoryList);
        //列表
        $articleList = $articleDao->getArticleList($categoryId,$keyWord,$p,12,empty($status) ? null : 0);
        $this->assign('_articleList',$articleList);
        $this->assign('_html',$articleDao->getHtml());
        $this->display('article_list');
    }

    /**
     * 模板显示 调用内置的模板引擎显示方法，
     * @access protected
     * @param string $templateFile 指定要调用的模板文件
     * 默认为空 由系统自动定位模板文件
     * @param string $charset 输出编码
     * @param string $contentType 输出类型
     * @param string $content 输出内容
     * @param string $prefix 模板缓存前缀
     * @return void
     */
    protected function display($templateFile='',$charset='',$contentType='',$content='',$prefix='') {
        //填充文章分类名称
        $this->assign('_categoryName',$this->categoryName);
        $this->view->display($templateFile,$charset,$contentType,$content,$prefix);
    }
    //通过审核
    public function setStatus(){
        if(!is_admin()){
            echo false;
            exit;
        }
        $articleId = I('article_id');
        $res = D('Article/Article')->setStatus($articleId);
        echo !empty($res);
    }
    //删除
    public function deleteArticle(){
        if(!is_admin()){
            echo false;
            exit;
        }
        $articleId = I('article_id');
        $res = D('Article/Article')->deleteArticle($articleId);
        echo !empty($res);
    }

}