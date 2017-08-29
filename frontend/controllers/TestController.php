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
        $xml = '<xml><ToUserName><![CDATA[gh_e136c6e50636]]></ToUserName>
<FromUserName><![CDATA[oMgHVjngRipVsoxg6TuX3vz6glDg]]></FromUserName>
<CreateTime>1408090651</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[pic_sysphoto]]></Event>
<EventKey><![CDATA[6]]></EventKey>
<SendPicsInfo><Count>1</Count>
<PicList><item><PicMd5Sum><![CDATA[1b5f7c23b5bf75682a53e7b6d163e185]]></PicMd5Sum>
</item>
</PicList>
</SendPicsInfo>
</xml>';
        $postObj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        echo $postObj->Event;
    }
}
