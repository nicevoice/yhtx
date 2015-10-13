<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/8/7
 * Time: 9:25
 */

namespace Admin\Controller;


class CategoryController extends AdminController{

    public function index(){
        $keyWord = I('key_word');
        $p = I('p');
        if(empty($p)) $p = 1;
        $categoryDao = D('Article/Category');
        $categoryList = $categoryDao->selectAll($keyWord,null,$p,12);
        $articleDao = D('Article/Article');
        foreach($categoryList as $key => $value){
            $articleList = $articleDao->getArticleListByCategoryId($value['category_id']);
            $categoryList[$key]['article_count'] = count($articleList);
            $categoryList[$key]['read_count'] = $this->getReadCount($articleList);
        }
        $this->assign('_categoryList',$categoryList);
        $this->assign('_html',$categoryDao->getHtml());
        $this->display();
    }
    //添加
    public function add(){
        $this->display();
    }
    public function doAdd(){
        $title = I('title');
        $des = I('des');
        $can_write = I('can_write');
        $write = !empty($can_write);
        $can_read = I('can_read');
        $read = !empty($can_read);
        $isNav = I('is_nav');
        $pannelId = I('pannel_id');
        $categoryDao = D('Article/Category');
        $category = $categoryDao->addCategory($title,$des,$isNav,$pannelId,$write,$read);
        if(empty($category)){
            $this->error($categoryDao->getErrorMsg());
        }else{
            $this->success('添加成功',U('Admin/Category/index'));
        }
    }
    //编辑
    public function edit(){
        $this->display();
    }
    public function doEdit(){
        $categoryId = I('category_id');
        $title = I('title');
        $des = I('des');
        $can_write = I('can_write');
        $write = !empty($can_write);
        $can_read = I('can_read');
        $read = !empty($can_read);
        $isNav = I('is_nav');
        $pannelId = I('pannel_id');
        $categoryKey = I('category_key');
        $categoryDao = D('Article/Category');
        $category = $categoryDao->editCategory($categoryId,$title,$des,$isNav,$pannelId,$write,$read,$categoryKey);
        if(empty($category)){
            $this->error($categoryDao->getErrorMsg());
        }else{
            $this->success('添加成功',U('Admin/Category/index'));
        }
    }
    //删除
    public function deleteCategory(){
        echo 1;
    }
    //计算阅读数
    private function getReadCount($articleList){
        $count = 0;
        foreach($articleList as $v){
            $count += $v['read_count'];
        }
        return $count;
    }
}