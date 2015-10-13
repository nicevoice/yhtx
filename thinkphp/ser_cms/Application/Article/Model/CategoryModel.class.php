<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/7/29
 * Time: 16:40
 */

namespace Article\Model;


use Common\Model\BaseModel;
use Think\Page;

class CategoryModel extends BaseModel{

    protected $tableName = 'article_category';
    const TITLE_LENGTH_ERROR = '标题长度应该为1-10位';
    const CATEGORY_ID_ERROR = '参数异常';
    const CATEGORY_KEY_ERROR = '分类标示已重复';
    /**
     * 获取可编辑分类表
     * @return Array
    */
    public function getCanWriteList(){
        $where['is_del'] = 0;
        $where['can_write'] = 1;
        return $this->where($where)->select();
    }
    /**
     * 检查分类是否有效
     * @param Integer $categoryId
     * @return Boolean
    */
    public function checkCategory($categoryId){
        $where['category_id'] = $categoryId;
        $category = $this->find($categoryId);
        if(empty($category) || $category['can_write'] <> 1) return false;
        return true;
    }
    /**
     * 获取导航分类
    */
    public function getNavCategory(){
        $where['can_read'] = 1;
        $where['is_del'] = 0;
        $where['is_nav'] = array('GT',0);
        return $this->where($where)->order('is_nav')->select();
    }
    /**
     * 获取板块分类id
    */
    public function getPannelCategory(){
        $where['can_read'] = 1;
        $where['is_del'] = 0;
        $where['pannel_id'] = array('GT',0);
        return $this->where($where)->order('pannel_id')->select();
    }
    /**
     * 获取分类列表
     * @param Integer $count 获取数量
     * @return Array
    */
    public function getCategoryList($count = 10){
        $where['is_del'] = 0;
        return $this->where($where)->order('category_id DESC')->limit(1,$count)->select();
    }
    /**
     * 获取分类名称
     * @param Integer $categoryId 分类id
     * @return String
    */
    public function getTitle($categoryId){
        $where['is_del'] = 0;
        $where['category_id'] = $categoryId;
        $category = $this->where($where)->find();
        return $category['title'];
    }
    /**
     * 分页的列表(Model的连贯函数)
     * @param String $keyWord 关键字
     * @param String $categoryId 关键字
     * @param Integer $pageNo 页码
     * @param Integer $count 查询数量
    */
    public function selectAll($keyWord = null,$categoryId = null,$pageNo = 1,$count = 10){
        $where['is_del'] = 0;
        if(!empty($keyWord)) $where['title'] = array('LIKE',"%{$keyWord}%");
        if(!empty($categoryId)) $where['category_id'] = array('LIKE',"%{$categoryId}%");
        $this->where($where);
        //分页
        $page = new Page($this->count(),$count);
        $this->html = $page->show();
        return $this->order('category_id DESC')->limit(($pageNo-1)*$count,$count)->select();
    }
    /**
     * 添加分类
     * @param String $title 标题
     * @param String $des 描述
     * @param Integer $isNav 导航位置
     * @param Integer $pannelId 面板位置
     * @param Boolean $write 前台可编辑
     * @param Boolean $read 可被访问
     * @param String $categoryKey 分类key
     * @return Array
    */
    public function addCategory($title,$des = null,$isNav = 0,$pannelId = 0,$write = true,$read = true,$categoryKey = null){
        if(!is_admin()){
            $this->setErrorMsg(self::NOT_ADMIN);
            return false;
        }
        if(!check_var_length($title,1,10)){
            $this->setErrorMsg(self::TITLE_LENGTH_ERROR);
            return false;
        }
        if(!empty($categoryKey) && !$this->checkCategoryKey($categoryKey)){
            $this->setErrorMsg(self::CATEGORY_KEY_ERROR);
            return false;
        }
        $data['title'] = $title;
        $data['des'] = $des;
        $data['is_nav'] = $isNav;
        $data['pannel_id'] = $pannelId;
        $data['can_write'] = $write;
        $data['can_read'] = $read;
        $data['is_del'] = 0;
        $data['category_key'] = $categoryKey;
        $categoryId = $this->add($data);
        return empty($categoryId) ? false : $this->find($categoryId);
    }
    /**
     * 编辑分类
     * @param Integer $categoryId 分类id
     * @param String $title 标题
     * @param String $des 描述
     * @param Integer $isNav 导航位置
     * @param Integer $pannelId 面板位置
     * @param Boolean $write 前台可编辑
     * @param Boolean $read 可被访问
     * @param String $categoryKey 分类key
     * @return Array
     */
    public function editCategory($categoryId,$title,$des = null,$isNav = 0,$pannelId = 0,$write = true,$read = true,$categoryKey = null){
        if(!is_admin()){
            $this->setErrorMsg(self::NOT_ADMIN);
            return false;
        }
        if(empty($categoryId)){
            $this->setErrorMsg(self::CATEGORY_ID_ERROR);
            return false;
        }
        if(!check_var_length($title,1,10)){
            $this->setErrorMsg(self::TITLE_LENGTH_ERROR);
            return false;
        }
        if(!empty($categoryKey) && !$this->checkCategoryKey($categoryKey,$categoryId)){
            $this->setErrorMsg(self::CATEGORY_KEY_ERROR);
            return false;
        }
        $data['title'] = $title;
        $data['des'] = $des;
        $data['is_nav'] = $isNav;
        $data['pannel_id'] = $pannelId;
        $data['can_write'] = $write;
        $data['can_read'] = $read;
        $data['is_del'] = 0;
        $data['category_key'] = $categoryKey;
        $data = array_filter($data);
        $where['category_id'] = $categoryId;
        $categoryId = $this->where($where)->save($data);
        return empty($categoryId) ? false : $this->find($categoryId);
    }
    /**
     * 检查分类key唯一
     * @param String $categoryKey 分类key
     * @param Integer $categoryId 分类id (存在分类使用)
     * @return Boolean
    */
    public function checkCategoryKey($categoryKey,$categoryId = null){
        if(!empty($categoryId)) $where['category_id'] = array('NEQ',$categoryId);
        $where['category_key'] = $categoryKey;
        $where['is_del'] = 0;
        $category = $this->where($where)->find();
        return !empty($category);
    }
    /**
     * 根据categoryId获取categoryKey
     * @param Integer $categoryId 分类id
     * @return String $categoryKey 分类key
    */
    public function getKeyById($categoryId){
        $category = $this->find($categoryId);
        return $category['category_key'];
    }
    /**
     * 根据categoryKey获取categoryId
     * @param String $categoryKey 分类key
     * @return Integer $categoryId 分类id
     */
    public function getIdByKey($categoryKey){
        $category = $this->where("category_key LIKE '{$categoryKey}'")->find();
        return $category['category_id'];
    }
}