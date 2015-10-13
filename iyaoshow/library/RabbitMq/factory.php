<?php
load_lib('/bll/rabbitmq/logger');
/**
 * @filename factory.php
 * 
 * 
 * @project hf-code
 * @package app-service
 * @author Randy Hong <hongmingwei@pinganfang.com>
 * @created at 14-8-1
 */

/**
 * Class bll_rabbitmq_factory
 * rabbitmq工厂类，可以派生生产者和消费者
 * @package app-service-bll-rabbitmq
 * @Author Randy Hong <hongmingwei@pinganfang.com>
 * @Update 2014-08-01
 */
class bll_rabbitmq_factory{

    //实例列表
    public static $instances = array();

    /**
     * 创建实例的方法
     * @param string $p_sType
     * @param string $p_sProduct
     * @return object
     */
    public static function create($p_sType, $p_sProduct='default_producer'){
        $p_sType = strtolower($p_sType);
        $sKey = $p_sType.'_'.$p_sProduct;
        if( isset(self::$instances[$sKey]) ){
            return self::$instances[$sKey];
        }
        if( ! in_array($p_sType, array('producer', 'consumer')) ){
            bll_rabbitmq_logger::create(array(
                'type'  => 'create',
                'error' => '[rabbitmq_factory] invalid rabbitmq type:'.$p_sType
            ));
            return false;
        }
        $sClassPath = '/bll/rabbitmq/'.$p_sType;
        $sClassName = path_to_classname($sClassPath,'');
        if( ! class_exists( $sClassName) ){
            load_lib($sClassPath);
        }
        try{
            self::$instances[$sKey] = new $sClassName($p_sProduct);
            return self::$instances[$sKey];
        } catch (Exception $e){
            bll_rabbitmq_logger::create(array(
                'type'  => 'create',
                'error' => '[rabbitmq_factory] Class:'.$sClassName.' init failed'
            ));
            return false;
        }
    }

}