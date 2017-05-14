<?php

require_once "Crypt.php";

$key = 'pwd';
$des = new Crypt($key, MCRYPT_DES, MCRYPT_MODE_CBC);//DES
echo $ret = $des->encrypt("123456").PHP_EOL;//pQSWMWLBGQg=
echo $ret = $des->decrypt($ret);//解密结果  123456
echo PHP_EOL.'--------------'.PHP_EOL;

$key = '12345678';
$des = new Crypt($key, MCRYPT_DES, MCRYPT_MODE_CBC, '12345678');//DES
echo $ret = $des->encrypt("123456").PHP_EOL;// HUX+7VtHgb0=
echo $ret = $des->decrypt($ret);//解密结果  123456
echo PHP_EOL.'--------------'.PHP_EOL;
