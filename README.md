# crypt-demo

测试结果均base64编码。

## AES

### AES-ECB 

测试1：
```
加密密钥："1234567812345678"
加密向量：
加密填充算法：Pkcs7
加密分组模式：ECB
加密密文：123456
加密结果：mdSm0RmB+xAKrTah3DG31A==
备注：ECB模式下加密向量iv没有用到。
```

测试2：
```
加密密钥："pwd"
加密向量：
加密填充算法：Pkcs7
加密分组模式：ECB
加密密文：123456
加密结果：3+WQyhMavuxzPzy40PZhJg==
备注：ECB模式下加密向量iv没有用到。
```

这时候由于加密块大小是16bytes，但加密密钥只有3bytes，需要补位：

- php

使用 `"\0"` 或者 `chr(0)` 填充。不能使用单引号，否则长度不为16（可以使用strlen可以测试长度）。示例：
```
$key = "pwd\0\0\0\0\0\0\0\0\0\0\0\0\0";
$key = "pwd" . str_repeat(chr(0), 13);
```

- js
使用 `"\0"`  或者 `String.fromCharCode(0)`填充。不区分单引号。示例：
```
function str_repeat(target, n) {return (new Array(n + 1)).join(target);}

var key  = "pwd\0\0\0\0\0\0\0\0\0\0\0\0\0";
var key  = "pwd" + str_repeat(String.fromCharCode(0), 13);
var key  = "pwd" + str_repeat(String.fromCharCode(0), 13);
```

- python
使用 `"\0"` 或者 `chr(0)` 填充，不区分单引号。示例：
```
key = "pwd\0\0\0\0\0\0\0\0\0\0\0\0\0"
key = "pwd" + "\0"*13
key = "pwd" + chr(0)*13
```

### AES-CBC 

测试1：
```
加密密钥："1234567812345678"
加密向量："1234567812345678"
加密填充算法：Pkcs7
加密分组模式：CBC
加密密文：123456
加密结果：2eDiseYiSX62qk/WS/ZDmg==
```

测试2：
```
加密密钥："pwd"
加密向量："\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"
加密填充算法：Pkcs7
加密分组模式：CBC
加密密文：123456
加密结果：3+WQyhMavuxzPzy40PZhJg==
```
结果和AES-ECB相同。

这时候由于加密块大小是16bytes，但加密密钥和加密向量都只有3bytes，需要补位。这里默认补"\0"到16字节。
