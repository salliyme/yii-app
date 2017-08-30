<?php
/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/30 21:13
 */

namespace frontend\components;

use Yii;
use yii\authclient\OAuth2;
use yii\web\HttpException;
use yii\authclient\OAuthToken;

class WeixinAuth extends OAuth2
{
    public $authUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    public $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    public $refreshUrl = 'https://api.weixin.qq.com/sns/oauth2/refresh_token';
    public $apiBaseUrl = 'https://api.weixin.qq.com';

    // 构建初始访问微信URL准备去拿code
    public function buildAuthUrl(array $params = [])
    {
        $defaultParams = [
            'appid' => $this->clientId,
            'redirect_uri' => $this->getReturnUrl(),
            'response_type' => 'code',
            'scope' => 'snsapi_userinfo',
        ];

        if ($this->validateAuthState) {
            $authState = $this->generateAuthState();
            $this->setState('authState', $authState);
            $defaultParams['state'] = $authState;
        }
        $url = $this->composeUrl($this->authUrl, array_merge($defaultParams, $params));
        return $url . '#wechat_redirect';
    }

    // 拿到code后也就是$authCode去拿accessToken;拿到后传给AuthHandler，并再拿到一个authclient对象。
    public function fetchAccessToken($authCode, array $params = [])
    {
        if ($this->validateAuthState) {
            $authState = $this->getState('authState');
            if (!isset($_REQUEST['state']) || empty($authState) || strcmp($_REQUEST['state'], $authState) !== 0) {
                throw new HttpException(400, 'Invalid auth state parameter.');
            } else {
                $this->removeState('authState');
            }
        }
        $defaultParams = [
            'appid' => $this->clientId,
            'secret' => $this->clientSecret,
            'code' => $authCode,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->getReturnUrl(),
        ];
        $request = $this->createRequest()
            ->setMethod('GET')
            ->setUrl($this->tokenUrl)
            ->setData(array_merge($defaultParams, $params));
        $response = $this->sendRequest($request);
        $token = $this->createToken(['params' => $response]);
        $this->setAccessToken($token);
        return $token;
    }

    public function refreshAccessToken(OAuthToken $token)
    {
        $params = [
            'appid' => $this->clientId,
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->clientSecret,
        ];
        $params = array_merge($token->getParams(), $params);
        $request = $this->createRequest()
            ->setMethod('POST')
            ->setUrl($this->refreshUrl)
            ->setData($params);
        $response = $this->sendRequest($request);
        $token = $this->createToken(['params' => $response]);
        $this->setAccessToken($token);
        return $token;
    }

    protected function initUserAttributes()
    { // 这一步是拿认证后的用户 详细信息，供AuthHandler处理，比如绑定会员、添加新会员等。逻辑自行处理
        $oauthToken = $this->getAccessToken();
        $defaultParams = [
            'access_token' => $oauthToken->params['access_token'],
            'openid' => $oauthToken->params['openid'],
            'lang' => 'zh-CN'
        ];
        $request = $this->createRequest()
            ->setMethod('GET')
            ->setUrl('sns/userinfo')
            ->setData($defaultParams);
        return $this->sendRequest($request);
    }

    protected function defaultName()
    {
        return 'weixin';
    }

    protected function defaultTitle()
    {
        return 'weixin';
    }
}