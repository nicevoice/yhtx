<?php

/**
 * @filename logger.php
 * RABBITMQ文本日志
 * 
 * @project hf-code
 * @package system
 * @author Randy Hong <hongmingwei@pinganfang.com>
 * @created at 14-10-13
 */

class bll_rabbitmq_logger {

    /**
     * rabbitmq异常的文本日志
     * @param  array  $p_aData 消息
     * @return void
     * @author Randy Hong <hongmingwei@pinganfang.com>
     */
    public static function create($p_aData){
        if (is_string($p_aData)) {
            $aData['msg'] = $p_aData;
        } else {
            $aData = $p_aData;
        }
        $oLogger = sys_logger::getInstance();
        $oLogger->createLog($aData, 'rabbitmq');
    }

}