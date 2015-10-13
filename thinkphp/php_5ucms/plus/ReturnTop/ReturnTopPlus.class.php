<?php
namespace Plus\ReturnTop;
use Common\Controller\Plus;
/**
 * 返回顶部插件
 */
class ReturnTopPlus extends Plus{
    /**
     * 插件信息
     */
    public $info = array(
        'name'=>'ReturnTop',
        'title'=>'返回顶部',
        'description'=>'返回顶部',
        'state'=>1,
        'author'=>'5ucms.com',
        'version'=>'1.0'
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

    //实现的PageFooter钩子方法
    public function PageFooter($param){
        $Plus_config = $this->getConfig();
        if($Plus_config['state']){
            $this->assign('Plus_config', $Plus_config);
            $this->display($Plus_config['theme']);
        }
    }
}
