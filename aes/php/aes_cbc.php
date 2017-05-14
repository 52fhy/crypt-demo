<?php

require_once "Crypt.php";

//$key = 'pwd';
//$des = new Crypt($key, MCRYPT_DES, MCRYPT_MODE_CBC);//DES
//$des = new Crypt($key);//AES，默认是MCRYPT_RIJNDAEL_128+MCRYPT_MODE_ECB
//echo $ret = $des->encrypt("123456").PHP_EOL;//加密字符串，结果默认已经base64了
//echo $ret = $des->decrypt($ret);//解密结果
//echo PHP_EOL.'--------------'.PHP_EOL;

$key = '1234567812345678';
$des = new Crypt($key, MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC, '1234567812345678');//DES
echo $ret = $des->encrypt("123456").PHP_EOL;// 2eDiseYiSX62qk/WS/ZDmg==
echo $ret = $des->decrypt($ret);//解密结果  123456
echo PHP_EOL.'--------------'.PHP_EOL;
