<?php
//插件plus表操作
namespace ADMIN\Model;
use Think\Model;

class PlusModel extends Model{
	 
	protected $autoCheckFields = false;
    //自动验证规则 
    protected $_validate = array(
        array('name', 'require', '插件名称不能为空', 0, 'regex', 3), //0 存在字段就验证 
        array('name', '1,32', '插件名称长度为1-32个字符', 0, 'length', 3),
        array('name', '', '插件名称已经存在', 2, 'unique', 3), //2 不为空时验证 3添加和修改都验证
        array('description','require','插件描述必须！', 0, 'regex', 3), 
    );

    //自动完成规则 
    protected $_auto = array(
        array('createtime', NOW_TIME, 1),
        array('modifytime', NOW_TIME, 3),
        array('order', '0', 1),
        array('state', '1', 1),
    );

    //插件类型 
    public function Plus_type($id){
        $list[0] = '系统插件';
        $list[1] = '前台插件';
        return $id ? $list[$id] : $list;
    }

    //获取插件列表 150828
    public function getAllPlus($Plus_dir = PLUS_PATH){
        $dirs = array_map('basename', glob($Plus_dir.'*', GLOB_ONLYDIR)); //读取插件plus目录下的文件夹名称
        if($dirs === FALSE || !file_exists($Plus_dir)){
            $this->error = '插件目录不可读或者不存在';
            return FALSE;
        }
        $Pluss            =    array(); //从plus数据表中，找出和插件名称相同的结果
        $map['name']      =    array('in', $dirs);
        $list             =    $this->where($map)->field(true)->order(array('order'=>'Desc','id'=>'Desc'))->select();
        foreach($list as $Plus){ //格式化数组  插件名 = 对应插件信息数组
            $Pluss[$Plus['name']]    =    $Plus;
        }
        foreach ($dirs as $value){ //格式化插件文件夹内名称数组
            if(!isset($Pluss[$value])){
                $class = get_Plus_class($value);
                if(!class_exists($class)){ // 实例化插件失败忽略执行
                    // \Think\Log::record('插件'.$value.'的入口文件不存在！');
                    continue;
                }
                $obj = new $class; //实例化一下下
                $Pluss[$value] = $obj->info; //读取出插件的基本配置信息
                if($Pluss[$value]){
                    $Pluss[$value]['state'] = -1; //未安装
                }
            }
        }
        foreach($Pluss as &$val){
            switch($val['state']){
                case '-1': //未安装
                    $val['state'] = '<i class="fa fa-trash" style="color:red"></i>';
                    $val['right_button']  = '<a class="label label-success ajax-get" href="'.U('install?Plus_name='.$val['name']).'">安装</a>';
                    break;
                case '0': //禁用
                    $val['state'] = '<i class="fa fa-ban" style="color:red"></i>';
                    $val['right_button']  = '<a class="label label-info " href="'.U('config',array('id'=>$val['id'])).'">设置</a> ';
                    $val['right_button'] .= '<a class="label label-success ajax-get" href="'.U('setstate',array('state'=>'resume', 'ids' => $val['id'])).'">启用</a> ';
                    $val['right_button'] .= '<a class="label label-danger ajax-get" href="'.U('uninstall?id='.$val['id']).'">卸载</a> ';
                    if($val['adminlist']){
                        $val['right_button'] .= '<a class="label label-success " href="'.U('adminlist',array('name'=>$val['name'])).'">后台管理</a>';
                    }
                    break;
                case '1': //正常
                    $val['state'] = '<i class="fa fa-check" style="color:green"></i>';
                    $val['right_button']  = '<a class="label label-info " href="'.U('config',array('id'=>$val['id'])).'">设置</a> ';
                    $val['right_button'] .= '<a class="label label-warning ajax-get" href="'.U('setstate',array('state'=>'forbid', 'ids' => $val['id'])).'">禁用</a> ';
                    $val['right_button'] .= '<a class="label label-danger ajax-get" href="'.U('uninstall?id='.$val['id']).'">卸载</a> ';
                    if($val['adminlist']){
                        $val['right_button'] .= '<a class="label label-success " href="'.U('adminlist',array('name'=>$val['name'])).'">后台管理</a>';
                    }
                    break;
            }
        }
        return $Pluss;
    }

    //插件显示内容里生成访问插件的url 
    public function getPlusUrl($url, $param = array()){
        $url        = parse_url($url);
        $case       = C('URL_CASE_INSENSITIVE');
        $Pluss     = $case ? parse_name($url['scheme']) : $url['scheme'];
        $controller = $case ? parse_name($url['host']) : $url['host'];
        $action     = trim($case ? strtolower($url['path']) : $url['path'], '/');
        // 解析URL带的参数
        if(isset($url['query'])){
            parse_str($url['query'], $query);
            $param = array_merge($query, $param);
        }
        // 基础参数
        $params = array(
            '_Pluss'     => $Pluss,
            '_controller' => $controller,
            '_action'     => $action,
        );
        $params = array_merge($params, $param); //添加额外参数
        return U('Home/Plus/execute', $params);
    }
}
