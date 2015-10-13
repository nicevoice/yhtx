<?php
/**
 * 验证内容长度（UTF-8）
 * @param String|Integer $var 检查内容
 * @param Integer $min 最小长度
 * @param Integer $max 最大长度
 * @return Boolean
*/
function check_var_length($var,$min = 1,$max = 10){
    $length = mb_strlen($var,'UTF-8');
    return ($length >= $min && $length <= $max);
}

/**
 * 验证内容是否为数字
 * @param String|Integer $var 检查内容
 * @return Boolean
*/
function check_is_integer($var){
    return is_integer($var);
}
/**
 * 验证是否是邮箱
 * @param String $var 检查内容
 * @return Boolean
*/
function check_is_email($var){
    $arr = explode('@',$var);
    return count($arr) == 2;
}
/**
 * 密码加密
 * @param String $password
 * @return String
*/
function make_password($password){
    return md5($password.'demo_2015');
}
/**
 * 获取用户名称
 * @param Integer $uid
 * @return String
*/
function get_nickname($uid = null){
    if(empty($uid)){
        $user = session('user');
    }else{
        $user = cache_user($uid);
    }
    return $user['nickname'];
}
/**
 * 获取用户头像
 * @param Integer $uid
 * @return String
*/
function get_head_picture($uid = null){
    if(empty($uid)){
        $user = session('user');
    }else{
        $user = cache_user($uid);
    }
    return empty($user['head_pic_url']) ? 'http://' . $_SERVER['HTTP_HOST'] . '/Public/Home/images/default_head.png' : $user['head_pic_url'];
}
/**
 * 判断是否登陆
 * @return Array|Boolean
*/
function is_login(){
    $user = session('user');
    return !empty($user) ? $user : false;
}
/**
 * 获取当前登录者user_id
 * @return Integer
*/
function user_id(){
    $user = session('user');
    return !empty($user) ? $user['user_id'] : false;
}
/**
 * 判断是不是机构用户
*/
function org_user(){
    $user = session('user');
    return !empty($user['org_id']);
}
/**
 * user缓存
 * @param Integer $userId
 * @param Array $user
 * @return Array
 */
function cache_user($userId,$user = null){
    if(empty($user)){//查询
        $user = S('user_cache_' . $userId);
        if(empty($user)){
            $user = D('User/User')->where("user_id = {$userId}")->find();
            S('user_cache_' . $userId,$user);
        }
    }else{//设置
        S('user_cache_' . $userId,$user);
    }
    return $user;
}
/**
 * 获取分类标题
 * @param Integer $categoryId
 * @return String
*/
function get_category_title($categoryId){
    $title = S("category_{$categoryId}_title");
    if(empty($title)){
        $title = D('Article/Category')->getTitle($categoryId);
        S("category_{$categoryId}_title",$title);
    }
    return $title;
}
/**
 * 管理员判断
 * @return Boolean
*/
function is_admin(){
    $where['user_id'] = user_id();
    $admin = M('administrator')->where($where)->find();
    return !empty($admin);
}
/**
 * 用户身份
 * @param Integer $status 身份状态
 * @return String
*/
function user_status_str($status){
    $statusArray = array(0=>'普通用户',-1=>'禁用用户',1=>'认证用户',2=>'社工',99=>'管理员');
    return $statusArray[$status];
}

/**
 * 发送模板短信
 * @param String $to 手机号码集合,用英文逗号分开
 * @param String $code 验证码
 * @param Integer $limitTime 有效时间（秒）
 * @param Integer $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
 * @return Boolean|String
 */
function sendTemplateSMS($to,$code,$limitTime = 300,$tempId = 1){
    vendor('CCPRestSmsSDK',VENDOR_PATH.'/cppRestSms/','.php');
    // 初始化REST SDK
    $config = C('RL_CONFIG');
    $rest = new REST($config['serverIP'],$config['serverPort'],$config['softVersion']);
    $rest->setAccount($config['accountSid'],$config['accountToken']);
    $rest->setAppId($config['appId']);
    // 发送模板短信
    $result = $rest->sendTemplateSMS($to,array($code,(int)($limitTime/60)),$tempId);
    if($result == NULL ) {
        return false;
    }
    if($result->statusCode!=0) {
        return false;
    }else{
        // 获取返回信息
        return $result->TemplateSMS;
    }
}



