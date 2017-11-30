<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property int $id
 * @property int $client_id
 * @property int $type_id
 * @property int $action_id
 * @property string $text
 * @property int $created_by_user
 * @property string $date
 * @property int $call_status_id
 * @property int $status
 *
 * @property Types $type
 * @property Actions $action
 */
class Comments extends \yii\db\ActiveRecord
{
    public $phone;
    public $count;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id',  'action_id', 'created_by_user', 'call_status_id', 'status'], 'integer'],
            [['text'], 'required'],
            [['text','phone'], 'string'],
            [['date'], 'safe'],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommentsActions::className(), 'targetAttribute' => ['action_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'ID Клиента',
            'action_id' => 'Действие',
            'text' => 'Комментарий',
            'created_by_user' => 'Создан пользователем',
            'date' => 'Дата',
            'call_status_id' => 'Call Status ID',
            'status' => 'Статус',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(CommentsTypes::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAction()
    {
        return $this->hasOne(CommentsActions::className(), ['id' => 'action_id']);
    }

    public function getClient()
    {
        return $this->hasOne(Clients::className(), ['id' => 'client_id']);
    }

    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'created_by_user']);
    }
}
