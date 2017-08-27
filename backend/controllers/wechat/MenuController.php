<?php

/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/27 15:21
 */

namespace backend\controllers\wechat;

use backend\components\XController;
use common\components\wechat\MenuApi;
use Yii;

/**
 * Class MenuController
 * @package backend\controllers\wechat
 */
class MenuController extends XController
{
    public $enableCsrfValidation = false;

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string
     */
    public function actionCustom()
    {
        if (Yii::$app->request->isPost) {
            $json = ' {
             "button":[
                 {    
                      "type":"click",
                      "name":"今日歌曲",
                      "key":"V1001_TODAY_MUSIC"
                  },
                  {
                       "type":"click",
                       "name":"歌手简介",
                       "key":"V1001_TODAY_SINGER"
                  },
                  {
                       "name":"菜单",
                       "sub_button":[
                       {    
                           "type":"view",
                           "name":"搜索",
                           "url":"http://www.soso.com/"
                        },
                        {
                           "type":"view",
                           "name":"视频",
                           "url":"http://v.qq.com/"
                        },
                        {
                           "type":"click",
                           "name":"赞一下我们",
                           "key":"V1001_GOOD"
                        }]
                   }]
             }';
            $menu = new MenuApi();
            $result = $menu->create($json);
            var_dump($result);
        }

        return $this->render('custom');
    }
}