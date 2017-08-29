<?php
/**
 * TestController.
 *
 * @author pan.liu
 * @datetime 2017/8/29 14:58
 */

namespace frontend\controllers;

use yii\web\Controller;

/**
 * Class TestController
 * @package frontend\controllers
 */
class TestController extends Controller
{
    public function actionIndex()
    {
        $content = "发送的地址详细信息：(X: 30.589799880981445Y: 104.0660171508789Scale: 16Label: 武侯区茂业中心(天韵路)Poiname: [位置])";
        $content = preg_replace(['/[\[\]]/'], [''], $content);
        echo $content;
    }
}
