<?php

/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/27 15:42
 */

namespace common\components\wechat;

use Yii;

/**
 * Class BaseApi
 * @package common\components\wechat
 */
class BaseApi
{
    /**
     * @var string wechat appid
     */
    protected $appid;

    /**
     * @var string wechat app secret
     */
    protected $secret;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string wechat token cache key
     */
    private $accessTokenKey = 'wechat_access_token';

    public function __construct()
    {
        $this->appid = Yii::$app->params['wechat']['appid'];
        $this->secret = Yii::$app->params['wechat']['secret'];
        $this->token = Yii::$app->params['wechat']['token'];
    }

    /**
     * @return \yii\caching\Cache
     */
    public function getCache()
    {
        return Yii::$app->cache;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getAccessToken()
    {
        $cache = self::getCache();
        $accessToken = $cache->get($this->accessTokenKey);
        if (!$accessToken) {
            $url = 'https://api.weixin.qq.com/cgi-bin/token';
            $params = [
                'grant_type' => 'client_credential',
                'appid' => $this->appid,
                'secret' => $this->secret
            ];

            $url = $this->composeUrl($url, $params);
            $result = static::curlGet($url);
            if (!$result) {
                throw new \Exception('wechat API 调用失败');
            }
            $result = json_decode($result, true);
            if (!isset($result['access_token'], $result['expires_in'])) {
                throw new \Exception('获取accessToken 失败');
            }
            $cache->set($this->accessTokenKey, $result['access_token'], intval($result['expires_in'], 10) - 200);
            $accessToken = $result['access_token'];
        }

        return $accessToken;
    }

    /**
     * @param string $url
     * @param array $options
     * @return mixed
     */
    public static function curlGet($url = '', $options = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * @param string $url
     * @param string $postData
     * @param array $options
     * @return mixed
     */
    public static function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * @param string $url
     * @param array $postData
     * @return mixed
     */
    public static function curlPostFile($url = '', $postData = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        curl_setopt($ch, CURLOPT_HEADER, false);
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * Composes URL from base URL and GET params.
     * @param string $url base URL.
     * @param array $params GET params.
     * @return string composed URL.
     */
    protected function composeUrl($url, array $params = [])
    {
        if (!empty($params)) {
            if (strpos($url, '?') === false) {
                $url .= '?';
            } else {
                $url .= '&';
            }
            $url .= http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        }
        return $url;
    }

    /**
     * 微信服务器消息签名验证
     * @return array|mixed|string
     */
    public function validate()
    {
        $echoStr = Yii::$app->request->get('echostr');
        $signature = Yii::$app->request->get('signature');
        $timestamp = Yii::$app->request->get('timestamp');
        $nonce = Yii::$app->request->get('nonce');

        $tmpArr = array($this->token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return $echoStr;
        } else {
            return 'valid failed';
        }
    }
}
