<?php

/**
 * 数据操作类
 *
 * @author Jack Xie <xiejinci@gmail.com>
 * @copyright 2006-2010 1lele.com
 * $Id: Db.php 8 2010-03-18 08:34:29Z xiejc $
 * @history
 */
class Db_MsSQL
{

    private $aConf;

    private $bIsPersistent = false;

    /**
     * 事务计数
     * 
     * @var int
     */
    private $iTransaction = 0;

    /**
     * 存放当前连接
     * 
     * @var object
     */
    private $rLink = null;

    private $bUseCommit = false;

    /**
     * 执行sql数组
     */
    private $aSQL = array();
    
    private $iPingTime = 0;

    /**
     * 构造函数
     * 
     * @param array $aConf            
     * @param array $is_static            
     * @param bool $bIsPersistent            
     * @return void
     */
    public function __construct ($aConf, $bIsPersistent = false)
    {
        $this->iPingTime = time();
        $this->aConf = $aConf;
        $this->bIsPersistent = $bIsPersistent;
        $this->connect();
    }

    public function connect ()
    {
        if ($this->bIsPersistent) {
            $rLink = mssql_pconnect($this->aConf['host'], $this->aConf['user'], $this->aConf['pass']);
        } else {
            $rLink = mssql_connect($this->aConf['host'], $this->aConf['user'], $this->aConf['pass'], true);
        }
        if (! $rLink) {
            throw new Exception($this->aConf['host'] . ' connect failed', 999);
        }
        // mssql_query( 'set names utf8', $rLink );
        
        if (! mssql_select_db($this->aConf['dbname'], $rLink)) {
            throw new Exception($this->aConf['host'] . '.' . $this->aConf['dbname'] . ' database is not exist', 999);
        }
        $this->rLink = $rLink;
    }

    /**
     * 释放数据库连接
     * 
     * @return void
     */
    public function close ()
    {
        if (is_resource($this->rLink)) {
            mssql_close($this->rLink);
        }
    }

    /**
     * 查询操作的底层接口
     * 
     * @param string $sql
     *            要执行查询的SQL语句
     * @return Object
     */
    public function execute ($sql)
    {
        if (time() - $this->iPingTime > 300) {
            $this->close();
            $this->connect();
            $this->iPingTime = time();
        }
        
        $res = mssql_query($sql, $this->rLink);
        if ($res === false) {
            throw new Exception($sql);
            // echo $sql;exit;
        }
        return $res;
    }

    /**
     * 自动执行操作(针对Insert/Update操作)
     * 
     * @param string $sql            
     * @return int 影响的行数
     */
    public function query ($sql)
    {
        $this->execute($sql);
        return mssql_rows_affected($this->rLink);
    }

    /**
     * 取得所有数据
     * 
     * @param string $sql
     *            SQL语句
     * @param string $field
     *            以字段做为数组的key
     * @return array
     */
    public function getAll ($sql, $field = null)
    {
        $res = $this->execute($sql);
        if (! $res) {
            return array();
        }
        $rows = array();
        if (null == $field) {
            while ($row = mssql_fetch_assoc($res)) {
                $rows[] = $row;
            }
        } else {
            while ($row = mssql_fetch_assoc($res)) {
                $rows[$row[$field]] = $row;
            }
        }
        mssql_free_result($res);
        return $rows;
    }

    /**
     * 以get_row方式取得所有数据
     * 
     * @param string $sql
     *            SQL语句
     * @param int $index
     *            以字段做为数组的key
     * @return array
     */
    public function get_all_row ($sql, $index = -1)
    {
        $res = $this->execute($sql);
        if (! $res) {
            return array();
        }
        $rows = array();
        if (- 1 == $index) {
            while ($row = mssql_fetch_row($res)) {
                $rows[] = $row;
            }
        } else {
            while ($row = mssql_fetch_row($res)) {
                $rows[$row[$index]] = $row;
            }
        }
        mssql_free_result($res);
        return $rows;
    }

    /**
     * 取得指定条数的数据
     * 
     * @param string $sql
     *            SQL语句
     * @param int $offset
     *            LIMIT的第一个参数
     * @param int $limit
     *            LIMIT的第二个参数
     * @param string $field
     *            以字段做为数组的key
     * @return array
     */
    public function get_limit ($sql, $offset, $limit, $field = null)
    {
        $limit = intval($limit);
        if ($limit <= 0) {
            return array();
        }
        $offset = intval($offset);
        if ($offset < 0) {
            return array();
        }
        $sql = $sql . ' LIMIT ' . $limit;
        if ($offset > 0) {
            $sql .= ' OFFSET ' . $offset;
        }
        return $this->getAll($sql, $field);
    }

    /**
     * 返回所有记录中以第一个字段为值的数组
     * 
     * @param string $sql
     *            SQL语句
     * @param bool $isMaster
     *            主从
     * @return array
     */
    public function getCol ($sql)
    {
        $res = $this->execute($sql);
        if (! $res) {
            return array();
        }
        $rows = array();
        while ($row = mssql_fetch_row($res)) {
            $rows[] = $row[0];
        }
        mssql_free_result($res);
        return $rows;
    }

    /**
     * 返回所有记录中以第一个字段为key,第二个字段为值的数组
     * 
     * @param string $sql
     *            SQL语句
     * @return array
     */
    public function getPair ($sql)
    {
        $res = $this->execute($sql);
        if (! $res) {
            return array();
        }
        $rows = array();
        while ($row = mssql_fetch_row($res)) {
            $rows[$row[0]] = $row[1];
        }
        mssql_free_result($res);
        return $rows;
    }

    /**
     * 取得第一条记录
     * 
     * @param string $sql
     *            SQL语句
     * @return array
     */
    public function getRow ($sql)
    {
        $res = $this->execute($sql);
        if (! $res) {
            return null;
        }
        $row = mssql_fetch_assoc($res);
        mssql_free_result($res);
        return $row;
    }

    /**
     * 取得第一条记录的第一个字段值
     * 
     * @param string $sql
     *            SQL语句
     * @return int string
     */
    public function getOne ($sql)
    {
        $res = $this->execute($sql);
        if (! $res) {
            return null;
        }
        $row = mssql_fetch_row($res);
        mssql_free_result($res);
        return $row[0];
    }

    /**
     * 替换操作
     * @param unknown $table
     * @param unknown $row
     * @param string $quote
     * @return number
     */
    public function replace ($table, $row, $quote = false)
    {
        return $this->insert($table, $row, $quote, 'REPLACE');
    }

    /**
     * 插入一条记录
     * 
     * @param string $table 表数
     * @param array $row 数据
     * @param bool $quote 是否进行数据过滤
     * @return int 影响的条数
     */
    public function insert ($table, $row, $quote = false, $type = 'INSERT')
    {
        $cols = array();
        $vals = array();
        if (false == $quote) {
            foreach ($row as $col => $val) {
                $cols[] = $col;
                $vals[] = $val;
            }
        } else {
            foreach ($row as $col => $val) {
                $cols[] = $col;
                $vals[] = $this->quote($val);
            }
        }
        $sql = $type . ' INTO `' . $table . '`' . '(`' . join('`, `', $cols) . '`) ' . 'VALUES (\'' . join('\',\'', $vals) . '\')';
        return $this->query($sql);
    }

    /**
     * 插入一批数据库
     * 
     * @param string $table
     *            表名
     * @param array $rows
     *            数据列表 array( array( 'field1'=>$val1, 'field2'=>$val2, ... ), array( 'field1'=>$val1, 'field2'=>$val2, ...), ... )
     * @param string $type
     *            插入类型(INSERT|REPLACE)
     * @param bool $quote
     *            是否进行数据过滤
     * @param bool $return_sql
     *            如果启用，则无数据库操作，仅返回SQL字符串。
     * @return int 影响的条数
     */
    public function insertRows ($table, $rows, $type = 'INSERT', $quote = false)
    {
        if (empty($rows)) {
            return true;
        }
        $cols = array();
        $vals = array();
        foreach ($rows as $n => $row) {
            $arr = array();
            if (false == $quote) {
                foreach ($row as $col => $val) {
                    if (0 == $n) {
                        $cols[] = $col;
                    }
                    $arr[] = $val;
                }
            } else {
                foreach ($row as $col => $val) {
                    if (0 == $n) {
                        $cols[] = $col;
                    }
                    $arr[] = $this->quote($val);
                }
            }
            $vals[] = '(\'' . join('\', \'', $arr) . '\')';
        }
        $sql = $type . ' INTO `' . $table . '`(`' . join('`, `', $cols) . '`) VALUES' . join(', ', $vals);
        return $this->query($sql);
    }

    /**
     * 数据更新
     * 
     * @param string $table
     *            表名
     * @param array $data
     *            记录
     * @param string $where
     *            更新条件
     * @param bool $quote
     *            是否进行过滤
     * @return int 影响的条数
     */
    public function update ($table, $data, $where = '', $quote = false)
    {
        $sets = array();
        if (false == $quote) {
            foreach ($data as $col => $val) {
                $sets[] = '`' . $col . '` = \'' . $val . '\'';
            }
        } else {
            foreach ($data as $col => $val) {
                $sets[] = '`' . $col . '` = \'' . $this->quote($val) . '\'';
            }
        }
        
        $sql = 'UPDATE `' . $table . '`' . ' SET ' . implode(', ', $sets) . (($where) ? ' WHERE ' . $where : '');
        return $this->query($sql);
    }

    /**
     * 删除数据
     * 
     * @param string $table
     *            表名
     * @param string $where
     *            条件
     * @return int
     */
    public function delete ($table, $where)
    {
        return $this->query('DELETE FROM ' . $table . ' WHERE ' . $where);
    }

    /**
     * 取得最后的lastInsertId
     * 
     * @return int
     */
    public function lastInsertId ()
    {
        return $this->getOne('SELECT @@identity');
    }

    /**
     * 数据过滤
     * 
     * @param mixed $value
     *            要过滤的值
     * @return string
     */
    public function quote ($value)
    {
        if (is_int($value)) {
            return $value;
        } elseif (is_float($value)) {
            return sprintf('%F', $value);
        }
        return addcslashes($value, "\000\n\r\\'\"\032");
    }

    /**
     * 事务开始
     * 
     * @param bool $no_check            
     * @return bool
     */
    public function begin ()
    {
        if ($this->iTransaction == 0) {
            if ($this->bUseCommit) {
                throw new Exception('本次操作里已经使用了一次事务。', 3);
            }
            $this->execute('BEGIN');
            $this->bUseCommit = true;
        }
        $this->iTransaction ++;
        return true;
    }

    /**
     * 事务提交
     */
    public function commit ()
    {
        if ($this->iTransaction < 1) {
            throw new Exception('出错啦！事务不配对！', 3);
        }
        $this->iTransaction --;
        if (0 == $this->iTransaction) {
            $this->execute('COMMIT');
        }
        return true;
    }

    /**
     * 事务回滚
     */
    public function rollBack ()
    {
        $this->execute('ROLLBACK');
        $this->iTransaction = 0;
        $this->bUseCommit = false;
        return true;
    }

    public function __destruct ()
    {
        $this->close();
    }
}