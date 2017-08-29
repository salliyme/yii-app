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
        $xml = " <xml>
<ToUserName><![CDATA[oiQfstwVBtsfJRb9n7vjzch6JSHo]]></ToUserName>
<FromUserName><![CDATA[gh_486e130541d5]]></FromUserName>
<CreateTime>1503996961</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[发送的地址详细信息：(X: 30.590505599975586Y: 104.06562042236328Scale: 16Label: ACC中航城市广场(九方购物中心东北)Poiname: 位置)]]></Content>
</xml> 
";
        $object = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        var_dump($object);
    }
}
