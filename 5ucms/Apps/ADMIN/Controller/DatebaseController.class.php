<?php
//数据库管理 该类参考了OneThink Corethink的部分实现
namespace ADMIN\Controller;
use Common\Controller\CommonController;
use Think\Controller;
use Think\Db;
use Common\Util\Database;
//继承公共控制器 
class DatebaseController extends CommonController {  
    /**
     * 数据库备份/还原路径
     */
    public static $backup_path = './Backup/';

    /**
     * 数据字典
     */
    public function index($table_id = 0){
        //取得所有表
        $database   = C('DB_NAME'); //数据库名
        $table_list = M()->query('show tables'); //获取所有数据表名称 	
		$table_list = array_change_key_case($table_list,CASE_LOWER);//数组小写化
        //构造Tab列表
        $tab_list = array(); 
		
		//print_r($table_list);
		
        foreach($table_list as $key => $val){
            //获取数据表名称
            $tab_name = $table_title.$val['tables_in_'.$database]; 
			
            //获取当前表的详细信息
            $sql  = 'SELECT * FROM ';
            $sql .= 'INFORMATION_SCHEMA.TABLES ';
            $sql .= 'WHERE ';
            $sql .= "table_name = '{$tab_name}' AND table_schema = '{$database}'";
            $table_info = M()->query($sql); 

            //获取数据表标题与链接
            $tab_list[$key]['title'] = $table_info[0]['table_comment'].'('.$tab_name.')';
            $tab_list[$key]['href']  = U('index', array('table_id' => $key));
        }

        //获取当前数据表名称
        $current_table['table_name'] = $table_list[$table_id]['tables_in_'.$database];

        //获取当前表的详细信息
        $sql  = 'SELECT * FROM ';
        $sql .= 'INFORMATION_SCHEMA.TABLES ';
        $sql .= 'WHERE ';
        $sql .= "table_name = '{$current_table['table_name']}' AND table_schema = '{$database}'";
        $current_table_info = M()->query($sql);

        //获取当前表的备注
        $current_table['table_comment'] = $current_table_info[0]['table_comment'];

        //获取当前表的字段详细信息
        $sql  = 'SELECT * FROM ';
        $sql .= 'INFORMATION_SCHEMA.COLUMNS ';
        $sql .= 'WHERE ';
        $sql .= "table_name = '{$current_table['table_name']}' AND table_schema = '{$database}'";
        $current_table_columns_info = M()->query($sql); 
		$current_table_columns_info = array_change_key_case($current_table_columns_info,CASE_LOWER);//数组小写化

        //获取当前表的字段信息
        $current_table['fields'] = $current_table_columns_info;

        //使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle($current_table['table_name'].'｜数据字典')  //设置页面标题
				->addTopButton('addnew', array('title' => '备份数据库','href' => U('export'))) //添加备份按钮
				->addTopButton('addnew', array('title' => '还原数据库','href' => U('import')))  //添加还原按钮 
                ->SetTabNav($tab_list, $table_id) //设置Tab导航
                ->addTableColumn('column_name', '字段名', 'text')
                ->addTableColumn('column_type', '数据类型', 'text')
                ->addTableColumn('column_default', '默认值', 'text')
                ->addTableColumn('is_nullable', '允许非空', 'text')
                ->addTableColumn('extra', '自动递增', 'text')
                ->addTableColumn('column_comment', '备注', 'text')
                ->setTableDataList($current_table['fields']) //数据列表
                ->display();  
	
    }

    /**
     * 数据库备份
     */
    public function export(){
        $Db   = Db::getInstance();
        $list = $Db->query('SHOW TABLE STATUS');
        $list = array_map('array_change_key_case', $list);
        $this->assign('meta_title', "数据备份");
        $this->assign('list', $list);
        $this->display();
    }
    /**
     * 数据库还原
     */
    public function import(){
        //列出备份文件列表
        $path = self::$backup_path;
        if(!is_dir($path)){
            mkdir($path, 0755, true);
        }
        $path = realpath($path);
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $glob = new \FilesystemIterator($path,  $flag);

        $list = array();
        foreach ($glob as $name => $file) {
            if(preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)){
                $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');
                $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                $part = $name[6];
                if(isset($list["{$date} {$time}"])){
                    $info = $list["{$date} {$time}"];
                    $info['part'] = max($info['part'], $part);
                    $info['size'] = $info['size'] + $file->getSize();
                }else{
                    $info['part'] = $part;
                    $info['size'] = $file->getSize();
                }
                $extension        = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                $info['time']     = strtotime("{$date} {$time}");
                $list["{$date} {$time}"] = $info;
            }
        }
        $this->assign('meta_title', "数据还原");
        $this->assign('list', $list);
        $this->display($type);
    }

    /**
     * 优化表
     * @param  String $tables 表名
     */
    public function optimize($tables = null){
        if($tables) {
            $Db   = Db::getInstance();
            if(is_array($tables)){
                $tables = implode('`,`', $tables);
                $list = $Db->query("OPTIMIZE TABLE `{$tables}`");

                if($list){
                    $this->success("数据表优化完成！");
                }else{
                    $this->error("数据表优化出错请重试！");
                }
            }else{
                $list = $Db->query("OPTIMIZE TABLE `{$tables}`");
                if($list){
                    $this->success("数据表'{$tables}'优化完成！");
                }else{
                    $this->error("数据表'{$tables}'优化出错请重试！");
                }
            }
        }else{
            $this->error("请指定要优化的表！");
        }
    }

    /**
     * 修复表
     * @param  String $tables 表名
     */
    public function repair($tables = null){
        if($tables) {
            $Db   = Db::getInstance();
            if(is_array($tables)){
                $tables = implode('`,`', $tables);
                $list = $Db->query("REPAIR TABLE `{$tables}`");

                if($list){
                    $this->success("数据表修复完成！");
                }else{
                    $this->error("数据表修复出错请重试！");
                }
            }else{
                $list = $Db->query("REPAIR TABLE `{$tables}`");
                if($list){
                    $this->success("数据表'{$tables}'修复完成！");
                }else{
                    $this->error("数据表'{$tables}'修复出错请重试！");
                }
            }
        }else{
            $this->error("请指定要修复的表！");
        }
    }

    /**
     * 删除备份文件
     * @param  Integer $time 备份时间
     */
    public function del($time = 0){
        if($time){
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path  = realpath(self::$backup_path) . DIRECTORY_SEPARATOR . $name;
            array_map("unlink", glob($path));
            if(count(glob($path))){
                $this->error('备份文件删除失败，请检查权限！');
            }else{
                $this->success('备份文件删除成功！');
            }
        }else{
            $this->error('参数错误！');
        }
    }

    /**
     * 备份数据库
     * @param  String  $tables 表名
     * @param  Integer $id     表ID
     * @param  Integer $start  起始行数
     */
    public function do_export($tables = null, $id = null, $start = null){  
        if(IS_POST && !empty($tables) && is_array($tables)){ //初始化
            $path = self::$backup_path;
            if(!is_dir($path)){
                mkdir($path, 0755, true);
            }
            //读取备份配置
            $config = array(
                'path'     => realpath($path) . DIRECTORY_SEPARATOR,
                'part'     => 20971520,
                'compress' => 1,
                'level'    => 9,
            );

            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            if(is_file($lock)){
                $this->error('检测到有一个备份任务正在执行，请稍后再试！');
            }else{
                //创建锁文件
                file_put_contents($lock, NOW_TIME);
            }

            //检查备份目录是否可写
            is_writeable($config['path']) || $this->error('备份目录不存在或不可写，请检查后重试！');
            session('backup_config', $config);

            //生成备份文件信息
            $file = array(
                'name' => date('Ymd-His', NOW_TIME),
                'part' => 1,
            );
            session('backup_file', $file);

            //缓存要备份的表
            session('backup_tables', $tables);

            //创建备份文件
            $Database = new Database($file, $config);
            if(false !== $Database->create()){
                $tab = array('id' => 0, 'start' => 0);
                $this->success('初始化成功！', '', array('tables' => $tables, 'tab' => $tab));
            }else{
                $this->error('初始化失败，备份文件创建失败！');
            }
        } elseif (IS_GET && is_numeric($id) && is_numeric($start)) { //备份数据
            $tables = session('backup_tables');
            //备份指定表
            $Database = new Database(session('backup_file'), session('backup_config'));
            $start  = $Database->backup($tables[$id], $start);
            if(false === $start){ //出错
                $this->error('备份出错！');
            }elseif (0 === $start) { //下一表
                if(isset($tables[++$id])){
                    $tab = array('id' => $id, 'start' => 0);
                    $this->success('备份完成！', '', array('tab' => $tab));
                }else{ //备份完成，清空缓存
                    unlink(session('backup_config.path') . 'backup.lock');
                    session('backup_tables', null);
                    session('backup_file', null);
                    session('backup_config', null);
                    $this->success('备份完成！');
                }
            }else{
                $tab  = array('id' => $id, 'start' => $start[0]);
                $rate = floor(100 * ($start[0] / $start[1]));
                $this->success("正在备份...({$rate}%)", '', array('tab' => $tab));
            }

        }else{ //出错
            $this->error('参数错误！');
        }
    }

    /**
     * 还原数据库
     */
    public function do_import($time = 0, $part = null, $start = null){
        if(is_numeric($time) && is_null($part) && is_null($start)){ //初始化
            //获取备份文件信息
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path  = realpath(self::$backup_path) . DIRECTORY_SEPARATOR . $name;
            $files = glob($path);
            $list  = array();
            foreach($files as $name){
                $basename = basename($name);
                $match    = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                $gz       = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
                $list[$match[6]] = array($match[6], $name, $gz);
            }
            ksort($list); 
            //检测文件正确性
            $last = end($list);
            if(count($list) === $last[0]){
                session('backup_list', $list); //缓存备份列表
                $this->success('初始化完成！', '', array('part' => 1, 'start' => 0));
            }else{
                $this->error('备份文件可能已经损坏，请检查！');
            }
        }elseif(is_numeric($part) && is_numeric($start)) {
            $list  = session('backup_list');
            $db = new Database($list[$part], array(
                'path'     => realpath($this->backup_path) . DIRECTORY_SEPARATOR,
                'compress' => $list[$part][2])); 
            $start = $db->import($start);

            if(false === $start){
                $this->error('还原数据出错！');
            }elseif(0 === $start) { //下一卷
                if(isset($list[++$part])){
                    $data = array('part' => $part, 'start' => 0);
                    $this->success("正在还原...#{$part}", '', $data);
                }else{
                    session('backup_list', null);
                    $this->success('还原完成！');
                }
            }else{
                $data = array('part' => $part, 'start' => $start[0]);
                if($start[1]){
                    $rate = floor(100 * ($start[0] / $start[1]));
                    $this->success("正在还原...#{$part} ({$rate}%)", '', $data);
                }else{
                    $data['gz'] = 1;
                    $this->success("正在还原...#{$part}", '', $data);
                }
            }
        }else{
            $this->error('参数错误！');
        }
    }
}
