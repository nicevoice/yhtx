<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/7/29
 * Time: 13:50
 */

namespace Home\Controller;

require_once VENDOR_PATH . '/Qiniu/autoload.php';
use Qiniu\Auth;
use Think\Controller;

class BaseController extends Controller{

    const MUSE_SET_ERROR = '内容不完整，请继续完善信息';
    const PLEASE_LOGIN = '亲，请先登录';
    const NOT_ORG_USER = '亲，您还没有这样做的权限';
    //需要被记录uri记录集合(不算参数)
    protected $canSaveRequestUriArray = array();
    //需要登录后访问uri记录集合(不算参数)
    protected $mustLoginRequestUriArray = array();
    //机构用户可访问uri记录集合(不算参数)
    protected $mustOrgUserRequestUriArray = array();

    public function _initialize(){
        //记录请求地址
        $this->saveRequestUri();
        //控制需要登录的地址访问
        $this->checkMustLoginRequest();
        //机构用户权限
        $this->checkMustOrgUserRequest();
        //导航
        $this->assign('_categoryNavList',D('Article/Category')->getNavCategory());
        //系统公告
        $this->ggArticle();
    }
    /**
     * 记录请求地址
     * 仅记录get 非ajax的地址
     * @return String|Boolean
     */
    protected function saveRequestUri(){
        if($this->is_ajax()) return false;
        if(strtoupper($_SERVER['REQUEST_METHOD']) == 'GET'){
            if(in_array(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$this->canSaveRequestUriArray)){
                $_SESSION['history_url'] = $_SERVER['REQUEST_URI'];
                return $_SESSION['history_url'];
            }
        }
        return false;
    }
    /**
     * 是否ajax请求
     * @return Boolean
     */
    protected function is_ajax(){
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }
    /**
     * 获取uid
     */
    protected function getUid(){
        return user_id();
    }
    /**
     * 需要登录
     */
    protected function checkMustLoginRequest(){
        if(in_array(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$this->mustLoginRequestUriArray)){
            $uid = $this->getUid();
            if(empty($uid)){
                $this->error(self::PLEASE_LOGIN,U('Home/User/login'));
            }
        }
    }
    /**
     * 需要机构用户 TODO
    */
    protected function checkMustOrgUserRequest(){
        if(in_array(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$this->mustOrgUserRequestUriArray)){
            $user = session('user');
            if(empty($user['org_id'])){
                $this->error(self::NOT_ORG_USER);
            }
        }
    }
    //七牛上传token
    public function get_qiniu_token(){
        $qiniuConfig = C('qiniu_config');
        $auth = new Auth($qiniuConfig['AccessKey'],$qiniuConfig['SecretKey']);
        $token = $auth->uploadToken($qiniuConfig['bucket']);
        echo json_encode(array('uptoken' => $token));
    }
    /**
     * 获取七牛图片url
     * @param String $fileName
     * @return String
    */
    public function getUrlByName($fileName){
        $qiniuConfig = C('qiniu_config');
        return $qiniuConfig['url'] . $fileName;
    }
    /**
     * 系统公告
    */
    protected function ggArticle(){
        $articleList = S('ggList');
        if(empty($articleList)){
            $category = D('Article/Category')->where("pannel_id = -1")->find();
            $articleList = D('Article/Article')->getArticleList($category['category_id'],null,1,4);
            S('ggList',$articleList,array('expire'=>60*60*2));
        }
        $this->assign('_ggList',$articleList);
    }
}