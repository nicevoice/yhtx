<?php
abstract class Sftp_Base
{
    private $errno_  = 0;
    private $errstr_ = '';

    protected function setErr_($errno, $errstr)
    {
        $this->errno_  = $errno;
        $this->errstr_ = $errstr;
    }

    /**
     * get errno of last operation
     *
     * @return errno
     */
    public function errno()
    {
        return $this->errno_;
    }

    /**
     * get error description of last operation
     *
     * @return error description
     */
    public function errstr()
    {
        return $this->errstr_;
    }

    /**
     * init/set remote connection setup parameters
     *
     * @param {array} $conf config data(e.g., host/port of ssh)
     * @return boolean
     */
    abstract public function init($conf);

    /**
     * fini remote connection
     *
     * @return boolean
     */
    abstract public function fini();

    /**
     * do connect to remote server
     *
     * @return boolean
     */
    abstract public function connect();

    /**
     * download file from remote server
     *
     * @param {string} $remote abs path of remote file to download from
     * @param {string} $local  path of local file to download to
     * @return boolean
     */
    abstract public function get($remote, $local);

    /**
     * upload file to remote server
     *
     * @param {string} $local abs path of local file to upload from
     * @param {string} $remote path of remote file to upload to
     * @return boolean
     */
    abstract public function put($local, $remote);

    /**
     * remove file on remote server
     *
     * @param {string} $remote abs path of remote file to remove
     * @return boolean
     */
    abstract public function remove($remote);

    /**
     * check existence of remote file
     *
     * @param {string} $remote abs path of remote file
     * @return boolean
     */
    abstract public function exists($remote);

    /**
     * get remote file contents
     * !!! you should care memory usage
     *
     * @param {string} $remote abs path of remote file
     * @return contents of the remote file
     */
    abstract public function contents($remote);

    /**
     * traverse remote file line by line
     *
     * @param {string} $remote abs path of remote file
     * @param {callable} $cb callback to deal with one line
     * @return boolean
     */
    abstract public function traverse($remote, $cb);

    /**
     * get stat info of remote file
     *
     * @param {string} $remote abs path of remote file
     * @return false|array
     */
    abstract public function stat($remote);
}
