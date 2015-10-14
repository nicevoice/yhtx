<?php
//插件plus表操作
namespace ADMIN\Model;
use Think\Model;
/**
 * 插件钩子模型
 * 该类参考了CoreThink的部分实现
 */
class PlusHookModel extends Model {
    /**
     * 自动验证规则
     */
    protected $_validate = array(
        array('name','require','钩子名称必须！', 0, 'regex', 3),
        array('name', '1,32', '钩子名称长度为1-32个字符', 0, 'length', 3),
        array('name', '', '钩子名称已经存在', 0, 'unique', 3),
        array('description','require','钩子描述必须！', 0, 'regex', 3),
    );

    /**
     * 自动完成规则
     */
    protected $_auto = array(
        array('createtime', NOW_TIME, 1),
        array('modifytime', NOW_TIME, 3),
        array('type', '0', 1),
        array('state', '1', 1),
    );

    //获取插件钩子列表 150901
    public function getAllPlushook(){ 
        $Plushooks =  $this-> field(true) -> order('id asc') -> select();
		
        foreach($Plushooks as &$val){
            switch($val['state']){
                case '0': //禁用
                    $val['state'] = '<i class="fa fa-ban" style="color:red"></i>'; 
                    $val['right_button'] .= '<a class="label label-success ajax-get" href="'.U('setstate',array('state'=>'resume', 'ids' => $val['id'])).'">启用</a> ';
					if($val['type']==0){
						$val['right_button'] .= '<a class="label label-info " href="'.U('config',array('id'=>$val['id'])).'">修改</a> ';
                    	$val['right_button'] .= '<a class="label label-danger ajax-get" href="'.U('setstate',array('state'=>'delete', 'ids' => $val['id'])).'">删除</a> '; 
					} else {
						$val['right_button'] .= '<a class="label label-default" href="javacript:void(0);">禁改</a> '; 
						$val['right_button'] .= '<a class="label label-default" href="javacript:void(0);">禁删</a> '; 
					}
                    break;
                case '1': //正常
                    $val['state'] = '<i class="fa fa-check" style="color:green"></i>';
                    $val['right_button'] .= '<a class="label label-warning ajax-get" href="'.U('setstate',array('state'=>'forbid', 'ids' => $val['id'])).'">禁用</a> ';
					if($val['type']==0){ 
                   		$val['right_button'] .= '<a class="label label-info " href="'.U('config',array('id'=>$val['id'])).'">修改</a> ';
                    	$val['right_button'] .= '<a class="label label-danger ajax-get" href="'.U('setstate',array('state'=>'delete', 'ids' => $val['id'])).'">删除</a> '; 
					} else {
						$val['right_button'] .= '<a class="label label-default" href="javacript:void(0);">禁改</a> '; 
						$val['right_button'] .= '<a class="label label-default" href="javacript:void(0);">禁删</a> '; 
					}
                    break;
            }
			
            switch($val['type']){
                case '1': //系统
                    $val['type'] = '系统';
                    break;
                case '0': //个人
                    $val['type'] = '个人';
                    break;
            }
			
        }
        return $Plushooks;
    }


    /**
     * 更新插件里的所有钩子对应的插件
     */
    public function updateHooks($Plushooks_name){
        $Plushooks_class = get_plus_class($Plushooks_name);//获取插件名
        if(!class_exists($Plushooks_class)){
            $this->error = "未实现{$Plushooks_name}插件的入口文件";
            return false;
        }
        $methods = get_class_methods($Plushooks_class);
        $hooks = $this->getField('name', true);
        $common = array_intersect($hooks, $methods);
        if(!empty($common)){
            foreach ($common as $hook) {
                $flag = $this->updatePlushooks($hook, array($Plushooks_name));
                if(false === $flag){
                    $this->removeHooks($Plushooks_name);
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 更新单个钩子处的插件
     */
    public function updatePlushooks($hook_name, $Plushooks_name){
        $o_Plushooks = $this->where("name='{$hook_name}'")->getField('pluss');
        if($o_Plushooks)
            $o_Plushooks = explode(',', $o_Plushooks);
        if($o_Plushooks){
            $Plushooks = array_merge($o_Plushooks, $Plushooks_name);
            $Plushooks = array_unique($Plushooks);
        }else{
            $Plushooks = $Plushooks_name;
        }
        $flag = $this->where("name='{$hook_name}'")
        ->setField('pluss',implode(',', $Plushooks));
        if(false === $flag)
            $this->where("name='{$hook_name}'")->setField('pluss',implode(',', $o_Plushooks));
        return $flag;
    }

    /**
     * 去除插件所有钩子里对应的插件数据
     */
    public function removeHooks($Plushooks_name){
        $Plushooks_class = get_plus_class($Plushooks_name);
        if(!class_exists($Plushooks_class)){
            return false;
        }
        $methods = get_class_methods($Plushooks_class);
        $hooks = $this->getField('name', true);
        $common = array_intersect($hooks, $methods);
        if($common){
            foreach ($common as $hook) {
                $flag = $this->removePlushooks($hook, array($Plushooks_name));
                if(false === $flag){
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 去除单个钩子里对应的插件数据
     */
    public function removePlushooks($hook_name, $Plushooks_name){
        $o_Plushooks = $this->where("name='{$hook_name}'")->getField('pluss');
        $o_Plushooks = explode(',', $o_Plushooks);
        if($o_Plushooks){
            $Plushooks = array_diff($o_Plushooks, $Plushooks_name);
        }else{
            return true;
        }
        $flag = $this->where("name='{$hook_name}'")
                          ->setField('pluss',implode(',', $Plushooks));
        if(false === $flag)
            $this->where("name='{$hook_name}'")
                      ->setField('pluss',implode(',', $o_Plushooks));
        return $flag;
    }
}
