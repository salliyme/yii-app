<?php
/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/25 21:03
 */

namespace frontend\components;

use yii\base\Object;

/**
 * Class OAuthToken
 * @package frontend\components
 */
class OAuthToken extends Object
{
    private $access_token;

    private $expires_in;

    private $refresh_token;

    private $open_id;

    private $scope;
}