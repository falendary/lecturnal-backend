<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "presentation_permission".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $presentation_id
 */
class PresentationPermission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'presentation_permission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'presentation_id'], 'integer'],
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
            'presentation_id' => 'Presentation ID',
        ];
    }
}
