<?php

class Db_Orm
{

    /**
     * 所有执行的SQL语句
     *
     * @var array
     */
    protected static $_aSQLs = array();

    /**
     * 数据库操作次数
     *
     * @var int
     */
    protected static $_iQueryCnt = 0;

    /**
     * 当String字段超过长度时，是否自动裁剪
     * @var unknown
     */
    protected static $_bAutoCutFieldLength = FALSE;

    /**
     * ORM数据
     *
     * @var array
     */
    protected $_aData = array();

    /**
     * ORM名称
     *
     * @var string
     */
    protected $_sDbName = null;

    /**
     * 数据库表结构
     *
     * @var array
     */
    protected $_aTblField = array();

    /**
     * 最后执行的SQL语句
     *
     * @var string
     */
    protected $_sLastSQL = null;

    /**
     * 主键字段
     *
     * @var string
     */
    protected $_sPKIDField = null;

    /**
     * 表名称
     *
     * @var string
     */
    protected $_sTblName = null;

    /**
     * Master数据库连接
     *
     * @var object
     */
    protected $_oMasterDB = null;

    /**
     * Slave数据库连接
     *
     * @var object
     */
    protected $_oSlaveDB = null;

    /**
     * 数据库陈述
     *
     * @var object
     */
    protected $_oSth = null;

    /**
     * 缓存连接
     *
     * @var object
     */
    protected $_oCache = null;

    /**
     * 是否开启主键缓存
     *
     * @var $_bNeedCache
     */
    protected $_bNeedCache = false;//没有缓存，暂时屏蔽

    /**
     * 是否开启Where缓存
     *
     * @var $_bWhereCache
     */
    protected $_bWhereCache = false;//没有缓存，暂时屏蔽

    /**
     * 数据缓存时间
     *
     * @var int
     */
    protected $_iCacheTime = 7200;

    /**
     * Debug类实例
     *
     * @var object
     */
    protected $_oDebug = null;

    protected $_bStrictMaster = false;

    protected static $_iCommitCnt = 0;

    protected static $_iUseTime = 0;

    protected static $_iConnentTime = 0;

    protected static $_aComparisonOperators = array(
        '=',
        '!=',
        '<>',
        '>',
        '>=',
        '<',
        '<=',
        'IN',
        'NOT',
        'LIKE'
    );

    public function __construct ($sDbName, $sTblName, $bStrictMaster = false)
    {
        $this->_sDbName = $sDbName;
        $this->_sTblName = $sTblName;
        $this->_bStrictMaster = $bStrictMaster;
        $this->_oDebug = Util_Common::getDebug();
        $this->_getTblField();
    }

    /**
     * 设置是否自动裁剪长度
     */
    public static function setAutoCutFieldLength($bAuto) {
        self::$_bAutoCutFieldLength = $bAuto;
    }

    /**
     * 析构实例
     */
    public function __destruct ()
    {
        self::$_aSQLs = null;
    }

    public function setDebug ($p_mDebug)
    {
        $this->_oDebug = $p_mDebug;
    }

    public static function getDebugStat ()
    {
        return '[ORM]->Query: ' . self::$_iQueryCnt . ', Connent Time: ' . self::$_iConnentTime . ' Use Time:' . self::$_iUseTime;
    }

    public function __set ($sField, $mValue)
    {
        if (isset($this->_aTblField[$sField])) {
            $sOperator = $iParam = '';
            if (! $this->_isSelfOperate($sField, $mValue, $sOperator, $iParam)) {
                if ('i' == $this->_aTblField[$sField]['TYPE'] or 'f' == $this->_aTblField[$sField]['TYPE']) {
                    if (! is_numeric($mValue)) {
                        $mValue = 0;
                    }
                }
                if ('s' == $this->_aTblField[$sField]['TYPE'] && isset($this->_aTblField[$sField]['LENGTH']) && 0 < $this->_aTblField[$sField]['LENGTH']) {
                    $iLength = mb_strlen($mValue, 'UTF-8');
                    if ($iLength > $this->_aTblField[$sField]['LENGTH']) {
                        if (self::$_bAutoCutFieldLength) {
                            $mValue = mb_substr($mValue, 0, $this->_aTblField[$sField]['LENGTH']);
                        } else {
                            throw new Exception($this->_sTblName . '.' . $sField . '长度超过了限制， 最大(' . $this->_aTblField[$sField]['LENGTH'] . ')，当前(' . $iLength . '):' . $mValue);
                        }
                    }
                }
            }
            $this->_aData[$sField] = $mValue;
        } else {
            throw new Exception($this->_sTblName . '表中未找到(' . $sField . ')字段！');
        }
    }

    public function __get ($sField)
    {
        if (isset($this->_aTblField[$sField])) {
            return $this->_aData[$sField];
        } else {
            throw new Exception($this->_sTblName . '表中未找到(' . $sField . ')字段！');
        }
    }

    public function __isset ($sField)
    {
        return isset($this->_aData[$sField]);
    }

    public function __unset ($sField)
    {
        unset($this->_aData[$sField]);
    }

    /**
     * 得到所有执行的SQL语句
     *
     * @return array;
     */
    public static function getAllSQL ()
    {
        return self::$_aSQLs;
    }

    /**
     * 返回数据库操作次数
     *
     * @return int
     */
    public static function getQueryCnt ()
    {
        return self::$_iQueryCnt;
    }

    /**
     * 得到ORM的所有数据
     *
     * @return array
     */
    public function getSource ()
    {
        return $this->_aData;
    }

    /**
     * 设置ORM缓存时间
     *
     * @param int $iSecond
     */
    public function setCacheTime ($iSecond)
    {
        $this->_iCacheTime = $iSecond;
    }

    /**
     * 设置缓存功能
     */
    public function setNeedCache ($bNeedCache)
    {
        $this->_bNeedCache = $bNeedCache;
    }

    /**
     * 设置缓存功能
     */
    public function setWhereCache ($bWhereCache)
    {
        $this->_bWhereCache = $bWhereCache;
    }

    /**
     * 清除所有缓存
     */
    public function clearAllCache ()
    {
        $this->clearWhereCache();
        $this->clearFieldCache();
        if ($this->_bNeedCache) {
            $aCacheKey = $this->_getCacheRowKey('');
            $this->_getCache()->del($aCacheKey[0]);
        }
    }

    /**
     * 清除Where缓存
     */
    public function clearWhereCache ()
    {
        if ($this->_bWhereCache) {
            $aCacheKey = $this->_getWhereCache('', '');
            $this->_getCache()->del($aCacheKey[0]);
        }
    }

    /**
     * 取得Where查询Cache
     */
    private function _getWhereCache ($sSQL, $aParameter)
    {
        return array(
            'DB_' . $this->_sDbName . ':TW:' . $this->_sTblName,
            $this->_formatSQL($sSQL, $aParameter)
        );
    }

    /**
     * 清除数据结构Cache
     */
    public function clearFieldCache ()
    {
        if ($this->_bNeedCache) {
            $aCacheKey = $this->_getFieldCacheKey();
            $this->_getCache()->del($aCacheKey[0]);
        }
    }

    /**
     * 根据主键删除单行缓存
     *
     * @param int $iPKID
     * @return true/false
     */
    public function clearRowCache ($iPKID)
    {
        $aCacheKey = $this->_getCacheRowKey($iPKID);
        return $this->_getCache()->hDel($aCacheKey[0], $aCacheKey[1]);
    }

    /**
     * 根据主键缓存单行数据
     *
     * @param unknown $iPKID
     * @param unknown $aValue
     */
    private function _setRowCache ($iPKID, $aValue)
    {
        if ($this->_bNeedCache && ! empty($aValue)) {
            $aCacheKey = $this->_getCacheRowKey($iPKID);
            return $this->_getCache()->hSet($aCacheKey[0], $aCacheKey[1], $aValue);
        }
        return false;
    }

    /**
     * 获取ORM数据缓存Key
     *
     * @param string $sDbName
     * @param string $sTblName
     * @param int $iPKID
     * @return string
     */
    protected function _getCacheRowKey ($iPKID)
    {
        return array(
            'DB_' . $this->_sDbName . ':TR:' . $this->_sTblName,
            $iPKID
        );
    }

    /**
     * 获取数据库表结构缓存key
     *
     * @param string $sDbName
     * @param string $sTblName
     * @return string
     */
    private function _getFieldCacheKey ()
    {
        return array(
            'DB_' . $this->_sDbName . ':TF:' . $this->_sTblName,
            'field'
        );
    }

    /**
     * 取得数据结构缓存
     */
    private function _getFieldCache ()
    {
        if ($this->_bNeedCache) {
            $aCacheKey = $this->_getFieldCacheKey();
            return $this->_getCache()->hGet($aCacheKey[0], $aCacheKey[1]);
        }

        return false;
    }

    /**
     * 缓存数据结构
     *
     * @param unknown $aValue
     */
    private function _setFieldCache ($aValue)
    {
        if ($this->_bNeedCache && ! empty($aValue)) {
            $aCacheKey = $this->_getFieldCacheKey();
            return $this->_getCache()->hSet($aCacheKey[0], $aCacheKey[1], $aValue);
        }
        return false;
    }

    /**
     * 添加数据
     *
     * @return int/false
     */
    public function addData ()
    {
        $aSQLParam = $this->_joinAddString($this->_aTblField, $this->_aData);
        $sSQL = 'insert into ' . $this->_sTblName . ' (' . $aSQLParam['FIELDSSTR'] . ') values(' . $aSQLParam['PARAMSTR'] . ')';
        $iLastInsertID = $this->_insert($sSQL, $aSQLParam['VALUEARR']);
        return $iLastInsertID;
    }

    /**
     * 插入数据库数据
     *
     * @param string $sSQL
     * @param array $aParam
     * @return int/false
     */
    protected function _insert ($sSQL, $aParam)
    {
        $iRowCount = $this->_exectue($sSQL, $aParam, true, true);
        if ($iRowCount != 1) {
            return 0;
        }
        return $this->_oMasterDB->lastInsertId();
    }

    /**
     * 更新数据
     *
     * @return int
     */
    public function updData ()
    {
        $aNewData = $this->getSource();
        if (! isset($aNewData[$this->_sPKIDField])) {
            throw new Exception($this->_sTblName . '表中未找到主键字段！');
        }
        // 如果只有主键刚不更新
        if (2 > count($aNewData)) {
            return 0;
        }
        $aSQLParam = $this->_joinUpdString($this->_aTblField, $aNewData, $this->_sPKIDField);
        $aSQLParam['VALUEARR'] = array_merge($aSQLParam['VALUEARR'], array(
            $this->_sPKIDField => $aNewData[$this->_sPKIDField]
        ));
        $sSQL = 'UPDATE ' . $this->_sTblName . ' SET ' . $aSQLParam['FIELDSSTR'] . ' WHERE ' . $this->_sPKIDField . ' = :' . $this->_sPKIDField;
        $iAffectedCnt = $this->_exectue($sSQL, $aSQLParam['VALUEARR'], true, true);
        $this->clearRowCache($aNewData[$this->_sPKIDField]);
        return $iAffectedCnt;
    }

    /**
     * 删除数据
     *
     * @return int
     */
    public function delData ()
    {
        $sSQL = 'DELETE FROM ' . $this->_sTblName . ' WHERE ' . $this->_sPKIDField . ' = :' . $this->_sPKIDField;
        $this->clearRowCache($this->_aData[$this->_sPKIDField]);
        $iAffectedCnt = $this->_exectue($sSQL, array(
            $this->_sPKIDField => $this->_aData[$this->_sPKIDField]
        ), true, true);
        return $iAffectedCnt;
    }

    /**
     * 获取主键
     *
     * @author lisumliang
     * @return string PKID
     */
    public function getPKIDField ()
    {
        return $this->_sPKIDField;
    }

    /**
     * 获取PKIDs
     */
    public function getPKIDList ($sWhere = '', $aParameter = array(), $bStrictMaster = false)
    {
        $sSQL = 'SELECT ' . $this->_sPKIDField . ' FROM ' . $this->_sTblName;
        if ('' != $sWhere) {
            $sSQL .= ' WHERE ' . $sWhere;
        }
        return $this->_getDBData($sSQL, $aParameter, 3, $bStrictMaster);
    }

    /**
     * 设置主键的值
     *
     * @param unknown $sValue
     */
    public function setPKIDValue ($sValue)
    {
        $this->__set($this->_sPKIDField, $sValue);
    }

    /**
     * 根据主键值获取一行数据
     *
     * @param unknown $p_sValue
     * @return Ambigous <array/null, NULL, multitype:>
     */
    public function getRowByPKID ($sValue)
    {
        $aList = $this->getListByPKIDs($sValue, true);
        return isset($aList[$sValue]) ? $aList[$sValue] : null;
    }

    /**
     * 获取多行数据
     *
     * @param string $sWhere
     * @param array $aParameter
     * @return array
     */
    public function getList ($sWhere = '', $aParameter = array(), $bUsePKID, $bStrictMaster = false)
    {
        $sSQL = 'SELECT ' . $this->_sPKIDField . ' FROM ' . $this->_sTblName;
        if ('' != $sWhere) {
            $sSQL .= ' WHERE ' . $sWhere;
        }
        $aPKID = false;
        if ($this->_bWhereCache) {
            $aCacheKey = $this->_getWhereCache($sSQL, $aParameter);
            $aPKID = $this->_getCache()->hGet($aCacheKey[0], $aCacheKey[1]);
        }
        if ($aPKID === false) {
            $aList = $this->_getDBData($sSQL, $aParameter, 3, $bStrictMaster);
            $aPKID = array();
            if (! empty($aList)) {
                foreach ($aList as $v) {
                    $aPKID[] = $v[$this->_sPKIDField];
                }
            }
            if ($this->_bWhereCache) {
                $this->_getCache()->hSet($aCacheKey[0], $aCacheKey[1], $aPKID);
            }
        }
        if (count($aPKID) > 0) {
            return $this->getListByPKIDs($aPKID, $bUsePKID, $bStrictMaster);
        } else {
            return array();
        }
    }

    /**
     * 根据PKID获取数据
     *
     * @param mix $mIDs
     * @param boolean $bStrictFreshCache
     * @return array
     */
    public function getListByPKIDs ($mIDs, $bUsePKID = false, $bStrictMaster = false)
    {
        if ('' == $mIDs or empty($mIDs)) {
            return array();
        }
        if (! is_array($mIDs)) {
            $mIDs = explode(',', $mIDs);
        }
        $aRS = array();
        $iCnt = count($mIDs);
        $aCacheKey = array();
        $aCacheRS = array();
        if ($this->_bNeedCache) {
            $this->_getCache();
            for ($i = 0; $i < $iCnt; ++ $i) {
                $aTmp = $this->_getCacheRowKey($mIDs[$i]);
                $aCacheKey[0] = $aTmp[0];
                $aCacheKey[1][] = $aTmp[1];
            }
            $aCacheRows = $this->_oCache->hMGet($aCacheKey[0], $aCacheKey[1]);
            foreach ($aCacheRows as $k => $v) {
                $aCacheRS[$v[$this->_sPKIDField]] = $v;
            }
            unset($aCacheRows);
        }
        // 判断缓存是否读取成功，并记录不成功的PKID
        $aMissedID = array();
        for ($i = 0; $i < $iCnt; ++ $i) {
            if (isset($aCacheRS[$mIDs[$i]])) {
                $aRS[$mIDs[$i]] = $aCacheRS[$mIDs[$i]];
            } else {
                $aRS[$mIDs[$i]] = false; // 保持顺序
                $aMissedID[] = $mIDs[$i];
            }
        }

        $iCnt = count($aMissedID);
        if ($iCnt > 0) {

            if ($this->_aTblField[$this->_sPKIDField]['PDOTYPE'] == PDO::PARAM_INT) {
                $sValue = join(',', $aMissedID);
            } else {
                $sValue = "'" . join("','", $aMissedID) . "'";
            }
            // 生成SQL并查询
            $sSQL = 'SELECT * FROM `' . $this->_sTblName . '`' . ' WHERE `' . $this->_sPKIDField . '` IN (' . $sValue . ')';
            $aData = $this->_getDBData($sSQL, array(), 3, $bStrictMaster);

            // 获取结果，并生成缓存
            foreach ($aData as $aRow) {
                $aRS[$aRow[$this->_sPKIDField]] = $aRow;
                $this->_setRowCache($aRow[$this->_sPKIDField], $aRow);
            }
        }

        $aRS = array_filter($aRS);
        if (! $bUsePKID) {
            $aRS = array_values($aRS);
        }
        return $aRS;
    }

    /**
     * 得到统计数据
     *
     * @param string $sWhere
     * @param array $aParameter
     * @param boolean $bStrictFreshCache
     * @return int
     */
    public function getCnt ($sWhere = '', $aParameter = array(), $bStrictMaster = false)
    {
        $sSQL = 'SELECT COUNT(*) AS cnt FROM ' . $this->_sTblName;
        if ('' != $sWhere) {
            $sSQL .= ' WHERE ' . $sWhere;
        }
        $iCnt = false;
        if ($this->_bWhereCache) {
            $aCacheKey = $this->_getWhereCache($sSQL, $aParameter);
            $iCnt = $this->_getCache()->hGet($aCacheKey[0], $aCacheKey[1]);
        }
        if ($iCnt === false) {
            $iCnt = $this->_getDBData($sSQL, $aParameter, 1, $bStrictMaster);
            if ($this->_bWhereCache) {
                $this->_getCache()->hSet($aCacheKey[0], $aCacheKey[1], $iCnt);
            }
        }
        return $iCnt;
    }

    /**
     * 批量更新数据
     *
     * @param string $sWhere
     * @param array $aParameter
     * @return int
     */
    public function updList ($sWhere, $aParameter = array())
    {
        $sSQL = 'SELECT ' . $this->_sPKIDField . ' FROM ' . $this->_sTblName;
        if ('' != $sWhere) {
            $sSQL .= ' WHERE ' . $sWhere;
        }
        $aPKIDs = $this->_getDBData($sSQL, $aParameter, 3, true);
        $iCnt = count($aPKIDs);
        if (0 < $iCnt) {
            $aPKID = array();
            for ($i = 0; $i < $iCnt; ++ $i) {
                $aPKID[] = $aPKIDs[$i][$this->_sPKIDField];
            }
            return $this->updListByPKIDs($aPKID);
        } else {
            return 0;
        }
    }

    /**
     * 根据PKID更新数据
     *
     * @param mix $mIDs
     * @return int
     */
    public function updListByPKIDs ($mIDs)
    {
        if ('' == $mIDs or empty($mIDs)) {
            return 0;
        }
        $mIDs = is_array($mIDs) ? $mIDs : explode(',', $mIDs);
        $aSQLParam = $this->_joinUpdString($this->_aTblField, $this->_aData, $this->_sPKIDField);
        $iCnt = count($mIDs);
        $aPKIDsPattern = array();
        for ($i = 0; $i < $iCnt; ++ $i) {
            $aSQLParam['VALUEARR'][$this->_sPKIDField . '_' . $i]['VALUE'] = $mIDs[$i];
            $aSQLParam['VALUEARR'][$this->_sPKIDField . '_' . $i]['FIELD'] = $this->_sPKIDField;
            $aPKIDsPattern[] = ':' . $this->_sPKIDField . '_' . $i;
            $this->clearRowCache($mIDs[$i]);
        }
        $strPKIDsPattern = join(',', $aPKIDsPattern);
        $sSQL = 'UPDATE ' . $this->_sTblName . ' SET ' . $aSQLParam['FIELDSSTR'] . ' WHERE ' . $this->_sPKIDField . ' IN (' . $strPKIDsPattern . ')';
        $iAffectedCnt = $this->_exectue($sSQL, $aSQLParam['VALUEARR'], true, true);
        return $iAffectedCnt;
    }

    /**
     * 执行SQL,此方法不提供Cache功能
     *
     * @param string $sSQL
     * @param array $aParameter
     * @param string $sFetchType
     *            ALL/ROW/ONE
     * @return array
     */
    public function query ($sSQL, $aParameter = array(), $sFetchType = 'ALL', $bStrictMaster = false)
    {
        $aSQLInfo = array();
        if (preg_match('/^([a-z]+)/i', trim($sSQL), $aSQLInfo)) {
            $sSQLType = strtolower($aSQLInfo[1]);
            switch ($sSQLType) {
                case 'insert':
                    return $this->_insert($sSQL, $aParameter);
                    break;
                case 'delete':
                case 'update':
                case 'truncate':
                case 'alter':
                    return $this->_exectue($sSQL, $aParameter, true, true);
                    break;
                default:
                    switch (strtoupper($sFetchType)) {
                        case 'ONE':
                            return $this->_getDBData($sSQL, $aParameter, 1, $bStrictMaster);
                            break;
                        case 'ROW':
                            return $this->_getDBData($sSQL, $aParameter, 2, $bStrictMaster);
                            break;
                        case 'ALL':
                        default:
                            return $this->_getDBData($sSQL, $aParameter, 3, $bStrictMaster);
                    }
            }
        } else {
            throw new Exception('不被允许的SQL: ' . $this->_sLastSQL);
        }
    }

    /**
     * 得到最后执行的SQL语句
     *
     * @return string
     */
    public function getLastSQL ()
    {
        return $this->_sLastSQL;
    }

    /**
     * 返回数据库表结构
     */
    protected function _getTblField ()
    {
        if (empty($this->_aTblField)) {
            $aTblField = $this->_getFieldCache();
            if (false === $aTblField) {
                $sSQL = 'DESC ' . $this->_sTblName;
                $aTblField = $this->_getDBData($sSQL, array(), 3, false);
                $this->_setFieldCache($aTblField);
            }
            $iFieldCnt = count($aTblField);
            for ($i = 0; $i < $iFieldCnt; ++ $i) {
                $aTmpFieldInfo = $this->_field2ORMField($aTblField[$i]['Type']);
                $this->_aTblField[$aTblField[$i]['Field']] = array(
                    'TYPE' => $aTmpFieldInfo['TYPE'],
                    'PDOTYPE' => $aTmpFieldInfo['PDOTYPE'],
                    'LENGTH' => isset($aTmpFieldInfo['LENGTH']) ? $aTmpFieldInfo['LENGTH'] : 0,
                    'NULLABLE' => ('YES' == $aTblField[$i]['Null'] ? true : false)
                );
                if ('PRI' == $aTblField[$i]['Key']) {
                    $this->_sPKIDField = $aTblField[$i]['Field'];
                }
            }
        }
    }

    /**
     * 获取缓存连接
     */
    protected function _getCache ()
    {
        if (null == $this->_oCache) {
            $this->_oCache = Util_Common::getRedis('orm');
        }
        return $this->_oCache;
    }

    /**
     * 获取MasterDB连接
     */
    protected function _getMasterDB ()
    {
        if (null == $this->_oMasterDB) {
            $iStartTime = microtime(true);
            $this->_oMasterDB = Util_Common::getDb($this->_sDbName, 'master');
            self::$_iConnentTime += round((microtime(true) - $iStartTime) * 1000, 2);
        }
        return $this->_oMasterDB;
    }

    /**
     * 获取SlaveDB连接
     */
    protected function _getSlaveDB ()
    {
        if (null == $this->_oSlaveDB) {
            $iStartTime = microtime(true);
            if ($this->_bStrictMaster) {
                $this->_oSlaveDB = Util_Common::getDb($this->_sDbName, 'master');
            } else {
                $this->_oSlaveDB = Util_Common::getDb($this->_sDbName, 'salve');
            }
            self::$_iConnentTime += round((microtime(true) - $iStartTime) * 1000, 2);
        }
        return $this->_oSlaveDB;
    }

    /**
     * 获取表结构
     *
     * @return string
     */
    public function getTblField ()
    {
        return $this->_aTblField;
    }

    /**
     * mysql字段信息转换为ORM字段信息
     *
     * @param string $sField
     * @todo 确认各种PDOTYPE
     * @return array
     */
    private function _field2ORMField ($sField)
    {
        $aTmpFieldInfo = array();
        preg_match('/(tinyint|smallint|mediumint|int|bigint|char|varchar|text|timestamp|datetime|date|float|decimal|enum|double|mediumtext|binary)\(?(\d*)\)?\s*(unsigned)?/', $sField, $aTmpFieldInfo);
        $aFieldInfo = array();
        switch ($aTmpFieldInfo[1]) {
            case 'tinyint':
                $aFieldInfo['TYPE'] = 'i';
                $aFieldInfo['LENGTH'] = 3;
                $aFieldInfo['PDOTYPE'] = PDO::PARAM_INT;
                break;
            case 'smallint':
                $aFieldInfo['TYPE'] = 'i';
                $aFieldInfo['LENGTH'] = 6;
                $aFieldInfo['PDOTYPE'] = PDO::PARAM_INT;
                break;
            case 'mediumint':
                $aFieldInfo['TYPE'] = 'i';
                $aFieldInfo['LENGTH'] = 9;
                $aFieldInfo['PDOTYPE'] = PDO::PARAM_INT;
                break;
            case 'int':
                $aFieldInfo['TYPE'] = 'i';
                $aFieldInfo['LENGTH'] = 11;
                $aFieldInfo['PDOTYPE'] = PDO::PARAM_INT;
                break;
            case 'bigint':
                $aFieldInfo['TYPE'] = 'i';
                $aFieldInfo['LENGTH'] = 20;
                $aFieldInfo['PDOTYPE'] = PDO::PARAM_INT;
                break;
            case 'char':
                $aFieldInfo['TYPE'] = 's';
                $aFieldInfo['LENGTH'] = $aTmpFieldInfo[2];
                $aFieldInfo['PDOTYPE'] = PDO::PARAM_STR;
                break;
            case 'varchar':
                $aFieldInfo['TYPE'] = 's';
                $aFieldInfo['LENGTH'] = $aTmpFieldInfo[2];
                $aFieldInfo['PDOTYPE'] = PDO::PARAM_STR;
                break;
            case 'text':
                $aFieldInfo['TYPE'] = 's';
                $aFieldInfo['LENGTH'] = 20000;
                $aFieldInfo['PDOTYPE'] = PDO::PARAM_LOB;
                break;
            case 'mediumtext':
                $aFieldInfo['TYPE'] = 's';
                $aFieldInfo['LENGTH'] = 5000000;
                $aFieldInfo['PDOTYPE'] = PDO::PARAM_LOB;
                break;
            case 'timestamp':
                $aFieldInfo['TYPE'] = 's';
                $aFieldInfo['LENGTH'] = 19;
                $aFieldInfo['PDOTYPE'] = PDO::PARAM_STR;
                break;
            case 'datetime':
                $aFieldInfo['TYPE'] = 's';
                $aFieldInfo['LENGTH'] = 19;
                $aFieldInfo['PDOTYPE'] = PDO::PARAM_STR;
                break;
            case 'date':
                $aFieldInfo['TYPE'] = 's';
                $aFieldInfo['LENGTH'] = 10;
                $aFieldInfo['PDOTYPE'] = PDO::PARAM_STR;
                break;
            case 'float':
                $aFieldInfo['TYPE'] = 'i';
                $aFieldInfo['LENGTH'] = 10;
                $aFieldInfo['PDOTYPE'] = PDO::PARAM_STR;
                break;
            case 'decimal':
                $aFieldInfo['TYPE'] = 'i';
                $aFieldInfo['LENGTH'] = 10;
                $aFieldInfo['PDOTYPE'] = PDO::PARAM_STR;
                break;
            case 'enum':
                $aFieldInfo['TYPE'] = 's';
                $aFieldInfo['PDOTYPE'] = PDO::PARAM_STR;
                break;
            case 'double':
                $aFieldInfo['TYPE'] = 's';
                $aFieldInfo['PDOTYPE'] = PDO::PARAM_STR;
                break;
            case 'binary':
                $aFieldInfo['TYPE'] = 's';
                $aFieldInfo['LENGTH'] = 100;
                $aFieldInfo['PDOTYPE'] = PDO::PARAM_STR;
                break;
            default:
                preg_match('/(float|decimal)\((\d*),(\d*)\)/', $sField, $aTmpFieldInfo);
                switch ($aTmpFieldInfo[1]) {
                    case 'float':
                        $aFieldInfo['TYPE'] = 'f';
                        $aFieldInfo['LENGTH'] = $aTmpFieldInfo[2] + $aTmpFieldInfo[3] + 1;
                        $aFieldInfo['PDOTYPE'] = PDO::PARAM_STR;
                        break;
                    case 'decimal':
                        $aFieldInfo['TYPE'] = 'f';
                        $aFieldInfo['LENGTH'] = $aTmpFieldInfo[2] + $aTmpFieldInfo[3] + 1;
                        $aFieldInfo['PDOTYPE'] = PDO::PARAM_STR;
                    default:
                        throw new Exception('不知道的MySQL字段(' . $sField . ').');
                        break;
                }
                break;
        }
        if (isset($aTmpFieldInfo[3]) and 'unsigned' == $aTmpFieldInfo[3]) {
            ++ $aFieldInfo['LENGTH'];
        }
        return $aFieldInfo;
    }

    /**
     * 获取ORM添加信息所需SQL信息
     *
     * @param array $aTblField
     * @param array $aData
     * @return array
     */
    private function _joinAddString ($aTblField, $aData)
    {
        $sFields = '';
        $strParams = '';
        $aValues = '';
        foreach ($aTblField as $sField => $aFieldSet) {
            if (isset($aData[$sField])) {
                $sFields .= ', `' . $sField . '`';
                $strParams .= ', :' . $sField;
                $aValues[$sField] = $aData[$sField];
            }
        }
        if ('' == $sFields) {
            throw new Exception('INSERT ORM is illegally.');
        } else {
            return array(
                'FIELDSSTR' => substr($sFields, 2),
                'PARAMSTR' => substr($strParams, 2),
                'VALUEARR' => $aValues
            );
        }
    }

    /**
     * 获取ORM更新信息所需SQL信息
     *
     * @param array $aTblField
     * @param array $aData
     * @param string $sPKIDField
     * @return array
     */
    private function _joinUpdString ($aTblField, $aData, $sPKIDField)
    {
        $sFields = '';
        $aValues = array();
        foreach ($aTblField as $sField => $aFieldSet) {
            if ($sField != $sPKIDField and isset($aData[$sField])) {
                $strSelfOperator = $iSelfParam = '';
                if ($this->_isSelfOperate($sField, $aData[$sField], $strSelfOperator, $iSelfParam)) {
                    $sFields .= ', `' . $sField . '` = ' . $sField . $strSelfOperator . ':' . $sField;
                    $aValues[$sField] = $iSelfParam;
                } else {
                    $sFields .= ', `' . $sField . '` = :' . $sField;
                    $aValues[$sField] = $aData[$sField];
                }
            }
        }
        if ('' == $sFields) {
            throw new Exception('UPDATE ORM is illegally.');
        } else {
            return array(
                'FIELDSSTR' => substr($sFields, 2),
                'VALUEARR' => $aValues
            );
        }
    }

    /**
     * 判断是否为自运算
     *
     * @param string $sField
     * @param mix $mValue
     * @param string $sOperator
     * @param int $iParam
     * @return boolean
     */
    protected function _isSelfOperate ($sField, $mValue, &$sOperator, &$iParam)
    {
        $strPattern = '/^' . $sField . '\s?([+\-*\/])\s?([\.\d]+)/i';
        $aResult = array();
        if (1 == preg_match($strPattern, $mValue, $aResult)) {
            $sOperator = $aResult[1];
            $iParam = $aResult[2];
            return true;
        } else {
            return false;
        }
    }

    /**
     * 绑定变量
     *
     * @param array $aParam
     */
    private function _bindParams ($aParam)
    {
        if (! empty($aParam)) {
            foreach ($aParam as $sField => $mValue) {
                if (is_array($mValue) && isset($mValue['FIELD']) && isset($mValue['VALUE'])) {
                    $trueValue = $mValue['VALUE'];
                    $trueField = $mValue['FIELD'];
                } else {
                    $trueValue = $mValue;
                    $trueField = $sField;
                }
                $this->_oSth->bindParam(':' . $sField, $trueValue, $this->_aTblField[$trueField]['PDOTYPE'], $this->_aTblField[$trueField]['LENGTH']);
                unset($mValue, $trueField, $trueValue);
            }
        }
    }

    /**
     * 获取数据库数据
     *
     * @param string $sSQL
     * @param array $aParam
     * @param int $iType
     *            1-column, 2-row, 3-list
     * @return array/string
     */
    protected function _getDBData ($sSQL, $aParam, $iType, $bStrictMaster)
    {
        $this->_exectue($sSQL, $aParam, $bStrictMaster, false);
        $this->_oSth->setFetchMode(PDO::FETCH_ASSOC);
        switch ($iType) {
            case 1:
                $mData = $this->_oSth->fetchColumn();
                break;
            case 2:
                $mData = $this->_oSth->fetch();
                break;
            case 3:
                $mData = $this->_oSth->fetchAll();
                break;
        }

        return $mData;
    }

    /**
     * 执行SQL
     *
     * @param string $sSQL
     * @param array $aParam
     * @param boolean $bStrictMaster
     * @param boolean $bIsADU
     * @return unknown
     */
    protected function _exectue ($sSQL, $aParam, $bStrictMaster, $bIsADU = false)
    {
        $iStartTime = microtime(true);
        ++ self::$_iQueryCnt;
        self::$_aSQLs[] = $this->_sLastSQL = $sSQL;
        $db = ($bStrictMaster) ? $this->_getMasterDB() : $this->_getSlaveDB();
        $this->_oSth = $db->prepare($sSQL);
        if (! empty($aParam)) {
            $this->_bindParams($aParam);
        }
        $bRet = $this->_oSth->execute();
        if (false === $bRet) {
            throw new Exception('SQL Error: ' . $this->_formatSQL($sSQL, $aParam));
            return 0;
        }
        $iUseTime = round((microtime(true) - $iStartTime) * 1000, 2);
        self::$_iUseTime += $iUseTime;
        $iAffectedRows = $this->_oSth->rowCount();
        if ($this->_oDebug) {
            $this->_oDebug->debug('[DB->' . $this->_sDbName . ']: ' . $this->_formatSQL($sSQL, $aParam) . ' AffectedRows:' . $iAffectedRows . ' Use Time:' . $iUseTime . '毫秒');
        }
        if ($iAffectedRows > 0 && $bIsADU) {
            $this->clearWhereCache();
        }
        return $iAffectedRows;
    }

    /**
     * 格式化sql
     *
     * @param unknown $sSQL
     * @param unknown $aParam
     * @return mixed
     */
    protected function _formatSQL ($sSQL, $aParam)
    {
        if (! empty($aParam)) {
            foreach ($aParam as $k => $v) {
                if (is_array($v)) {
                    if (isset($v['FIELD']) && isset($v['VALUE'])) {
                        $v = $v['VALUE'];
                    } else {
                        $v = join(',', $v);
                    }
                }
                $sSQL = str_replace(':' . $k, "'$v'", $sSQL);
            }
        }
        return $sSQL;
    }

    /**
     * 减少一个PHP进程占用的内存
     *
     * @author yj
     */
    public function clearSQLs ()
    {
        parent::$_aSQLs = array();
    }

    // ================================================= //
    // *************以下为大唐增加的功能*********************** //
    // ================================================= //
    /**
     * Do Insert
     *
     * @param
     *            $params
     * @return last_insert_id
     */
    public function insert ($params = array())
    {
        $this->_bindField($params);
        return $this->addData();
    }

    /**
     * Do Update by PKID
     *
     * @author lisumliang
     * @param
     *            $params
     * @return int
     */
    public function update ($params = array())
    {
        $this->_bindField($params);
        return $this->updData();
    }

    /**
     * bind field by __get()
     *
     * @param array $params
     */
    private function _bindField ($params)
    {
        $aFields = $this->getTblField();
        foreach ($aFields as $key => $value) {
            if (isset($params[$key])) {
                $this->$key = $params[$key];
            } else {
                unset($this->$key);
            }
        }
    }

    /**
     * Creating SQL statements
     * $opts = array(
     * 'where' => array(
     * 'brokerid >' => 1,
     * 'cityid IN' => '1,2,3',
     * 'status <>' => 0,
     * 'title' => 'test title'
     * ),
     * 'group' => 'cityid',
     * 'order' => '`id` DESC',
     * 'limit' => '10'
     * );
     * //sql above will return "where brokerid > 1 and cityid in (1,2,3) and status <>0 and title='测试标题'"
     * $opts = array(
     * 'where' => '`age` = ? OR `age` = ?',
     * 'params' => array(24, 25),
     * 'limit' => '10,20'
     * );
     * //NOT accept 'OR' yet, so use string as 'where' param if you need 'OR' logic.
     *
     * @param array $opts
     * @param string $clause
     * @param array $params
     */
    protected function parseClause ($opts, &$clause, &$params)
    {
        $clause = array();
        if (! empty($opts['where'])) {
            if (is_array($opts['where'])) {
                $tmp_where = array();
                $where_keys = array();
                foreach ($opts['where'] as $field => $value) {
                    $a_key = explode(' ', trim($field));
                    $where_keys[$a_key[0]][] = $field;
                    if (count($a_key) == 1) {
                        $tmp_where[$field] = array(
                            $value,
                            '='
                        );
                    } else {
                        if (preg_match('/[a-zA-Z]+/', $a_key[1])) {
                            $a_key[1] = strtoupper($a_key[1]);
                        }
                        if (in_array($a_key[1], self::$_aComparisonOperators)) {
                            $tmp_where[$field] = array(
                                $value,
                                $a_key[1]
                            );
                        } else {
                            $tmp_where[$field] = array(
                                $value,
                                '='
                            );
                        }
                    }
                }
                $where = $params = array();
                foreach ($this->_aTblField as $key => $value) {
                    if (isset($where_keys[$key])) {
                        foreach ($where_keys[$key] as $k => $v) {
                            switch ($tmp_where[$v][1]) {
                                case 'IN':
                                    $where[] = '`' . $key . '` IN (' . (is_array($tmp_where[$v][0]) ? implode(',', $tmp_where[$v][0]) : $tmp_where[$v][0]) . ')';
                                    break;
                                case 'NOT':
                                    $where[] = '`' . $key . '` NOT IN (' . (is_array($tmp_where[$v][0]) ? implode(',', $tmp_where[$v][0]) : $tmp_where[$v][0]) . ')';
                                    break;
                                case 'LIKE':
                                    $where[] = '`' . $key . '` LIKE "' . $tmp_where[$v][0] . '"';
                                    break;
                                default:
                                    $where[] = '`' . $key . '` ' . $tmp_where[$v][1] . ' :' . $key . $k;
                                    $params[$key . $k] = [
                                        'FIELD' => $key,
                                        'VALUE' => $tmp_where[$v][0]
                                    ];
                                    break;
                            }
                        }
                    }
                }
                if (isset($opts['where']['sWhere'])) {
                    $where[] = $opts['where']['sWhere'];
                }
                $clause[] = implode(' AND ', $where);
            } else {
                $clause[] = $opts['where'];
                $params = isset($opts['params']) ? $opts['params'] : array();
            }
        } else {
            $clause[] = '1';
            $params = array();
        }

        if (! empty($opts['group'])) {
            $clause[] = 'GROUP BY ' . $opts['group'];
        }

        if (! empty($opts['order'])) {
            $clause[] = 'ORDER BY ' . $opts['order'];
        }

        if (! empty($opts['limit'])) {
            if (strpos($opts['limit'], ',') === false) {
                $opts['limit'] = '0,' . $opts['limit'];
            }
            $clause[] = 'LIMIT ' . $opts['limit'];
        } else {
            $clause[] = 'LIMIT 0,10000';
        }

        $clause = implode(' ', $clause);
    }

    /**
     * 获取多行数据，默认刷新列缓存，不刷新行缓存
     * $opts = array(
     * ...
     * );
     *
     * @param array $opts
     * @return array
     */
    public function fetchAll ($opts = array(), $bUsePKID = false, $bStrictMaster = false)
    {
        $clause = $params = null;
        $this->parseClause($opts, $clause, $params);
        return $this->getList($clause, $params, $bUsePKID, $bStrictMaster);
    }

    /**
     * 取得键值对
     *
     * @param unknown $opts
     * @return multitype:multitype:
     */
    public function fetchCol ($opts, $sField, $bStrictMaster = false)
    {
        $aList = $this->fetchAll($opts, true, $bStrictMaster);
        $aCol = array();
        foreach ($aList as $aRow) {
            $aCol[] = $aRow[$sField];
        }
        return $aCol;
    }

    /**
     * 取得键值对
     *
     * @param unknown $opts
     * @return multitype:multitype:
     */
    public function fetchPair ($opts, $kField, $vField, $bStrictMaster = false)
    {
        $aList = $this->fetchAll($opts, true, $bStrictMaster);
        $aPair = array();
        foreach ($aList as $aRow) {
            $aPair[$aRow[$kField]] = $aRow[$vField];
        }
        return $aPair;
    }

    /**
     * 获取单行数据
     * $opts = array(
     * ...
     * );
     *
     * @param array $opts
     * @return array
     */
    public function fetchOne ($opts, $sField, $bStrictMaster = false)
    {
        $opts['limit'] = '0,1';
        $aRow = current($this->fetchAll($opts, true, $bStrictMaster));
        return isset($aRow[$sField]) ? $aRow[$sField] : null;
    }

    /**
     * 获取单行数据
     * $opts = array(
     * ...
     * );
     *
     * @param array $opts
     * @return array
     */
    public function fetchRow ($opts, $bStrictMaster = false)
    {
        if (! isset($opts['limit'])) {
            $opts['limit'] = '0,1';
        }
        return current($this->fetchAll($opts, true, $bStrictMaster));
    }

    /**
     * 获取行数，可刷新缓存
     * $opts = array(
     * ...
     * );
     *
     * @param array $opts
     * @return integer
     */
    public function fetchCnt ($opts = array(), $bStrictMaster = false)
    {
        $clause = $params = null;
        $this->parseClause($opts, $clause, $params);
        return $this->getCnt($clause, $params, $bStrictMaster) + 0;
    }

    /**
     * 清空所有属性
     */
    public function resetFields ()
    {
        foreach ($this->getTblField() as $key => $value) {
            unset($this->$key);
        }
    }

    /**
     * 获取表字段
     */
    public function filterFields ($params)
    {
        $aFields = $this->getTblField();

        foreach ($aFields as $key => $value) {
            if (! isset($params[$key])) {
                unset($params[$key]);
            }
        }
        return $params;
    }

    /**
     * 事务开始
     */
    public function begin ()
    {
        if (self::$_iCommitCnt == 0) {
            $this->_getMasterDB()->query('BEGIN');
        }
        self::$_iCommitCnt ++;
    }

    /**
     * 事务提交
     */
    public function commit ()
    {
        self::$_iCommitCnt --;
        if (self::$_iCommitCnt == 0) {
            return $this->_getMasterDB()->query('COMMIT');
        }
    }

    /**
     * 事务回滚
     */
    public function rollback ()
    {
        self::$_iCommitCnt = 0;
        return $this->_getMasterDB()->query('ROLLBACK');
    }
}
