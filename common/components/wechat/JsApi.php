<?php
/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/27 15:53
 */

namespace common\components\wechat;


class JsApi extends BaseApi
{
    /**
     * @var string wechat jsapi ticket cache key
     */
    private $jsapiTicketKey = 'wechat_jsapi_ticket';

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getJsApiTicket()
    {
        $cache = static::getCache();
        $ticket = $cache->get($this->jsapiTicketKey);
        if (!$ticket) {
            $accessToken = static::getAccessToken();
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket';
            $params = [
                'type' => 'jsapi',
                'access_token' => $accessToken
            ];
            $url = $this->composeUrl($url, $params);

            $result = static::curlGet($url);
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
        $jsapiTicket = static::getJsApiTicket();
        $timestamp = time();
        $nonceStr = static::createNonceStr();
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
}
