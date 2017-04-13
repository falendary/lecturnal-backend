<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "auth_token".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 */
class AuthToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['created_at', 'expires_at'], 'safe'],
            [['token'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'created_at' => 'Created at',
            'expires_at' => 'Expires at',
            'token' => 'Token',
        ];
    }
}
