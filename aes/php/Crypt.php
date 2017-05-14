<?php

/**
 * DES/AES加密封装
 *
 * 1、默认使用Pkcs7填充加密内容。
 * 2、默认加密向量是"\0\0\0\0\0\0\0\0"
 * 3、默认情况下key做了处理：过长截取，过短填充
 *
 * @author 52fhy
 * @github https://github.com/52fhy/
 * @date 2017-5-13 17:08:57
 * Class Crypt
 */
class Crypt {

    private $key;//加密key：如果密钥长度不是加解密算法能够支持的有效长度，会自动填充"\0"。过长则会截取
    private $iv;//加密向量：这里默认填充"\0"。假设为空，程序会随机产生，导致加密的结果是不确定的。ECB模式下会忽略该变量
    private $mode; //分组密码模式：MCRYPT_MODE_modename 常量中的一个，或以下字符串中的一个："ecb"，"cbc"，"cfb"，"ofb"，"nofb" 和 "stream"。
    private $cipher; //算法名称：MCRYPT_ciphername 常量中的一个，或者是字符串值的算法名称。

    public function __construct($key, $cipher = MCRYPT_RIJNDAEL_128, $mode = MCRYPT_MODE_ECB, $iv = "\0\0\0\0\0\0\0"){
        $this->key = $key;
        $this->iv = $iv;
        $this->mode = $mode;
        $this->cipher = $cipher;
    }

    public function encrypt($input){
        $block_size = mcrypt_get_block_size($this->cipher, $this->mode);
        $key = $this->_pad0($this->key, $block_size);//将key填充至block大小
        $td = mcrypt_module_open($this->cipher, '', $this->mode, '');
        $iv = $this->iv ? $this->_pad0($this->iv, $block_size) : @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

        $input = $this->pkcs7_pad($input, $block_size);

        //加密方法一：
//        @mcrypt_generic_init($td, $key, $iv);//ECB模式下，初始向量iv会被忽略
//        $data = mcrypt_generic($td, $input);
//        mcrypt_generic_deinit($td);
//        mcrypt_module_close($td);

        //加密方法二：
        $data = mcrypt_encrypt(
            $this->cipher,
            $key,
            $input,
            $this->mode,
            $iv  //ECB模式下，向量iv会被忽略
        );

        $data = base64_encode($data);//如需转换二进制可改成  bin2hex 转换
        return $data;
    }

    public function decrypt($encrypted){
        $block_size = mcrypt_get_block_size($this->cipher, $this->mode);
        $key = $this->_pad0($this->key, $block_size);
        $td = mcrypt_module_open($this->cipher, '', $this->mode, '');
        $iv = $this->iv ? $this->_pad0($this->iv, $block_size) : @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

        //解密方法一：
//        $encrypted = base64_decode($encrypted); //如需转换二进制可改成  bin2hex 转换
//        @mcrypt_generic_init($td, $key, $iv);
//        $decrypted = mdecrypt_generic($td, $encrypted);
//        mcrypt_generic_deinit($td);
//        mcrypt_module_close($td);

        //解密方法二：
        $decrypted = mcrypt_decrypt(
            $this->cipher,
            $key,
            base64_decode($encrypted),
            $this->mode,
            $iv  //ECB模式下，向量iv会被忽略
        );

        return $this->_unpad($decrypted);
    }

    /**
     * 当使用“PKCS＃5”或“PKCS5Padding”别名引用该算法时，不应该假定支持8字节以外的块大小。
     * @url http://www.users.zetnet.co.uk/hopwood/crypto/scan/cs.html#pad_PKCSPadding
     * @param $text
     * @return string
     */
    public  function pkcs5_pad($text) {
        $pad = 8 - (strlen($text) % 8);
        //$pad = 8 - (strlen($text) & 7); //也可以使用这种方法
        return $text . str_repeat(chr($pad), $pad);
    }

    public  function pkcs7_pad ($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    public  function _unpad($text){
        $pad = ord(substr($text, -1));//取最后一个字符的ASCII 码值
        if ($pad < 1 || $pad > strlen($text)) {
            $pad = 0;
        }
        return substr($text, 0, (strlen($text) - $pad));
    }

    /**
     * 秘钥key和向量iv填充算法：大于block_size则截取，小于则填充"\0"
     * @param $str
     * @param $block_size
     * @return string
     */
    private  function _pad0($str, $block_size) {
        return str_pad(substr($str, 0, $block_size), $block_size, chr(0)); //chr(0) 与 "\0" 等效,因为\0转义后表示空字符，与ASCII表里的0代表的字符一样
    }
}