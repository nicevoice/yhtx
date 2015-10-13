<?php
/**
 * @filename base.php
 * 
 * 
 * @project hf-code
 * @package app-service
 * @author Randy Hong <hongmingwei@pinganfang.com>
 * @created at 14-8-1
 */


/**
 * Class bll_rabbitmq_base
 * rabbitmq操作基类
 * @package app-service-bll-rabbitmq
 * @Author Randy Hong <hongmingwei@pinganfang.com>
 * @Update 2014-08-01
 */
abstract class bll_rabbitmq_base{

    //RabbitMQ连接对象
    public $oConnection = null;

    //配置数组
    public $aConfigs = array();

    //当前配置
    public $aConfig = array();

    //Channel对象
    public $oChannel = null;

    //Exchange对象
    public $oExchange = null;

    //Queue对象
    public $oQueue = null;

    //Exchange名称
    public $sExchangeName = '';

    //Exchange类型
    public $sExchangeType = 'topic';

    //Routing Key
    public $sRoutingKey = '';

    //Queue名称
    public $sQueueName = '';

    //是否持久化
    public $bDurable = false;

    //是否自动返回接收标记
    public $bAutoAck = true;

    //错误代码和描述
    private $_sErrorCode = '';
    private $_sErrorMsg = '';

    /**
     * 创建连接，并指定交换机
     * @param string $p_sKey RabbitMQ配置标识
     * @param string $p_sExchange 交换机名称
     * @return bool
     */
    public function __construct($p_sKey){
        if ( ! $p_sKey ) {
            $this->setError('001','rabbitmq_base::__construct $p_sKey is missing');
            bll_rabbitmq_logger::create(array(
                'type'  => 'create',
                'error' => 'rabbitmq_base::__construct '.$p_sKey.' is missing'
            ));
            return;
        }
        $aConfigs = get_config($p_sKey, 'rabbitmq');
        $this->aConfigs = $aConfigs;
        if ( ! $this->connect() ) {
            bll_rabbitmq_logger::create(array(
                'type'  => 'create',
                'error' => 'rabbitmq_base::__construct connect failed'
            ));
            return;
        }
        $this->sExchangeName = isset($this->aConfig['exchange']) ? $this->aConfig['exchange'] : '';
        $this->sExchangeType = isset($this->aConfig['exgtype']) ? $this->aConfig['exgtype'] : '';
        $this->sRoutingKey = isset($this->aConfig['routing']) ? $this->aConfig['routing'] : '';
        $this->sQueueName = isset($this->aConfig['queue']) ? $this->aConfig['queue'] : '';
        $this->bDurable = isset($this->aConfig['durable']) ? $this->aConfig['durable'] : false;
        $this->bAutoAck = isset($this->aConfig['autoack']) ? $this->aConfig['autoack'] : true;
        if( ! $this->createChannel($this->oConnection) ){
            bll_rabbitmq_logger::create(array(
                'type'  => 'create',
                'error' => 'rabbitmq_base::__construct createChannel failed'
            ));
            return;
        }
    }

    /**
     * 设置交换机类型
     * @param string $p_sType
     */
    public function setExchangeType($p_sType=''){
        $sType = $this->parseExchangeType($p_sType);
        $this->oExchange->setType( $sType );
    }

    /**
     * 获取当前交换机类型
     * @return string
     */
    public function getExchangeType(){
        return $this->sExchangeType;
    }

    /**
     * 解析转换交换机类型
     * @param $p_sExgType
     * @return string
     */
    protected function parseExchangeType($p_sExgType){
        $p_sExgType = strtolower($p_sExgType);
        $sExgType = '';
        switch($p_sExgType){
            case 'topic':
                $sExgType = AMQP_EX_TYPE_TOPIC;
                break;
            case 'fanout':
                $sExgType = AMQP_EX_TYPE_FANOUT;
                break;
            case 'header':
                $sExgType = AMQP_EX_TYPE_HEADER;
                break;
            case 'direct':
            default:
                $sExgType = AMQP_EX_TYPE_DIRECT;
                break;
        }
        return $sExgType;
    }

    /**
     * 连接RabbitMQ
     * 如果一个服务器连接不上，则自动连接下一个
     * @param int $i
     * @return bool
     */
    protected function connect($i = 0){
        if (isset($this->aConfigs[$i])){
            $this->aConfig = $this->aConfigs[$i];
            try{
                $aConfig = array(
                    'host'      => $this->aConfig['host'],
                    'port'      => $this->aConfig['port'],
                    'vhost'     => $this->aConfig['vhost'],
                    'login'     => $this->aConfig['login'],
                    'password'  => $this->aConfig['password'],
                );
                $this->oConnection = new AMQPConnection($aConfig);
                if( isset($this->aConfig['persist']) && $this->aConfig['persist'] ){
                    $this->oConnection->pconnect();
                } else {
                    $this->oConnection->connect();
                }
                $bRet = true;
            } catch (AMQPConnectionException $e){
                $this->setError($e->getCode(), $e->getMessage());
                $bRet = $this->connect(++$i);
                bll_rabbitmq_logger::create(array(
                    'type'  => 'connect',
                    'error' => $e->getMessage()
                ));
            }
        } else {
            $this->setError('003', 'aConfig['.$i.'] not exist');
            $bRet = false;
            bll_rabbitmq_logger::create(array(
                'type'  => 'connect',
                'error' => 'aConfig['.$i.'] not exist'
            ));
        }
        return $bRet;
    }

    /**
     * 检测是否还在连接状态
     * 只有connection和channel都连接时才认为是正确连接
     * @return bool
     */
    public function isConnected(){
        if($this->oConnection->isConnected() && $this->oChannel->isConnected()) return true;
        return false;
    }

    /**
     * 重新连接
     */
    public function reconnect(){
        if($this->oConnection->isConnected() && $this->oChannel->isConnected()) return true;
        try{
            $this->oConnection->reconnect();
            $this->createChannel($this->oConnection);
        } catch (Exception $e){
            bll_rabbitmq_logger::create(array(
                'type'  => 'reconnect',
                'error' => 'reconnect failed'
            ));
        }
    }

    /**
     * 创建一个消息通道
     * @param object $p_oConnection
     * @return bool
     */
    protected function createChannel($p_oConnection){
        try{
            $this->oChannel = new AMQPChannel($p_oConnection);
            return true;
        } catch (AMQPConnectionException $e){
            $this->setError($e->getCode(), $e->getMessage());
            bll_rabbitmq_logger::create(array(
                'type'  => 'createChannel',
                'error' => $e->getMessage()
            ));
            return false;
        }
    }

    /**
     * 创建一个Exchange
     * @param string $p_sExchange
     * @return bool
     */
    public function createExchange($p_sExchange){
        try{
            $this->oExchange = new AMQPExchange($this->oChannel);
            $this->oExchange->setName($p_sExchange);
            $this->setExchangeType($this->sExchangeType);
            return $this->oExchange;
        } catch (AMQPExchangeException $e){
            $this->setError($e->getCode(), $e->getMessage());
            bll_rabbitmq_logger::create(array(
                'type'  => 'createExchange',
                'error' => $e->getMessage()
            ));
            return false;
        }
    }

    /**
     * 创建一个队列
     * @param string $p_sQueue
     * @return bool
     */
    public function createQueue($p_sQueue){
        try {
            $this->oQueue = new AMQPQueue($this->oChannel);
            $this->oQueue->setName($p_sQueue);
            return $this->oQueue;
        } catch (AMQPQueueException $e) {
            $this->setError($e->getCode(), $e->getMessage());
            bll_rabbitmq_logger::create(array(
                'type'  => 'createQueue',
                'error' => $e->getMessage()
            ));
            return false;
        }
    }

    /**
     * 获取当前的exchange对象
     * @return object
     */
    public function getExchange(){
        return $this->oExchange;
    }

    /**
     * 获取当前的Queue对象
     * @return object
     */
    public function getQueue(){
        return $this->oQueue;
    }

    /**
     * 声明交换机
     * @return mixed
     */
    public function declareExchange(){
        return $this->oExchange->declareExchange();
    }

    /**
     * 声明一个新的队列
     */
    public function declareQueue(){
        return $this->oQueue->declareQueue();
    }

    /**
     * 绑定交换机和队列，并指定路由键
     * @param string $p_sExchangeName
     * @param string $p_sRoutingKey
     */
    public function bindQueue($p_sExchangeName,$p_sRoutingKey){
        return $this->oQueue->bind($p_sExchangeName,$p_sRoutingKey);
    }

    /**
     * 解绑队列
     * @param string $p_sQueueName
     */
    public function cancelQueue(){
        return $this->oQueue->cancel($this->sQueueName);
    }

    /**
     * 切换队列
     * @param $p_sQueuename
     * @param string $p_sRouting
     * @param null $p_bDurable
     */
    public function switchQueue($p_sQueuename, $p_sRouting = '', $p_bDurable = null){
        //先解绑
        $this->cancelQueue();
        //再绑定新队列
        $this->sQueueName = $p_sQueuename;
        $this->createQueue($this->sQueueName);
        $this->declareQueue();
        $this->bindQueue($this->sExchangeName, $this->sQueueName);
        if( $p_bDurable !== null ){
            $this->setDurable($p_bDurable);
        }
    }

    /**
     * 设置每个监听进程一次获取的消息数目
     * @param int $p_iNum
     * @return bool
     */
    public function qos($p_iPrefetchSize = 0, $p_iPrefetchCount = 0){
        return $this->oChannel->qos($p_iPrefetchSize, $p_iPrefetchCount);
    }

    /**
     * 获取一个通道同时处理的消息数量
     * @param int $p_iNum
     * @return mixed
     */
    public function setPrefetchCount($p_iNum = 0){
        return $this->oChannel->setPrefetchCount($p_iNum);
    }

    /**
     * 设置一次发送给一个客户端的消息数
     * @param int $p_iNum
     * @return mixed
     */
    public function setPrefetchSize($p_iNum = 0){
        return $this->oChannel->setPrefetchSize($p_iNum);
    }

    /**
     * 设置消息持久化
     * @param bool $p_bDurable
     * @return bool
     */
    public function setDurable($p_bDurable = false){
        if($p_bDurable){
            $this->bDurable = true;
            $this->oExchange->setFlags(AMQP_DURABLE);
            if(is_object($this->oQueue)){
                $this->oQueue->setFlags(AMQP_DURABLE);
            }
        } else {
            $this->bDurable = false;
        }
    }

    /**
     * 设置错误信息
     * @param string $p_sErrorCode
     * @param string $p_sErrorMsg
     * @param bool $p_bThrow
     */
    public function setError($p_sErrorCode, $p_sErrorMsg){
        $this->_sErrorCode = $p_sErrorCode;
        $this->_sErrorMsg = $p_sErrorMsg;
    }

    /**
     * 获取错误信息
     * @return array
     */
    public function getError(){
        return array(
            'code'  => $this->_sErrorCode,
            'error' => $this->_sErrorMsg
        );
    }

    /**
     * 判断是否有错误发生
     * @return bool
     */
    public function hasError(){
        return $this->_sErrorCode === '' ? false : true;
    }

    /**
     * 获取错误代码
     * @return string
     */
    public function getErrorCode(){
        return $this->_sErrorCode;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getErrorMsg(){
        return $this->_sErrorMsg;
    }

    /**
     * 断开连接
     */
    public function disconnect(){
        $this->oConnection->disconnect();
    }

    /**
     * 析构方法，断开连接
     */
    public function __destruct(){
        if ($this->oConnection) {
            $this->disconnect();
        }
    }

}