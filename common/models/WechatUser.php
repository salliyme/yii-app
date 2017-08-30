<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%wechat_user}}".
 *
 * @property integer $id
 * @property string $openid
 * @property string $nickname
 * @property integer $sex
 * @property string $headimgurl
 * @property string $country
 * @property string $province
 * @property string $city
 * @property string $access_token
 * @property string $refresh_token
 * @property integer $created_at
 */
class WechatUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wechat_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['openid', 'nickname', 'sex', 'headimgurl', 'country', 'province', 'city', 'access_token', 'refresh_token'], 'required'],
            [['sex', 'created_at'], 'integer'],
            [['openid', 'headimgurl', 'access_token', 'refresh_token'], 'string', 'max' => 255],
            [['nickname', 'country', 'province', 'city'], 'string', 'max' => 50],
            [['openid'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => 'Openid',
            'nickname' => 'Nickname',
            'sex' => 'Sex',
            'headimgurl' => 'Headimgurl',
            'country' => 'Country',
            'province' => 'Province',
            'city' => 'City',
            'access_token' => 'Access Token',
            'refresh_token' => 'Refresh Token',
            'created_at' => 'Created At',
        ];
    }
}
