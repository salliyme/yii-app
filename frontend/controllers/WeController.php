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
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)) {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $msgType = trim($postObj->MsgType);

            switch ($msgType) {
                case "text":
                    $resultStr = $this->receiveText($postObj);
                    break;
                case "event":
                    $resultStr = $this->receiveEvent($postObj);
                    break;
                default:
                    $resultStr = "";
                    break;
            }
            echo $resultStr;
        } else {
            echo "";
            exit;
        }
    }

    /**
     * receive Text
     * @param $object
     * @return mixed
     */
    private function receiveText($object)
    {
        $funcFlag = 0;
        $contentStr = "你发送的内容为：".$object->Content;
        $resultStr = $this->transmitText($object, $contentStr, $funcFlag);
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
            case "CLICK":
                switch ($object->EventKey) {
                    case "company":
                        $contentStr[] = array(
                            "Title" => "公司简介",
                            "Description" => "方倍工作室提供移动互联网相关的产品及服务",
                            "PicUrl" => "http://discuz.comli.com/weixin/weather/icon/cartoon.jpg",
                            "Url" => "weixin://addfriend/pondbaystudio"
                        );
                        break;
                    default:
                        $contentStr[] = array(
                            "Title" => "默认菜单回复",
                            "Description" => "您正在使用的是方倍工作室的自定义菜单测试接口",
                            "PicUrl" => "http://discuz.comli.com/weixin/weather/icon/cartoon.jpg",
                            "Url" => "weixin://addfriend/pondbaystudio"
                        );
                        break;
                }
                break;
            default:
                break;
        }
        if (is_array($contentStr)) {
            $resultStr = $this->transmitNews($object, $contentStr);
        } else {
            $resultStr = $this->transmitText($object, $contentStr);
        }
        return $resultStr;
    }

    /**
     * @param $object
     * @param $content
     * @param int $funcFlag
     * @return string
     */
    private function transmitText($object, $content, $funcFlag = 0)
    {
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>%d</FuncFlag>
                    </xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $funcFlag);
        return $resultStr;
    }

    /**
     * @param $object
     * @param $arr_item
     * @param int $funcFlag
     * @return bool|string
     */
    private function transmitNews($object, $arr_item, $funcFlag = 0)
    {
        //首条标题28字，其他标题39字
        if (!is_array($arr_item)) {
            return false;
        }

        $itemTpl = "<item>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <PicUrl><![CDATA[%s]]></PicUrl>
                        <Url><![CDATA[%s]]></Url>
                    </item>";
        $item_str = "";
        foreach ($arr_item as $item) {
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }

        $newsTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[news]]></MsgType>
                        <Content><![CDATA[]]></Content>
                        <ArticleCount>%s</ArticleCount>
                        <Articles>
                        $item_str</Articles>
                        <FuncFlag>%s</FuncFlag>
                   </xml>";

        $resultStr = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($arr_item), $funcFlag);
        return $resultStr;
    }


    /**
     * auth function
     */
    public function actionAuth()
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