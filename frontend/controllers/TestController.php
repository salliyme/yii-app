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
        $xml = ' <xml><ToUserName><![CDATA[gh_486e130541d5]]></ToUserName>
<FromUserName><![CDATA[oiQfstwVBtsfJRb9n7vjzch6JSHo]]></FromUserName>
<CreateTime>1503995280</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[location_select]]></Event>
<EventKey><![CDATA[rselfmenu_3_2]]></EventKey>
<SendLocationInfo><Location_X><![CDATA[30.5900936126709]]></Location_X>
<Location_Y><![CDATA[104.06595611572266]]></Location_Y>
<Scale><![CDATA[16]]></Scale>
<Label><![CDATA[武侯区茂业中心西北(天韵路)]]></Label>
<Poiname><![CDATA[[位置]]]></Poiname>
</SendLocationInfo>
</xml> 
';
        $object = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        $loc_x = $object->SendLocationInfo->Location_X;
        $loc_y = $object->SendLocationInfo->Location_Y;
        $loc_scale = $object->SendLocationInfo->Scale;
        $loc_label = $object->SendLocationInfo->Label;
        $loc_Poiname =  $object->SendLocationInfo->Poiname;
        $contentStr = "发送的地址详细信息：(X: ".$loc_x . "Y: ".$loc_y .
            "Scale: ".$loc_scale . "Label: ". $loc_label . "Poiname: " . $loc_Poiname .")";
        echo $contentStr;
    }
}
