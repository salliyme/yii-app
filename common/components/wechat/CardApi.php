<?php
/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/27 16:02
 */

namespace common\components\wechat;


class CardApi extends BaseApi
{
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