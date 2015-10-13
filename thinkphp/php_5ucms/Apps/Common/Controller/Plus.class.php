<?php
namespace Common\Controller;
/**
 * 插件类
 * 该类参考了corethink的部分实现 在此表示感谢！
 */
abstract class Plus{
    //视图实例对象 
    protected $view           =  null;
    public $info              =  array();
    public $plus_path        =  '';
    public $config_file       =  '';
    public $custom_config     =  '';
    public $admin_list        =  array();
    public $custom_adminlist  =  '';
    public $access_url        =  array();

    //构造方法 
    public function __construct(){
        $this->view         =   \Think\Think::instance('Think\View');
        $this->plus_path    =   PLUS_PATH.$this->getName().'/';
        $TMPL_PARSE_STRING  = C('TMPL_PARSE_STRING');
        $TMPL_PARSE_STRING['__PLUSROOT__'] = __ROOT__ . '/plus/'.$this->getName();
        C('TMPL_PARSE_STRING', $TMPL_PARSE_STRING);
        if(is_file($this->plus_path.'config.php')){
            $this->config_file = $this->plus_path.'config.php';
        }else{
			 $this->config_file = '插件中配置文件config.php不存在！';	
		}
    }

    //模板主题设置 
    final protected function theme($theme){
        $this->view->theme($theme);
        return $this;
    }

    //显示方法 
    final protected function display($template=''){
        if($template == '')
            $template = CONTROLLER_NAME;
        echo ($this->fetch($template));
    }

    //模板变量赋值
    final protected function assign($name,$value='') {
        $this->view->assign($name,$value);
        return $this;
    }

    //用于显示模板的方法 
    final protected function fetch($templateFile = CONTROLLER_NAME){
        if(!is_file($templateFile)){
            $templateFile = $this->plus_path.$templateFile.C('TMPL_TEMPLATE_SUFFIX');
            if(!is_file($templateFile)){
                throw new \Exception("模板不存在:$templateFile");
            }
        }
        return $this->view->fetch($templateFile);
    }

    //获取名称 如 AdFloatPlus.class 获取到AdFloat
    final public function getName(){
        $class = get_class($this);
        return substr($class,strrpos($class, '\\')+1, -4);
    }

	//验证参数 
    final public function checkInfo(){
        $info_check_keys = array('name','title','description','state','author','version');
        foreach ($info_check_keys as $value) {
            if(!array_key_exists($value, $this->info))
                return FALSE;
        }
        return TRUE;
    }

    //获取插件的配置数组 
    final public function getConfig($name=''){
        static $_config = array();
        if(empty($name)){
            $name = $this->getName();
        }
        if(isset($_config[$name])){
            return $_config[$name];
        }
        $config =   array();
        $map['name'] = $name;
        $map['state'] = 1;
        $config = M('plus')->where($map)->getField('config');
        if($config){
            $config = json_decode($config, true);
        }else{
            $temp_arr = include $this->config_file;
            foreach ($temp_arr as $key => $value) {
                if($value['type'] == 'group'){
                    foreach ($value['options'] as $gkey => $gvalue) {
                        foreach ($gvalue['options'] as $ikey => $ivalue) {
                            $config[$ikey] = $ivalue['value'];
                        }
                    }
                }else{
                    $config[$key] = $temp_arr[$key]['value'];
                }
            }
        }
        $_config[$name] = $config;
        return $config;
    }

    //必须实现安装 
    abstract public function install();

    //必须卸载插件方法 
    abstract public function uninstall();
}
