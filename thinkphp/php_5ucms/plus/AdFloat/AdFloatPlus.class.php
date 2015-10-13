<?php
namespace Plus\AdFloat;
use Common\Controller\Plus;
/**
 * 两侧浮动广告插件
 */
class AdFloatPlus extends Plus{
    /**
     * 插件信息
     */
    public $info = array(
        'name'=>'AdFloat',
        'title'=>'图片漂浮广告',
        'description'=>'图片漂浮广告',
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

    /**
     * 实现的PageFooter钩子方法
     */
    public function PageFooter($param){
        $config = $this->getConfig();
        if($config['state']){
            $this->assign('config', $config);
            $this->display('content');
        } 
    }
}
