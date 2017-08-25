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
     * @var string wechat token cache key
     */
    private $accessTokenKey = 'wechat_access_token';

    /**
     * @var string wechat jsapi ticket cache key
     */
    private $jsapiTicketKey = 'wechat_jsapi_ticket';

    /**
     * @return \yii\caching\Cache
     */
    public function getCache()
    {
        return Yii::$app->cache;
    }

    /*****************************************************/
    /* 微信网页授权操作
     /****************************************************/

    /**
     * get Oauth2 Url
     * @param $redirect_uri
     * @param $scope
     * @param $state
     * @return string
     */
    public function buildOAuth2Url($redirect_uri, $scope, $state)
    {
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize';
        $params = [
            'appid' => $this->appid,
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code',
            'scope' => $scope,
            'state' => $state
        ];
        $url = $this->composeUrl($url, $params);
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

    /*****************************************************/
    /* 微信基础token 操作
     /****************************************************/

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
     * @return mixed
     * @throws \Exception
     */
    public function getJsApiTicket()
    {
        $cache = self::getCache();
        $ticket = $cache->get($this->jsapiTicketKey);
        if (!$ticket) {
            $accessToken = self::getAccessToken();
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket';
            $params = [
                'type' => 'jsapi',
                'access_token' => $accessToken
            ];
            $url = $this->composeUrl($url, $params);

            $result = self::curlGet($url);
            if (!$result) {
                throw new \Exception('wechat API:getjsApiToken 调用失败！');
            }
            $result = json_decode($result, true);
            if (!isset($result['ticket'], $result['expires_in'])) {
                throw new \Exception('获取 jsApiTicket 失败');
            }
            $cache->set($this->jsapiTicketKey, $result['ticket'], intval($result['expires_in'], 10) - 200);
            $ticket = $result['ticket'];
        }
        return $ticket;
    }

    /**
     * @param $url
     * @return array
     */
    public function getSignPackage($url)
    {
        $jsapiTicket = self::getJsApiTicket();
        $timestamp = time();
        $nonceStr = self::createNonceStr();
        $params = [
            'jsapi_ticket' => $jsapiTicket,
            'noncestr' => $nonceStr,
            'timestamp' => $timestamp,
            'url' => $url
        ];
        ksort($params);
        $string = http_build_str($params);
        $signature = sha1($string);
        $signPackage = array(
            'appId' => $this->appid,
            'nonceStr' => $nonceStr,
            'timestamp' => $timestamp,
            'url' => $url,
            'signature' => $signature,
            'rawString' => $string
        );
        return $signPackage;
    }

    /**
     * @param int $length
     * @return string
     */
    public static function createNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
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

    // 素材管理相关 以后移动到子类中

    /**
     * @param $mediaPath
     * @param $type
     * @return mixed
     */
    public function addTempMedia($mediaPath, $type)
    {
        $url = 'http://file.api.weixin.qq.com/cgi-bin/media/upload';
        $params = [
            'access_token' => $this->getAccessToken(),
            'type' => $type
        ];

        if (class_exists('\CURLFile')) {
            $params['media'] = new \CURLFile(realpath($mediaPath));
        } else {
            $params['media'] ='@' . realpath($mediaPath);
        }

        $result = static::curlPostFile($url, $params);
        return $result;
    }

    /**
     * @param $media_id
     * @return mixed
     */
    public function getTempMedia($media_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get';
        $params = [
            'access_token' => $this->getAccessToken(),
            'media_id' => $media_id
        ];
        $url .= '?'. http_build_query($params);
        $result = self::curlGet($url);

        return $result;
    }
}
