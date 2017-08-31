<?php
/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/30 21:29
 */

namespace frontend\components;
use common\models\WechatUser;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        $attributes = $this->client->getUserAttributes();
        $openid = ArrayHelper::getValue($attributes, 'openid');

        /* @var $weUser WechatUser */
        $weUser = WechatUser::findOne(['openid' => $openid]);
        if ($weUser) {
            // update
            $weUser->openid = $attributes['openid'];
            $weUser->nickname = $attributes['nickname'];
            $weUser->sex = $attributes['sex'];
            $weUser->headimgurl = $attributes['headimgurl'];
            $weUser->country = $attributes['country'];
            $weUser->province = $attributes['province'];
            $weUser->city = $attributes['city'];
            $weUser->access_token = $this->client->getAccessToken()->getToken();
            $weUser->refresh_token = $this->client->getAccessToken()->getParam('refresh_token');
            $weUser->created_at = $_SERVER['REQUEST_TIME'];
            $weUser->save();
        } else {
            // add
            $weUser = new WechatUser();
            $weUser->openid = $attributes['openid'];
            $weUser->nickname = $attributes['nickname'];
            $weUser->sex = $attributes['sex'];
            $weUser->headimgurl = $attributes['headimgurl'];
            $weUser->country = $attributes['country'];
            $weUser->province = $attributes['province'];
            $weUser->city = $attributes['city'];
            $weUser->access_token = $this->client->getAccessToken()->getToken();
            $weUser->refresh_token = $this->client->getAccessToken()->getParam('refresh_token');
            $weUser->created_at = $_SERVER['REQUEST_TIME'];
            $weUser->save();
        }
        if (Yii::$app->user->isGuest) {// 如果是未登录账号就处理添加账号

        } else { // 已登录用户就与威信号绑定

        }
    }

    /**
     * @param User $user
     */
    private function updateUserInfo(User $user)
    {
        $attributes = $this->client->getUserAttributes();
        $github = ArrayHelper::getValue($attributes, 'login');
        if ($user->github === null && $github) {
            $user->github = $github;
            $user->save();
        }
    }
}