<?php
namespace Plus\SyncLogin;
use Common\Controller\Plus;
/**
 * 同步登陆插件
 */
class SyncLoginPlus extends Plus{
    /**
     * 插件信息
     */
    public $info = array(
        'name' => 'SyncLogin',
        'title' => '第三方账号登陆',
        'description' => '第三方账号登陆',
        'state' => 1,
        'author' => '5ucms.com',
        'version' => '1.0'
    );

    /**
     * 自定义插件后台
     */
    //public $custom_adminlist = './Plus/SyncLogin/admin.html';

    /**
     * 插件后台数据表配置
     */
    public $admin_list = array(
        '1' => array(
            'title' => '第三方登录列表',
            'model' => 'sync_login',
        )
    );

    /**
     * 插件安装方法
     */
    public function install(){
        return true;
    }

    /**
     * 插件卸载方法
     */
    public function uninstall(){
        return true;
    }

    /**
     * 登录按钮钩子
     */
    public function SyncLogin($param){
        $this->assign($param);
        $config = $this->getConfig();
        $this->assign('config',$config);
        $this->display('login');
    }

    /**
     * meta代码钩子
     */
    public function PageHeader($param){
        $platform_options = $this->getConfig();
        echo $platform_options['meta'];
    }
}
