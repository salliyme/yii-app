<?php
/**
 * wechatCoupon.
 *
 * @author pan.liu
 * @datetime 2017/8/21 9:44
 */

namespace frontend\components;

/**
 * Class WeApiCard
 * @package frontend\components
 */
class WeApiCard extends WeApi
{
    /**
     * @param $filepath
     * @return mixed
     */
    public function uploadImge($filepath)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg';
        $params = [
            'access_token' => $this->getAccessToken()
        ];

        if (class_exists('\CURLFile')) {
            $params['buffer'] = new \CURLFile(realpath($filepath));
        } else {
            $params['buffer'] ='@' . realpath($filepath);
        }

        $result = self::curlPostFile($url, $params);
        return $result;
    }

    /**
     * 创建卡券
     */
    public function create()
    {
        $url = 'https://api.weixin.qq.com/card/create';

        $params = [
            'access_token' => $this->getAccessToken()
        ];

        $cardInfo = [];

        $cardInfo['card_type'] = 'GROUPON';
        $cardInfo['groupon'] = [
            'base_info' => [
                'logo_url' => 'http:\/\/mmbiz.qpic.cn\/mmbiz_png\/bvrI4UVAvXs8MnLKIwYAMMZ517K0MdlEwQang9fmH2AiapErvcdnA4FLnNMbDzHWNsyrymC1zf43YsAzgbkAMyQ\/0',
                'brand_name' => '微信餐厅',
                'code_type' => 'CODE_TYPE_TEXT',
                'title' => '132'
            ],
        ];
    }
}
