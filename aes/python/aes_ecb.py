# -*- coding=utf-8-*-
from Crypto.Cipher import AES
import os
from Crypto import Random
import base64

"""
aes加密算法
padding : PKCS7
"""

class AESUtil:

    __BLOCK_SIZE_16 = BLOCK_SIZE_16 = AES.block_size

    @staticmethod
    def encryt(str, key, iv):
        cipher = AES.new(key, AES.MODE_ECB,iv)
        x = AESUtil.__BLOCK_SIZE_16 - (len(str) % AESUtil.__BLOCK_SIZE_16)
        if x != 0:
            str = str + chr(x)*x
        msg = cipher.encrypt(str)
        # msg = base64.urlsafe_b64encode(msg).replace('=', '')
        msg = base64.b64encode(msg)
        return msg

    @staticmethod
    def decrypt(enStr, key, iv):
        cipher = AES.new(key, AES.MODE_ECB, iv)
        # enStr += (len(enStr) % 4)*"="
        # decryptByts = base64.urlsafe_b64decode(enStr)
        decryptByts = base64.b64decode(enStr)
        msg = cipher.decrypt(decryptByts)
        paddingLen = ord(msg[len(msg)-1])
        return msg[0:-paddingLen]

if __name__ == "__main__":
    key = "1234567812345678"
    iv = "1234567812345678"
    res = AESUtil.encryt("123456", key, iv)
    print res # mdSm0RmB+xAKrTah3DG31A==
    print AESUtil.decrypt(res, key, iv) # 123456
