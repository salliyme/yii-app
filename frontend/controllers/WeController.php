<?php
/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/24 22:18
 */

namespace frontend\controllers;

use common\components\wechat\BaseApi;
use common\components\wechat\OAuthApi;
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
        $we = new BaseApi();
        $result = $we->validate();
        echo $result;
    }

    /**
     * index function
     */
    public function actionIndex()
    {
        $we = new OAuthApi();
        if (isset($_GET['code'], $_GET['state'])) {
            $result = $we->getOauth2AccessToken($_GET['code']);
            $info = $we->getOauth2UserInfo($result['access_token'], $result['openid']);
            var_dump($info);
        } else {
            $url = $we->buildOAuth2Url(Yii::$app->request->absoluteUrl, 'snsapi_userinfo', 'login');
            $this->redirect($url);
        }
    }
}