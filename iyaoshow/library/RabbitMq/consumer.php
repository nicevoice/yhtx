<?php
load_lib('/bll/rabbitmq/base');
/**
 * @filename consumer.php
 * RabbitMQ消费者
 * 
 * @project hf-code
 * @package app-service
 * @author Randy Hong <hongmingwei@pinganfang.com>
 * @created at 14-8-1
 */

/**
 * Class bll_rabbitmq_consumer
 * RabbitMQ消费者类
 * @package app-service-bll-rabbitmq
 * @Author Randy Hong <hongmingwei@pinganfang.com>
 * @Update 2014-08-04
 */
class bll_rabbitmq_consumer extends bll_rabbitmq_base{

    /**
     * 构造方法，继承父类
     * 初始化连接
     * @param string $p_sKey 配置
     */
    public function __construct( $p_sKey ) {
        parent::__construct( $p_sKey );
        $this->init($this->sExchangeName,$this->sQueueName,$this->sRoutingKey,$this->bDurable);
    }

    /**
     * 消费者初始化方法
     * @param string $p_sExgName 交换机名
     * @param string $p_sExgType 交换机类型
     * @param bool $p_bDurable 是否持久化
     */
    public function init($p_sExgName = '', $p_sQueueName = '', $p_sRoutingKey = '', $p_bDurable = false){
        //创建交换机
        $this->createExchange($p_sExgName);
        $this->declareExchange();
        //创建队列
        $this->createQueue($p_sQueueName);
        $this->declareQueue();
        //绑定交换机和队列
        $this->bindQueue($p_sExgName, $p_sRoutingKey);
        //持久化
        $this->setDurable($p_bDurable);
        //一次消费一条消息
        $this->setPrefetchCount(1);
    }

    /**
     * 重写重新连接
     * @return bool|void
     */
    public function reconnect(){
        parent::reconnect();
        $this->init($this->sExchangeName,$this->sQueueName,$this->sRoutingKey,$this->bDurable);
    }

    /**
     * 消费消息
     * @param callable $p_mCallback 回调函数
     * @param int $p_iFlags
     * @return bool
     */
    public function consume( $p_mCallback, $p_iFlags = AMQP_NOPARAM ){
        try {
            if($this->bAutoAck && $p_iFlags == AMQP_NOPARAM){
                $p_iFlags = AMQP_AUTOACK;
            }
            $this->oQueue->consume($p_mCallback, $p_iFlags);
            return true;
        } catch (AMQPExchangeException $e) {
            $this->setError($e->getCode(), $e->getMessage());
            bll_rabbitmq_logger::create(array(
                'type'  => 'consume',
                'error' => $e->getMessage()
            ));
            return false;
        }
    }

    /**
     * 订阅消息,consume的别名
     * @param callable $p_mCallback 回调函数
     * @param int $p_iFlags
     * @return bool
     */
    public function subscribe( $p_mCallback, $p_iFlags = AMQP_NOPARAM ){
        return $this->consume( $p_mCallback, $p_iFlags );
    }

    /**
     * 获取消息
     * @param int $p_iFlags
     */
    public function get($p_iFlags = AMQP_NOPARAM){
        if($this->bAutoAck && $p_iFlags == AMQP_NOPARAM){
            $p_iFlags = AMQP_AUTOACK;
        }
        return $this->oQueue->get($p_iFlags);
    }

    /**
     * 通知队列消息已经收到
     * @param string $delivery_tag
     * @param int $p_iFlags
     * @return mixed
     */
    public function ack($delivery_tag, $p_iFlags = AMQP_NOPARAM){
        return $this->oQueue->ack($delivery_tag, $p_iFlags);
    }

    /**
     * 通知队列某个消息尚未收到，该方法主要用到AUTO_ACK时但是处理失败的情况。
     * @param string $delivery_tag
     * @param int $p_iFlags
     * @return mixed
     */
    public function nack($delivery_tag, $p_iFlags = AMQP_NOPARAM){
        return $this->oQueue->nack($delivery_tag, $p_iFlags);
    }

}