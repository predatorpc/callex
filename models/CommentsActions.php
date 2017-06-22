<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comments_actions".
 *
 * @property int $id
 * @property string $name
 * @property string $desctiption
 * @property int $status
 *
 * @property Comments[] $comments
 */
class CommentsActions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments_actions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'desctiption'], 'required'],
            [['desctiption'], 'string'],
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
            'name' => 'Name',
            'desctiption' => 'Desctiption',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['action_id' => 'id']);
    }
}
