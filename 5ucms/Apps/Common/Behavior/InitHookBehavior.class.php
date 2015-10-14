<?php 
namespace Common\Behavior;
use Think\Behavior;
use Think\Hook;
defined('THINK_PATH') or exit();
/**
 * 初始化钩子信息
 */
class InitHookBehavior extends Behavior{
    /**
     * 行为扩展的执行入口必须是run
     */
    public function run(&$content){
        //安装模式下直接返回
        if(defined('BIND_MODULE') && BIND_MODULE === 'Install') return;
        $data = S('hooks'); //读取缓存信息
        if(!$data){ //不存在就从钩子表里读取
            $hooks = D('plushook') -> where('state = 1') -> getField('name,pluss');
            foreach($hooks as $key => $value){
                if($value){
                    $map['state']  =   1;
                    $names         =   explode(',',$value);
                    $map['name']   =   array('IN',$names);
                    $data = D('plus')->where($map)->getField('id,name');
                    if($data){ //存在有效的插件就加载
                        $pluss = array_intersect($names, $data);
                        Hook::add($key, array_map('get_plus_class', $pluss));
                    }
                }
            }
            S('hooks', Hook::get());
        }else{ //存在就加载
            Hook::import($data,false); 
        }
    }
}
