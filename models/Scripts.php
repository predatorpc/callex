<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "scripts".
 *
 * @property int $id
 * @property string $name
 * @property string $text
 * @property int $status
 */
class Scripts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scripts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['text'], 'string'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'text' => 'Описание',
            'status' => 'Активно',
        ];
    }
}
