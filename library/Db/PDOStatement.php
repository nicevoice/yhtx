<?php

class Db_PDOStatement extends PDOStatement
{
    /*

    private $iCnt = 0;

    private $oDebug = null;

    protected $pdo;

    protected function __construct ($pdo)
    {
        $this->pdo = $pdo;
    }

    public function execute ($input_parameters = NULL)
    {
        $start = microtime(true);
        $ret = parent::execute($input_parameters);
        $end = microtime(true);
        if (! $ret) {
            $error_info = parent::errorInfo();
            $_error_info = preg_replace("#[\r\n \t]+#", ' ', print_r($error_info, true));
            if (parent::errorCode() !== '00000') {
                trigger_error($this->queryString . ' | ' . join(' | ', $error_info), E_USER_ERROR);
            }
        }
        
        return $ret;
    }
    */
}