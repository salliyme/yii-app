<?php
/**
 * error code 说明.
 * <ul>
 *    <li>-40001: 签名验证错误</li>
 *    <li>-40002: xml解析失败</li>
 *    <li>-40003: sha加密生成签名失败</li>
 *    <li>-40004: encodingAesKey 非法</li>
 *    <li>-40005: appid 校验错误</li>
 *    <li>-40006: aes 加密失败</li>
 *    <li>-40007: aes 解密失败</li>
 *    <li>-40008: 解密后得到的buffer非法</li>
 *    <li>-40009: base64加密失败</li>
 *    <li>-40010: base64解密失败</li>
 *    <li>-40011: 生成xml失败</li>
 * </ul>
 */

namespace common\components\wx;

/**
 * Class ErrorCode
 * @package common\components\wx
 */
class ErrorCode
{
    /**
     * @var int ok
     */
    public static $OK = 0;
    /**
     * @var int validate Signature error
     */
    public static $ValidateSignatureError = -40001;
    /**
     * @var int parse xml error
     */
    public static $ParseXmlError = -40002;
    /**
     * @var int compute signature error
     */
    public static $ComputeSignatureError = -40003;
    /**
     * @var int illegal aes key
     */
    public static $IllegalAesKey = -40004;
    /**
     * @var int validate appid error
     */
    public static $ValidateAppidError = -40005;
    /**
     * @var int encrypt aes error
     */
    public static $EncryptAESError = -40006;
    /**
     * @var int decrypt aes error
     */
    public static $DecryptAESError = -40007;
    /**
     * @var int illegal buffer
     */
    public static $IllegalBuffer = -40008;
    /**
     * @var int encode base64 error
     */
    public static $EncodeBase64Error = -40009;
    /**
     * @var int decode base64 error
     */
    public static $DecodeBase64Error = -40010;
    /**
     * @var int generate return xml error
     */
    public static $GenReturnXmlError = -40011;
}
