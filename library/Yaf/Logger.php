<?php
/**
 * Class Yaf_Logger
 * User: felixtang
 * Date: 15-1-5
 * Time: 下午4:
 *
 * 日志配置文件格式
 * $config['logger']['sBaseDir'] = '/data/log/php'; // 日志目录
 * // 默认日志
 * $config['logger']['common'] = array(
 * 'sSplitType' => 'day',   // day, week, month, persistent
 * 'sDir' => 'common',     // 目录为可以选的，不配置时，默认为key值 /data/log/php/sDir or /data/log/php/sDir/
 * 'sLevelName' => 'info',  //  输出日志级别依次增高 debug, info, warn, error , fatal  注：all为输出所有日志，off关闭所有日志
 * );
 *
 */

class Yaf_Logger {
    /**
     * 所有都打印
     */
    const ALL = 0;

    /**
     * Detailed debug information
     */
    const DEBUG = 1;

    /**
     * Interesting events
     */
    const INFO = 2;
    /**
     * Uncommon event
     */

    /**
     * Exceptional occurrences that are not errors
     */
    const WARN = 3;
    /**
     * Runtime errors
     */
    const ERROR = 4;
    /**
     * fatal conditions
     */
    const FATAL = 5;

    /**
     * Action must be taken immediately
     */
    const OFF = 6;
    /**
     * Urgent alert
     */

    static $aLevelNames = array(
        'ALL'=>0, 'DEBUG'=>1, 'INFO'=>2, 'WARN'=>3, 'ERROR'=>4,
        'FATAL'=>5, 'OFF'=>6,
    );

    /**
     * 系统日志实例
     * @var object
     */
    private static $_oInstance = null;

    /**
     * 配置信息
     * @var array
     */
    private $_aConfig;

    /**
     * 日志级别
     */
    private $iLevel = 0;

    /**
     * 构造函数
     */
    private function __construct(){
        $this->_aConfig = Yaf_G::getConf(null, 'logger');

        $sBaseDir = $this->_aConfig['sBaseDir'];
        unset($this->_aConfig['sBaseDir']);

        $iTime = time();

        foreach($this->_aConfig as $sKey => $mConfig){
            //  设置日志级别
            if (isset($this->_aConfig[$sKey]['sLevelName'])) {
                $this->_aConfig[$sKey]['iLevel'] = $this->getLogLevelByName($this->_aConfig[$sKey]['sLevelName']);
            }

            $sDir = isset($this->_aConfig[$sKey]['sDir'])? $this->_aConfig[$sKey]['sDir']: $sKey;

            switch($mConfig['sSplitType']){
                case 'day':
                    $this->_aConfig[$sKey]['sFilename'] = $sBaseDir . DIRECTORY_SEPARATOR . $sDir . DIRECTORY_SEPARATOR . date('Ymd', $iTime) . '.log';
                    break;
                case 'week':
                    $this->_aConfig[$sKey]['sFilename'] = $sBaseDir . DIRECTORY_SEPARATOR . $sDir . DIRECTORY_SEPARATOR . date('YW', $iTime) . '.log';
                    break;
                case 'month':
                    $this->_aConfig[$sKey]['sFilename'] = $sBaseDir . DIRECTORY_SEPARATOR . $sDir . DIRECTORY_SEPARATOR . date('Ym', $iTime) . '.log';
                    break;
                case 'persistent':
                    $this->_aConfig[$sKey]['sFilename'] = $sBaseDir . DIRECTORY_SEPARATOR . $sDir . DIRECTORY_SEPARATOR . $this->_aConfig[$sKey]['sFile'];
                    break;
            }
            $sPath = dirname($this->_aConfig[$sKey]['sFilename']);
            if(!is_dir($sPath)){
                umask(0000);
                if(false === mkdir($sPath, 0755, true)){
                    throw new Exception(__CLASS__ . ': can not create path(' . $sPath . ').');
                    return false;
                }
            }
        }
    }

    /**
     * 构造函数
     */
    private function __clone(){

    }

    /**
     * 获取实例
     * @return Yaf_Logger
     */
    static function getInstance(){
        if(!(self::$_oInstance instanceof self)){
            self::$_oInstance = new self();
        }

        return self::$_oInstance;
    }

    /**
     * 设置日志级别
     */
    public function setLogLevel($p_iLevel)
    {
        if ($p_iLevel >= count(self::$aLevelNames) || $p_iLevel < 0)
        {
            $this->iLevel = 1; // 默认为info级别
        } else {
            $this->iLevel = $p_iLevel;
        }
    }

    public function getLogLevelByName($p_sLevelName) {
        $p_sLevelName = strtoupper($p_sLevelName);

        $iLevel = isset(self::$aLevelNames[$p_sLevelName])? self::$aLevelNames[$p_sLevelName]: -1;

        return $iLevel;
    }

    public function debug($p_sMsg, $p_sName='common')
    {
        $this->log(self::DEBUG, $p_sMsg, $p_sName);
    }

    public function info($p_sMsg, $p_sName='common')
    {
        $this->log(self::INFO, $p_sMsg, $p_sName);
    }

    public function warn($p_sMsg, $p_sName='common')
    {
        $this->log(self::WARN, $p_sMsg, $p_sName);
    }

    public function error($p_sMsg, $p_sName='common')
    {
        $this->log(self::ERROR, $p_sMsg, $p_sName);
    }

    public function fatal($p_mMsg, $p_sName='common')
    {
        $this->log(self::FATAL, $p_mMsg, $p_sName);
    }

    private function log($p_iLevel, $p_mMsg, $p_sClass='common')
    {
        if ($p_iLevel < $this->_aConfig[$p_sClass]['iLevel']) {
            return;
        }

        $sLogFile = $this->_aConfig[$p_sClass]['sFilename'];
        $sLogLevelName = array_keys(self::$aLevelNames)[$p_iLevel];
        $sContent = date('Y-m-d H:i:s') . ' [' . $sLogLevelName . '] ' . $this->convertToStr($p_mMsg) . PHP_EOL;

        file_put_contents($sLogFile, $sContent, FILE_APPEND);
    }

    protected function convertToStr($data)
    {
        if (null === $data || is_bool($data)) {
            return var_export($data, true);
        }

        if (is_scalar($data)) {
            return (string) $data;
        }

        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            return @json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        return str_replace('\\/', '/', @json_encode($data));
    }
}