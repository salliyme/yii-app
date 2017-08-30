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
        $menu = new MenuApi();
        if (Yii::$app->request->isPost) {
            $json = Yii::$app->request->post('menu');
            $result = $menu->create($json);
            $result = json_decode($result, true);
            if (isset($result['errcode']) && $result['errcode']) {
                Yii::$app->session->setFlash('success', '保存成功');
            } else {
                Yii::$app->session->setFlash('error', '保存失败');
            }
        } else {
            $json = $menu->get();
        }

        return $this->render('custom', ['json' => $json]);
    }
}