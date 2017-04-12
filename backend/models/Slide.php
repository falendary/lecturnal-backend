<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "slide".
 *
 * @property integer $id
 * @property string $last_update
 * @property string $created_at
 * @property string $content
 * @property integer $presentation_id
 */
class Slide extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'slide';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['last_update', 'created_at'], 'safe'],
            [['content'], 'string'],
            [['presentation_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'last_update' => 'Last Update',
            'created_at' => 'Created At',
            'content' => 'Content',
            'presentation_id' => 'Presentation ID',
        ];
    }
}
