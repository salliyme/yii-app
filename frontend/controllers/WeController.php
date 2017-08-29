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
use Yii;

/**
 * Class WeController
 * @package frontend\controllers
 */
class WeController extends BaseController
{
    /**
     * @var bool 禁用CSRF 验证
     */
    public $enableCsrfValidation = false;

    /**
     * default action
     */
    public function actionIndex()
    {
        if (isset($_GET['echostr'])) {
            $this->validate();
        } else {
            $this->responseMsg();
        }
    }

    /**
     * we-chat server validate
     */
    private function validate()
    {
        $we = new BaseApi();
        $result = $we->validate();
        echo $result;
    }

    /**
     * response we-chat server message
     */
    private function responseMsg()
    {
        $inputData = file_get_contents("php://input");
        $this->log($inputData);
        if (!empty($inputData)) {
            $postObj = simplexml_load_string($inputData, 'SimpleXMLElement', LIBXML_NOCDATA);
            $msgType = trim($postObj->MsgType);

            switch ($msgType) {
                case "text":
                    $resultStr = $this->receiveText($postObj);
                    break;
                case "event":
                    $resultStr = $this->receiveEvent($postObj);
                    break;
                case "location":
                    $resultStr = "位置信息";
                    break;
                default:
                    $resultStr = "";
                    break;
            }
            echo $resultStr;
        } else {
            echo "no input data";
        }
    }

    /**
     * receive Text
     * @param $object
     * @return mixed
     */
    private function receiveText($object)
    {
        $contentStr = "你发送的内容为：".$object->Content;
        $resultStr = $this->transmitText($object, $contentStr);
        return $resultStr;
    }

    /**
     * receive Event
     * @param $object
     * @return string
     */
    private function receiveEvent($object)
    {
        $contentStr = "";
        switch ($object->Event) {
            case "subscribe":
                $contentStr = "欢迎关注紫蓝旋律测试号";
                break;
            case "unsubscribe":
                // 取消关注
                break;
            case "scancode_push":
                // 扫码推事件
                $type = $object->ScanCodeInfo->ScanType;
                $result = $object->ScanCodeInfo->ScanResult;
                $contentStr = $object->EventKey ."扫码解析：类型：" . $type . "内容：" . $result;
                break;
            case "scancode_waitmsg":
                // 扫码推带提示
                $type = $object->ScanCodeInfo->ScanType;
                $result = $object->ScanCodeInfo->ScanResult;
                $contentStr = $object->EventKey ."扫码解析：类型：" . $type . "内容：" . $result;
                break;
            case "pic_sysphoto":
                // 系统拍照发图
                $md5Sum = $object->SendPicsInfo->PicList->item->PicMd5Sum;
                $contentStr = $object->Event . "发送的图片md5Sum：" .$md5Sum;
                break;
            case "pic_photo_or_album":
                // 拍照或者相册发图
                $md5Sum = $object->SendPicsInfo->PicList->item->PicMd5Sum;
                $contentStr =  $object->Event . "发送的图片md5Sum：" .$md5Sum;
                break;
            case "pic_weixin":
                // 微信相册发图
                $md5Sum = $object->SendPicsInfo->PicList->item->PicMd5Sum;
                $contentStr =  $object->Event . "发送的图片md5Sum：" .$md5Sum;
                break;
            case "location_select":
                // 发送地理位置
                $loc_x = $object->SendLocationInfo->Location_X;
                $loc_y = $object->SendLocationInfo->Location_Y;
                $loc_scale = $object->SendLocationInfo->Scale;
                $loc_label = $object->SendLocationInfo->Label;
                $loc_Poiname =  $object->SendLocationInfo->Poiname;
                $contentStr = "发送的地址详细信息：(X: ".$loc_x . "Y: ".$loc_y .
                    "Scale: ".$loc_scale . "Label: ". $loc_label . "Poiname: " . $loc_Poiname .")";
                break;
            case "CLICK":
                switch ($object->EventKey) {
                    case "sign_in":
                        // 点击签到
                        $contentStr = "签到成功，记得明日再来";
                        break;
                }
                break;
            case "VIEW":
                $contentStr = "点击：" . $object->EventKey;
                break;
            default:
                break;
        }
        $resultStr = $this->transmitText($object, $contentStr);
        $this->log($resultStr);
        return $resultStr;
    }

    /**
     * @param $object
     * @param $content
     * @return string
     */
    private function transmitText($object, $content)
    {
        $content = preg_replace(['/[\[\]]/'], [''], $content);
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $resultStr;
    }

    /**
     * @param $data
     */
    private function log($data)
    {
        $log = \Yii::getAlias("@runtime/logs/wechat.log");
        $tmp = date('[Y-m-d H:i:s]');
        file_put_contents($log, "{$tmp}\n{$data}\n", FILE_APPEND);
    }
}
