<?php
/**
 * AuthController.
 *
 * @author pan.liu
 * @datetime 2017/8/29 11:46
 */

namespace frontend\controllers;

use common\components\wechat\OAuthApi;
use yii\web\Controller;
use Yii;

/**
 * Class AuthController
 * @package frontend\controllers
 */
class AuthController extends Controller
{
    /**
     * index action redirect user to auth
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
