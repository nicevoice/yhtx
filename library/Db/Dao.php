<?php

/**
 * Class Db_Dao
 *
 * @method static int getQueryCnt()  返回数据库操作次数
 * @method static string getDebugStat() 返回debug状态
 * @method static array getAllSQL() 得到所有执行的SQL语句
 *
 */
class Db_Dao
{

    /**
     * 数据状态有效
     *
     * @var int
     */
    const STATUS_VALID = 1;

    /**
     * 数据状态无效
     *
     * @var int
     */
    const STATUS_INVALID = 0;

    /**
     * 构造函数
     *
     * @return Db_Orm
     */
    public static function getOrm ()
    {
        $class = get_called_class();
        if (defined($class . '::WHERE_CACHE')) {
            $bWhereCache = $class::WHERE_CACHE;
        }
        return Util_Common::getOrm($class::DB_NAME, $class::TABLE_NAME, $bWhereCache);
    }

    /**
     * 获取主键数据
     *
     * @param int $iPKID            
     * @return array/null
     */
    public static function getDetail ($iPKID)
    {
        return self::getOrm()->getRowByPKID($iPKID);
    }

    /**
     * 事务开始
     */
    public static function begin ()
    {
        self::getOrm()->begin();
    }

    /**
     * 事务提交
     */
    public static function commit ()
    {
        return self::getOrm()->commit();
    }

    /**
     * 事务回滚
     */
    public static function rollback ()
    {
        return self::getOrm()->rollback();
    }

    /**
     * 新增数据
     *
     * @param array $aData            
     * @return int/false
     */
    public static function addData ($aData)
    {
        if (! isset($aData['iCreateTime'])) {
            $aData['iCreateTime'] = time();
        }
        if (! isset($aData['iUpdateTime'])) {
            $aData['iUpdateTime'] = time();
        }
        if (! isset($aData['iStatus'])) {
            $aData['iStatus'] = self::STATUS_VALID;
        }
        $oOrm = self::getOrm();
        return $oOrm->insert($aData);
    }

    /**
     * 更新数据
     *
     * @param array $aData            
     * @return int/false
     */
    public static function updData ($aData)
    {
        if (! isset($aData['iUpdateTime'])) {
            $aData['iUpdateTime'] = time();
        }
        $oOrm = self::getOrm();
        return $oOrm->update($aData);
    }

    /**
     * 删除数据,实际为更改db状态位
     *
     * @param int $iPKID            
     * @return int/false
     */
    public static function delData ($iPKID)
    {
        $aData['iUpdateTime'] = time();
        $oOrm = self::getOrm();
        $oOrm->setPKIDValue($iPKID);
        $oOrm->iStatus = self::STATUS_INVALID;
        return $oOrm->updData();
    }

    /**
     * 获取主键列表
     *
     * @param array $aPKIDs            
     * @return array
     */
    public static function getPKIDList ($aPKIDs, $bUsePKID = false)
    {
        return self::getOrm()->getListByPKIDs($aPKIDs, $bUsePKID);
    }

    /**
     * 获取列表
     *
     * @param array $aParam            
     * @param string $sOrder            
     * @return array
     */
    public static function getAll ($aParam, $bUsePKID = false)
    {
        return self::getOrm()->fetchAll($aParam, $bUsePKID);
    }

    /**
     * 获取列表
     *
     * @param array $aParam            
     * @param string $sOrder            
     * @return array
     */
    public static function getList ($aWhere, $iPage, $sOrder = '', $iPageSize = 20, $sUrl = '', $aArg = array(), $bReturnPager = true)
    {
        $iPage = max($iPage, 1);
        $aRet = array();
        $aParam = array(
            'where' => $aWhere,
            'limit' => ($iPage - 1) * $iPageSize . ',' . $iPageSize,
            'order' => $sOrder
        );
        $aRet['aList'] = self::getOrm()->fetchAll($aParam);
        if ($iPage == 1 && count($aRet['aList']) < $iPageSize) {
            $aRet['iTotal'] = count($aRet['aList']);
            if ($bReturnPager) {
                $aRet['aPager'] = null;
            }
        } else {
            unset($aParam['limit'], $aParam['order']);
            $aRet['iTotal'] = self::getOrm()->fetchCnt($aParam);
            if ($bReturnPager) {
                if (empty($sUrl)) {
                    $sUrl = Util_Common::getUrl();
                }
                if (empty($aArg)) {
                    $aArg = $_REQUEST;
                }
                $aRet['aPager'] = Util_Page::getPage($aRet['iTotal'], $iPage, $iPageSize, $sUrl, $aArg);
            }

        }
        return $aRet;
    }

    /**
     * 获取数量
     *
     * @param array $aParam            
     * @return int
     */
    public static function getCnt ($aParam)
    {
        return self::getOrm()->fetchCnt($aParam);
    }

    /**
     * 获取数量
     *
     * @param array $aParam            
     * @return int
     */
    public static function getOne ($aParam, $sField)
    {
        return self::getOrm()->fetchOne($aParam, $sField);
    }

    /**
     * 获取数量
     *
     * @param array $aParam            
     * @return int
     */
    public static function getCol ($aParam, $sField)
    {
        return self::getOrm()->fetchCol($aParam, $sField);
    }

    /**
     * 获取数量
     *
     * @param array $aParam            
     * @return int
     */
    public static function getPair ($aParam, $sKeyField, $sValueField)
    {
        return self::getOrm()->fetchPair($aParam, $sKeyField, $sValueField);
    }

    /**
     * 获取数量
     *
     * @param array $aParam            
     * @return int
     */
    public static function getRow ($aParam)
    {
        return self::getOrm()->fetchRow($aParam);
    }

    /**
     * 执行SQL，还回结果
     *
     * @param unknown $sSQL            
     * @param unknown $sFetchType
     *            (all,row,one,pair,col)
     */
    public static function query ($sSQL, $sFetchType = 'all')
    {
        $aData = self::getOrm()->query($sSQL);
        $aRet = array();
        switch ($sFetchType) {
            case 'row':
                $aRet = current($aData);
                break;
            case 'one':
                $aRet = current($aData);
                $aRet = current($aRet);
                break;
            case 'pair':
                foreach ($aData as $aRow) {
                    $aRow = array_values($aRow);
                    $aRet[$aRow[0]] = $aRow[1];
                }
                break;
            case 'col':
                foreach ($aData as $aRow) {
                    list ($k, $v) = each($aRow);
                    $aRet[] = $v;
                }
                break;
            case 'all':
            default:
                $aRet = $aData;
                break;
        }
        return $aRet;
    }

    public static function __callstatic ($sMethod, $aParam)
    {
        $oOrm = self::getOrm();
        if (method_exists($oOrm, $sMethod)) {
            return call_user_func_array(array(
                $oOrm,
                $sMethod
            ), $aParam);
        } else {
            throw new Exception('【Dao::' . $sMethod . '】方法不存在！');
        }
    }
}
