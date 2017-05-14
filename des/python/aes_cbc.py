# -*- coding=utf-8-*-
from Crypto.Cipher import DES
import base64
"""
des cbc加密算法
padding : PKCS5
"""
class DESUtil:
    __BLOCK_SIZE_8 = BLOCK_SIZE_8 = DES.block_size
    __IV = "\0\0\0\0\0\0\0\0" # __IV = chr(0)*8
    @staticmethod
    def encryt(str, key):
        cipher = DES.new(key, DES.MODE_CBC, DESUtil.__IV)
        x = DESUtil.__BLOCK_SIZE_8 - (len(str) % DESUtil.__BLOCK_SIZE_8)
        if x != 0:
            str = str + chr(x)*x
        msg = cipher.encrypt(str)
        # msg = base64.urlsafe_b64encode(msg).replace('=', '')
        msg = base64.b64encode(msg)
        return msg
    @staticmethod
    def decrypt(enStr, key):
        cipher = DES.new(key, DES.MODE_CBC,DESUtil.__IV)
        # enStr += (len(enStr) % 4)*"="
        # decryptByts = base64.urlsafe_b64decode(enStr)
        decryptByts = base64.b64decode(enStr)
        msg = cipher.decrypt(decryptByts)
        paddingLen = ord(msg[len(msg)-1])
        return msg[0:-paddingLen]
if __name__ == "__main__":
    key = "12345678"
    res = DESUtil.encryt("123456", key)
    print res # ED5wLgc3Mnw=
    print DESUtil.decrypt(res, key)