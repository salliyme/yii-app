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
            $json = Yii::$app->request->post('menu');
            $menu = new MenuApi();
            $result = $menu->create($json);
            var_dump($result);
        }

        return $this->render('custom');
    }
}