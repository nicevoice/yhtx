<?php
namespace Plus\Email;
use Common\Controller\Plus;
/**
 * 邮件插件
 */
class EmailPlus extends Plus{
    /**
     * 插件信息
     */
    public $info = array(
        'name' => 'Email',
        'title' => '邮件插件',
        'description' => '实现系统发邮件功能',
        'state' => 1,
        'author' => '5ucms.com',
        'version' => '1.0'
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
}
