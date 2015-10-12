<?php

class Db_PDO extends PDO
{
/*
    private $oDebug = null;

    private $sDefaultFetchMode = PDO::FETCH_BOTH;

    public $sDsn = null;

    public function __construct ($dsn, $username = "", $password = "", $driver_options = array())
    {
        parent::__construct($dsn, $username, $password, $driver_options);
        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array(
            'Db_PDOStatement',
            array(
                $this
            )
        ));
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $this->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        $this->sDsn = $dsn;
        $this->oDebug = Util_Common::getDebug();
    }

    public function exec ($statement)
    {
        if ($this->oDebug) {
            $this->oDebug->add(__CLASS__ . '[' . $this->sDsn . ']' . "->exec: $statement");
        }
        $stmt = parent::exec($statement);
        if ($stmt instanceof PDOStatement) {
            $stmt->setFetchMode($this->sDefaultFetchMode);
        } else {
            $error_info = parent::errorInfo();
            if (parent::errorCode() !== '00000') {
                trigger_error($statement . ' | ' . join(' | ', $error_info), E_USER_ERROR);
            }
        }
        return $stmt;
    }

    public function prepare ($statement, $driver_options = array())
    {
        $stmt = parent::prepare($statement, $driver_options);
        if ($stmt instanceof PDOStatement) {
            $stmt->setFetchMode($this->sDefaultFetchMode);
        }
        return $stmt;
    }

    public function query ($statement, $pdo = NULL, $object = NULL)
    {
        if ($this->oDebug) {
            $this->oDebug->add(__CLASS__ . '[' . $this->sDsn . ']' . "->query: $statement");
        }
        if ($pdo != NULL && $object != NULL) {
            $stmt = parent::query($statement, $pdo, $object);
        } else {
            $stmt = parent::query($statement);
        }
        if ($stmt instanceof PDOStatement) {
            $stmt->setFetchMode($this->sDefaultFetchMode);
        }
        return $stmt;
    }
*/
}
