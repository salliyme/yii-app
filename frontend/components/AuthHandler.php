<?php
/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/30 21:29
 */

namespace frontend\components;
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
        $email = ArrayHelper::getValue($attributes, 'email');
        $openid = ArrayHelper::getValue($attributes, 'openid');
        $nickname = ArrayHelper::getValue($attributes, 'nickname');

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