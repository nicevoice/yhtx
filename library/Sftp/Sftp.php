<?php
class Sftp_Sftp extends Sftp_Base
{
	private $conf_ = NULL;
	private $conn_ = NULL;
	private $use_pubkey_file_ = false;

	public function init($conf)
	{
		$ret = false;
		do {
			if (!isset($conf['host']) || !isset($conf['user'])) {
				break;
			}

			if (!isset($conf['passwd'])) {
				if (isset($conf['pubkey_file'])) {
					if (!isset($conf['privkey_file'])) {
						break;
					}

					$this->use_pubkey_file_ = true;
				} else {
					break;
				}
			}

			$ret = true;
		} while (0);

		if ($ret) {
			$this->conf_ = array_merge(array('port' => 22), $conf);
		} else {
			$this->setErr_(-1, 'conf is not complete');
		}

		return $ret;
	}

	public function fini()
	{
		$this->conf_ = NULL;
		$this->conn_ = NULL;
	}

	public function connect()
	{
		$methods = array();

		if ($this->use_pubkey_file_) {
			$methods['hostkey'] = 'ssh-rsa';
		}

		$conn = ssh2_connect($this->conf_['host'], $this->conf_['port'], $methods);

		if (false === $conn) {
			$this->setErr_(
				-1,
				sprintf(
					'failed to connect [%s:%d]',
					$this->conf_['host'],
					$this->conf_['port']
				)
			);

			return false;
		}

		if ($this->use_pubkey_file_) {
			$rc = ssh2_auth_pubkey_file(
				$conn,
				$this->conf_['user'],
				$this->conf_['pubkey_file'],
				$this->conf_['privkey_file'],
				isset($this->conf_['passphrase']) ?
					$this->conf_['passphrase'] : NULL
			);

			if (false === $rc) {
				$this->setErr_(
					-1,
					sprintf(
						'failed to auth with[%s:%s,%s,%s]',
						$this->conf_['user'],
						$this->conf_['pubkey_file'],
						$this->conf_['privkey_file'],
						$this->conf_['passphrase']
					)
				);

				return false;
			}
		} else {
			$rc = ssh2_auth_password(
				$conn,
				$this->conf_['user'],
				$this->conf_['passwd']
			);

			if (false === $rc) {
				$this->setErr_(
					-1,
					sprintf(
						'failed to auth with[%s:%s]',
						$this->conf_['user'],
						$this->conf_['passwd']
					)
				);

				return false;
			}
		}

		$this->conn_ = $conn;
		return true;
	}

	public function get($remote, $local)
	{
		return ssh2_scp_recv($this->conn_, $remote, $local);
	}

	public function put(
		$local,
		$remote,
		$file_mode  = 0644,
		$auto_mkdir = true,
		$dir_mode   = 0644
	) {
		$dir		= dirname($remote);
		$sftp	   = ssh2_sftp($this->conn_);
		$remote_dir = "ssh2.sftp://{$sftp}/{$dir}";

		if (!file_exists($remote_dir)) {
			if ($auto_mkdir) {
				$rc = mkdir($remote_dir, $dir_mode, true);

				if (false === $rc) {
					$this->setErr_(-1, 'failed to mkdir ' . $dir);
					return false;
				}
			} else {
				return false;
			}
		}

		return ssh2_scp_send($this->conn_, $local, $remote, $file_mode);
	}

	public function remove($remote)
	{
		$sftp = ssh2_sftp($this->conn_);
		$rc   = false;

		if (is_dir("ssh2.sftp://{$sftp}/{$remote}")) {
			$rc = ssh2_sftp_rmdir($sftp, $remote);
		} else {
			$rc = ssh2_sftp_unlink($sftp, $remote);
		}

		return $rc;
	}

	public function exists($remote)
	{
		$sftp = ssh2_sftp($this->conn_);
		return file_exists("ssh2.sftp://{$sftp}/{$remote}");
	}

	public function contents($remote)
	{
		$sftp = ssh2_sftp($this->conn_);
		$rfh  = @fopen("ssh2.sftp://{$sftp}/{$remote}", 'r');

		if (false === $rfh) {
			$this->setErr_(-1, 'failed to open ' . $remote);
			return false;
		}

		return stream_get_contents($rfh);
	}

	public function traverse($remote, $cb, $ending = "\n", $mol = 8388608)
	{
		$sftp = ssh2_sftp($this->conn_);
		$rfh  = @fopen("ssh2.sftp://{$sftp}/{$remote}", 'r');

		if (false === $rfh) {
			return false;
		}

		$ret = false;

		while (!feof($rfh)) {
			$line = stream_get_line($rfh, $mol, $ending);

			if (false === $line) {
				$this->setErr_(-1, 'failed to fetch one line');
				break;
			}

			if (false === call_user_func($cb, $line)) {
				$ret = true;
				break;
			}
		}

		return true;
	}

    public function unzipFile($sourceZip, $desDir)
    {
        if ($this->exists($sourceZip)) {
            $command = "unzip $sourceZip -d $desDir";
            return $this->commandExec($command);

        } else {
            $this->setErr_(-1, 'source zip or target directory not exists');
        }
        return true;
    }

    public function ls($path)
    {
        $command = "ls $path";
        if ($this->exists($path)) {
            return $this->commandExec($command);
        }
        return false;
    }

    public function mv($sourceFile, $destFile)
    {
        if ($this->exists($sourceFile)) {
            $command = "mv $sourceFile $destFile";
            return $this->commandExec($command);
        }
        return false;
    }

    public function mkdir($path)
    {
        $sftp = ssh2_sftp($this->conn_);
        return ssh2_sftp_mkdir($sftp, $path);
    }

    protected function commandExec($command)
    {
        $stream = ssh2_exec($this->conn_, $command);
        if(false != $stream) {
            return $this->getStream($stream);
        }
        return false;
    }

    protected function getStream($stream)
    {
        stream_set_blocking($stream, true);
        return stream_get_contents($stream);
    }


	public function stat($remote)
	{
		$sftp = @ssh2_sftp($this->conn_);
		return ssh2_sftp_stat($sftp, $remote);
	}
}
