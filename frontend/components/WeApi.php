<?php
/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/24 22:14
 */

namespace frontend\components;

use Yii;

/**
 * Class WeApi
 * @package frontend\components
 */
class WeApi
{
    /**
     * @var string wechat appid
     */
    private $appid = 'wxc378d8aa32db0b26';
    /**
     * @var string wechat app secret
     */
    private $secret = 'd4624c36b6795d1d99dcf0547af5443d';

    private $token = 'salliyme';

    /**
     * @return \yii\caching\Cache
     */
    public function getCache()
    {
        return Yii::$app->cache;
    }

    /**
     * get Oauth2 Url
     * @param $redirect_uri
     * @param $scope
     * @param $state
     * @return string
     */
    public function Oauth2Url($redirect_uri, $scope, $state)
    {
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize';
        $params = [
            'appid' => $this->appid,
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code',
            'scope' => $scope,
            'state' => $state
        ];
        $url .= '?' . http_build_query($params);
        $url .= '#wechat_redirect';

        return $url;
    }

    /**
     * get Oauth2 access_token
     * @param $code
     * @return mixed
     */
    public function getOauth2AccessToken($code)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
        $params = [
            'appid' => $this->appid,
            'secret' => $this->secret,
            'code' => $code,
            'grant_type' => 'authorization_code'
        ];

        $result = static::curlPost($url, $params);
        return $result;
    }

    /**
     * refresh Oauth2 access token
     * @param $refresh_token
     * @return mixed
     */
    public function refreshOauth2AccessToken($refresh_token)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token';
        $params = [
            'appid' => $this->appid,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token
        ];

        $result = static::curlPost($url, $params);

        return $result;
    }

    /**
     * get Oauth2 user information
     * @param $access_token
     * @param $openid
     * @return mixed
     */
    public function getOauth2UserInfo($access_token, $openid)
    {
        $url = ' https://api.weixin.qq.com/sns/userinfo';
        $params = [
            'access_token' => $access_token,
            'openid' => $openid,
            'lang' => 'zh_CN'
        ];

        $result = static::curlPost($url, $params);

        return $result;
    }

    /**
     * @param string $url
     * @param array $options
     * @return mixed
     */
    public static function curlGet($url = '', $options = array())
    {
        $ch = curl_init($url);
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