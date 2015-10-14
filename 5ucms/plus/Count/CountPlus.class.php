<?php
namespace Plus\Count;
use Common\Controller\Plus;
/**
 * 内容浏览量统计插件
 */
class CountPlus extends Plus{
    /**
     * 插件信息
     */
    public $info = array(
        'name'=>'Count',
        'title'=>'内容浏览量统计',
        'description'=>'可自定义统计方式 卸载将导致内容无法统计浏览量',
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
    public function ContentFooter($param){
        $config = $this->getConfig();
        if($config['state']){ 
			$id = I('get.id');
			$num = $config['num'];
			if(!is_numeric($num) or $num<1){$num=1;} //默认最小为1
			if($config['method']==1){$num = rand(1,$num);} //随机模式
            $r = M('content') -> where('id='.$id)->setInc('views',$num); // 文章阅读数加1  
        } 
    }
}
