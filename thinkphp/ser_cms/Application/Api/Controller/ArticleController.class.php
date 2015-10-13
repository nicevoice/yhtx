<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/9/16
 * Time: 10:12
 */

namespace Api\Controller;


class ArticleController extends ApiController
{
    /**
     * 获取内容导航
    */
    public function getCategory(){
        $categoryDao = D('Article/Category');
        $nav = $categoryDao->getNavCategory();
        $this->success($nav);
    }
    /**
     * 获取文章列表（categoryKey）
     * @param String $categoryKey 分类标示
     * @param String $keyWord 关键词
     * @param Integer $pageNo
     * @param Integer $count
    */
    public function getArticleByCategoryKey($categoryKey=null,$keyWord=null,$pageNo=1,$count=10){
        $categoryDao = D('Article/Category');
        $articleDao = D('Article/Article');
        $categoryId = $categoryDao->getIdByKey($categoryKey);
        $data = $articleDao->getArticleList($categoryId,$keyWord,$pageNo,$count,$status = null);
        if(empty($data)){
            $this->error(self::EMPTY_DATA_CODE);
        }else{
            $this->success($data);
        }
    }
    /**
     * 获取文章列表（categoryId）
     * @param String $categoryId 分类id
     * @param String $keyWord 关键词
     * @param Integer $pageNo
     * @param Integer $count
     */
    public function getArticleByCategoryId($categoryId=null,$keyWord=null,$pageNo=1,$count=10){
        $articleDao = D('Article/Article');
        $data = $articleDao->getArticleList($categoryId,$keyWord,$pageNo,$count,$status = null);
        if(empty($data)){
            $this->error(self::EMPTY_DATA_CODE);
        }else{
            $this->success($data);
        }
    }

}