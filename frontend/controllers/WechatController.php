<?php
/**
 * WeApiController.
 *
 * @author pan.liu
 * @datetime 2017/8/17 12:01
 */

namespace frontend\controllers;

use frontend\components\WeApi;
use frontend\components\WeApiCoupon;
use yii\web\Controller;
use Yii;

/**
 * Class WeApiController
 * @package frontend\controllers
 */
class WeApiController extends Controller
{
    /**
     * index action
     */
    public function actionIndex()
    {
        $WeApi = new WeApi();
        $accessToken = $WeApi->getAccessToken();
        echo $accessToken;
        echo '<br/>';
        $ticket = $WeApi->getJsApiTicket();
        echo $ticket;
    }

    /**
     * @return string
     */
    public function actionAddTempMedia()
    {
        $this->layout = false;
        if (Yii::$app->request->isPost) {
            $WeApi = new WeApi();
            $mediaPath = '/var/www/mall/frontend/web/image/qq_9.png';
            $result = $WeApi->addTempMedia($mediaPath, 'image');

            echo $result;
        }

        return $this->render('media');
    }

    /**
     * 获取 临时素材
     */
    public function actionGetTempMedia()
    {
        header("Content-Type: image/jpeg");

        $WeApi = new WeApi();
        $media_id = 'wwK9eZ7Zx39z2zI2M-F3FZQoM4SiT8uXxoiC_y6dFd5pkEcnIGjkIbP47E5h1nHr';
        $result = $WeApi->getTempMedia($media_id);

        echo $result;
    }

    /**
     * 上传卡券logo
     */
    public function actionUploadImage()
    {
        $this->layout = false;

        $coupon = new WeApiCard();
        $mediaPath = '/var/www/mall/frontend/web/image/404.png';
        $result = $coupon->uploadImge($mediaPath);

        echo $result;
    }

    /**
     * 创建卡券
     */
    public function actionCreateCard()
    {
        $result = [
            'card' => [
                'card_type' => 'GROUPON',
                 'groupon' => [
                     'base_info' => [
                        'logo_url' => 'http://mmbiz.qpic.cn/mmbiz/iaL1LJM1mF9aRKPZJkmG8xXhiaHqkKSVMMWeN3hLut7X7hicFNjakmxibMLGWpXrEXB33367o7zHN0CwngnQY7zb7g/0',
                         'brand_name' => '微信餐厅',
                         'code_type' => 'CODE_TYPE_TEXT',
                         'title' => '132元双人火锅套餐',
                         'color' => 'Color010',
                         'notice' => '使用时向服务员出示此券',
                         'service_phone' => '020-88888888',
                         'description' => '不可与其他优惠同享\n如需团购券发票，请在消费时向商户提出\n店内均可使用，仅限食堂',
                         'date_info' => [
                             'type' => 'DATE_TYPE_FIX_TIME_RANGE',
                             'begin_timestamp' => 1503417600, // 2017-8-23 00:00:00
                             'end_timestamp' => 1504195199, // 2017-8-31 23:59:59
                         ],
                         'sku' => [
                             'quantity' => 500000
                         ],
                         'use_limit' => 100,
                         'get_limit' => 3,
                         'use_custom_code' => false,
                         'bind_openid' => false,
                         'can_share' => true,
                         'can_give_friend' => true,
                         'location_id_list' => [
                             123,
                             12321,
                             345345
                         ],
                         'center_title' => '顶部居中按钮',
                         'center_sub_title' => '按钮下方的wording',
                         'center_url' => 'www.qq.com',
                         'custom_url_name' => '立即使用',
                         'custom_url' => 'http://www.qq.com',
                         'custom_url_sub_title' => '6个汉字tips',
                         'promotion_url_name' => '更多优惠',
                         'promotion_url' => 'http://www.qq.com',
                         'source' => '大众点评'
                     ],
                     'advanced_info' => [
                         'use_condition' => [
                             'accept_category' => '鞋类',
                             'reject_category' => '阿迪达斯',
                             'can_use_with_other_discount' => true
                         ],
                         'abstract' => [
                             'abstract' => '微信餐厅推出多种新季菜品， 期待您的光临',
                             'icon_url_list' => [
                                 "http://mmbiz.qpic.cn/mmbiz/p98FjXy8LacgHxp3sJ3vn97bGLz0ib0Sfz1bjiaoOYA027iasqSG0sj  piby4vce3AtaPu6cIhBHkt6IjlkY9YnDsfw/0"
                             ]
                         ],
                         'text_image_list' => [
                             [
                                 'image_url' => 'http://mmbiz.qpic.cn/mmbiz/p98FjXy8LacgHxp3sJ3vn97bGLz0ib0Sfz1bjiaoOYA027iasqSG0sjpiby4vce3AtaPu6cIhBHkt6IjlkY9YnDsfw/0',
                                 'text' => '此菜品精选食材，以独特的烹饪方法，最大程度地刺激食 客的味蕾',
                             ],
                             [
                                 'image_url' => 'http://mmbiz.qpic.cn/mmbiz/p98FjXy8LacgHxp3sJ3vn97bGLz0ib0Sfz1bjiaoOYA027iasqSG0sj piby4vce3AtaPu6cIhBHkt6IjlkY9YnDsfw/0',
                                 'text' => '此菜品迎合大众口味，老少皆宜，营养均衡',
                             ],
                         ],
                         'time_limit' => [
                             [
                                 'type' => 'MONDAY',
                                'begin_hour' => 0,
                                'end_hour' => 10,
                                'begin_minute' => 10,
                                'end_minute' => 59
                             ],
                             [
                                 'type' => 'HOLIDAY'
                             ],
                         ],
                         'business_service' => [
                             "BIZ_SERVICE_FREE_WIFI",
                             "BIZ_SERVICE_WITH_PET",
                             "BIZ_SERVICE_FREE_PARK",
                             "BIZ_SERVICE_DELIVER"
                         ]
                     ],
                     'deal_detail' => '以下锅底2选1（有菌王锅、麻辣锅、大骨锅、番茄锅、清补 凉锅、酸菜鱼锅可选）：\n大锅1份 12元\n小锅2份 16元'
                 ]
            ]
        ];

        $json = json_encode($result, JSON_UNESCAPED_UNICODE);
        $WeApi = new WeApi();
        $url = 'https://api.weixin.qq.com/card/create?access_token='.$WeApi->getAccessToken();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        curl_setopt($ch, CURLOPT_HEADER, false);
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);

        echo $data;
    }

    /**
     * 投放卡券
     */
    public function actionPostCard()
    {
        $WeApi = new WeApi();
        $url = 'https://api.weixin.qq.com/card/qrcode/create?access_token=' . $WeApi->getAccessToken();

        $post = [
            'action_name' => 'QR_CARD',
            'expire_seconds' => 60 * 60 * 24,
            'action_info' => [
                'card' => [
                    'card_id' => 'piQfst1xrHffRzxwLTd3eRwEbXjU',
                    'code' => '3293294320942309',
                    'outer_str' => '12b'
                ]
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        curl_setopt($ch, CURLOPT_HEADER, false);
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);


        echo $data;
    }

    /**
     * 设置开发白名单
     */
    public function actionWhitelist()
    {
        $WeApi = new WeApi();

        $url = 'https://api.weixin.qq.com/card/testwhitelist/set?access_token='. $WeApi->getAccessToken();

        $json = '{
            "openid":[
                "oiQfstwVBtsfJRb9n7vjzch6JSHo"
            ],
            "username":[
                "oiQfstwVBtsfJRb9n7vjzch6JSHo"
            ]
        }';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        curl_setopt($ch, CURLOPT_HEADER, false);
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);

        echo $data;
    }

    /**
     * 网页认证redirect Url
     */
    public function actionNet()
    {
        $WeApi = new WeApi();
        $redirect_url = $WeApi->getAuthUrl('http://www.salliyme.com/index.php', 'snsapi_userinfo', 'login');
        echo $redirect_url;
    }

    /**
     * 代金券
     */
    public function actionCreateCardCash()
    {
        $result = [
            'card' => [
                'card_type' => 'CASH',
                'cash' => [
                    'base_info' => [
                        'logo_url' => 'http://mmbiz.qpic.cn/mmbiz/iaL1LJM1mF9aRKPZJkmG8xXhiaHqkKSVMMWeN3hLut7X7hicFNjakmxibMLGWpXrEXB33367o7zHN0CwngnQY7zb7g/0',
                        'brand_name' => '哈根达斯',
                        'code_type' => 'CODE_TYPE_TEXT',
                        'title' => '哈根达斯1元代金券',
                        'color' => 'Color050',
                        'notice' => '使用时向服务员出示此券',
                        'service_phone' => '020-88888888',
                        'description' => '不可与其他优惠同享\n店内均可使用，仅限哈根达斯',
                        'date_info' => [
                            'type' => 'DATE_TYPE_FIX_TIME_RANGE',
                            'begin_timestamp' => 1503417600, // 2017-8-23 00:00:00
                            'end_timestamp' => 1504195199, // 2017-8-31 23:59:59
                        ],
                        'sku' => [
                            'quantity' => 500000
                        ],
                        'use_limit' => 100,
                        'get_limit' => 3,
                        'use_custom_code' => false,
                        'bind_openid' => false,
                        'can_share' => true,
                        'can_give_friend' => true,
                        'location_id_list' => [
                            123,
                            12321,
                            345345
                        ],
                        'center_title' => '立即使用',
                        'center_sub_title' => '抵扣现金一元',
                        'center_url' => 'www.qq.com',
                        'custom_url_name' => '立即使用',
                        'custom_url' => 'http://www.qq.com',
                        'custom_url_sub_title' => '6个汉字tips',
                        'promotion_url_name' => '更多优惠',
                        'promotion_url' => 'http://www.qq.com',
                        'source' => '大众点评'
                    ],
                    'advanced_info' => [
                        'use_condition' => [
                            'accept_category' => '哈根达斯夏季新品',
                            'reject_category' => '哈根达斯特价款',
                            'can_use_with_other_discount' => true
                        ],
                        'abstract' => [
                            'abstract' => '哈根达斯推出多种新季菜品， 期待您的光临',
                            'icon_url_list' => [
                                "http://mmbiz.qpic.cn/mmbiz/p98FjXy8LacgHxp3sJ3vn97bGLz0ib0Sfz1bjiaoOYA027iasqSG0sj  piby4vce3AtaPu6cIhBHkt6IjlkY9YnDsfw/0"
                            ]
                        ],
                        'text_image_list' => [
                            [
                                'image_url' => 'http://mmbiz.qpic.cn/mmbiz/p98FjXy8LacgHxp3sJ3vn97bGLz0ib0Sfz1bjiaoOYA027iasqSG0sjpiby4vce3AtaPu6cIhBHkt6IjlkY9YnDsfw/0',
                                'text' => '此菜品精选食材，以独特的烹饪方法，最大程度地刺激食 客的味蕾',
                            ],
                            [
                                'image_url' => 'http://mmbiz.qpic.cn/mmbiz/p98FjXy8LacgHxp3sJ3vn97bGLz0ib0Sfz1bjiaoOYA027iasqSG0sj piby4vce3AtaPu6cIhBHkt6IjlkY9YnDsfw/0',
                                'text' => '此菜品迎合大众口味，老少皆宜，营养均衡',
                            ],
                        ],
                        'time_limit' => [
                            [
                                'type' => 'MONDAY',
                                'begin_hour' => 0,
                                'end_hour' => 30,
                                'begin_minute' => 17,
                                'end_minute' => 59
                            ],
                            [
                                'type' => 'HOLIDAY'
                            ],
                        ],
                        'business_service' => [
                            "BIZ_SERVICE_FREE_WIFI",
                            "BIZ_SERVICE_WITH_PET",
                            "BIZ_SERVICE_FREE_PARK",
                            "BIZ_SERVICE_DELIVER"
                        ]
                    ],
                    'least_cost' => 1000,
                    'reduce_cost' => 100
                ]
            ]
        ];

        $json = json_encode($result, JSON_UNESCAPED_UNICODE);
        $WeApi = new WeApi();
        $url = 'https://api.weixin.qq.com/card/create?access_token='.$WeApi->getAccessToken();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        curl_setopt($ch, CURLOPT_HEADER, false);
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);

        echo $data;
    }

    /**
     * 创建会员卡
     */
    public function actionCreateMemberCard()
    {
        $json = '{
    "card": {
        "card_type": "MEMBER_CARD",
        "member_card": {
            "background_pic_url": "https://mmbiz.qlogo.cn/mmbiz/",
            "base_info": {
                "logo_url": "http://mmbiz.qpic.cn/mmbiz/iaL1LJM1mF9aRKPZ/0",
                "brand_name": "海底捞",
                "code_type": "CODE_TYPE_TEXT",
                "title": "海底捞会员卡",
                "color": "Color010",
                "notice": "使用时向服务员出示此券",
                "service_phone": "020-88888888",
                "description": "不可与其他优惠同享",
                "date_info": {
                    "type": "DATE_TYPE_PERMANENT"
                },
                "sku": {
                    "quantity": 50000000
                },
                "get_limit": 3,
                "use_custom_code": false,
                "can_give_friend": true,
                "location_id_list": [
                    123,
                    12321
                ],
                "custom_url_name": "立即使用",
                "custom_url": "http://weixin.qq.com",
                "custom_url_sub_title": "6个汉字tips",
                "promotion_url_name": "营销入口1",
                "promotion_url": "http://www.qq.com",
                "need_push_on_view": true
            },
             "advanced_info": {
               "use_condition": {
                   "accept_category": "鞋类",
                   "reject_category": "阿迪达斯",
                   "can_use_with_other_discount": true
               },
             "abstract": {
                   "abstract": "微信餐厅推出多种新季菜品，期待您的光临",
                   "icon_url_list": [
                       "http://mmbiz.qpic.cn/mmbiz/p98FjXy8LacgHxp3sJ3vn97bGLz0ib0Sfz1bjiaoOYA027iasqSG0sj  piby4vce3AtaPu6cIhBHkt6IjlkY9YnDsfw/0"
                   ]
               },
               "text_image_list": [
                   {
                       "image_url": "http://mmbiz.qpic.cn/mmbiz/p98FjXy8LacgHxp3sJ3vn97bGLz0ib0Sfz1bjiaoOYA027iasqSG0sjpiby4vce3AtaPu6cIhBHkt6IjlkY9YnDsfw/0",
                       "text": "此菜品精选食材，以独特的烹饪方法，最大程度地刺激食 客的味蕾"
                   },
                   {
                       "image_url": "http://mmbiz.qpic.cn/mmbiz/p98FjXy8LacgHxp3sJ3vn97bGLz0ib0Sfz1bjiaoOYA027iasqSG0sj piby4vce3AtaPu6cIhBHkt6IjlkY9YnDsfw/0",
                       "text": "此菜品迎合大众口味，老少皆宜，营养均衡"
                   }
               ],
               "time_limit": [
                   {
                       "type": "MONDAY",
                       "begin_hour":0,
                       "end_hour":10,
                       "begin_minute":10,
                       "end_minute":59
                   },
                   {
                       "type": "HOLIDAY"
                   }
               ],
               "business_service": [
                   "BIZ_SERVICE_FREE_WIFI",
                   "BIZ_SERVICE_WITH_PET",
                   "BIZ_SERVICE_FREE_PARK",
                   "BIZ_SERVICE_DELIVER"
               ]
           },
            "supply_bonus": true,
            "supply_balance": false,
            "prerogative": "test_prerogative",
            "auto_activate": true,
            "custom_field1": {
                "name_type": "FIELD_NAME_TYPE_LEVEL",
                "url": "http://www.qq.com"
            },
            "activate_url": "http://www.qq.com",
            "custom_cell1": {
                "name": "使用入口2",
                "tips": "激活后显示",
                "url": "http://www.xxx.com"
            },
            "bonus_rule": {
                "cost_money_unit": 100,
                "increase_bonus": 1,
                "max_increase_bonus": 200,
                "init_increase_bonus": 10,
                "cost_bonus_unit": 5,
                "reduce_money": 100,
                "least_money_to_use_bonus": 1000,
                "max_reduce_bonus": 50
            },
            "discount": 10
        }
    }
}';
        $WeApi = new WeApi();
        $url = 'https://api.weixin.qq.com/card/create?access_token='.$WeApi->getAccessToken();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        curl_setopt($ch, CURLOPT_HEADER, false);
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);

        echo $data;
    }

    /**
     * 卡券货架
     */
    public function actionCardShelf()
    {
        $json = '{  
"banner":"http://mmbiz.qpic.cn/mmbiz/iaL1LJM1mF9aRKPZJkmG8xXhiaHqkKSVMMWeN3hLut7X7h  icFN",
   "page_title": "惠城优惠大派送",
   "can_share": true,
   "scene": "SCENE_NEAR_BY",
   "card_list": [
       {
           "card_id": "piQfst1xrHffRzxwLTd3eRwEbXjU",
           "thumb_url": "www.qq.com/a.jpg"
       }
   ]
}';
        $WeApi = new WeApi();
        $url = 'https://api.weixin.qq.com/card/landingpage/create?access_token='. $WeApi->getAccessToken();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        curl_setopt($ch, CURLOPT_HEADER, false);
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);

        echo $data;
    }
}
