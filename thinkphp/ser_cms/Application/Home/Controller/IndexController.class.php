<?php
namespace Home\Controller;


class IndexController extends BaseController{

    public function index(){
        $categoryDao = D('Article/Category');
        $articleDao = D('Article/Article');
        $pannelCategoryList = $categoryDao->getPannelCategory();
        $indexList = array();
        foreach($pannelCategoryList as $key => $value){
            $value['article_list'] = $articleDao->getArticleList($value['category_id']);
            $indexList[$value['pannel_id']] = $value;
        }
        //社工
        $this->assign('_sgList',D('User/User')->getUserList(null,1,20,2));
        $this->assign('_indexList',$indexList);
        //心情语
        $this->assign('_xinqing',$this->getXinqing());
        //新闻
        $this->assign('_news',$this->getNews());
        $this->display();
    }
    //获取心情语
    public function getXinqing(){
        $xq = S('HOME_XINQING');
        if(empty($xq)){
            S('HOME_XINQING',file_get_contents("http://hello.api.235dns.com/api.php?code=json&key=e187077e6d0299a72faf043ceb64591d"),60*5);
            $xq = S('HOME_XINQING');
        }
        return json_decode($xq,true);
    }
    //获取新闻
    public function getNews($count = 20){
        $cacheKey = "HOME_NEWS_LIST_{$count}";
        $list = S($cacheKey);
        if(empty($list)){
            $ch = curl_init();
            $url = "http://apis.baidu.com/txapi/social/social?num={$count}&page=1";
            $header = array(
                'Expect:',
                'apikey:2cdb60afc7bd2eedea12a99176190bf5',
            );
            // 添加apikey到header
            curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // 执行HTTP请求
            curl_setopt($ch , CURLOPT_URL , $url);
            $res = curl_exec($ch);
            curl_error($ch);
            $res = json_decode($res,true);
            $data = array();
            for($i=0;$i<$count;$i++){
                if(!empty($res[$i]['picUrl'])){
                    $data[] = $res[$i];
                }
            }
            S($cacheKey,$data,60*60*2);
            $list = S($cacheKey);
        }
        return $list;
    }
}