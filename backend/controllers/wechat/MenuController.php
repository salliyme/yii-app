<?php

/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/27 15:21
 */

namespace backend\controllers\wechat;

use backend\components\XController;

/**
 * Class MenuController
 * @package backend\controllers\wechat
 */
class MenuController extends XController
{
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
        return $this->render('custom');
    }
}