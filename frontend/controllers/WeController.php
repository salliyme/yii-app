<?php
/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/24 22:18
 */

namespace frontend\controllers;

use frontend\components\BaseController;
use frontend\components\WeApi;
use Yii;

/**
 * Class WeController
 * @package frontend\controllers
 */
class WeController extends BaseController
{
    /**
     * wechat server validate
     */
    public function actionValid()
    {
        $we = new WeApi();
        $result = $we->validate();
        echo $result;
    }

    /**
     * index function
     */
    public function actionIndex()
    {
        $we = new WeApi();
        $url = $we->buildOAuth2Url(Yii::$app->request->absoluteUrl, 'base', 'login');
        $this->redirect($url);
    }

    /**
     * auth function
     */
    public function actionAuth()
    {
        $we = new WeApi();
        echo 'AuthUrl will be support';
    }
}