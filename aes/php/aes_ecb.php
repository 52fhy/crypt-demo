<?php

require_once "Crypt.php";

$key = 'pwd';
$des = new Crypt($key, MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);//AES
echo $ret = $des->encrypt("123456").PHP_EOL;//3+WQyhMavuxzPzy40PZhJg==
echo $ret = $des->decrypt($ret);//解密结果
echo PHP_EOL.'--------------'.PHP_EOL;

$key = '1234567812345678';
$des = new Crypt($key, MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB, '1234567812345678');//AES
echo $ret = $des->encrypt("123456").PHP_EOL;// mdSm0RmB+xAKrTah3DG31A==
echo $ret = $des->decrypt($ret);//解密结果  123456
echo PHP_EOL.'--------------'.PHP_EOL;
