<?php
load_lib('/bll/rabbitmq/base');
/**
 * @filename producer.php
 * RabbitMQ生产者
 * 
 * @project hf-code
 * @package app-service
 * @author Randy Hong <hongmingwei@pinganfang.com>
 * @created at 14-8-1
 */

/**
 * Class bll_rabbitmq_producer
 * RabbitMQ生产者类
 * @package app-service-bll-rabbitmq
 * @Author Randy Hong <hongmingwei@pinganfang.com>
 * @Update 2014-08-04
 */
class bll_rabbitmq_producer extends bll_rabbitmq_base{

    /**
     * 构造方法，继承父类
     * 初始化连接
     * @param string $p_sKey 配置
     */
    public function __construct( $p_sKey ) {
        parent::__construct( $p_sKey );
        $this->init($this->sExchangeName,$this->sExchangeType,$this->bDurable);
    }

    /**
     * 生产者初始化方法
     * @param string $p_sExgName 交换机名
     * @param string $p_sExgType 交换机类型
     * @param bool $p_bDurable 是否持久化
     */
    public function init($p_sExgName = '', $p_sExgType = 'topic', $p_bDurable = false){
        //创建交换机
        $this->createExchange($p_sExgName);
        //设置交换机类型
        $this->setExchangeType($p_sExgType);
        $this->declareExchange();
        //持久化
        $this->setDurable($p_bDurable);
    }

    /**
     * 重写重新连接
     * @return bool|void
     */
    public function reconnect(){
        parent::reconnect();
        $this->init($this->sExchangeName,$this->sExchangeType,$this->bDurable);
    }

    /**
     * 发送一个消息
     * @param string $p_sMsg 消息体
     * @param string $p_sRoutingKey 路由
     * @param int $p_iFlags 标记
     * @param array $p_aAttributes
     * @return bool
     */
    public function publish( $p_sMsg, $p_sRoutingKey = '', $p_iFlags = AMQP_NOPARAM, $p_aAttributes= array() ){
        try {
            if( $this->bDurable && !isset($p_aAttributes['delivery_mode']) ){
                $p_aAttributes['delivery_mode'] = '2';
            }
            if($p_sRoutingKey == '') $p_sRoutingKey = $this->sRoutingKey;
            $this->oExchange->publish($p_sMsg, $p_sRoutingKey , $p_iFlags, $p_aAttributes);
            return true;
        } catch (AMQPExchangeException $e) {
            $this->setError($e->getCode(), $e->getMessage());
            bll_rabbitmq_logger::create(array(
                'type'  => 'publish',
                'error' => $e->getMessage()
            ));
            return false;
        }
    }

}