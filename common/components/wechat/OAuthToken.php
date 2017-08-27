<?php
/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/25 21:03
 */

namespace common\components\wechat;

use yii\base\Object;

/**
 * Class OAuthToken
 * @package common\components\wechat
 */
class OAuthToken extends Object
{
    private $access_token;

    private $expires_in;

    private $refresh_token;

    private $open_id;

    private $scope;
}