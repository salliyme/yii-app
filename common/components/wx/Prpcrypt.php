<?php
/**
 * Prpcrypt.
 *
 * @author pan.liu
 * @datetime 2017/8/28 11:01
 */

namespace common\components\wx;

/**
 * Prpcrypt class
 *
 * 提供接收和推送给公众平台消息的加解密接口.
 */

/**
 * Class Prpcrypt
 * @package common\components\wx
 */
class Prpcrypt
{
    /**
     * @var string aes encoding key
     */
    public $key;

    /**
     * @param $k
     */
    public function __construct($k)
    {
        $this->key = base64_decode($k . "=");
    }

    /**
     * 对明文进行加密
     * @param string $text 需要加密的明文
     * @param string $appId app id
     * @return string 加密后的密文
     */
    public function encrypt($text, $appId)
    {

        try {
            // 获得16位随机字符串，填充到明文之前
            $random = $this->getRandomStr();
            $text = $random . pack("N", strlen($text)) . $text . $appId;
            // 网络字节序
            // $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
            $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
            $iv = substr($this->key, 0, 16);
            // 使用自定义的填充方式对明文进行补位填充
            $pkc_encoder = new PKCS7Encoder;
            $text = $pkc_encoder->encode($text);
            mcrypt_generic_init($module, $this->key, $iv);
            // 加密
            $encrypted = mcrypt_generic($module, $text);
            mcrypt_generic_deinit($module);
            mcrypt_module_close($module);

            // 使用BASE64对加密后的字符串进行编码
            return array(ErrorCode::$OK, base64_encode($encrypted));
        } catch (\Exception $e) {
            return array(ErrorCode::$EncryptAESError, null);
        }
    }

    /**
     * 对密文进行解密
     * @param string $encrypted 需要解密的密文
     * @param string $appId app id
     * @return string 解密得到的明文
     */
    public function decrypt($encrypted, $appId)
    {

        try {
            // 使用BASE64对需要解密的字符串进行解码
            $cipherTextDec = base64_decode($encrypted);
            $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
            $iv = substr($this->key, 0, 16);
            mcrypt_generic_init($module, $this->key, $iv);

            //解密
            $decrypted = mdecrypt_generic($module, $cipherTextDec);
            mcrypt_generic_deinit($module);
            mcrypt_module_close($module);
        } catch (\Exception $e) {
            return array(ErrorCode::$DecryptAESError, null);
        }


        try {
            // 去除补位字符
            $pkc_encoder = new PKCS7Encoder;
            $result = $pkc_encoder->decode($decrypted);
            // 去除16位随机字符串,网络字节序和AppId
            if (strlen($result) < 16) {
                return "";
            }
            $content = substr($result, 16, strlen($result));
            $len_list = unpack("N", substr($content, 0, 4));
            $xml_len = $len_list[1];
            $xml_content = substr($content, 4, $xml_len);
            $from_appId = substr($content, $xml_len + 4);
        } catch (\Exception $e) {
            return array(ErrorCode::$IllegalBuffer, null);
        }
        if ($from_appId != $appId) {
            return array(ErrorCode::$ValidateAppidError, null);
        }
        return array(0, $xml_content);
    }


    /**
     * 随机生成16位字符串
     * @return string 生成的字符串
     */
    public function getRandomStr()
    {
        $str = "";
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($str_pol) - 1;
        for ($i = 0; $i < 16; $i++) {
            $str .= $str_pol[mt_rand(0, $max)];
        }
        return $str;
    }
}
