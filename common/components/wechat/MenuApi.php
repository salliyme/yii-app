<?php
/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/27 16:10
 */

namespace common\components\wechat;


class MenuApi extends BaseApi
{
    /**
     * 创建自定义菜单
     * @param $json
     * @return mixed
     */
    public function create($json)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create';
        $params = [
            'access_token' => $this->getAccessToken()
        ];
        $url = $this->composeUrl($url, $params);

        $result = static::curlPost($url, $json);

        return $result;
    }
}