<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sentsms".
 *
 * @property int $id
 * @property int $user_id
 * @property int $client_id
 * @property string $text
 * @property string $date
 * @property int $status
 */
class Sentsms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sentsms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'client_id', 'text', ], 'required'],
            [['user_id', 'client_id', 'status'], 'integer'],
            [['text'], 'string'],
            [['date'], 'safe'],
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
            'client_id' => 'Client ID',
            'text' => 'Text',
            'date' => 'Date',
            'status' => 'Status',
        ];
    }
}
