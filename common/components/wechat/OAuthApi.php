<?php

/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/27 15:45
 */

namespace common\components\wechat;

/**
 * Class OAuthApi
 * @package common\components\wechat
 */
class OAuthApi extends BaseApi
{

    private $oAuthToken;

    public function setOAuthToken(OAuthToken $token)
    {
        $this->oAuthToken = $token;
    }

    public function getOAuthToken()
    {
        if (!is_object($this->oAuthToken) || $this->oAuthToken->isExpired()) {
            // 获取token
        }

        return $this->oAuthToken;
    }

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
     * @param $code
     * @return mixed
     * @throws \Exception
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
        $result = json_decode($result, true);
        if (!isset($result['access_token'])) {
            throw new \Exception('获取OAuth2AccessToken 失败');
        }

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
        $url = 'https://api.weixin.qq.com/sns/userinfo';
        $params = [
            'access_token' => $access_token,
            'openid' => $openid,
            'lang' => 'zh_CN'
        ];

        $url = $this->composeUrl($url, $params);
        $result = static::curlGet($url);
        $result = json_decode($result, true);

        return $result;
    }
}
