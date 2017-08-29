<?php

use yii\db\Migration;

/**
 * Handles the creation of table `wechat_user`.
 */
class m170829_035934_create_wechat_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('wechat_user', [
            'id' => $this->primaryKey(),
            'openid' => $this->string(255)->notNull()->unique(),
            'nickname' => $this->string(50)->notNull()->comment('微信昵称'),
            'sex' => $this->integer(4)->notNull()->comment('性别'),
            'headimgurl' => $this->string(255)->notNull()->comment('头像'),
            'country' => $this->string(50)->notNull()->comment('国家'),
            'province' => $this->string(50)->notNull()->comment('省份'),
            'city' => $this->string(50)->notNull()->comment('城市'),
            'access_token' => $this->string(255)->notNull()->comment('access token'),
            'refresh_token' => $this->string(255)->notNull()->comment('refresh token'),
            'created_at' => $this->integer(11)->defaultValue(0)
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('wechat_user');
    }
}
