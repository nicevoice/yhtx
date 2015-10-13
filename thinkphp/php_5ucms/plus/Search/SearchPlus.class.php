<?php
namespace Plus\Search;
use Common\Controller\Plus;
/**
 * 内容搜索插件
 */
class SearchPlus extends Plus{
    /**
     * 插件信息
     */
    public $info = array(
        'name'=>'Search',
        'title'=>'内容搜索插件',
        'description'=>'站内内容搜索',
        'state'=>1,
        'author'=>'5ucms.com',
        'version'=>'1.0'
    );
	
	
	/**
     * 自定义插件后台 
    public $custom_adminlist = './Plus/Search/admin.html';
	*/
	
    /**
     * 插件后台数据表配置
     */
    public $admin_list = array(
        '1' => array(
            'title' => '搜索关键词记录',
            'model' => 'tags',//操作表
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
     * meta代码钩子
     */
    public function PageHeader($param){
        $platform_options = $this->getConfig();
        echo $platform_options['meta'];
    }

    /**
     * 实现的Taginside钩子方法 独立插件模式
     */
    public function Taginside($param){
        $config = $this->getConfig();//获取插件配置 
		//模板加载区 
		$content  = file_get_contents('.'.C('installdir').'plus/Search/content.html'); //插件独立模板 
		$pcontent = file_get_contents('.'.C('installdir').'Template/'.C('templatedir').'/'.'Index_common.html'); //母模板  
        if($config['state']){ //正常状态则执行 
			//配置加载区
			$pagenum = $config['pagenum']; //每页数量
			$maxnum  = $config['maxnum']; //最大数量
			$method  = $config['method']; //方法 
			$p = !empty($_GET["p"])?$_GET["p"]:1;//页码
			$key = I('keyword');//获取搜索词 无论是get还是post都收
			if(!empty($key)){ //有搜索词时
				$map['title'] = array('like',"%$key%");
				if($method==1){$map['description'] = array('like',"%$key%");$map['_logic'] = 'OR';} //查询方式
				$qcount = M('content') -> where($map) -> count(); //统计条数
				$page = getpage2($qcount,$pagenum);  
				$qinfo  = M('content') -> where($map) -> order("id desc") -> limit($page['limit']) -> select(); 
				if(empty($qinfo)){echo '没有关于“<font color="red">'.$key.'</font>”的结果哦~';die;} //无结果展示
				//插件展示功能区
				$searchback = '<div class="qsslist"><div class="node">';
				foreach($qinfo as $qkey=>$val){ 
					//标红
					$description = str_replace($key,'<font color=red>'.$key.'</font>',$val['description']); 
					$title		 = str_replace($key,'<font color=red>'.$key.'</font>',$val['title']);   
					//独立每条信息组合
					$searchback .= '<div class="title"><h2><a title="'.$val['title'].'" target="_blank" href="'.U('content/index','id='.$val['id']).'">'.$title.'</a></h2></div><div class="description"><p>'.$description.'</p></div><div class="info"> 作者： <span> '.$val['author'].' </span> 来源： <span> '.$val['source'].' </span></div>'; 
				} 
				$searchback .= '</div></div>';  
				$content = str_replace('{searchback}',$searchback,$content); //替换主内容
				$content = str_replace('{tag:page}',$page['pages'],$content); //替换搜索分页
				
				//录入搜索结果
				$map['name'] = $key;
				$qid = M('tags') -> where($map) -> find(); 
				$data['name'] = $key;
				$data['modifytime'] = time();
				if(!empty($qid)){ //存在关键词则搜索次数加1 并且更新时间
					$qinfo = M('tags') -> where($map) -> save($data); 
					M('tags') -> where($map)->setInc('count',1);  
				} else { //不存在关键词则新增
					$data['count'] = 1;
					$qinfo = M('tags') -> where($map) -> add($data); 
				}
				
				//录入结束
				
			} else { //没有搜索词时
				$content = '请输入搜索词！';
			}  
			
        } else {
			$content = '插件已关闭';
		}
		//替换常用标签
		$content = str_replace('{tag:inside}',$content,$pcontent);//模板嵌套哩 不需要嵌套则注释掉
		$content = str_replace('{field:title}','关键词 '.$key.' 的搜索结果',$content); //替换标题
		$content = str_replace('{field:keyword}',$key,$content); //替换关键词
		$content = str_replace('{field:description}','关键词 '.$key.' 的搜索结果',$content); //替换描述
		$content = str_replace('{tag:sitepath}',getsitepath(0).' 搜索结果 '.$key,$content); //替换关键词
		$content = str_replace('{searchback}',$searchback,$content); //替换主内容
		$content = qss5ucms($content); //模板标签转换
		$content = ChageATurl($content,C('templatedir')); //修改资源文件路径 
		echo $content;die;
    } 
	
	
}
