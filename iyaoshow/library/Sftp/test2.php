<?php
ini_set('error_reporting', E_ALL | E_STRICT | E_NOTICE);
ini_set('display_errors', 1);

require 'Base.php';
require 'Sftp.php';

$sftp_access = new Sftp();

$sftp_access->init(array(
    'host'   => '192.168.11.220',
    'port'   => 22,
    'user'   => 'ybj',
    'passwd' => 'heartlaugh'
));

$rc = $sftp_access->connect();

if (!$rc) {
    echo "\n", $sftp_access->errstr(), "\n";
}

echo "\n====== stat ======\n\n";
var_dump($sftp_access->stat('/home/ybj/php.tar.gz'));
echo "\n==================\n";

echo "\n====== get ======\n\n";
$rc = $sftp_access->get('/home/ybj/php.tar.gz', './a.txt');
var_dump($rc);
echo "\n==================\n";

echo "\n====== put ======\n\n";
$rc = $sftp_access->put('./a.txt', '/home/ybj/a.txt');
var_dump($rc);
echo "\n==================\n";

echo "\n====== exists ======\n\n";
$exists = $sftp_access->exists('/home/ybj/php.tar.gz');
var_dump($exists);
echo "\n==================\n";

echo "\n====== remove ======\n\n";
$rc = $sftp_access->remove('/home/ybj/a.txt');
var_dump($rc);
echo "\n==================\n";

echo "\n====== contents ======\n\n";
echo $sftp_access->contents('/home/ybj/test.txt');
echo "\n==================\n";

echo "\n====== traverse ======\n\n";
$sftp_access->traverse('/home/ybj/test.txt', function($line) {
    static $i = 0;
    echo ++$i, " : ", $line, "\n";
});
echo "\n==================\n";
